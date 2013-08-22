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

class Date extends AField implements IField {
    public function getMetaBoxHtml($post) {
        list($meta_key, $post_key, $value) = $this->getMetaBoxDetails($post->ID);

        echo $this->getNonceHtml(),
             '<input type="text" name="' . $post_key . '" ',
             'id="' . $post_key . '" value="' . $value . '" ',
             'class="jquery-datepicker widefat" />';
    }
}