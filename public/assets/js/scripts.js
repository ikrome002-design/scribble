var $ = jQuery;

$(document).ready(function () {
	if ($('.textarea-wysihtml5').length > 0) {
		$('.textarea-wysihtml5').wysihtml5({
			toolbar: {
				'fa': true
			}
		});
	}
});
function convertToLocal(date = new Date(), date_only = false) {
	let day = date.getDate();
	let month = date.getMonth() + 1;
	let hour = date.getHours()
	let min = date.getMinutes()
	if (day < 10) {
		day = '0' + day;
	}

	if (month < 10) {
		month = `0${month}`;
	}

	if (hour < 10) {
		hour = `0${hour}`;
	}
	if (min < 10) {
		min = `0${min}`;
	}
	var current_date = date.getFullYear() + "-" + month + "-" + day;
	var current_time = hour + ":" + min;

	if (date_only) {
		return current_date
	}
	return current_date + 'T' + current_time

}
$('.set-datetime-local').each(function (i, obj) {

	if (!obj.value) {
		obj.value = convertToLocal(new Date())
	} else {
		obj.value = convertToLocal(new Date(obj.value))
	}
});

$('.set-date-local').each(function (i, obj) {

	if (!obj.value) {
		obj.value = convertToLocal(new Date(), true)
	} else {
		obj.value = convertToLocal(new Date(obj.value), true)
	}
});



$('#left-nav .nav-bottom-sec').slimScroll({
	height: '100%',
	size: '10px',
	color: '#999'
});


$('#bar-setting').click(function (e) {
	e.preventDefault();

	if ($(window).width() > 767) {
		$('body.has-left-bar').toggleClass('left-bar-open');
	} else {
		$('.left-nav-bar .nav-bottom-sec').slideToggle(500, function () {
			$('body.has-left-bar').toggleClass('left-bar-open');
		});
	}

});



$('#left-navigation').find('li.has-sub>a').on('click', function (e) {
	e.preventDefault();
	var $thisParent = $(this).parent();

	if ($thisParent.hasClass('sub-open')) {

		// Hide the Submenu
		$thisParent.removeClass('sub-open').children('ul.sub').slideUp(150);

	} else {

		// Show the Submenu
		$thisParent.addClass('sub-open').children('ul.sub').slideDown(150);

		// Hide Others Submenu
		$thisParent.siblings('.sub-open').removeClass('sub-open').children('ul.sub').slideUp(150);

	}
});

// Tooltip init

$(function () {
	$('[data-toggle="tooltip"]').tooltip()
});

// Form
(function () {

	$('.form-group.form-group-default .form-control').on('focus', function (e) {
		$(this).closest('.form-group').addClass('focused');
	}).on('blur', function (e) {
		var $closest = $(this).closest('.form-group');
		if ($(this).val().length > 0) {
			$closest.addClass('filled');
		} else {
			$closest.removeClass('filled');
		}
		$closest.removeClass('focused');
	});

	$('.form-group.form-group-default select.form-control').on('change', function () {
		$(this).closest('.form-group').addClass('filled');
	});

})();



