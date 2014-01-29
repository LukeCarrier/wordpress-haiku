(function($) {
    $(function() {
        /*
         * Date fields
         */

        $('input.jquery-datepicker').datepicker();

        /*
         * Single file fields
         */

        $('.media-upload-remove-button').click(function() {
            var fieldName = $(this).data('field-name');
            var $field     = $('input[name=' + fieldName + ']');

            $field.val('');

            return false;
        });

        $('.media-upload-select-button').click(function() {
            var fieldName = $(this).data('field-name');
            var $field    = $('input[name=' + fieldName + ']');

            tb_show('', 'media-upload.php?TB_iframe=true');

            window.send_to_editor = function(html) {
                url = $(html).attr('href');

                $field.val(url);
                $field.closest('.attachment-thumbnail').replaceWith(html);

                tb_remove();
            };

            return false;
        });
    });
})(jQuery);
