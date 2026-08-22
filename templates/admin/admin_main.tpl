<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html> 
<head> 
	<title>{if isset($smarty.session.adminLogin)}Панель управления сайтом - Mystery CMS{else}Авторизация администратора - Mystery CMS{/if}</title> 
	<meta http-equiv="content-type" content="text/html; charset=UTF-8" /> 
	<meta name="copyright" content="(C) 2009, mystery-group.ru " /> 
	<meta name="author" content="(C) 2009 mystery-group.ru" />
	<link href="/admin/css/main.css?{php}print rand();{/php}" type="text/css" rel="stylesheet" />
	<link href="/admin/css/enter.css?{php}print rand();{/php}" type="text/css" rel="stylesheet" />  
	<!--[if IE]><link href="/admin/css/main_ie.css?{php}print rand();{/php}" type="text/css" rel="stylesheet" /><![endif]-->
	
	<script type="text/javascript" src="/admin/js/jquery.js"></script>
	<script type="text/javascript" src="/admin/js/jquery.form.js"></script>
	<script type="text/javascript" src="/admin/js/jquery.history.js"></script>
	<script type="text/javascript" src="/admin/js/main.js?{php}print rand();{/php}"></script>
	<script type="text/javascript" src="/admin/js/messerr.js?{php}print rand();{/php}"></script>
	<script type="text/javascript" src="/ckeditor/ckeditor.js"></script>
	<script type="text/javascript" src="/ckfinder/ckfinder.js"></script>
	<script type="text/javascript" src="/admin/js/jquery-ui.js"></script>
	<script type="text/javascript" src="/admin/js/classic.js?{php}print rand();{/php}"></script>
	
	<link href="/admin/css/menu_classic.css?{php}print rand();{/php}" type="text/css" rel="stylesheet" />
	<!--[if IE]><link href="/admin/css/menu_classic_ie.css?{php}print rand();{/php}" type="text/css" rel="stylesheet" /><![endif]-->
	<link href="/admin/css/xajax.css?{php}print rand();{/php}" type="text/css" rel="stylesheet" />
</head> 
<body>

	<div class="mt"><table height="100%" width="100%" cellpadding="0" cellspacing="0">
	<tr>
		<td class="header">
			<div class="logo"><img src="/admin/img/logo.png" title="Mystery CMS" alt="Mystery CMS" width="127" height="30" /></div>
			<div class="loginbox_"><div class="loginbox">
				<div class="loginbox1">Вы вошли как:&nbsp;&nbsp;<span title="">{$smarty.session.nick}</span>&nbsp;|</div>
				<div class="loginbox2"><a href="/admin/?exit=true" title="Выйти из режима администрирования">Выйти</a></div>
			</div></div>
			<div class="menuheader">
				<div class="menuheaderitemcheck"><a href="#" title="Управление системой">Управление</a></div>
				<div class="menuheaderitem"><a href="http://{$smarty.server.HTTP_HOST}/" target="_blank" title="Перейти на сайт">Сайт</a></div>
			</div>
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
			<table class="view_admin_classic">
				<tbody>
					<tr>
						<td width="250">
							<table class="panelitemfull" cellspacing="5">
								<tbody>
									<tr>
										<td class="panelitemfullbg">
										<table class="panelitembg" width="100%" cellpadding="0"
											cellspacing="0" height="100%">
											<tbody>
												<tr>
													<td height="23">
														<div class="panelitemtitle">Структура сайта</div>
													</td>
												</tr>
												<tr>
													<td class="panelmain" id="menu_main" valign="top"></td>
												</tr>
												<tr>
													<td height="7">
														<div class="panelbottom"></div>
													</td>
												</tr>
											</tbody>
										</table>
										</td>
									</tr>
								</tbody>
							</table>
						</td>
						<td>
							<table class="panelitemfull" cellspacing="5">
								<tbody>
									<tr>
										<td class="panelitemfullbg">
										<table class="panelitembg" width="100%" cellpadding="0"
											cellspacing="0" height="100%">
											<tbody>
												<tr>
													<td height="23">
														<div class="panelitemtitle" id="current_title"></div>
													</td>
												</tr>
												<tr>
													<td class="panelmain" id="main_content" valign="top"><div class="xjaxcontent"><div class="xajax_tablelist">Добро пожаловать в панель администратора.<br /><br />Из данной панели Вы можете управлять Вашим сайтом.</div></div></td>
												</tr>
												<tr>
													<td height="7">
														<div class="panelbottom"></div>
													</td>
												</tr>
											</tbody>
										</table>
										</td>
									</tr>
								</tbody>
							</table>
						</td>
					</tr>
				</tbody>
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
