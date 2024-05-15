jQuery(document).ready(function($){
	// marking your touch and wheel event listeners as `passive` to improve your page's scroll performance.
	jQuery.event.special.touchstart = {
		setup: function( _, ns, handle ) {
			this.addEventListener("touchstart", handle, { passive: !ns.includes("noPreventDefault") });
		}
	};
	jQuery.event.special.touchmove = {
		setup: function( _, ns, handle ) {
			this.addEventListener("touchmove", handle, { passive: !ns.includes("noPreventDefault") });
		}
	};
	jQuery.event.special.wheel = {
		setup: function( _, ns, handle ){
			this.addEventListener("wheel", handle, { passive: true });
		}
	};
	jQuery.event.special.mousewheel = {
		setup: function( _, ns, handle ){
			this.addEventListener("mousewheel", handle, { passive: true });
		}
	};

	/* Start open submenus be default on mobile devices */
	function opensubmenus() {
		if($(window).width() < 768) {
			$("#top-navbar-collapse li").addClass('open');
			$("#header-navbar-collapse li").addClass('open');
			$("#top-navbar-collapse li a").attr('aria-expanded','true');
			$("#header-navbar-collapse li a").attr('aria-expanded','true');
		}else{
			$("#top-navbar-collapse li").removeClass('open');
			$("#header-navbar-collapse li").removeClass('open');
			$("#top-navbar-collapse li a").attr('aria-expanded','false');
			$("#header-navbar-collapse li a").attr('aria-expanded','false');
		}
	}
	$('#top-menu .navbar-toggle').click(function(){
		setTimeout(opensubmenus, 100);
	});
	$('#no-header-top-menu .navbar-toggle').click(function(){
		setTimeout(opensubmenus, 100);
	});
	$('#header-menu .navbar-toggle').click(function(){
		setTimeout(opensubmenus, 100);
	});
    $(window).resize(opensubmenus);
	opensubmenus();
	/* End open submenus be default on mobile devices */

	var isTouchDevice =	(
		('ontouchstart' in window) ||
		(navigator.maxTouchPoints > 0) ||
		(navigator.msMaxTouchPoints > 0)
	);
	
	function sleep(milliseconds) {
		var start = new Date().getTime();
		for (var i = 0; i < 1e7; i++) {
			if ((new Date().getTime() - start) > milliseconds){
				break;
			}
		}
	}
	
	if(!isTouchDevice){
		// open top menu item on focus in
		$(".dropdown").hover(
			function(e) {
				if (!($(this).hasClass('open'))) {
					$(".dropdown").removeClass("open");
					$(this).addClass("open");
				}
			},
			function(e) {
				if (($(this).hasClass('open'))) {
					sleep(250);
					$(this).removeClass("open");
				}
			}
		);
		
		// prevent blinkling
		$(".submenu-link").click(function(e) {
			e.stopPropagation();
		});
	}
	
	// open top menu item on focus in
	$(".dropdown").focusin(function(e) {
		if (!($(this).hasClass('open'))) {
			$(".dropdown").removeClass("open");
			$(this).addClass("open");
		}
	});
	
	
	/* Double click on root items links on Touch devices, and Click on non touch devices to open link */
	if(isTouchDevice){
		$(".dropdownt").dblclick(function(e){
			var linkhref = $(this).attr("href");
			window.location = linkhref;
		});
	}else{
		$(".dropdownt").click(function(e){
			var linkhref = $(this).attr("href");
			window.location = linkhref;
			//e.stopPropagation();
		});
	}

	// open first level links when double tap
	var tapped=false;
	$(".dropdownt").on("touchstart",function(e){
		if(!tapped){ //if tap is not set, set up single tap
			tapped=setTimeout(function(){
				tapped=null;
				//insert things you want to do when single tapped
			},300);   //wait 300ms then run single click code
		} else {    //tapped within 300ms of last tap. double tap
			clearTimeout(tapped); //stop single tap callback
			tapped=null;
			//insert things you want to do when double tapped
			var linkhref = $(this).attr("href");
			window.location = linkhref;
		}
	});

	function SetMegaMenu(){
		if($("body").css('direction')=='rtl'){ //RTL
			if($(window).width() >= 1024) {
				$(".rtl .megamenu .dropdown-menu").each(function(){
					var MegaMenuDropdown = $(this);
					var Window50 = $(window).width() * 0.50;
					var Window75 = $(window).width() * 0.75;
					var Window25 = $(window).width() * 0.25;
					MegaMenuDropdown.css("left", "auto");
					var ParentListItemRight = MegaMenuDropdown.parent().offset().left + MegaMenuDropdown.parent().width();
					var ListsItemsLength = $(this).children("li").length;
					if( ListsItemsLength > 3 ){
						MegaMenuDropdown.css("width", "100%");
						MegaMenuDropdown.children("li").css('width', '25%');
						MegaMenuDropdown.css("left", "0");
					}else if(ListsItemsLength == 3){
						MegaMenuDropdown.css("width", "75%");
						MegaMenuDropdown.children("li").css('width', '33%');
						if(ParentListItemRight < Window75){
							MegaMenuDropdown.css('left', '0');
						}
					}else if(ListsItemsLength == 2){
						MegaMenuDropdown.css("width", "50%");
						MegaMenuDropdown.children("li").css('width', '50%');
						if(ParentListItemRight <= Window50){
							MegaMenuDropdown.css('left', '0');
						}
					}else if(ListsItemsLength == 1){
						MegaMenuDropdown.css("width", "25%");
						MegaMenuDropdown.children("li").css('width', '100%');
						if(ParentListItemRight < Window25){
							MegaMenuDropdown.css('left', '0');
						}
					}
				});
			}else if($(window).width() >= 768 && $(window).width() < 1024) {
				$(".rtl .megamenu .dropdown-menu").each(function(){
					var MegaMenuDropdown = $(this);
					var Window50 = $(window).width() * 0.50;
					var Window75 = $(window).width() * 0.75;
					MegaMenuDropdown.css("left", "auto");
					var ParentListItemRight = MegaMenuDropdown.parent().offset().left + MegaMenuDropdown.parent().width();
					var ListsItemsLength = $(this).children("li").length;
					if( ListsItemsLength > 2 ){
						MegaMenuDropdown.css("width", "100%");
						MegaMenuDropdown.children("li").css('width', '33%');
						MegaMenuDropdown.css("left", "0");
					}else if(ListsItemsLength == 2){
						MegaMenuDropdown.css("width", "75%");
						MegaMenuDropdown.children("li").css('width', '50%');
						if(ParentListItemRight < Window75){
							MegaMenuDropdown.css('left', '0');
						}
					}else if(ListsItemsLength == 1){
						MegaMenuDropdown.css("width", "50%");
						MegaMenuDropdown.children("li").css('width', '100%');
						if(ParentListItemRight < Window50){
							MegaMenuDropdown.css('left', '0');
						}
					}
				});
			}else if($(window).width() < 768) {
				$(".rtl .megamenu .dropdown-menu").each(function(){
					var MegaMenuDropdown = $(this);
					MegaMenuDropdown.css("left", "auto");
					MegaMenuDropdown.css("width", "100%");
					MegaMenuDropdown.children("li").css('width', '100%');
				});
			}
		}else{ // LTR
			if($(window).width() >= 1024) {
				$(".megamenu .dropdown-menu").each(function(){
					var MegaMenuDropdown = $(this);
					var Window50 = $(window).width() * 0.50;
					var Window75 = $(window).width() * 0.75;
					var Window25 = $(window).width() * 0.25;
					MegaMenuDropdown.css("right", "auto");
					var ParentListItemLeft = MegaMenuDropdown.parent().offset().left;
					var ListsItemsLength = $(this).children("li").length;
					if( ListsItemsLength > 3 ){
						MegaMenuDropdown.css("width", "100%");
						MegaMenuDropdown.children("li").css('width', '25%');
						MegaMenuDropdown.css("right", "0");
					}else if(ListsItemsLength == 3){
						MegaMenuDropdown.css("width", "75%");
						MegaMenuDropdown.children("li").css('width', '33%');
						if(ParentListItemLeft > Window25){
							MegaMenuDropdown.css('right', '0');
						}
					}else if(ListsItemsLength == 2){
						MegaMenuDropdown.css("width", "50%");
						MegaMenuDropdown.children("li").css('width', '50%');
						if(ParentListItemLeft > Window50){
							MegaMenuDropdown.css('right', '0');
						}
					}else if(ListsItemsLength == 1){
						MegaMenuDropdown.css("width", "25%");
						MegaMenuDropdown.children("li").css('width', '100%');
						if(ParentListItemLeft > Window75){
							MegaMenuDropdown.css('right', '0');
						}
					}
				});
			}else if($(window).width() >= 768 && $(window).width() < 1024) {
				$(".megamenu .dropdown-menu").each(function(){
					var MegaMenuDropdown = $(this);
					var Window50 = $(window).width() * 0.50;
					var Window75 = $(window).width() * 0.75;
					MegaMenuDropdown.css("right", "auto");
					var ParentListItemLeft = MegaMenuDropdown.parent().offset().left;
					var ListsItemsLength = $(this).children("li").length;
					if( ListsItemsLength > 2 ){
						MegaMenuDropdown.css("width", "100%");
						MegaMenuDropdown.children("li").css('width', '33%');
						MegaMenuDropdown.css("right", "0");
					}else if(ListsItemsLength == 2){
						MegaMenuDropdown.css("width", "75%");
						MegaMenuDropdown.children("li").css('width', '50%');
						if(ParentListItemLeft > Window25){
							MegaMenuDropdown.css('right', '0');
						}
					}else if(ListsItemsLength == 1){
						MegaMenuDropdown.css("width", "50%");
						MegaMenuDropdown.children("li").css('width', '100%');
						if(ParentListItemLeft > Window50){
							MegaMenuDropdown.css('right', '0');
						}
					}
				});
			}else if($(window).width() < 768) {
				$(".megamenu .dropdown-menu").each(function(){
					var MegaMenuDropdown = $(this);
					MegaMenuDropdown.css("right", "auto");
					MegaMenuDropdown.css("width", "100%");
					MegaMenuDropdown.children("li").css('width', '100%');
				});
			}
		}
	}
	SetMegaMenu();
	$(window).resize(SetMegaMenu);

	// scroll to top
	$(window).scroll(function () {
		if ($(this).scrollTop() > 50) {
			$('#back-to-top').fadeIn();
		} else {
			$('#back-to-top').fadeOut();
		}
	});
	// scroll body to 0px on click
	$('#back-to-top').click(function () {
			$('body,html').animate({
				scrollTop: 0
			}, 800);
		return false;
	});

	$('.dropdown-menu li a').css('font-weight',$('.dropdown-menu>li>a').css('font-weight'));
	$('.page-footer button').css('color',$('body').css('color'));
	$('.page-footer input').css('color',$('body').css('color'));
	$('.page-footer optgroup').css('color',$('body').css('color'));
	$('.page-footer select').css('color',$('body').css('color'));
	$('.page-footer textarea').css('color',$('body').css('color'));

	// gallery
	$('a[href$=".jpg"], a[href$=".jpeg"], a[href$=".gif"], a[href$=".webp"], a[href$=".png"]').attr('data-lightbox', 'separate').attr('data-title',  $(this).find('img').attr('alt'));
	$('.gallery').each(function() {
	// set the rel for each gallery
		$(this).find('.gallery-icon a[href$=".jpg"], .gallery-icon a[href$=".jpeg"],.gallery-icon a[href$=".gif"],.gallery-icon a[href$=".webp"], .gallery-icon a[href$=".png"]').attr('data-lightbox', 'group-' + $(this).attr('id')).lightbox({
			infobar : true,
			protect: true
		});
		$('.gallery-icon').each(function() {
			$(this).find('a').attr('data-title',  $(this).find('a img').attr('alt'));
		})
	
	});

	//add class to woocommerce product categories
	$('.widget_product_categories .cat-item').addClass('panel box');

	$("#widgetModal").modal("show");

	// top navigation
	function setTopMenuBGColor(){
		if( $(document).scrollTop() > 50 ){
            $("#top-menu").addClass("navbar-default").removeClass("in-top").addClass("navbar-inverse").addClass("bg-inverse");
            if($(window).width() < 600 && currenttop){
                $(".admin-bar #top-menu").css('top', currenttop - 46 + 'px');
            }else{
                $(".admin-bar #top-menu").css('top', 'auto');
            }
		}else{
			$("#top-menu").removeClass("navbar-default").addClass("in-top").removeClass("navbar-inverse").removeClass("bg-inverse");
			if($(window).width() < 600 && currenttop){
                $(".admin-bar #top-menu").css('top', currenttop + 'px');
            }else{
                $(".admin-bar #top-menu").css('top', 'auto');
            }
		}
	}
	setTopMenuBGColor();
	if ($('body').hasClass('admin-bar')){
    var currenttop = $('.admin-bar #top-menu').position().top;
  }
	$(document).on("scroll", setTopMenuBGColor);
	$(window).resize(setTopMenuBGColor);
});