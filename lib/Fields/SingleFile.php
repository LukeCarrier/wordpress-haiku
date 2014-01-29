<?php

/**
 * Haiku: because code is poetry.
 *
 * @author Luke Carrier <luke@carrier.im>
 * @license GPL v3
 */

namespace Haiku\Fields;
use Haiku\AField,
    Haiku\IField;

class SingleFile extends AField implements IField {
    public function getMetaBoxHtml($post) {
        list($meta_key, $post_key, $value) = $this->getMetaBoxDetails($post->ID);

        $upload_dir = wp_upload_dir();
        $value = $upload_dir['baseurl'] . '/' . $value;

        echo $this->getNonceHtml(),
             '<img src="' . $value . '" alt="" class="haiku-attachment-thumbnail" />',
             '<input type="hidden" name="' . $post_key . '"',
             '       value="' . $value . '" />',
             '<div class="clearfix"></div>',
             '<input type="button" class="media-upload-select-button"',
             '       data-field-name="' . $post_key . '" value="' . __('Select media') . '" />',
             '<input type="button" class="media-upload-remove-button"',
             '       data-field-name="' . $post_key . '" value="' . __('Remove media') . '" />';
    }

    public function doSave($post_id, $post) {
        if (!$this->verifyNonce($post)) {
            return;
        }

        $meta_key = $this->getIdentifier();
        $post_key = $meta_key . '_value';
        $value    = (array_key_exists($post_key, $_POST)) ? $_POST[$post_key] : NULL;

        $upload_dir = wp_upload_dir();
        $value = substr($value, strlen($upload_dir['baseurl']) + 1);

        $existing_values = get_post_meta($post->ID, $meta_key);
        if (count($existing_values) === 1 && $value) {
            update_post_meta($post->ID, $meta_key, $value);
            return;
        } else {
            delete_post_meta($post->ID, $meta_key);
        }

        if ($value) {
            add_post_meta($post->ID, $meta_key, $value);
        }
    }
}