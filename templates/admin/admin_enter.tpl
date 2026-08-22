<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html> 
<head> 
	<title>{if isset($dwoo.session.cms_admin)}Панель управления сайтом - Mystery CMS{else}Авторизация администратора - Mystery CMS{/if}</title> 
	<meta http-equiv="content-type" content="text/html; charset=UTF-8" /> 
	<meta name="copyright" content="(C) 2009, mystery-group.ru " /> 
	<meta name="author" content="(C) 2009 mystery-group.ru" />
	<link href="/admin/css/main.css?{php}print rand();{/php}" type="text/css" rel="stylesheet" />
	<link href="/admin/css/enter.css?{php}print rand();{/php}" type="text/css" rel="stylesheet" />  
	<!--[if IE]><link href="/admin/css/main_ie.css" type="text/css" rel="stylesheet" /><![endif]-->
	<script type="text/javascript" src="/admin/js/jquery.js"></script>
	<script type="text/javascript" src="/admin/js/main.js?{php}print rand();{/php}"></script>
	<script type="text/javascript" src="/admin/js/messerr.js?{php}print rand();{/php}"></script>
</head> 
<body>

	<div class="mt"><table height="100%" width="100%" cellpadding="0" cellspacing="0">
	<tr>
		<td class="header">
			<div class="logo"><img src="/admin/img/logo.png" title="Mystery CMS" alt="Mystery CMS" width="127" height="30" /></div>
		</td>
	</tr>
	<tr>
		<td class="lineheader"></td>
	</tr>
	<tr {if !isset($err) || $err == ''}style="display:none;"{/if} id="messageBoxe">
		<td class="messageboxex">
			<div id="messageBoxin"><div class="messagebox" id="messageBox">{if isset($err)}{$err}{/if}</div></div>
		</td>
	</tr>
	<tr>
		<td class="container">
			<script type="text/javascript" src="/admin/js/enter.js"></script>
			<br /><br /><table cellpadding="0" cellspacing="0" align="center">
				<tr>
					<td>
						<div class="enterbgtd">
							<div class="enterbg">
								<div class="enterh">Авторизация</div>
								<div class="enterb">
									<div align="center">Для работы с панелью администратора Вам необходимо авторизоваться.</div>
									<div class="entericon"><img src="/admin/img/enter_icon.jpg" title="" alt="" width="96" height="96" /></div>
									<form method="post" action="{$ref}" id="auto_form">
									<div class="enterform">
										<div class="enterformi"><div class="enterformtext">Логин:</div>
										<div class="enterforminput"><input tabindex="1" type="text" id="auto_login" name="EnterLogin" value="Введите Ваш логин" autocomplete="off" /></div></div>
										<div class="enterformi"><div class="enterformtext">Пароль:</div>
										<div class="enterforminput"><input tabindex="2" type="password" id="auto_password" name="EnterPassword" value="Введите Ваш пароль" autocomplete="off" /></div></div>
									</div>
									<div class="enterformbutton"><input tabindex="3" type="submit" value="Авторизоваться" /></div>
									</form>
								</div>
							</div>
						</div>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td class="footer">
			<table align="center" cellpadding="0" cellspacing="0"><tr><td>
				<div class="logob"><img src="/admin/img/logo2.jpg" title="Mystery CMS" alt="Mystery CMS" width="127" height="30" /></div>
				<div class="line"><img src="/admin/img/lineb.jpg" title="" alt="" width="2" height="46" /></div>
				<div class="text">&nbsp;<br />Разработано компанией <a href="http://mystery-group.ru/" target="_blank" title="Сайт компании">Mystery Group</a>. Версия 2.1.9</div>
			</td></tr></table>
		</td>
	</tr>
	</table></div>
	<div id="insertionMarker">
	  <img src="/admin/img/marker_top.gif">
	  <img src="/admin/img/marker_middle.gif" id="insertionMarkerLine">
	  <img src="/admin/img/marker_bottom.gif">
	</div>
</body>
</html>
