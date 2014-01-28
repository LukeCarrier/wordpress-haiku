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
}