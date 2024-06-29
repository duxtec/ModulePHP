<?php

namespace App\View\Base\Head;

use Resources\Utils\Asset;

class Style
{
    public function render()
    {
        $layout1 = Asset::url("css/layout1.scss");
        $layout1_dark = Asset::url("css/dark/layout1.scss");
        $layout1_light = Asset::url("css/light/layout1.scss");
        $critical_css = Asset::content("css/palette.scss");
        $critical_css .= Asset::content("css/main.scss");
        $critical_css .= Asset::content("css/layout1.scss");
        $content = <<<HTML

        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer"
        rel="preload" as="style"
        onload="this.onload=null;this.rel='stylesheet'">
        <noscript><link rel="stylesheet" href="$layout1"></noscript>

        <link href="$layout1"
        rel="preload" as="style"
        onload="this.onload=null;this.rel='stylesheet'">
        <noscript><link rel="stylesheet" href="$layout1"></noscript>

        <script>
            const prefersColorScheme = window.matchMedia('(prefers-color-scheme: dark)');

            // Altera o tema
            function changeTheme(event) {
                // Remove os estilos anteriores, se existirem
                var existingStyles = document.querySelectorAll('[data-theme]');
                existingStyles.forEach(function(style) {
                    style.parentNode.removeChild(style);
                });

                // Adiciona o novo estilo com base na preferência de cores do usuário
                if (event.matches) {
                    // Se o evento de mídia corresponder, carrega o tema escuro
                    var darkStyle = document.createElement('link');
                    darkStyle.rel = 'stylesheet';
                    darkStyle.href = "$layout1_dark";
                    darkStyle.setAttribute('data-theme', 'dark');
                    document.head.appendChild(darkStyle);
                } else {
                    // Caso contrário, carrega o tema claro
                    var lightStyle = document.createElement('link');
                    lightStyle.rel = 'stylesheet';
                    lightStyle.href = "$layout1_light";
                    lightStyle.setAttribute('data-theme', 'light');
                    document.head.appendChild(lightStyle);
                }
            }

            // Escuta a mudança de tema no sistema
            prefersColorScheme.addListener(changeTheme);

            // Altera o tema com base nas preferências de cores do usuário
            changeTheme(prefersColorScheme);

        </script>

        <style>
            {$critical_css}
        </style>
        HTML;

        return $content;
    }
}