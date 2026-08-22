<div class="xjaxcontent">
<div class="xajax_tablelist">
{if isset($back)}
	<div style="padding: 10px 0 0 10px;"><a href="#{$back}" title="Вернуться назад">&laquo;&nbsp;Вернуться назад</a></div>
{/if}
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
			{foreach name=outer item=it from=$item}
				<td>{$it}</td>
			{/foreach}
		</tr>
		{/foreach}
	</tbody>
</table>
</div>
{if isset($listing) && $listing != ''}
<div class="xajax_listing">
	{$listing}
</div>
{/if}
</div>