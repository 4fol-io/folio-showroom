/**
 * FolioShowroom Main Frontend Script
 */


/**
 * Boostrap dependencies
 * NOTE: Popover, dropdowns and tooltip require popper and util modules. Popovers also require tooltip.
 * popper already exists
 */
//import * as Popper from "@popperjs/core"

// import { Util, Alert, Button, Collapse, Dropdown, Modal, Offcanvas, Popover, ScrollSpy, Tab, Toast, Tooltip } from 'bootstrap';
import { Util, Dropdown, Offcanvas } from 'bootstrap';

/**
 * App modules
 */
import { debounce, isMobile, goTo } from './modules/utils.js';
import './modules/anchor-scrolls.js';


(function ($, themeData) {

  'use strict';

  // Expose jquery to modules
  window.jQuery = $;
  window.$ = $;

  // Aux constants
  const $body = $('body');
  const $win = $(window);
  const $doc = $(document);
  const $goup = $('.sticky-scroll');

  /**
   * On scroll event handler (debounced)
   */
  const onScroll = debounce(() => {

    const top = $win.scrollTop();

    if (top > 300) {
      $goup.removeClass('off');
    } else {
      $goup.addClass('off');
    }

  }, 10, true)


  /**
   * Append external link suffix
   * @param {object} item dom element
   */
  const appendExternal = function (item) {
    var $item = $(item);
    if (!$item.find('.visually-hidden').length) {
      $item.append(`<span class="visually-hidden">${themeData.t.externalLink}</span>`);
    }
  }

  /**
   * Sum fixed elements to offset
   */
  const getOffset = function (offset) {
    const $header = $('.site-header');
    offset = offset || 0;

    if ($header.length) {
      offset += $header.height();
    }

    if ($('body').hasClass('admin-bar')) {
      offset += $('#wpadminbar').height();
    }

    return offset;
  }


  /**
   * Watch offcanvas search click outside
   */
  const offCanvasSearchWatch = function (ev) {

    const $offcanvas_instance = Offcanvas.getInstance('#offcanvas-search')

    if ($offcanvas_instance) {
      for (var n = !1, r = ev.target; r != document.body; r = r.parentNode) {
        if ("masthead" == r.id) {
          n = !0;
          break
        }
      }
      n || $offcanvas_instance.toggle();
    }

  }


  /**
   * Setup menu
   */
  const setupMenu = function () {


    /* Offcanvas menu */

    const $offcanvas_menu = $('#offcanvas-menu');

    $offcanvas_menu.on('show.bs.offcanvas', function () {
      $('.offcanvas-menu-trigger').removeClass('closed');
    });

    $offcanvas_menu.on('hide.bs.offcanvas', function () {
      $('.offcanvas-menu-trigger').addClass('closed');
    });

    /* Offcanvas search */

    const $offcanvas_search = $('#offcanvas-search');

    $offcanvas_search.on('shown.bs.offcanvas', function () {
      $offcanvas_search.find('input[name="s"]').trigger('focus');
      $doc.on("click", offCanvasSearchWatch);
    });

    $offcanvas_search.on('hide.bs.offcanvas', function () {
      $doc.off("click", offCanvasSearchWatch);
    });

    /* Header Menu Dropdowns */

    const $dropdowns = $('.site-menu .dropdown');
    $dropdowns.each(function () {
      const $toggle = $(this).find('.dropdown-toggle');
      if ($toggle.length) {
        new Dropdown($toggle[0]);
      }
    })


    $dropdowns.on('mouseenter', function (ev) {
      const $toggle = $(this).find('.dropdown-toggle');
      if ($toggle.length) {
        const toggleInstance = Dropdown.getInstance($toggle[0]);
        if (toggleInstance) {
          toggleInstance.show();
        }
      }
    })

    $dropdowns.on('mouseleave', function (ev) {
      const $toggle = $(this).find('.dropdown-toggle');
      if ($toggle.length) {
        const toggleInstance = Dropdown.getInstance($toggle[0]);
        if (toggleInstance) {
          toggleInstance.hide();
          $toggle.blur();
        }
      }
    })

    // External links
    /*const $external = $( '.site-menu ul.navbar-nav a[target=_blank], ul.footer-list a[target=_blank], ul.footer-text a[target=_blank]' );
    $external.each( function () {
      appendExternal( this );
    } )*/

  }


  /**
   * Setup generic events
   */
  const setupEvents = function () {

    // Scroll event
    $win.on('scroll', function () {
      onScroll();
    });

    // Go to top scroll
    $goup.on('click', function () {
      window.scrollTo({ top: 0, behavior: 'smooth' });
      return false;
    });

    // Tooltips
    /*$( '[data-toggle="tooltip"]' ).tooltip( {
      animated: 'fade'
    } );*/

    // Popovers
    //$( '[data-toggle="popover"]' ).popover();

    // Fetch all the forms we want to apply custom validation styles to
    $doc.on('submit', '.needs-validation', function (e) {
      if (!this.checkValidity()) {
        e.preventDefault();
        e.stopPropagation();
        const el = document.querySelector('.needs-validation :invalid');
        if (el) {
          goTo(el, 'smooth', getOffset(100), function () {
            el.focus();
          });
        }
      }
      $(this).addClass('was-validated');
    });


  }


  /**
   * Position and Fade In image thumbnails after load complete
   */
  const preloadThumbs = function () {
    $(".img-cover.fade:not(.show),.img-fluid.fade:not(.show)").on("load", function () {
      $(this).addClass('show');
    }).each(function () {
      // forze for cached images
      if (this.complete) $(this).trigger("load");
    });
  }

  /**
   * Display header shadow on scroll
   */
  const stickyShadow = function () {

    const header = document.getElementById("masthead");

    if (header) {

      const intercept = document.createElement("div");
      intercept.setAttribute("data-observer-intercept", "");
      intercept.setAttribute("class", "header-observer");

      header.before(intercept);

      const observer = new IntersectionObserver(([entry]) => {
        header.classList.toggle("active", !entry.isIntersecting);
      });

      observer.observe(intercept);

    }
  }

  /**
   * Initialize application
   */
  $(function () {
    console.log('🚀 Folio Showroom is ready!');
    if (isMobile.any()) $body.addClass('device-touch');
    setupMenu();
    setupEvents();
    preloadThumbs();
    onScroll();
    stickyShadow();
  })

})(jQuery, typeof folioShowroomData !== 'undefined' ? folioShowroomData : {});