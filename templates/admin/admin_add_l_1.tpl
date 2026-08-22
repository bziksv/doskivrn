<div class="xajax_add_box"> 
<script type="text/javascript">
	{literal}for (i=0;i<editors.length;i++){
		delete CKEDITOR.instances[editors[i].name];
	}{/literal}
	editors = '';
	editors = Array();
</script>
	{if isset($back)}
		<div style="padding: 10px 0 0 10px;"><a href="#{$back}" title="Вернуться назад">&laquo;&nbsp;Вернуться назад</a></div>
	{/if}
	{if isset($edit)}<script>var edit=1;var import_data = false;</script>{else}<script>var edit=0;var import_data = false;</script>{/if}
	<form id="form_edit" action="{$action}" onsubmit="f_save('form_edit','{$action}'); return false;" method="post" enctype="multipart/form-data">
	<table class="xajax_add_boxt">
	{foreach name=outer item=item from=$items}
		{if $item.0 == 'input'}
		<tr>
			<td class="text">{$item.1.TITLE}:</td>
			<td class="input_text"><input type="text" name="{$item.1.NAME}" value="{$item.1.VALUE}"/>{if $item.1.HELP!=''}<br /><span class="message">* {$item.1.HELP}</span>{/if}</td>
		</tr>
		{elseif $item.0 == 'input_autocomplete'}
		<script type='text/javascript' src='http://{$smarty.server.HTTP_HOST}/js/autocomplete.js'></script>
		<link rel="stylesheet" type="text/css" href="http://{$smarty.server.HTTP_HOST}/styles/autocomplete.css">
		{literal}
		<script type="text/javascript">
		$().ready(function() {
			function findValueCallback(event, data, formatted) {
				$("<li>").html( !data ? "No match!" : "Selected: " + formatted).appendTo("#result");
			}
			function formatItem(row) {
				return row[0];
			}
			function formatResult(row) {
				return row[0].replace(/(<.+?>)/gi, '');
			}
			$("#suggest_{/literal}{$item.1.NAME}{literal}").autocomplete('{/literal}{$item.2}{literal}', {
				width: 300,
				multiple: true,
				matchContains: true,
				formatItem: formatItem,
				formatResult: formatResult
			});
			$("textarea").result(findValueCallback).next().click(function() {
			  $(this).prev().search();
			});
			$("#clear").click(function() {
				$(":input").unautocomplete();
			});
		});
		</script>
		{/literal}
		<tr>
			<td class="text">{$item.1.TITLE}:</td>
			<td class="input_text"><input type="text" name="{$item.1.NAME}" id="suggest_{$item.1.NAME}" value="{$item.1.VALUE}"/>{if $item.1.HELP!=''}<br /><span class="message">* {$item.1.HELP}</span>{/if}</td>
		</tr>
		{elseif $item.0 == 'password'}
		<tr>
			<td class="text">{$item.1.TITLE}:</td>
			<td class="input_text"><input type="password" name="{$item.1.NAME}1" value=""/>{if $item.1.HELP!=''}<br /><span class="message">* {$item.1.HELP}</span>{/if}</td>
		</tr>
		<tr>
			<td class="text">{$item.1.TITLE} (еще раз):</td>
			<td class="input_text"><input type="password" name="{$item.1.NAME}2" value=""/></td>
		</tr>
		{elseif $item.0 == 'select'}
		<tr>
			<td class="text">{$item.1.TITLE}:</td>
			<td class="select1"><select name="{$item.1.NAME}">
			{foreach item=item_op from=$item.2}
				<option value="{$item_op.id}"{if isset($item_op.selected)} selected="selected"{/if}>{$item_op.name}</option>
			{/foreach}
			</select>{if $item.1.HELP!=''}<br /><span class="message">* {$item.1.HELP}</span>{/if}</td>
		</tr>
		{elseif $item.0 == 'text'}
		</table>
		<table class="xajax_add_boxt">
		<tr>
			<td class="text">{$item.1.TITLE}:</td>
			<td class="html_editor"><textarea name="{$item.1.NAME}" cols="50" rows="15">{$item.1.VALUE}</textarea>
			{if $item.1.HELP!=''}<br /><span class="message">* {$item.1.HELP}</span>{/if}</td>
		</tr>
		</table>
		<table class="xajax_add_boxt">
		{elseif $item.0 == 'textsmall'}
		</table>
		<table class="xajax_add_boxt">
		<tr>
			<td class="text">{$item.1.TITLE}:</td>
			<td class="html_editor"><textarea name="{$item.1.NAME}" cols="50" rows="4">{$item.1.VALUE}</textarea>
			{if $item.1.HELP!=''}<br /><span class="message">* {$item.1.HELP}</span>{/if}</td>
		</tr>
		</table>
		<table class="xajax_add_boxt">
		{elseif $item.0 == 'file'}
		<tr>
			<td class="text">{$item.1.TITLE}:</td>
			<td><input type="file" name="{$item.1.NAME}"/>{if $item.1.VALUE != ''}<br /><a href="{$item.1.VALUE}" target="_blank"><img src="{$item.1.VALUE}" width="60" alt="" /></a><br /><input type="checkbox" name="{$item.1.NAME}_del"/>&nbsp;Удалить{/if}{if $item.1.HELP!=''}<br /><span class="message">* {$item.1.HELP}</span>{/if}</td>
		</tr>
		{elseif $item.0 == 'editor'}
		
		</table>
		<script>editor=true;</script>
		<table class="xajax_add_boxt4">
		<tr>
			<td class="text">{$item.1.TITLE}:</td>
		</tr>
		<tr>
			<td>
			<textarea name="{$item.1.NAME}" id="editor_{$item.1.NAME}" class="_editor_" cols="50" rows="15">{$item.1.VALUE}</textarea>
			
			<script type="text/javascript">
				var edit_{$item.1.NAME} = CKEDITOR.replace( 'editor_{$item.1.NAME}' );
				CKFinder.SetupCKEditor( edit_{$item.1.NAME}, '/ckfinder/' ) ;
				editors[editors.length] = edit_{$item.1.NAME}
			</script>
			
			{if $item.1.HELP!=''}<br /><span class="message">* {$item.1.HELP}</span>{/if}</td>
		</tr>
		</table>
		<table class="xajax_add_boxt">
		{elseif $item.0 == 'file'}
		</table>
		<table class="xajax_add_boxt">
		<tr>
			<td class="text">{$item.1.TITLE}:</td>
			<td class="html_editor"><input type="file" name="{$item.1.NAME}">{if $item.1.HELP!=''}<br /><span class="message">* {$item.1.HELP}</span>{/if}</td>
		</tr>
		</table>
		<table class="xajax_add_boxt">
		{elseif $item.0 == 'radio'}
		</table>
		<table class="xajax_add_boxt">
		<td class="text1">{$item.1.TITLE}:</td>
		<td class="input_radio"><input type="radio" name="{$item.1.NAME}" value="1"{if $item.1.VALUE == 1} checked="checked"{/if} />&nbsp;Да</td>
		<td class="input_radio"><input type="radio" name="{$item.1.NAME}" value="0"{if $item.1.VALUE == 0} checked="checked"{/if} />&nbsp;Нет</td>
		</table>
		<table class="xajax_add_boxt">
		{elseif $item.0 == 'radio_array'}
		</table>
		<table class="xajax_add_boxt">
		<td class="text1">{$item.1.TITLE}:</td>
		{foreach item=item_op from=$item.2}
		<td class="input_radio"><input type="radio" name="{$item.1.NAME}" value="{$item_op.id}"{if $item.1.VALUE == $item_op.id} checked="checked"{/if} />&nbsp;{$item_op.name}</td>
		{/foreach}
		</table>
		<table class="xajax_add_boxt">
		{elseif $item.0 == 'checkbox_array'}
		<tr>
		<td class="text1" valign="top">{$item.1.TITLE}:{if $item.1.HELP!=''}<br /><br /><span class="message">* {$item.1.HELP}</span>{/if}</td>
		<td class="input_radio">
		<div><span class="message"><a href="#" onclick="{literal}$('.class_{/literal}{$item.1.NAME}{literal}').attr('checked','checked');return false;{/literal}">выделить всё</a> | <a href="#" onclick="{literal}$('.class_{/literal}{$item.1.NAME}{literal}').attr('checked','');return false;{/literal}">убрать выделение</a></span></div>
		<br />
		{foreach item=item_op from=$item.2}
			<table class="xajax_add_boxt2">
				<td class="input_checkbox_text" width="120" valign="top">{$item_op.0.0}<br />
				<span class="message"><a href="#" onclick="{literal}$('.class_{/literal}{$item.1.NAME}_{$item_op.0.1}{literal}').attr('checked','checked');return false;{/literal}">выделить</a><br />
				<a href="#" onclick="{literal}$('.class_{/literal}{$item.1.NAME}_{$item_op.0.1}{literal}').attr('checked','');return false;{/literal}">убрать</a></span>
				</td>
				<td class="input_checkbox">
				{foreach item=item_op2 from=$item_op.1 key="k"}
				<div><input class="class_{$item.1.NAME} class_{$item.1.NAME}_{$item_op.0.1}" type="checkbox" name="{$item.1.NAME}[]" value="{$item_op2.id}"{if isset($item_op2.selected)} checked="checked"{/if} id="checkbox_{$item.1.NAME}_{$k}"/><label for="checkbox_{$item.1.NAME}_{$k}">{$item_op2.name}</label></div>
				{/foreach}
				</td>
			</table>
		{/foreach}
		<br />
		<div><span class="message"><a href="#" onclick="{literal}$('.class_{/literal}{$item.1.NAME}{literal}').attr('checked','checked');return false;{/literal}">выделить всё</a> | <a href="#" onclick="{literal}$('.class_{/literal}{$item.1.NAME}{literal}').attr('checked','');return false;{/literal}">убрать выделение</a></span></div>
		</td>
		</tr>
		{/if}
		
	{/foreach}
	<tr>
		<td class="text">&nbsp;</td>
		<td><div class="form_button"><input type="submit" value="Сохранить" /></div></td>
	</tr>
	</table>
	<br /><br />
	</form>
</div>