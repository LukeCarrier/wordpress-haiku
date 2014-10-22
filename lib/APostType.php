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

    protected $existing_taxonomies = array();

    final public function __construct() {
        static::initialise();
    }

    final protected static function initialise() {
        if (!static::$initialised) {
            add_action('admin_enqueue_scripts', function() {
                wp_register_script('haiku', plugins_url('haiku/assets/haiku.js'), array(
                    'jquery',
                    'jquery-ui-datepicker',
                    'media-upload',
                    'thickbox',
                ));

                wp_register_style('haiku-jquery-ui', '//code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css');
                wp_register_style('haiku', plugins_url('haiku/assets/haiku.css'), array(
                    'haiku-jquery-ui',
                    'thickbox',
                ));

                wp_enqueue_script('haiku');
                wp_enqueue_style('haiku');
            });
        }

        static::$initialised = true;
    }

    public function getFields() {
        return array();
    }

    final public function register() {
        $this->registerTaxonomies();
        $this->registerPostType();
    }

    final public function registerFields() {
        foreach ($this->getFields() as $field) {
            $field->registerOnPostType($this->getIdentifier());
        }
    }

    final private function registerPostType() {
        register_post_type($this->getIdentifier(), array(
            'labels' => array(
                'name'          => __($this->getPluralName()),
                'singular_name' => __($this->getSingularName()),
            ),

            'rewrite' => array(
                'slug' => $this->getRewriteSlug(),
            ),

            'has_archive' => $this->isArchivable(),
            'public'      => $this->isPublic(),

            'register_meta_box_cb' => array($this, 'registerFields'),

            'taxonomies' => $this->existing_taxonomies,

            'supports'   => $this->supports(),
        ));

        add_action('save_post', array($this, 'doSave'), 10, 2);
    }

    final public function registerTaxonomies() {
        foreach ($this->getTaxonomies() as $taxonomy) {
            if ($taxonomy instanceof ITaxonomy) {
                $taxonomy->register($this->getIdentifier());
            } else {
                $this->existing_taxonomies[] = $taxonomy;
            }
        }
    }

    final public function doSave($post_id, $post) {
        foreach ($this->getFields() as $field) {
            $field->doSaveForPost($post_id, $post);
        }
    }

    public function getRewriteSlug() {
        return $this->getIdentifier();
    }

    public function isArchivable() {
        return true;
    }

    public function isPublic() {
        return true;
    }

    public function supports() {
        return array();
    }
}
