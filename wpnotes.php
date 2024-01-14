<?php
/**
 * Plugin Name: WordPress Notes Plugin
 * Description: A plugin to add notes to posts, pages, and products.
 * Version: 1.0
 * Author: D.Kandekore
 */

// Enqueue JavaScript for AJAX
add_action('admin_enqueue_scripts', 'notes_plugin_enqueue');

function notes_plugin_enqueue() {
    wp_enqueue_script('notes-plugin-js', plugin_dir_url(__FILE__) . 'notes-plugin.js', array('jquery'), '1.0', true);
    wp_localize_script('notes-plugin-js', 'notesPluginAjax', array('ajax_url' => admin_url('admin-ajax.php')));
}

// Hook to add meta box
add_action('add_meta_boxes', 'notes_plugin_add_custom_box');

// Hook for AJAX action
add_action('wp_ajax_notes_plugin_save_note', 'notes_plugin_save_note');


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
    // Retrieve existing notes
    $notes = get_post_meta($post->ID, '_notes_plugin_notes', true);
    $notes = $notes ? json_decode($notes, true) : [];

    // Display the notes
    echo '<div id="notes_plugin_notes">';
    foreach ($notes as $note) {
        echo '<div class="note"><strong>' . esc_html($note['username']) . ':</strong> ' . esc_html($note['note']) . ' <em>' . esc_html($note['date']) . '</em></div>';
    }
    echo '</div>';

    // Add new note area
    echo '<textarea id="notes_plugin_new_note" style="width:100%" rows="4"></textarea>';
    echo '<button type="button" id="notes_plugin_save_button" class="button">Add Note</button>';
}


function notes_plugin_save_note() {
    check_ajax_referer('notes_plugin_ajax_nonce', 'security');

    $post_id = $_POST['post_id'];
    $note_text = sanitize_text_field($_POST['note']);

    // Retrieve existing notes and add the new note
    $notes = get_post_meta($post_id, '_notes_plugin_notes', true);
    $notes = $notes ? json_decode($notes, true) : [];
    $notes[] = [
        'username' => wp_get_current_user()->display_name,
        'date' => current_time('mysql'),
        'note' => $note_text
    ];

    // Save the updated notes
    update_post_meta($post_id, '_notes_plugin_notes', json_encode($notes));

    wp_send_json_success(['note' => end($notes)]);
    wp_die();
}

