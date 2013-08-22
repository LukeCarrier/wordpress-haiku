<?php

/**
 * Haiku: because code is poetry.
 *
 * @author Luke Carrier <luke@carrier.im>
 * @license GPL v3
 */

namespace Haiku;

interface IPostType {
    // Internal stuff
    public function __construct();
    public function doSave($post_id, $post);
    public function register();

    // Identifiers and names
    public function getIdentifier();
    public function getPluralName();
    public function getSingularName();
    public function getRewriteSlug();

    // Behaviour changes
    public function isArchivable();
    public function isPublic();

    // The fun stuff
    public function getFields();
    public function getTaxonomies();
}
