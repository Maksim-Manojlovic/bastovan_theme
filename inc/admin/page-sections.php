<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Meta box za sekcije landing page
 */

add_action('add_meta_boxes', function() {

    add_meta_box(
        'bastovan_sections',
        'Landing Page Sekcije',
        'bastovan_sections_meta_box',
        'page',
        'normal',
        'high'
    );

});


function bastovan_sections_meta_box($post){

    $selected = get_post_meta($post->ID,'_bastovan_sections',true);

    if(!is_array($selected)){
        $selected = [];
    }

    $available = bastovan_get_available_sections();

?>

<div id="bastovan-sections-builder">

<?php wp_nonce_field('bastovan_sections_nonce','bastovan_sections_nonce_field'); ?>

<select id="section-select">

<option value="">Dodaj sekciju</option>

<?php foreach($available as $key=>$label): ?>

<option value="<?php echo esc_attr($key); ?>">
<?php echo esc_html($label); ?>
</option>

<?php endforeach; ?>

</select>

<button type="button" id="add-section">Dodaj</button>

<ul id="sections-list">

<?php foreach($selected as $section): ?>

<li data-section="<?php echo esc_attr($section); ?>">

<span class="handle">⠿</span>

<?php echo esc_html( ucwords(str_replace('-', ' ', $section)) ); ?>

<input type="hidden" name="bastovan_sections[]" value="<?php echo esc_attr($section); ?>">

<button type="button" class="remove-section">×</button>

</li>

<?php endforeach; ?>

</ul>

</div>

<?php
}


add_action('save_post_page', function($post_id){

    if(!isset($_POST['bastovan_sections_nonce_field'])){
        return;
    }

    if(!wp_verify_nonce($_POST['bastovan_sections_nonce_field'],'bastovan_sections_nonce')){
        return;
    }

    if(defined('DOING_AUTOSAVE') && DOING_AUTOSAVE){
        return;
    }

    if(!current_user_can('edit_post',$post_id)){
        return;
    }

    if(!isset($_POST['bastovan_sections'])){
        delete_post_meta($post_id,'_bastovan_sections');
        return;
    }

    $sections = array_map('sanitize_text_field', $_POST['bastovan_sections']);

    update_post_meta(
        $post_id,
        '_bastovan_sections',
        $sections
    );

});

function bastovan_parse_sections($lines){

    $sections = [];

    foreach($lines as $line){

        $line = trim($line);

        if(!$line) continue;

        $parts = explode(':',$line,2);

        $name = $parts[0];

        $args = [];

        if(isset($parts[1])){

            $pairs = explode(',',$parts[1]);

            foreach($pairs as $pair){

                $kv = explode('=',$pair);

                if(count($kv)===2){

                    $args[trim($kv[0])] = trim($kv[1]);

                }

            }

        }

        $sections[] = [
            'name'=>$name,
            'args'=>$args
        ];

    }

    return $sections;

}