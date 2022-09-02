<?php

#Turning errors into exceptions.
if (DEBUG) {
    set_error_handler(function ($errno, $errstr, $errfile, $errline) {
        if (0 === error_reporting()) {
            return false;
        }

        throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
    });
}

#Create function stream_resolve_include_path if not exist.
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