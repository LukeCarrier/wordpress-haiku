<?php

/**
 * Haiku: because code is poetry.
 *
 * @author Luke Carrier <luke@carrier.im>
 * @license GPL v3
 */

namespace Haiku;

abstract class AField {
    protected $identifier;
    protected $title;
    protected $options;

    final public function __construct($identifier, $title, $options=array()) {
        $this->identifier = $identifier;
        $this->title      = $title;
        $this->options    = $options;
    }

    final public function register($post_type_identifier) {
        add_meta_box($this->getIdentifier(), $this->getTitle(),
                     array($this, 'getMetaBoxHtml'), $post_type_identifier);
    }

    final protected function getNonceHtml() {
        $id = $this->getIdentifier() . '_nonce';

        return '<input type="hidden" name="' . $id . '" '
             . 'id="' . $id .'" '
             . 'value="' . wp_create_nonce($this->getIdentifier()) . '" />';
    }

    final protected function verifyNonce($post) {
        $id = $this->getIdentifier() . '_nonce';

        if (!array_key_exists($id, $_POST)
                || !wp_verify_nonce($_POST[$id], $this->getIdentifier())
                || !current_user_can('edit_post', $post->ID)
                || $post->post_type === 'revision') {
            return false;
        }

        return true;
    }

    public function getIdentifier() {
        return $this->identifier;
    }

    public function getTitle() {
        return $this->title;
    }

    public function doSave($post_id, $post) {
        if (!$this->verifyNonce($post)) {
            return;
        }

        $meta_key = $this->getIdentifier();
        $post_key = $meta_key . '_value';
        $value    = (array_key_exists($post_key, $_POST)) ? $_POST[$post_key] : NULL;

        if (get_post_meta($post->ID, $meta_key, true)) {
            if ($value) {
                update_post_meta($post->ID, $meta_key, $value);
            } else {
                delete_post_meta($post->ID, $meta_key);
            }
        } else {
            add_post_meta($post->ID, $meta_key, $value);
        }
    }

    protected function getMetaBoxDetails($post_id) {
        $meta_key = $this->getIdentifier();
        $post_key = $meta_key . '_value';
        $value    = get_post_meta($post_id, $meta_key, true);

        return array(
            $meta_key,
            $post_key,
            $value,
        );
    }
}