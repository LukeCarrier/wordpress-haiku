<?php

/**
 * Haiku: because code is poetry.
 *
 * @author Luke Carrier <luke@carrier.im>
 * @license GPL v3
 */

namespace Haiku;

abstract class APostType {
    protected static $initialised = false;

    final public function __construct() {
        static::initialise();
    }

    final protected static function initialise() {
        if (!static::$initialised) {
            add_action('admin_enqueue_scripts', function() {
                wp_register_script('haiku', plugins_url('haiku/js/haiku.js'), array(
                    'jquery-ui-datepicker',
                ));
                wp_register_style('haiku_jqueryui', '//code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css');

                wp_enqueue_script('haiku');
                wp_enqueue_style('haiku_jqueryui');
            });
        }

        static::$initialised = true;
    }

    final public function register() {
        $this->registerPostType();
        $this->registerTaxonomies();
    }

    final public function registerFields() {
        foreach ($this->getFields() as $field) {
            $field->register($this->getIdentifier());
        }
    }

    final private function registerPostType() {
        register_post_type($this->getIdentifier(), array(
            'labels' => array(
                'name'          => __($this->getPluralName()),
                'singular_name' => __($this->getSingularName()),
            ),

            'has_archive' => $this->isArchivable(),
            'public'      => $this->isPublic(),

            'register_meta_box_cb' => array($this, 'registerFields')
        ));

        add_action('save_post', array($this, 'doSave'), 10, 2);
    }

    final public function registerTaxonomies() {
        foreach ($this->getTaxonomies() as $taxonomy) {
            $taxonomy->register($this->getIdentifier());
        }
    }

    final public function doSave($post_id, $post) {
        foreach ($this->getFields() as $field) {
            $field->doSave($post_id, $post);
        }
    }

    public function isArchivable() {
        return true;
    }

    public function isPublic() {
        return true;
    }
}
