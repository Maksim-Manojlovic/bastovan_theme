<?php

if ( ! defined('ABSPATH') ) exit;

function bastovan_section_cache($name, $callback){

    $key = "bastovan_section_" . $name;

    $cached = get_transient($key);

    if ($cached !== false){

        echo $cached;
        return;

    }

    ob_start();

    call_user_func($callback);

    $content = ob_get_clean();

    set_transient($key, $content, HOUR_IN_SECONDS);

    echo $content;

}