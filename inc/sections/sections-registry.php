<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

function bastovan_get_available_sections(){

    $dir = get_template_directory() . '/sections';

    if(!is_dir($dir)){
        return [];
    }

    $folders = scandir($dir);

    $sections = [];

    foreach($folders as $folder){

        if($folder === '.' || $folder === '..'){
            continue;
        }

        if(is_dir($dir.'/'.$folder)){
            $sections[$folder] = ucfirst($folder);
        }

    }

    return $sections;

}