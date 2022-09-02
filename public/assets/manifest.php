<?php
require_once("../../config/website.php");

$SITE_NAME = SITE_NAME;
$SITE_DESCRIPTION = SITE_DESCRIPTION;
$SITE_COLOR1 = SITE_COLOR1;
$SITE_AUTHOR = SITE_AUTHOR;
$SITE_URL = SITE_URL;
$SITE_BANNER = SITE_BANNER;
$SITE_LOGO = SITE_LOGO;

echo <<<content
{
  "name": "$SITE_NAME",
  "short_name": "$SITE_NAME",
  "start_url": "/",
  "display": "standalone",
  "background_color": "$SITE_COLOR1",
  "theme_color": "$SITE_COLOR1",
  "description": "$SITE_DESCRIPTION",
  "icons": [
    {
      "src": "$SITE_LOGO",
      "sizes": "48x48",
      "type": "image/png",
      "purpose": "any maskable"
    },
    {
      "src": "$SITE_LOGO",
      "sizes": "72x72",
      "type": "image/png",
      "purpose": "any maskable"
    },
    {
      "src": "$SITE_LOGO",
      "sizes": "96x96",
      "type": "image/png",
      "purpose": "any maskable"
    },
    {
      "src": "$SITE_LOGO",
      "sizes": "144x144",
      "type": "image/png",
      "purpose": "any maskable"
    },
    {
      "src": "$SITE_LOGO",
      "sizes": "168x168",
      "type": "image/png",
      "purpose": "any maskable"
    },
    {
      "src": "$SITE_LOGO",
      "sizes": "192x192",
      "type": "image/png",
      "purpose": "any maskable"
    },
    {
      "src": "$SITE_LOGO",
      "sizes": "512x512",
      "type": "image/png",
      "purpose": "any maskable"
    }
  ],
  "related_applications": [
    {
      "platform": "web"
    },
    {
      "platform": "play",
      "url": "https://play.google.com/store/apps/details?id=duxtec.modulephp"
    }
    ]
  }
content;