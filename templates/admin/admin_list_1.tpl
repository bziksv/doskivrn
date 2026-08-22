<div class="xjaxcontent">

{*Поисковая форма*}
{if isset($items_names)}
<form action="#" method="post" onsubmit="f_search('{$action_search}'); return false;" id="f_search">
<table class="search_table">
	<tr>
		<td>Искать: </td>
		<td><input type="text" id="f_search_word" name="word" value="{if isset($smarty.get.search) && $smarty.get.search == 'true' && isset($smarty.get.word)}{$smarty.get.word}{/if}" /></td>
		<td>в</td>
		<td><select name="field" id="f_search_field">
		{foreach name=outer item=item from=$items_names key="key"}
			<option value="{$key}"{if isset($smarty.get.search) && $smarty.get.search == 'true' && isset($smarty.get.field) && $smarty.get.field == $key} selected="selected"{/if}>{$item}</option>
		{/foreach}
		</select></td>
		<td><input type="submit" value="начать поиск" /></td>
		{if isset($smarty.get.search) && $smarty.get.search == 'true'}<td><a href="{$action_search}">убрать поиск</a></td>{/if}
	</tr>
</table>
</form>
<br />
{/if}
{*****************}

{*Отсеячная форма*}
{if isset($items_view)}
<form action="#" method="post" onsubmit="f_view('{$action_search}'); return false;" id="f_view">
<table class="search_table">
	<tr>
		<td>Показать: </td>
		<td><select name="field" id="f_view_field">
		{foreach name=outer item=item from=$items_view key="key"}
			<option value="{$item.id}"{if isset($smarty.get.view_select) && $smarty.get.view_select == 'true' && isset($smarty.get.view_uid) && $smarty.get.view_uid == $item.id} selected="selected"{/if}>{$item.name}</option>
		{/foreach}
		</select></td>
		<td><input type="submit" value="показать" /></td>
	</tr>
</table>
</form>
<br />
{/if}
{*****************}

{*Форма сортировки*}
{if isset($items_names_sort)}
<form action="#" method="post" onsubmit="f_sort('{$action_search}'); return false;" id="f_sort">
<table class="search_table">
	<tr>
		<td>Сортировать по: </td>
		<td><select id="f_sort_field">
		{foreach name=outer item=item from=$items_names_sort key="key"}
			<option value="{$key}"{if isset($items_names_sort_sel_field) && $items_names_sort_sel_field == $key} selected="selected"{/if}>{$item}</option>
		{/foreach}
		</select></td>
		<td>
			<select id="f_sort_type">
				<option value="asc"{if isset($items_names_sort_sel_type) && $items_names_sort_sel_type=='asc'} selected="selected"{/if}>По возрастанию</option>
				<option value="desc"{if isset($items_names_sort_sel_type) && $items_names_sort_sel_type=='desc'} selected="selected"{/if}>По убыванию</option>
			</select>
		</td>
		<td><input type="submit" value="сортировать" /></td>
	</tr>
</table>
</form>
<br />
{/if}
{*****************}

<div class="xajax_tablelist" id="search_results">
{if isset($back)}
	<div style="padding: 10px 0 0 10px;"><a href="#{$back}" title="Вернуться назад">&laquo;&nbsp;Вернуться назад</a></div>
{/if}
{if isset($alt)}
	<div style="padding: 10px 0 0 10px;">{$alt}</div>
{/if}
{if count($names) > 0}
<table class="xajax_tablelist">
	<thead>
		<tr>
		{foreach name=outer item=item from=$titles}
			<th>{$item}</th>
		{/foreach}
		</tr>
	</thead>
	<tbody>
		{if isset($i_start)}
			{assign var="i" value=`$i_start`}
		{else}
			{assign var="i" value="0"}
		{/if}
		{foreach name=outer item=item from=$names}
		{assign var="i" value=`$i+1`}
			
		<tr>
			<td width="15" align="center">{$i}.</td>
			{foreach name=outer item=it from=$item}
				<td>{$it}</td>
			{/foreach}
		</tr>
		{/foreach}
	</tbody>
</table>
{elseif !isset($alt)}
Ничего не найдено.
{/if}
</div>
{if isset($listing) && $listing != ''}
<div class="xajax_listing">
	{$listing}
</div>
{/if}
</div>