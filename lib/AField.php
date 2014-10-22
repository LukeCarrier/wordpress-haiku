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

    final public function registerOnPostType($post_type_identifier) {
        add_meta_box($this->getIdentifier(), $this->getTitle(),
                     array($this, 'getMetaBoxHtml'), $post_type_identifier);
    }

    final public function registerOnTaxonomy($taxonomy_identifier, $mode,
                                             $taxonomy_or_term) {
        $mode = substr($mode, strlen($taxonomy_identifier) + 1);

        switch ($mode) {
            case 'add_form_fields':
                $term = (object) array(
                    'term_id'  => 0,
                    'taxonomy' => $taxonomy_or_term,
                );
                echo '<div class="form-field">',
                         '<label for="term_meta_' . $this->getIdentifier() . '">',
                             $this->getTitle(),
                         '</label>',
                         $this->getMetaBoxHtmlForTerm($term),
                     '</div>';
                break;

            case 'edit_form_fields':
                echo '<tr class="form-field">',
                         '<th scope="row" valign="top">',
                             '<label for="term_meta_' . $this->getIdentifier() . '">',
                                 $this->getTitle(),
                             '</label>',
                         '</th>',
                         '<td>',
                             $this->getMetaBoxHtmlForTerm($taxonomy_or_term),
                         '</td>',
                     '</tr>';
                break;
        }

    }

    final protected function getNonceHtml() {
        $id = $this->getIdentifier() . '_nonce';

        return '<input type="hidden" name="' . $id . '" '
             . 'id="' . $id .'" '
             . 'value="' . wp_create_nonce($this->getIdentifier()) . '" />';
    }

    final protected function verifyNonce($post=null) {
        $id = $this->getIdentifier() . '_nonce';

        if (!array_key_exists($id, $_POST)
                || !wp_verify_nonce($_POST[$id], $this->getIdentifier())) {
            return false;
        }

        if ($post !== null 
                && (!current_user_can('edit_post', $post->ID)
                        || $post->post_type === 'revision')) {
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

    public function doSaveForPost($post_id, $post) {
        if (!$this->verifyNonce($post)) {
            return;
        }

        $meta_key = $this->getIdentifier();
        $post_key = $meta_key . '_value';
        $value    = (array_key_exists($post_key, $_POST))
                ? $_POST[$post_key] : NULL;

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

    public function doSaveForTerm($term_id) {
        if (!$this->verifyNonce()) {
            return;
        }

        $meta_key = $this->getIdentifier();
        $post_key = $meta_key . '_value';
        $value    = (array_key_exists($post_key, $_POST))
                ? $_POST[$post_key] : NULL;

        $term_option = $_POST['taxonomy'] . '_' . $term_id;

        $term_meta = get_option($term_option, array());
        $term_meta[$meta_key] = $value;
        update_option($term_option, $term_meta);
    }

    protected function getPostDetails($post_id) {
        $meta_key = $this->getIdentifier();
        $post_key = $meta_key . '_value';
        $value    = get_post_meta($post_id, $meta_key, true);

        return array(
            $meta_key,
            $post_key,
            $value,
        );
    }

    protected function getTermDetails($taxonomy, $term_id) {
        $meta_key = $this->getIdentifier();
        $post_key = $meta_key . '_value';

        $term_meta = get_option($taxonomy . '_' . $term_id, array());
        $value     = (array_key_exists($meta_key, $term_meta))
                ? $term_meta[$meta_key] : NULL;

        return array(
            $meta_key,
            $post_key,
            $value,
        );
    }
}
