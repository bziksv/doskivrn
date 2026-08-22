$(document).ready( function() {
	$("#auto_login").focus( function() {
		if ($(this).val() == 'Введите Ваш логин'){
			$(this).val( "" );
		}
	});
	$("#auto_login").blur( function() {
		if ($(this).val () == ''){
			$(this).val( "Введите Ваш логин" );
		}
	});
	$("#auto_password").focus( function() {
		if ($(this).val() == 'Введите Ваш пароль'){
			$(this).val( "" );
		}
	});
	$("#auto_password").blur( function() {
		if ($(this).val() == ''){
			$(this).val( "Введите Ваш пароль");
		}
	});
	$("#auto_form").submit( function() {
		if ($("#auto_login").val() == 'Введите Ваш логин'){
			showmessage("Вы не заполнили логин/пароль. Для авторизации данные поля являются обязательными!");
			return false;
		} else if ($("#auto_password").val() == 'Введите Ваш пароль'){
			showmessage("Вы не заполнили логин/пароль. Для авторизации данные поля являются обязательными!");
			return false;
		} else return true;
	});
});