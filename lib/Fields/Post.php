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

class Post extends AField implements IField {
    public function getMetaBoxHtmlForPost($post) {
        list($meta_key, $post_key, $value) = $this->getPostDetails($post->ID);
        $this->getMetaBox($meta_key, $post_key, $value);
    }

    public function getMetaBoxHtmlForTerm($term) {
        list($meta_key, $post_key, $value) = $this->getPostDetails($term->taxonomy,
                                                                   $term->term_id);
        $this->getMetaBox($meta_key, $post_key, $value);
    }

    protected function getMetaBox($meta_key, $post_key, $value) {
        $value = (int) $value ?: 0;
        $posts = $this->getSelectOptions();

        array_unshift($posts, (object) array(
            'ID'         => 0,
            'post_title' => __('-- Select --'),
        ));

        $options_html = '';
        foreach ($posts as $post) {
            $selected = ($post->ID === $value) ? ' selected="selected"' : '';
            $options_html .= '<option value="' . $post->ID . '"' . $selected . '>'
                          .      $post->post_title
                          .  '</option>';
        }

        echo $this->getNonceHtml(),
             '<select name="' . $post_key . '">' . $options_html . '</select>';
    }

    protected function getSelectOptions() {
        $options = get_posts($this->options['post_criteria']);

        if (array_key_exists('post_filter', $this->options)) {
            $options = array_map($this->options['post_filter'], $options);
        }

        return $options;
    }
}
