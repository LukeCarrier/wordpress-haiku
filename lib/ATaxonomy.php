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
    }

    public function getRewriteSlug() {
        return $this->getIdentifier();
    }

    public function isHierarchical() {
        return false;
    }
}