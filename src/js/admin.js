
/**
 * FolioShowroom Admin Script
 */

(function ($, themeData) {

    'use strict';

    console.log('🚀 Folio Showroom Admin ready!', themeData);

    // Inscription featured action buttons ajax handler
    $('.folio-showroom-action-btn-featured').on('click', function (e) {

        e.preventDefault();

        const $this = $(this);
        const _featured = $this.data('status') == 1 ? true : false;

        if (!$this.hasClass('disabled')) {

            $this.addClass('disabled');

            const _icon_class = _featured ? 'empty' : 'filled';
            const _toggle_class = _featured ? 'off' : 'on';
            const _toggle_label = _featured ? themeData.t.notFeatured : themeData.t.featured;
            const _url_obj = new URL($this.attr('href'));
            _url_obj.searchParams.set('featured', _featured ? 0 : 1);

            console.log($this.data('status'), _featured, _url_obj.href);

            // Ajax: update featured post status
            $.ajax(_url_obj.href)
                .done(function (data) {
                    if (data.success) {
                        $this
                            .removeClass('folio-showroom-featured-off folio-showroom-featured-on')
                            .addClass('folio-showroom-featured-' + _toggle_class)
                            .data('status', _featured ? 0 : 1)
                            .attr('data-status', _featured ? 0 : 1)
                            .attr('aria-label', _toggle_label)
                            .attr('title', _toggle_label)

                        $this.find('span.dashicons')
                            .removeClass('dashicons-star-filled dashicons-star-empty')
                            .addClass('dashicons-star-' + _icon_class);

                        $this.find('span.screen-reader-text').text(_toggle_label);
                    }
                })
                .fail(function (e) {
                    console.log("Error", e);
                }).always(function () {
                    $this.removeClass('disabled');
                });
        }

    });

})(jQuery, typeof folioShowroomAdminData !== 'undefined' ? folioShowroomAdminData : {})