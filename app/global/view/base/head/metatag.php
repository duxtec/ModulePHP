<?php

namespace App\View\Base\Head;

use Resources\Utils\Asset;

class Metatag
{
    public $title;
    public $description;

    public function __construct()
    {
        global $M;
        $this->title = $M->Config->site->NAME;
        $this->description = $M->Config->site->DESCRIPTION;
    }
    public function render()
    {
        global $M;
        $SITE = $M->Config->site;
        $ICON = Asset::url($SITE->ICON);

        // Protocolo HTTP (http:// ou https://)
        $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https://" : "http://";

        // URL completa da página
        $url = $protocol . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

        $content = <<<content
        <title>$this->title</title>
        <meta name="description" content="$this->description">
        <meta name="theme-color" content="$SITE->COLOR1"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="apple-touch-icon" href="$ICON">
        <link rel="icon" href="$ICON" type="image/x-icon">
        <link rel="icon" type="image/webp" sizes="16x16" href="$ICON">
        <link rel="icon" type="image/webp" sizes="32x32" href="$ICON">
        <meta name="format-detection" content="telephone=no">
        <link rel="canonical" href="$url">

        <meta property="og:title" content="$this->title">
        <meta property="og:description" content="$this->description">
        <meta property="og:image" content="$ICON">
        <meta property="og:url" content="$url">
        <meta property="og:type" content="website">

        <meta name="twitter:title" content="$this->title">
        <meta name="twitter:description" content="$this->description">
        <meta name="twitter:image" content="$ICON">
        <meta name="twitter:card" content="summary_large_image">


        content;

        return $content;
    }
}