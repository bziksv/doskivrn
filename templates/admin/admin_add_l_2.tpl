<div class="xajax_add_box">
	{if isset($back)}
		<div style="padding: 10px 0 0 10px;"><a href="#{$back}" title="Вернуться назад">&laquo;&nbsp;Вернуться назад</a></div>
	{/if}
	{if isset($edit)}<script>edit=1;</script>{/if}
	<form id="form_edit" action="{$action}" onsubmit="f_save('form_edit','{$action}'); return false;" method="post">
	<table class="xajax_add_boxt3">
	{foreach name=outer item=item from=$items}
		{if $item.0 == 'text'}
		<tr>
			<td class="text">{$item.1.TITLE}:</td>
		</tr>
		<tr>
			<td class="html_editor2"><textarea name="{$item.1.NAME}" cols="50" rows="15">{$item.1.VALUE}</textarea>
			{if $item.1.HELP!=''}<br /><span class="message">* {$item.1.HELP}</span>{/if}</td>
		</tr>
		{elseif $item.0 == 'textsmall'}
		<tr>
			<td class="text">{$item.1.TITLE}:</td>
			<td class="html_editor"><textarea name="{$item.1.NAME}" cols="50" rows="4">{$item.1.VALUE}</textarea>
			{if $item.1.HELP!=''}<br /><span class="message">* {$item.1.HELP}</span>{/if}</td>
		</tr>
		{elseif $item.0 == 'editor'}
		</table>
		<script>editor=true;</script>
		<table class="xajax_add_boxt">
		<tr>
			<td class="text">{$item.1.TITLE}:</td>
		</tr>
		<tr>
			<td class="html_editor">
			<input type="hidden" id="FCKeditor_{$item.1.NAME}" class="editor" name="{$item.1.NAME}" value="{$item.1.VALUE}" style="display:none" />
			<input type="hidden" id="FCKeditor_{$item.1.NAME}___Config" value="" style="display:none" />
			<iframe id="FCKeditor_{$item.1.NAME}___Frame" src="/fckeditor/editor/fckeditor.html?InstanceName=FCKeditor_{$item.1.NAME}&amp;Toolbar=Default" width="400" height="300" frameborder="0" scrolling="no"></iframe>
			{if $item.1.HELP!=''}<br /><span class="message">* {$item.1.HELP}</span>{/if}</td>
		</tr>
		</table>
		<table class="xajax_add_boxt">
		{/if}
		
	{/foreach}
	<tr>
		<td><div class="form_button"><input type="submit" value="Сохранить" /></div></td>
	</tr>
	</table>
	<br /><br />
	</form>
</div>