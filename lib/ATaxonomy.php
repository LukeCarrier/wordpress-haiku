<?php

/**
 * Haiku: because code is poetry.
 *
 * @author Luke Carrier <luke@carrier.im>
 * @license GPL v3
 */

namespace Haiku;

abstract class ATaxonomy {
    final public function __construct() {}

    final public function register($post_type_identifier) {
        register_taxonomy($this->getIdentifier(), $post_type_identifier, array(
            'label'   => __($this->getLabel()),
            'rewrite' => array(
                'slug' => $this->getRewriteSlug(),
            ),

            'hierarchical' => $this->isHierarchical(),
        ));

        add_action($this->getIdentifier() . '_add_form_fields',
                   array($this, 'registerFields'));
        add_action($this->getIdentifier() . '_edit_form_fields',
                   array($this, 'registerFields'));

        add_action('create_' . $this->getIdentifier(),
                   array($this, 'doSave'));
        add_action('edited_' . $this->getIdentifier(),
                   array($this, 'doSave'));
    }

    final public function doSave($term_id) {
        foreach ($this->getFields() as $field) {
            $field->doSaveForTerm($term_id);
        }
    }

    public function getFields() {
        return array();
    }

    public function getRewriteSlug() {
        return $this->getIdentifier();
    }

    public function isHierarchical() {
        return false;
    }

    final public function registerFields($taxonomy_or_term) {
        foreach ($this->getFields() as $field) {
            $field->registerOnTaxonomy($this->getIdentifier(), current_filter(),
                                       $taxonomy_or_term);
        }
    }
}
