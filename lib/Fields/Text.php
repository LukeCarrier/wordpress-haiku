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

class Text extends AField implements IField {
    public function getMetaBoxHtmlForPost($post) {
        list($meta_key, $post_key, $value) = $this->getPostDetails($post->ID);
        $this->getMetaBox($meta_key, $post_key, $value);
    }

    public function getMetaBoxHtmlForTerm($term) {
        list($meta_key, $post_key, $value) = $this->getTermDetails($term->taxonomy,
                                                                   $term->term_id);
        $this->getMetaBox($meta_key, $post_key, $value);
    }

    protected function getMetaBox($meta_key, $post_key, $value) {
        echo $this->getNonceHtml(),
             '<input type="text" name="' . $post_key . '" ',
             'id="' . $post_key . '" value="' . $value . '" ',
             'class="widefat" />';
    }
}
