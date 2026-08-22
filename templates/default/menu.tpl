<ul>
{foreach name=outer item=item from=$menu}
 	<li{if isset($item.2)} class="active"{/if}><a href="{$item.0}">{$item.1}</a></li>
{/foreach}
</ul>