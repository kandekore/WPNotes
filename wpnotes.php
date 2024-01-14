<?php
/**
 * Plugin Name: WordPress Notes Plugin
 * Description: A plugin to add notes to posts, pages, and products.
 * Version: 1.0
 * Author: D.Kandekore
 */

// Hook to add meta box
add_action('add_meta_boxes', 'notes_plugin_add_custom_box');

// Hook to save the meta box data
add_action('save_post', 'notes_plugin_save_postdata');


function notes_plugin_add_custom_box() {
    $screens = ['post', 'page', 'product']; 
    foreach ($screens as $screen) {
        add_meta_box(
            'notes_plugin_box_id',           
            'Notes',                         
            'notes_plugin_custom_box_html',  
            $screen                          
        );
    }
}


function notes_plugin_custom_box_html($post) {
    $value = get_post_meta($post->ID, '_notes_plugin_meta_key', true);
    echo '<textarea style="width:100%" rows="4" name="notes_plugin_field">' . esc_textarea($value) . '</textarea>';
}


function notes_plugin_save_postdata($post_id) {
    if (array_key_exists('notes_plugin_field', $_POST)) {
        update_post_meta(
            $post_id,
            '_notes_plugin_meta_key',
            $_POST['notes_plugin_field']
        );
    }
}
