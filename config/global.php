<?php
//Universal system settings.
const DEBUG = true;

if (DEBUG) {
    error_reporting(E_ALL);
    ini_set('display_errors', 'On');
    ini_set('zlib.output_compression', 'Off');
} else {
    error_reporting(0);
    ini_set('display_errors', 'Off');
    ini_set('zlib.output_compression', 'On');
}

$M["Config"] = array_merge(
    parse_ini_file("config/database.env"),
    parse_ini_file("config/mail.env"),
    parse_ini_file("config/ldap.env")
);