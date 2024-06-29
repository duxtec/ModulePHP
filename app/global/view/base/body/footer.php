<?php

namespace App\View\Base\Body;

class Footer
{
    public $layout = 1;
    private $copyright = <<<HTML
    <div id="footer_copyright">
        <p>© Copyright Dux Tecnologia 2024.</p>
        <p>Todos os direitos reservados.</p>
        <p>Desenvolvido por:
        <a href="https://wa.me/5521989032187"
        target="_blank">
        Dux Tecnologia.
        </a></p>
    </div>
    HTML;
    public $social = <<<HTML
    <div id="footer_social">
        <a id="link_whatsapp" href="https://wa.me/5521989032187"
        target="_blank"
        aria-label="Open Dux Tecnologia WhatsApp">
        <i class="fa-brands fa-whatsapp" aria-hidden="true" aria-label="Open Dux Tecnologia WhatsApp"></i>
        <span class="sr-only">Open Dux Tecnologia WhatsApp</span>
        </a>
        <a id="link_instagram" href="https://instagram.com/"
        target="_blank"
        aria-label="Open Dux Tecnologia Instagram">
            <i class="fa-brands fa-instagram" aria-hidden="true" aria-label="Open Dux Tecnologia Instagram"></i>
            <span class="sr-only">Open Dux Tecnologia Instagram</span>
        </a>
        <a id="link_facebook" href="https://facebook.com"
        target="_blank"
        aria-label="Open Dux Tecnologia Facebook">
            <i class="fa-brands fa-facebook" aria-hidden="true" aria-label="Open Dux Tecnologia Facebook"></i>
            <span class="sr-only">Open Dux Tecnologia Facebook</span>
        </a>
    </div>
    HTML;

    public $contact = <<<HTML
    <div id="footer_contact">
        <a id="link_email" href="mailto:contact@modulephp.com" target="_blank" aria-label="Send mail to contact@modulephp.com">
            <i class="fa fa-envelope"></i>
            <p>contact@modulephp.com</p>
        </a>
        <a id="link_telefone" href="tel:5521989032187" target="_blank" aria-label="Call to +55 (21) 98903-2187">
            <i class="fa fa-phone"></i>
            <p>(21) 98903-2187</p>
        </a>
        <a id="link_maps" href="https://maps.app.goo.gl/zBmmN6qMPWsMsrkk8" target="_blank" aria-label="See location on Fictitious Street, 93, Niterói, Rio de Janeiro, Brazil on Google Maps">
            <i class="fa fa-map-marker-alt"></i>
            <p>Fictitious Street, 93, Niterói, Rio de Janeiro, Brazil.</p> 
        </a>
    </div>
    HTML;
    public function render()
    {
        $render = "";
        switch ($this->layout) {
            case 1:
                $render = <<<HTML
                $this->copyright
                HTML;
                break;
            case 2:
                $render = <<<HTML
                $this->contact
                $this->social
                $this->copyright
                HTML;
                break;

            default:
                $render = <<<HTML
                HTML;
                break;
        }
        return $render;
    }
}