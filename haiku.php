<?php

/*
Plugin Name: Haiku
Plugin URI: https://github.com/LukeCarrier/wordpress-haiku
Description: Because code is poetry.
Version: 0.1.0
Author: Luke Carrier
Author URI: https://github.com/LukeCarrier
License: GPL v3
*/

namespace Haiku;

spl_autoload_register(function($class_name) {
    if (substr($class_name, 0, 6) !== 'Haiku\\') {
        return;
    }

    $file_name = str_replace('\\', '/', substr($class_name, 6)) . '.php';

    require_once __DIR__ . '/lib/' . $file_name;
});
