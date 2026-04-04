<?php
/**
 * Template Name: Landing Page
 */

get_header();

$sections = get_post_meta(get_the_ID(),'_bastovan_sections',true);

if(!empty($sections)){

    bastovan_sections($sections);

}

get_footer();