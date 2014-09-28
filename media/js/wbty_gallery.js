// JavaScript Document

jQuery(document).ready(function($) {
				
	$('.image-thumb img').hover(function() {
		$(this).stop().animate({'opacity': 1, 'filter': 'alpha(opacity=100)'}, 500);
	}, function() {
		if ($(this).attr('class') != 'bright') $(this).stop().animate({'opacity': 0.4, 'filter': 'alpha(opacity=40)'}, 500);
	});
	
	$('.image-thumb img').click(function(e) {
		e.preventDefault();
		
		$('.bright').stop().animate({'opacity': 0.4, 'filter': 'alpha(opacity=40)'}, 500, function() {
			$(this).attr('class', '');
		});
		$(this).attr('class', 'bright');
		var src = $(this).attr('data-src');
		
		if (src == $('#showcase .image img').attr('src')) { return false; }
		$('#showcase .image img').attr('data-count', $(this).attr('data-count'));
		
		var title = $(this).parent().find('.image-title').html();
		var desc = $(this).parent().find('.image-desc').html();
		$('#showcase .image img').stop().animate({'opacity': 0, 'filter': 'alpha(opacity=0)'}, 300, function() {
			$(this).attr('src', src).stop().animate({'opacity': 1, 'filter': 'alpha(opacity=100)'}, 300);
			$('#showcase .image-title').html('<h3>' + title + '</h3>');
			$('#showcase .image-desc .desc').html(desc);
			if ($(document).scrollTop() > $('#showcase').offset().top) {
				$('html, body').animate({
					 scrollTop: $('#showcase').offset().top
				 }, 1000);
			}
		});

		window.location.hash = 'wbty_gallery/' + $(this).attr('data-id');
	});
	
	$('#showcase .prev-image').click(function() {
		curimage = parseInt($('#showcase .image img').attr('data-count'));
		if (curimage == 0) {
			newimage = parseInt($('#thumbs .image-thumb').length)-1;
		} else {
			newimage = curimage-1;
		}
		$('#thumbs .image-thumb img[data-count='+newimage+']').trigger('click');
	});
	
	$('#showcase .next-image').click(function() {
		curimage = parseInt($('#showcase .image img').attr('data-count'));
		if (curimage >= parseInt($('#thumbs .image-thumb').length)-1) {
			newimage = 0;
		} else {
			newimage = curimage+1;
		}
		$('#thumbs .image-thumb img[data-count='+newimage+']').trigger('click');
	});
	
	// save hash before click or it will be overwritten by first click
	hash = window.location.hash;

	// load first image
	$('#thumbs .image-thumb img').first().trigger('click');

	//unless we have one that matches the hash :)
	if (hash.indexOf('wbty_gallery')) {
		id = hash.replace('#wbty_gallery/', '');
		console.log(id);
		if ($('#thumbs .image-thumb img[data-id=' + id + ']').length > 0) {
			$('#thumbs .image-thumb img[data-id=' + id + ']').first().trigger('click');
		}
	}
	
	//preload main images
	var images = new Array()
	$('#thumbs .image-thumb img').each(function(key, value) {
		src = $(this).attr('data-src');
		count = $(this).attr('data-count');
		
		images[count] = new Image();
		images[count].src = src;
	});

});