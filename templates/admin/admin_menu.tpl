{*					Меню						*}
{if (isset($menu_all)) && $menu_all == '1'}
<ul class="menu_classic">{$menu_all_info}</ul>
{*					пункт меню					*}
{elseif isset($menu_item) && $menu_item == '1'}
<li class="{if $menu_item_info.childrens==''}menu_item_non_childs{else}menu_item_yes_childs{/if}" id="menu_item_{$numeric}">
<div class="folder"><a href="#" rel="menuitem" open="{$menu_item_info.open}" openlink="{$menu_item_info.openlink}" class="{if $menu_item_info.childrens=='' && $menu_item_info.open==0}menu_item_non_childs{else}menu_item_yes_childs{/if}">&nbsp;</a></div>
<span class="numeric">{$menu_item_info.num}.</span>
<a href="#{$menu_item_info.link}" class="menutext" title="{$menu_item_info.help}">{$menu_item_info.name}</a>
{$menu_item_info.childrens}
</li>
{/if}