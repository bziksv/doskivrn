$(document).ready(function(){
	$("a[class^='comgal']").prettyPhoto();
	$('a.btn-send').click(function(){
		$('#ffeed').submit();
		return false;
	})

	$('#feedback-captcha-refresh').click(function(){
		$('#feedback-captcha-img').attr('src', '/img.php?type=reg&' + new Date().getTime());
		$('#i5').val('').focus();
		return false;
	})
	
	$('#cgo').click(function (){
		calculation();
	})
	
});

var _width = 100;
var _length = 2
var _thickness = 25

function calculation (){
	_price = $('#baseprice').val();
	_count = $('#cnt').val();
	
	result = Math.round( _price * _width * _length * _thickness *  _count );
	$('#results').html(result);
	$('#res6').html(result);
	
	$('#rr1').val($('#res1').html())
	$('#rr2').val($('#res2').html())
	$('#rr3').val($('#res3').html())
	$('#rr4').val($('#res4').html())
	$('#rr5').val($('#res5').html())
	$('#rr6').val($('#res6').html())
}