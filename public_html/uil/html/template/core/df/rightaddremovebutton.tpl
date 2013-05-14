<!-- core/rightaddremovebutton.tpl -->
{if $G_NUMROLLEID > 0}
    {if $HASRIGHT == 0}
        <a title="{$L_RECHTHINZUFUEGEN}" class="btn btn-danger racoretooltip" href="{$G_BASELINKACTION}&strAddRight={$RIGHT}"><i class="icon-plus-sign"></i></a>
    {else}
        <a title="{$L_RECHTENTFERNEN}" class="btn btn-success racoretooltip" href="{$G_BASELINKACTION}&strRemoveRight={$RIGHT}"><i class="icon-minus-sign"></i></a>
    {/if}
{/if}
