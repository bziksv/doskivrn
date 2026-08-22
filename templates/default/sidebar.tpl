<div class="aside">
	<div class="feedback-head">
		<h3 class="feedback-title">ON-LINE ЗАЯВКА</h3>
		<div class="cite">
			<p>Воспользуйтесь on-line заявкой для быстрой связи с менеджером нашей компании.</p>
		</div>
	</div>
	<form method="post" id="ffeed">
		<input type="hidden" name="feedback_form" value="1" />
		<fieldset>
			{if isset($feedback_message) && $feedback_message != ''}
			<div class="feedback-alert feedback-alert--ok">{$feedback_message}</div>
			{elseif isset($feedback_errors) && $feedback_errors}
			<div class="feedback-alert feedback-alert--error">
				<strong>Исправьте ошибки в форме:</strong>
				<ul>
				{foreach from=$feedback_errors item=err}
					<li>{$err}</li>
				{/foreach}
				</ul>
			</div>
			{/if}
			<div class="contact-form">
				<div class="box">
					<div class="row{if isset($feedback_errors.name)} row--error{/if}">
						<label for="i1">Ваше имя</label>
						<div class="text-inp">
							<input id="i1" class="text" type="text" name="name" placeholder="Иван Иванов" value="{if isset($smarty.post.name)}{$smarty.post.name}{/if}" />
							{if isset($feedback_errors.name)}<span class="field-error">{$feedback_errors.name}</span>{/if}
						</div>
					</div>
					<div class="row">
						<label for="i2">Компания</label>
						<div class="text-inp"><input id="i2" class="text" type="text" name="company" placeholder="Необязательно" value="{if isset($smarty.post.company)}{$smarty.post.company}{/if}" /></div>
					</div>
				</div>
				<div class="box">
					<div class="row{if isset($feedback_errors.phone)} row--error{/if}">
						<label for="i3">Телефон</label>
						<div class="text-inp">
							<input id="i3" class="text" type="tel" name="phone" placeholder="+7-___-___-__-__" maxlength="16" autocomplete="tel" required="required" pattern="{literal}+7-[0-9]{3}-[0-9]{3}-[0-9]{2}-[0-9]{2}{/literal}" title="Формат: +7-___-___-__-__" value="{if isset($smarty.post.phone)}{$smarty.post.phone}{/if}" />
							{if isset($feedback_errors.phone)}<span class="field-error">{$feedback_errors.phone}</span>{/if}
						</div>
					</div>
					<div class="row{if isset($feedback_errors.mail)} row--error{/if}">
						<label for="i4">E-mail</label>
						<div class="text-inp">
							<input id="i4" class="text" type="text" name="mail" placeholder="mail@example.ru" value="{if isset($smarty.post.mail)}{$smarty.post.mail}{/if}" />
							{if isset($feedback_errors.mail)}<span class="field-error">{$feedback_errors.mail}</span>{/if}
						</div>
					</div>
				</div>
				<div class="box">
					<div class="row captcha-row{if isset($feedback_errors.code)} row--error{/if}">
						<label for="i5">Проверка</label>
						<div class="captcha-wrap">
							<span class="captcha-code">
								<span class="captcha-img-wrap">
								<img src="/img.php?type=reg" alt="Сколько будет?" id="feedback-captcha-img" />
								</span>
								<button type="button" id="feedback-captcha-refresh" class="captcha-refresh" title="Обновить код" aria-label="Обновить код">
									<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M17.65 6.35C16.2 4.9 14.21 4 12 4c-4.42 0-7.99 3.58-7.99 8s3.57 8 7.99 8c3.73 0 6.84-2.55 7.73-6h-2.08c-.82 2.33-3.04 4-5.65 4-3.31 0-6-2.69-6-6s2.69-6 6-6c1.66 0 3.14.69 4.22 1.78L13 11h7V4l-2.35 2.35z"/></svg>
								</button>
							</span>
							<div class="text-inp">
								<input id="i5" class="text captcha-inp" type="text" name="code" autocomplete="off" placeholder="Ответ" value="{if isset($smarty.post.code)}{$smarty.post.code}{/if}" />
								{if isset($feedback_errors.code)}<span class="field-error">{$feedback_errors.code}</span>{/if}
							</div>
						</div>
					</div>
				</div>
				<div class="box{if isset($feedback_errors.msg)} box--error{/if}">
					<div class="row row-message">
						<label for="t1">Сообщение</label>
						<div class="text-ar">
							<textarea id="t1" class="area" cols="30" rows="5" name="msg" placeholder="Опишите, что вас интересует…">{if isset($smarty.post.msg)}{$smarty.post.msg}{/if}</textarea>
							{if isset($feedback_errors.msg)}<span class="field-error">{$feedback_errors.msg}</span>{/if}
						</div>
					</div>
				</div>
				<div class="box box-consent{if isset($feedback_errors.rule)} box--error{/if}">
					<label class="consent-label" for="rule-check">
						<input type="checkbox" name="rule" value="Y" id="rule-check"{if isset($smarty.post.rule)} checked="checked"{/if}>
						<span class="consent-text">Нажимая «Отправить», даю <a target="_blank" href="/pages/doskivrn_soglasie_pd/">согласие</a> на <a target="_blank" href="/pages/doskivrn_politika_pd/">обработку персональных данных</a>.</span>
					</label>
					{if isset($feedback_errors.rule)}<span class="field-error field-error--consent">{$feedback_errors.rule}</span>{/if}
				</div>
				<a onclick="yaCounter11319412.reachGoal('ORDER2'); return true;" href="#" class="btn-send">Отправить</a>
			</div>
		</fieldset>
	</form>
</div>
