<?php

namespace ModulePHP\Base;

class Metatag
{
    public function render()
    {
        $SITE_NAME = SITE_NAME;
        $SITE_DESCRIPTION = SITE_DESCRIPTION;
        $SITE_COLOR1 = SITE_COLOR1;
        $SITE_AUTHOR = SITE_AUTHOR;
        $SITE_URL = SITE_URL;
        $SITE_BANNER = SITE_BANNER;
        $SITE_LOGO = SITE_LOGO;

        $content = <<<content
        <title>$SITE_NAME</title>
        <meta name="description" content="$SITE_DESCRIPTION ">
        <meta name="theme-color" content="$SITE_COLOR1"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="manifest" href="/assets/manifest.php">
        <link rel="apple-touch-icon" href="$SITE_LOGO">
        content;

        return $content;
    }
}