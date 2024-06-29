<?php

namespace Resources\Config;

use ErrorException;
use Resources\Utils\Asset;

class PreActions
{
    private $config;
    private $server;

    public function __construct()
    {
        global $M;
        $this->config = $M->Config;
    }

    public function execute()
    {
        $this->startServiceWorker();
        $this->loadFavicon();
        $this->loadManifest();
        $this->startLoader();
        $this->setErrorHandler();
        $this->createStreamResolveIncludePathFunction();
    }

    private function startServiceWorker()
    {
        if (
            $this->config->system->PWA &&
            in_array(
                $_SERVER["REQUEST_URI"],
                ["/sw", "/sw.js"]
            )
        ) {
            header('Content-Type: application/javascript');
            echo file_get_contents("app/global/view/pages/sw.js", true);
            exit();
        }
    }

    private function loadManifest()
    {
        if (
            in_array(
                $_SERVER["REQUEST_URI"],
                ["/manifest", "/manifest.webmanifest", "manifest.json"]
            )
        ) {
            error_reporting(E_ALL);
            header('Content-Type: application/manifest+json');
            include (APP_FOLDER . "global/view/pages/manifest.php");
            exit();
        }
    }

    private function startLoader()
    {
        if ($this->config->system->LOADER && !isset($_GET["loaded"])) {
            echo <<<loader
            <!DOCTYPE html>
            <html lang="{$this->config->site->LANG}" dir="ltr">
            loader;

            include ("app/global/view/pages/loader.php");
            if ($this->config->system->PWA) {
                echo <<<content
                <link rel="manifest" href="/manifest">
                <script>
                    /* Only register a service worker if it's supported */
                    if ('serviceWorker' in navigator) {
                        navigator.serviceWorker.register('/sw');
                    }
                </script>
                content;
            }

            echo <<<loader
            <script>
                const params = new URLSearchParams(window.location.search);
                params.set('loaded', true);
                url = `\${window.location.pathname}?\${params}`;
                fetch(url)
                    .then((response) => response.text())
                    .then((page) => {
                        document.querySelector("html").innerHTML = page;
                    });
            </script>
            loader;
            exit();
        }
    }

    private function setErrorHandler()
    {
        set_error_handler(function ($severity, $message, $filename, $lineno) {
            global $M;
            if (!$M->Config->system->PRODUCTION) {
                exit ("$message IN $filename IN line $lineno, SEVERITY = $severity");
            }
            throw new ErrorException($message, 0, $severity, $filename, $lineno);
        });
    }

    private function createStreamResolveIncludePathFunction()
    {
        if (!function_exists('stream_resolve_include_path')) {
            function stream_resolve_include_path($filename)
            {
                $paths = PATH_SEPARATOR == ':' ?
                    preg_split('#(?<!phar):#', get_include_path()) :
                    explode(PATH_SEPARATOR, get_include_path());
                foreach ($paths as $prefix) {
                    $ds = substr($prefix, -1) == DIRECTORY_SEPARATOR ? '' : DIRECTORY_SEPARATOR;
                    $file = $prefix . $ds . $filename;

                    if (file_exists($file)) {
                        return $file;
                    }
                }

                return false;
            }
        }
    }

    private function loadFavicon()
    {
        if ($this->config->site->ICON) {
            $icon = Asset::url($this->config->site->ICON);
            header("Link <{$icon}>; rel='icon'");

            if (
                in_array(
                    $_SERVER["REQUEST_URI"],
                    ["/favicon", "/favicon.ico"]
                )
            ) {
                header("Location: {$icon}");
                exit();
            }
        }
    }
}