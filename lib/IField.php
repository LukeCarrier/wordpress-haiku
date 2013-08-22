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
    public function register($post_type_identifier);

    // Identification stuff
    public function getIdentifier();
    public function getTitle();

    // Procedural stuff
    public function getMetaBoxHtml($post);
    public function doSave($post_id, $post);
}