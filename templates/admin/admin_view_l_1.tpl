<div class="xjaxcontent">
<div class="xajax_add_box">
	{if isset($pagename)}<div class="xajax_menulineitem2">{$pagename}</div>{/if}
	{if isset($back)}
		<div style="padding: 10px 0 0 10px;"><a href="#{$back}" title="Вернуться назад">&laquo;&nbsp;Вернуться назад</a></div>
	{/if}
	<table class="xajax_add_boxt">
	{foreach name=outer item=item from=$items key="k"}
		<tr>
			<td class="{if $item=='----'}text21{else}text2{/if}"{if $item=='----'} colspan="2"{/if}>{$titles[$k]}:</td>
			{if $item=='----'}{else}<td class="input_text">{$item}</td>{/if}
		</tr>
	{/foreach}
	</table>
	{if isset($content)}<div class="xajax_menulineitem1">{$content}</div>{/if}
	<br /><br />
</div></div>