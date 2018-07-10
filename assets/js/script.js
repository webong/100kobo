// When live, change root_dir to /
var root_dir = '/';

var validate_price = 0;
function validate() {
	if (validate_price.length > 1) {
    	return confirm('You will be charged '+validate_price+'. Are you sure?');
    }
}
function set_validate(price) {
	validate_price = price;
}

$(document).ready(function() {

	// AJAX call for 5 mins kobo
	setInterval(function() {
		$.ajax({
			url: root_dir,
			type: 'POST'
		}).done(function() {
	  		console.log('AJAX CALL');
		});
	}, 1000 * 60 * 5); // 5 minutes

	// Load more button (center)
	var pathname = window.location.pathname;
	$("#load_more").click(function(e){
        e.preventDefault();
        loadMorePosts();
    });
 	var offset_lm = 1;
    var loadMorePosts = function(page) {
        $("#loading").show();
        $("#load_more").hide();
        $.ajax({
            // url:"<?php echo base_url() ?>main/loadMorePosts/"+offset_lm*10,
            url:root_dir+"main/loadMorePosts/"+offset_lm*10,
            type:'GET',
            data: {page:page}
        }).done(function(response){
        	if (response) {
	            $("#posts-list").append(response);
	            $("#loading").hide();
	        	$("#load_more").show();
	        	offset_lm++;
	            scrollPosts();
	        } else {
	        	$("#loading").hide();
	        	$("#no_more_posts").show();
	        }
        });
    };
    var scrollPosts  = function(){
        $('html, body').animate({
            scrollTop: $('#load_more').offset().top
        }, 1000);
        console.log('scrolling');
    };

    // Load more button (right)
    $("#t_load_more").click(function(e){
        e.preventDefault();
        loadMoreTrendings();
    });
    var t_offset_lm = 1;
    var loadMoreTrendings = function(page) {
        $("#t_loading").show();
        $("#t_load_more").hide();
        $.ajax({
            url: root_dir+"main/loadMoreTrendings/"+t_offset_lm*10,
            type:'GET',
            data: {page:page}
        }).done(function(response){
        	if (response) {
	            $("#t-posts-list").append(response);
	            $("#t_loading").hide();
	            $("#t_load_more").show();
	            t_offset_lm++;
	            scrollTrendings();
	        } else {
	        	$("#t_loading").hide();
	        	$("#no_more_t_posts").show();
	        }
        });
    };
    var scrollTrendings  = function(){
    	/*
        $('html, body').animate({
            scrollTop: $('#t_load_more').offset().top
        }, 1000);
        */
    };

	$('#calc-amount').on('input', function() {
		this.value = this.value.replace(/[^0-9\.]/g,'');
        calculate();
    });

    function calculate()
    {
    	var amount = $('#calc-amount').val();
    	var calculated = amount * 0.81;

    	calculated = calculated.toFixed(2);

    	if ($.isNumeric(calculated)) {
    		$('#calc-cash').html(calculated);
    	} else {
    		$('#calc-cash').html('Error');
    	}
    }

    $('#calc-amount-topup').on('input', function() {
		this.value = this.value.replace(/[^0-9\.]/g,'');
        calculateTopup();
    });

    function calculateTopup()
    {
    	var amount = $('#calc-amount-topup').val();
    	var calculated = amount * 100;

    	calculated = calculated.toFixed(0);

    	if ($.isNumeric(calculated)) {
    		$('#calc-topup').html(calculated);
    	} else {
    		$('#calc-topup').html('Error');
    	}
    }

	$('#voucher').change(function() {
		if ($(this).val() == 'mtn') {
			$(this).removeClass('v-glo');
			$(this).addClass('v-mtn');
			update_subtext();
		} else if ($(this).val() == 'glo') {
			$(this).removeClass('v-mtn');
			$(this).addClass('v-glo');
			update_subtext();
		} else {
			$(this).removeClass('v-mtn');
			$(this).removeClass('v-glo');
			$('.voucher-option').hide();
		}
	});

	function update_subtext()
	{
		if ($('#product').val() == 'paid') {
			$('.voucher-option').show();
			$('.voucher-option#calc-naira-holder').show();
			$('.voucher-option#calc-cash-holder').hide();
			$('.voucher-option#topup-info-holder').hide();
		} else if ($('#product').val() == 'vfc') {
			$('.voucher-option').show();
			$('.voucher-option#calc-naira-holder').hide();
			$('.voucher-option#calc-cash-holder').show();
			$('.voucher-option#topup-info-holder').hide();
		} else if ($('#product').val() == 'topu') {
			$('.voucher-option').show();
			$('.voucher-option#calc-naira-holder').hide();
			$('.voucher-option#calc-cash-holder').hide();
			$('.voucher-option#topup-info-holder').show();
		}
	}

	$('.popup_voucher').change(function() {
		if ($(this).val() == 'mtn') {
			$(this).removeClass('v-glo');
			$(this).addClass('v-mtn');
		} else if ($(this).val() == 'glo') {
			$(this).removeClass('v-mtn');
			$(this).addClass('v-glo');
		} else {
			$(this).removeClass('v-mtn');
			$(this).removeClass('v-glo');
		}
	});

	var amountHistory = 0;
	$('#product').change(function() {
		if ($(this).val() == 'paid') {
			amountHistory = $('#calc-amount').val();
			$('#calc-amount').val('10000');
			$('#calc-amount').prop('readonly', true);
			$('#calc-cash-holder').hide(0);
			$('#calc-naira-holder').show(0);
			$('#topup-info-holder').hide(0);
			$('.n-kobo .n').hide(0);
			$('.n-kobo .kobo').show(0);
			$('.n-kobo input').css('width', '83%');
		} else if($(this).val() == 'topu') {
			$('#calc-amount').prop('readonly', false);
			$('#calc-cash-holder').hide(0);
			$('#calc-naira-holder').hide(0);
			$('#topup-info-holder').show(0);
			$('#calc-amount').val(amountHistory);
			$('.n-kobo .n').show(0);
			$('.n-kobo .kobo').hide(0);
			$('.n-kobo input').css('width', '90%');
			calculate();
		} else {
			$('#calc-amount').prop('readonly', false);
			$('#calc-cash-holder').show(0);
			$('#calc-naira-holder').hide(0);
			$('#topup-info-holder').hide(0);
			$('#calc-amount').val(amountHistory);
			$('.n-kobo .n').show(0);
			$('.n-kobo .kobo').hide(0);
			$('.n-kobo input').css('width', '90%');
			calculate();
		}
	});

	$('.btn-comment').click(function() {
		// alert($(this).find('.number-n'));
	});

	$(document.body).on('click', '.btn-m', function() {
		var id = $(this).closest('.row.post').find('.hidden').text(); // get id
		var post_text = $(this).closest('.row.post').find('.post_text').text(); // get post text
		var post_image = $(this).closest('.row.post').find('.post_image').prop('src'); // get post text

		$('#post_id').val(id);
		$('#textarea_edit').text($.trim(post_text));

		if (typeof post_image != 'undefined') {
			$('#image_edit p span').text(post_image);
			$('#image_edit_img').attr('src', post_image);
			$('#upload_image').addClass('hidden');
			$('#image_edit_img').removeClass('hidden');
			$('#image_edit').removeClass('hidden');
		} else {
			$('#upload_image').removeClass('hidden');
			$('#image_edit_img').addClass('hidden');
			$('#image_edit').addClass('hidden');
		}
	});

	$('#remove_image').click(function() {
		$('#upload_image').removeClass('hidden');
		$('#image_edit_img').addClass('hidden');
		$('#image_edit').addClass('hidden');
	});

	$('#btn-posts').click(function() {
		$('button.selected').removeClass('selected');
		$(this).addClass('selected');

		$('.post.image').hide();
		$('.post.no-image').show();
	});

	$('#btn-gallery').click(function() {
		$('button.selected').removeClass('selected');
		$(this).addClass('selected');

		$('.post.no-image').hide();
		$('.post.image').show();

		$('.collapse.in').removeClass('in');
	});

    var text_max = 160;
    $('#textarea_feedback').html(text_max + ' remaining');

    $('#textarea').keyup(function() {
        var text_length = $('#textarea').val().length;
        var text_remaining = text_max - text_length;

        $('#textarea_feedback').html(text_remaining + ' remaining');
    });

    // Auto scroll trendings
    var i = 1;
    var relativeY = 0;
    var max_i = 0;
    var old_max_i = 0;
    // console.log('Max i: '+max_i);

	var refreshIntervalId = setInterval(function(){
		if ($("#t-posts-list .row.post-mini:eq("+i+")").length > 0) {
		    relativeY = $("#t-posts-list .row.post-mini:eq("+i+")").offset().top - $("#t-posts-list .row.post-mini:eq(0)").offset().top;
			max_i = $("#t-posts-list .row.post-mini").length;
			// console.log('Max_i: '+max_i);
			// console.log('Old_max_i: '+old_max_i);
			if (max_i > old_max_i && old_max_i != 0) {
				if (i != old_max_i) {
					relativeY = $("#t-posts-list .row.post-mini:eq("+old_max_i+")").offset().top - $("#t-posts-list .row.post-mini:eq(0)").offset().top;
				}
			}
			old_max_i = max_i;

			if (i < max_i - 1) {
	        	i++;
		    } else {
		    	i = 0;
		    }
		    // Animate scroll
		    $('#t-posts-list').animate({
	        	scrollTop: relativeY + 'px'
	    	}, 1000);
	    }
	}, 4000);


	setInterval(function() {
		$('#t-loading').slideUp(500);
		$('#t-posts-list').slideDown(500);
		$('#t-load-more').slideDown(500);
	}, 3000);
});