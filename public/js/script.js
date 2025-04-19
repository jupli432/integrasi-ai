"use strict";



/* ==== Jquery Functions ==== */
(function ($) {


	$('[data-toggle="offcanvas"]').on("click", function () {
		$(".navbar-collapse").toggleClass("show");
	});

	const header = document.getElementById('siteheader');
	const scrollThreshold = 500;

	window.onscroll = () => window.pageYOffset > scrollThreshold ? header.classList.add('issticky') : header.classList.remove('issticky');



	






	/* ==== Revolution Slider ==== */
	if ($('.tp-banner').length > 0) {
		$('.tp-banner').show().revolution({
			delay: 6000,
			startheight: 550,
			startwidth: 1140,
			hideThumbs: 1000,
			navigationType: 'none',
			touchenabled: 'on',
			onHoverStop: 'on',
			navOffsetHorizontal: 0,
			navOffsetVertical: 0,
			dottedOverlay: 'none',
			fullWidth: 'on'
		});
	}


	//Top search bar open/close
	if (!$('.srchbox').hasClass("searchStayOpen")) {
		$("#jbsearch").click(function () {
			$(".srchbox").addClass("openSearch");
			$(".additional_fields").slideDown();
		});


		$(".srchbox").click(function (e) {
			e.stopPropagation();
		});
	}



	window.onload = () => {
		$('#show_alert').modal('show');
	}

})(jQuery);



