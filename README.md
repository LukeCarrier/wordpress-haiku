Haiku
=====

Because code is poetry.

* * *

Haiku is a tool that aims to vastly simplify the process of creating custom
post types for WordPress. Instead of manually adding actions for all those
crafty WordPress events, just define a class, instantiate it and call one method
on it during the ```init``` event.

Example taxonomy
----------------

Create a taxonomy called ```my_taxonomy```, and make it behave like a category:

    <?php

    namespace MyPlugin;

    Use Haiku\ATaxonomy;
    Use Haiku\ITaxonomy;

    class MyTaxonomy extends ATaxonomy implements ITaxonomy {
        /**
         * @override \Haiku\ATaxonomy
         */
        public function getIdentifier() {
            return 'my_taxonomy';
        }

        /**
         * @override \Haiku\ATaxonomy
         */
        public function getLabel() {
            return 'My Taxonomy Terms';
        }

        /**
         * @override \Haiku\ATaxonomy
         */
        public function isHierarchical() {
            return true; // false would cause a tag-like taxonomy
        }
    }

Since taxonomies are registered automatically when they're used within a post
type, you don't need to do anything further to have this show up in your menu.

Example post type
-----------------

Create a post type called ```my_post_type``` and add the taxonomy we just
created to it. This will handle all of the finicky metabox creation, nonce
checking and storing the data for you.

    <?php

    namespace MyPlugin;

    use Haiku\APostType;
    use Haiku\Fields\Text;
    use Haiku\IPostType;

    class MyPostType extends APostType implements IPostType {
        /**
         * @override \Haiku\APostType
         */
        public function getIdentifier() {
            return 'my_post_type';
        }

        /**
         * @override \Haiku\APostType
         */
        public function getPluralName() {
            return 'My Posts';
        }

        /**
         * @override \Haiku\APostType
         */
        public function getSingularName() {
            return 'My Post';
        }

        /**
         * @override \Haiku\APostType
         */
        public function getFields() {
            return array(
                new Text('my_custom_url', 'My Custom URL'),
            );
        }

        /**
         * @override \Haiku\APostType
         */
        public function getTaxonomies() {
            return array(
                new MyTaxonomy(),
            );
        }
    }

    add_action('init', function() {
        $post_type = new MyPostType();
        $post_type->register();
    });
