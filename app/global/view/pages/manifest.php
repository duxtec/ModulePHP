<?php

use Resources\Utils\Asset;

global $M;
$SITE = $M->Config->site;

$ICON = Asset::url($SITE->ICON);

function createIconObject($iconUrl, $size) {
    return [
        "src" => $iconUrl,
        "sizes" => $size,
        "type" => "image/png",
        "purpose" => "any maskable"
    ];
}

$icons = [
    createIconObject($ICON, "48x48"),
    createIconObject($ICON, "72x72"),
    createIconObject($ICON, "96x96"),
    createIconObject($ICON, "144x144"),
    createIconObject($ICON, "168x168"),
    createIconObject($ICON, "192x192"),
    createIconObject($ICON, "512x512")
];

$relatedApplications = [
    [
        "platform" => "web",
        "url" =>  $SITE->URL
    ],
    [
        "platform" => "play",
        "url" => $SITE->PLAYSTOREURL
    ]
];

echo json_encode([
    "name" => $SITE->NAME,
    "short_name" => $SITE->NAME,
    "start_url" => "/",
    "display" => "standalone",
    "background_color" => $SITE->COLOR1,
    "theme_color" => $SITE->COLOR1,
    "description" => $SITE->DESCRIPTION,
    "icons" => $icons,
    "related_applications" => $relatedApplications
], JSON_PRETTY_PRINT);

?>
