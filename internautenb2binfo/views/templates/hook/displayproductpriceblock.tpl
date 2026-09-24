{*
 * Renders the group message or the original selling price in the product price block.
 * Copyright (c) 2026 die.internauten.ch GmbH
 * License: MIT
 *}
{if isset($group_message) && $group_message}
    <div class="group-price-text alert alert-info" style="margin-top: 10px;">
        <i class="material-icons" title="{l s='Special pricing for your group extended' mod='internautenb2binfo'}">info</i>
        <span>{$group_message nofilter}</span>
    </div>
{/if}

{if isset($regular_price) && $regular_price}
    <br>
    <strong>{l s='Regular price:' mod='internautenb2binfo'} {$regular_price}</strong>
{/if}