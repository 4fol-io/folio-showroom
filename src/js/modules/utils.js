/**
  * Debounce ES6 implementation
  * https://gist.github.com/beaucharman/1f93fdd7c72860736643d1ab274fee1a
  */
export const debounce = function (callback, wait, immediate = false) {
    let timeout = null;
    return function() {
      const callNow = immediate && !timeout;
      const next = () => callback.apply(this, arguments);

      clearTimeout(timeout);
      timeout = setTimeout(next, wait);

      if (callNow) {
        next();
      }
    }
};

/**
 * Mobile devices detection
 */
export const isMobile = {
    Android: function() {
        return navigator.userAgent.match(/Android/i);
    },
    BlackBerry: function() {
        return navigator.userAgent.match(/BlackBerry/i);
    },
    iOS: function() {
        return navigator.userAgent.match(/iPhone|iPad|iPod/i);
    },
    Opera: function() {
        return navigator.userAgent.match(/Opera Mini/i);
    },
    Windows: function() {
        return navigator.userAgent.match(/IEMobile/i);
    },
    any: function() {
        return (isMobile.Android() || isMobile.BlackBerry() || isMobile.iOS() || isMobile.Opera() || isMobile.Windows());
    }
};


/**
 * Go to Behaviour
 */
export const goTo = function (elm, behavior, offset, callback) {
  behavior = behavior || 'smooth';
  offset = offset || 0;

  const top = (elm.getBoundingClientRect().top + (window.scrollY || document.documentElement.scrollTop) - offset).toFixed();

  const onScrollTo = function () {
    if (window.pageYOffset.toFixed() === top) {
      window.removeEventListener('scroll', onScrollTo)
      if (callback) callback()
    }
  }

  window.addEventListener('scroll', onScrollTo)
  onScrollTo()

  window.scrollTo({
    top: top,
    behavior: behavior
  })

};


export const pageTransitions = function(){
  if($body.hasClass('with-transitions')){

    var transitionIn = $body.attr('data-transition-in'),
        transitionOut = $body.attr('data-transition-out'),
        durationIn = $body.attr('data-duration-in'),
        durationOut = $body.attr('data-duration-out');
      
    if( !transitionIn ) { transitionIn = 'fade-in'; }
    if( !transitionOut ) { transitionOut = 'fade-out'; }
    if( !durationIn ) { durationIn = 700; }
    if( !durationOut ) { durationOut = 300; }


    $wrapper.animsition({
      inClass: transitionIn,
      outClass: transitionOut,
      inDuration: Number(durationIn),
      outDuration: Number(durationOut),
      linkElement: 'a:not([target="_blank"]):not([href*="#"]):not([href*="#comment"]):not([href^="tel\\:"]):not([href^="mailto\\:"]):not([data-lightbox]):not(.bx-pager-link,.bx-prev,.bx-next,.comment-reply-link, #cancel-comment-reply-link)',
      loading: true,
      loadingParentElement: 'body',
      loadingClass: 'animsition-spinner',
      loadingInner: '<div class="spin1"></div><div class="spin2"></div><div class="spin3"></div><div class="spin4"></div>',
      timeout: false,
      timeoutCountdown: 5000,
      onLoadEvent: true,
      browser: [ 'animation-duration', '-webkit-animation-duration', '-o-animation-duration'],
      overlay : false,
      overlayClass : 'animsition-overlay',
      overlayParentElement : 'body'
    });

  }
}

export const animations = function(){
  if($body.hasClass('with-animations')){
    var $dataAnimateEl = $('[data-animate]');
    if( $dataAnimateEl.length > 0 ){
      if( !TeSEO.isMobile.any() && ($body.hasClass('device-lg') || $body.hasClass('device-md') || $body.hasClass('device-sm')) ){
        $dataAnimateEl.each(function(){
          var element = $(this),
            animationDelay = element.attr('data-delay'),
            animationDelayTime = 0;

          if( animationDelay ) { animationDelayTime = Number( animationDelay ); } else { animationDelayTime = 0; }

          if( !element.hasClass('animated') ) {
            element.addClass('not-animated');
            var elementAnimation = element.attr('data-animate');

            var waypoint = new Waypoint({
              element: element[0],
              handler: function(direction) {
                setTimeout(function() {
                  element.removeClass('not-animated').addClass( elementAnimation + ' animated');
                }, animationDelayTime);
              },
              offset: '95%'
            });

          }
        });
      }
    }
  }
}