/**
 * Script run inside a Customizer control sidebar
 */
(function ($, api) {

    api.controlConstructor['range-value'] = api.Control.extend({
        ready: function () {

            var $input = this.container.find('input[type=range]');

            if ($input.length) {

                var value = $input.val();
                var suffix = $input.attr('suffix') ? $input.attr('suffix') : '';

                $input.next().html('<strong>' + value + '</strong>' + suffix);

                $input.on('input', function () {
                    var suffix = ($(this).attr('suffix')) ? $(this).attr('suffix') : '';
                    $(this).next().html('<strong>' + this.value + '</strong>' + suffix);
                });

            }
        }
    });


})(jQuery, wp.customize)
