{if isset($error) && $error !=''}<div style="color:#ca3333;">{$error}</div><br />{/if}
{if isset($message) && $message !=''}<div style="color:#20a71a;">{$message}</div>{/if}

<div class="lightbox" style="display:block;">
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
			<strong id="results">0</strong>
		</div>
	</div>
	<form action="/order/" method="post" id="forder">
		<fieldset>
			<input type="hidden" id="rr1" name="r1" value="{if isset($smarty.post.r1)}{$smarty.post.r1}{/if}" />
			<input type="hidden" id="rr2" name="r2" value="{if isset($smarty.post.r2)}{$smarty.post.r2}{/if}" />
			<input type="hidden" id="rr3" name="r3" value="{if isset($smarty.post.r3)}{$smarty.post.r3}{/if}" />
			<input type="hidden" id="rr4" name="r4" value="{if isset($smarty.post.r4)}{$smarty.post.r4}{/if}" />
			<input type="hidden" id="rr5" name="r5" value="{if isset($smarty.post.r5)}{$smarty.post.r5}{/if}" />
			<input type="hidden" id="rr6" name="r6" value="{if isset($smarty.post.r6)}{$smarty.post.r6}{/if}" />
			<div class="sv-form">
				<div class="col1">
					<div class="row">
						<label for="i5">Ваше имя</label>
						<div class="text-inp">
							<input id="i5" class="text" name="name" type="text" value="{if isset($smarty.post.name)}{$smarty.post.name}{/if}" />
						</div>
					</div>
					<div class="row">
						<label for="i6">Компания</label>
						<div class="text-inp">
							<input id="i6" class="text" name="company" type="text" value="{if isset($smarty.post.company)}{$smarty.post.company}{/if}" />
						</div>
					</div>
				</div>
				<div class="col2">
					<div class="row">
						<label for="i7">Телефон</label>
						<div class="text-inp">
							<input id="i7" class="text" name="phone" type="text" value="{if isset($smarty.post.phone)}{$smarty.post.phone}{/if}" />
						</div>
					</div>
					<div class="row">
						<label for="i8">E-mail</label>
						<div class="text-inp">
							<input id="i8" class="text" name="mail" type="text" value="{if isset($smarty.post.mail)}{$smarty.post.mail}{/if}" />
						</div>
					</div>
				</div>
				<div class="row">
				
				<input type="checkbox" name="rule" value="Y" checked>
				 Нажимая на эту кнопку, я даю <a href="/pages/doskivrn_soglasie_pd/" target="_blank">согласие</a> на <a href="/pages/doskivrn_politika_pd/" target="_blank">обработку персональных данных</a>.
				
				</div>
			</div>
			<a href="#" class="btn-off" onclick="$('#forder').submit();">Оформить заказ</a>
		</fieldset>
	</form>
</div>