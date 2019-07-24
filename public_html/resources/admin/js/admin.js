(function($) {
  "use strict"; // Start of use strict
  // Configure tooltips for collapsed side navigation
  $('.navbar-sidenav [data-toggle="tooltip"]').tooltip({
    template: '<div class="tooltip navbar-sidenav-tooltip" role="tooltip"><div class="arrow"></div><div class="tooltip-inner"></div></div>'
  })
  // Toggle the side navigation
  $("#sidenavToggler").click(function(e) {
    e.preventDefault();
    $("body").toggleClass("sidenav-toggled");
    $(".navbar-sidenav .nav-link-collapse").addClass("collapsed");
    $(".navbar-sidenav .sidenav-second-level, .navbar-sidenav .sidenav-third-level").removeClass("show");
  });
  // Force the toggled class to be removed when a collapsible nav link is clicked
  $(".navbar-sidenav .nav-link-collapse").click(function(e) {
    e.preventDefault();
    $("body").removeClass("sidenav-toggled");
  });
  // Prevent the content wrapper from scrolling when the fixed side navigation hovered over
  $('body.fixed-nav .navbar-sidenav, body.fixed-nav .sidenav-toggler, body.fixed-nav .navbar-collapse').on('mousewheel DOMMouseScroll', function(e) {
    var e0 = e.originalEvent,
      delta = e0.wheelDelta || -e0.detail;
    this.scrollTop += (delta < 0 ? 1 : -1) * 30;
    e.preventDefault();
  });
  // Scroll to top button appear
  $(document).scroll(function() {
    var scrollDistance = $(this).scrollTop();
    if (scrollDistance > 100) {
      $('.scroll-to-top').fadeIn();
    } else {
      $('.scroll-to-top').fadeOut();
    }
  });
  // Configure tooltips globally
  $('[data-toggle="tooltip"]').tooltip()
  // Smooth scrolling using jQuery easing
  $(document).on('click', 'a.scroll-to-top', function(event) {
    var $anchor = $(this);
    $('html, body').stop().animate({
      scrollTop: ($($anchor.attr('href')).offset().top)
    }, 1000, 'easeInOutExpo');
    event.preventDefault();
  });
  
  // Inline popups
  $('.inline-popups').each(function () {
  	$(this).magnificPopup({
  		delegate: 'a',
  		removalDelay: 500, //delay removal by X to allow out-animation
  		callbacks: {
  			beforeOpen: function () {
  				this.st.mainClass = this.st.el.attr('data-effect');
  			}
  		},
  		midClick: true // allow opening popup on middle mouse click. Always set it to true if you don't provide alternative source.
  	});
  });

 // Bookmarks
 $('.wishlist_close').on('click', function (c) {
 	$(this).parent().parent().parent().fadeOut('slow', function (c) {});
 });
	
  // Selectbox
  $(".selectbox").selectbox();
  
  // Pricing add
	function newMenuItem() {
		var newElem = $('tr.pricing-list-item').first().clone();
		newElem.find('input').val('');
		newElem.appendTo('table#pricing-list-container');
	}
	if ($("table#pricing-list-container").is('*')) {
		$('.add-pricing-list-item').on('click', function (e) {
			e.preventDefault();
			newMenuItem();
		});
		$(document).on("click", "#pricing-list-container .delete", function (e) {
			e.preventDefault();
			$(this).parent().parent().parent().remove();
		});
	}

  function UpdateQueryString(key, value, url) {
      if (!url) url = window.location.href;
      var re = new RegExp("([?&])" + key + "=.*?(&|#|$)(.*)", "gi"),
          hash;

      if (re.test(url)) {
          if (typeof value !== 'undefined' && value !== null) {
              return url.replace(re, '$1' + key + "=" + value + '$2$3');
          } 
          else {
              hash = url.split('#');
              url = hash[0].replace(re, '$1$3').replace(/(&|\?)$/, '');
              if (typeof hash[1] !== 'undefined' && hash[1] !== null) {
                  url += '#' + hash[1];
              }
              return url;
          }
      }
      else {
          if (typeof value !== 'undefined' && value !== null) {
              var separator = url.indexOf('?') !== -1 ? '&' : '?';
              hash = url.split('#');
              url = hash[0] + separator + key + '=' + value;
              if (typeof hash[1] !== 'undefined' && hash[1] !== null) {
                  url += '#' + hash[1];
              }
              return url;
          }
          else {
              return url;
          }
      }
  }

  $(".filter .selectbox").change(function() {
    var newUrl = UpdateQueryString("page", null, window.location.href);
    window.location.href = UpdateQueryString($(this).attr("name"), $(this).val(), newUrl);
  })



})(jQuery); // End of use strict
