<?php
$constant = function ($constant) {
    return $constant;
};

#Website informations.
const SITE_NAME = "Module PHP Example";
const SITE_DESCRIPTION = "Module PHP Example";
const SITE_TWITTER = "";
const SITE_COLOR1 = "#A0F";
const SITE_COLOR2 = "#FA0";
const SITE_AUTHOR = "Thiago Costa Pereira - https://tpereira.com.br";
const SITE_URL = "http://modulephp.com";
const SITE_BANNER = "url('assets/img/background.jpg')";
define("SITE_BANNER2", "linear-gradient({$constant(SITE_COLOR1)},{$constant(SITE_COLOR2)})");
const SITE_LOGO = "/assets/img/logo.png";