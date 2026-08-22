{if !isset($smarty.get.step)}
<div class="xjaxcontent">
<div class="xajax_tablelist">
<p><b>Импорт CSV Base</b></p>
<p>Импорт в <b>"{$name_import}"</b></p>
<div id="step">
{/if}

{if !isset($smarty.get.step) || $smarty.get.step == 1}


<form action="{$action}" method="post" onsubmit="f_import(); return false;" id="f_import">
<table class="xajax_add_boxt">
	<tr>
		<td class="text" width="190">Укажите файл:</td>
		<td><input type="file" name="import_ext" value="" /></td>
	</tr>
	<!-- <tr>
		<td class="text">Или укажите путь к файлу:</td>
		<td class="input_text"><input type="text" name="import_int" value="" /><br /><span class="message">* относительно корня сайта</span></td>
	</tr> -->
	<tr>
		<td class="text">&nbsp;</td>
		<td><div class="form_button"><input type="submit" value="Продолжить" /></div></td>
	</tr>
</table>
</form>

{elseif $smarty.get.step == 2}


<form action="{$action}" method="post" onsubmit="f_import(); return false;" id="f_import">
<input type="hidden" name="file" value="{$file}" />
<table class="xajax_add_boxt">
	<tr>
		<td class="text">Соответствие полей:</td>
		<td><table border="0">
			{foreach name=outer item=item from=$fields key="key"}
			<tr>
				<td width="150">Поле {$key+1} ({$item})</td>
				<td><select name="field[{$key}]"><option value="">---</option>{foreach name=outer item=item2 from=$names_fields key="key2"}<option value="{$item2}">{$key2}</option>{/foreach}</select></td>
			</tr>
			{/foreach}
			</table>
		</td>
	</tr>
	<tr>
		<td class="text">Образцы данных:</td>
		<td><textarea cols="50" rows="15">{$file_data}</textarea></td>
	</tr>
	<tr>
		<td class="text">&nbsp;</td>
		<td><div class="form_button"><input type="submit" value="Начать импорт" /></div></td>
	</tr>
</table>
</form>

{elseif $smarty.get.step == 3}
<p>Импорт закончен</p>
{/if}
{if !isset($smarty.get.step)}
</div>
</div>
</div>
{/if}