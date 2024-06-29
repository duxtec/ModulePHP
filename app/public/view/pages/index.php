<?php
use Resources\Utils\Asset;

$title = "ModulePHP: Modular PHP Framework with Development Automation";
global $M;

$M->HTML->head->metatag->base->title = $title;
$M->HTML->body->footer->base->layout = 2;

?>

<a href="https://wa.me/5521989032187" target="_blank" class="fixed_button fixed_bottom_right"
    aria-label="Open Dux Tecnologia WhatsApp">
    <?php echo Asset::imgSmall("img/whatsapp.webp", "whatsapp's logo", "whatsapp") ?>
</a>

<div class="block" id="block_start">
    <div class="large_image">
        <?php echo Asset::img("img/logo.webp", "ModulePHP logo") ?>
    </div>
    <h1 class="heading1">
        <?php echo $title ?>
    </h1>
</div>
<div class="block" id="about_module">
    <h2 class="heading7">About Module</h2>
    <p>
        Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore
        magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
        consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla
        pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est
        laborum.
    </p>
</div>