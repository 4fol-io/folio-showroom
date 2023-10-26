/**
 * FolioShowroom Customizer Main Script
 *
 * Theme Customizer enhancements for a better user experience.
 *
 * Contains handlers to make Theme Customizer preview reload changes asynchronously.
 */

(function ($) {

	// Site title
	wp.customize('blogname', function (value) {
		value.bind(function (to) {
			console.log(to);
			$('.site-title a').text(to);
		});
	});

	// Site description
	wp.customize('blogdescription', function (value) {
		value.bind(function (to) {
			$('.site-description').text(to);
			if (to !== '') {
				$('.site-title').addClass('desc-on');
			} else {
				$('.site-title').removeClass('desc-on');
			}
		});
	});

	// Headere blur
	wp.customize('siteheaderblur', function (value) {
		console.log(value);
		value.bind(function (to) {
			$('.site-header').css({
				'-webkit-backdrop-filter': 'blur(' + to + 'px)',
				'backdrop-filter': 'blur(' + to + 'px)'
			});
			/*if ('blank' === to) {
				$('.site-title, .site-description').css({
					clip: 'rect(1px, 1px, 1px, 1px)',
					position: 'absolute',
				});
			} else {
				$('.site-title, .site-description').css({
					clip: 'auto',
					position: 'relative',
				});
				$('.site-title a, .site-description').css({
					color: to,
				});
			}*/
		});
	});

})(jQuery);
