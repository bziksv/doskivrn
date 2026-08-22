<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ru" lang="ru" >
<!--

	Mystery Group Design Studio (c)
	info@mygr.ru
	www.mystery-group.ru
	 
-->
<head>
	{include file='head.tpl'}
</head>
<body>
	<div id="wrapper">
		<div class="w-hold">
			{include file='header.tpl'}
			<div id="main">
				<div class="container">
					{if $smarty.get.page == 'price'}
					<div class="price-hold">
						<div class="cont-hold">
							{if $pagename}
								<h2>{$pagename}</h2>
							{/if}
							{$content}
						</div>
					</div>
					{else}
					<div class="twocol">
						<div class="m1">
							{include file='sidebar.tpl'}
							<div class="cont">
								<div class="c1">
									<div class="cont-hold">
										<h1>{$pagename}</h1>
										<div class="contact-hold">
											{$content}
										</div>
										{if $smarty.get.page == 'calc'}{include file='calc.tpl'}{/if}
										{if $smarty.get.page == 'catalog'}{include file='additional/catalog.tpl'}{/if}
									</div>
								</div>
							</div>
						</div>
					</div>
					{/if}
				</div>
			</div>
			{include file='footer.tpl'}
		</div>
	</div>
	{if $smarty.get.page == 'calc'}
		<div class="lightbox" id="popup1">
			<a href="#" class="close">close</a>
			<h2>Оформление заказа</h2>
			<div class="box">
				<ul class="info">
					<li class="type">
						<span>Тип продукции: </span>
						<strong id="res1">Брус</strong>
					</li>
					<li>
						<div class="col1">
							<span>Ширина:</span>
							<strong><font id="res2">100</font> <em>мм</em></strong>
						</div>
						<div class="col2">
							<span>Длина:</span>
							<strong><font id="res3">2</font> <em>м</em></strong>
						</div>
						<div class="col3">
							<span>Толщина:</span>
							<strong><font id="res4">25</font> <em>мм</em></strong>
						</div>
					</li>
					<li class="col-sh">
						<div class="col1">
							<span>Кол-во:</span>
							<strong><font id="res5">1</font> <em>шт</em></strong>
						</div>
					</li>
				</ul>
			</div>
			<div class="box">
				<div class="sum-box">
					<span>Общая стоимость:</span>
					<strong id="res6">0</strong>
				</div>
			</div>
			<form action="/order/" method="post" id="forder">
				<fieldset>
					<input type="hidden" id="rr1" name="r1" value="" />
					<input type="hidden" id="rr2" name="r2" value="" />
					<input type="hidden" id="rr3" name="r3" value="" />
					<input type="hidden" id="rr4" name="r4" value="" />
					<input type="hidden" id="rr5" name="r5" value="" />
					<input type="hidden" id="rr6" name="r6" value="" />
					<div class="sv-form">
						<div class="col1">
							<div class="row">
								<label for="i5">Ваше имя</label>
								<div class="text-inp">
									<input id="i5" class="text" name="name" type="text" value="" />
								</div>
							</div>
							<div class="row">
								<label for="i6">Компания</label>
								<div class="text-inp">
									<input id="i6" class="text" name="company" type="text" value="" />
								</div>
							</div>
						</div>
						<div class="col2">
							<div class="row">
								<label for="i7">Телефон</label>
								<div class="text-inp">
									<input id="i7" class="text" name="phone" type="text" value="" />
								</div>
							</div>
							<div class="row">
								<label for="i8">E-mail</label>
								<div class="text-inp">
									<input id="i8" class="text" name="mail" type="text" value="" />
								</div>
							</div>
						</div>
						<div class="row">
				
							<input type="checkbox" name="rule" value="Y" checked>
							 Нажимая кнопку, я даю <a target="_blank" href="/pages/doskivrn_soglasie_pd/">согласие</a> на <a target="_blank" href="/pages/doskivrn_politika_pd/">обработку персональных данных</a>.
						
						</div>
					</div>
					<a href="#" class="btn-off" onclick="$('#forder').submit();">Оформить заказ</a>
				</fieldset>
			</form>
		</div>
	{/if}
</body>
</html>