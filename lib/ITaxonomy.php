<?php

/**
 * Haiku: because code is poetry.
 *
 * @author Luke Carrier <luke@carrier.im>
 * @license GPL v3
 */

namespace Haiku;

interface ITaxonomy {
    // Internal stuff
    public function __construct();
    public function register($post_type_identifier);

    // Identifiers and names
    public function getIdentifier();
    public function getLabel();
    public function getRewriteSlug();

    // Behaviour changes
    public function isHierarchical();

    // The fun stuff
    public function getFields();
}
