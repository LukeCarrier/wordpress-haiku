<?php

/**
 * Haiku: because code is poetry.
 *
 * @author Luke Carrier <luke@carrier.im>
 * @license GPL v3
 */

namespace Haiku;

interface IField {
    // Internal stuff
    public function __construct($id, $title, $options=array());
    public function registerOnPostType($post_type_identifier);
    public function registerOnTaxonomy($taxonomy_identifier, $mode,
                                       $taxonomy_or_term);

    // Identification stuff
    public function getIdentifier();
    public function getTitle();

    // Procedural stuff
    public function getMetaBoxHtmlForPost($post);
    public function getMetaBoxHtmlForTerm($term);
    public function doSaveForPost($post_id, $post);
    public function doSaveForTerm($term_id);
}
