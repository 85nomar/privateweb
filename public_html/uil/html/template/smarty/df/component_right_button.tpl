
{if isset($COMPONENTRIGHT) AND isset($COMPONENTRIGHTTEXT) AND isset($COMPONENTROLLRIGHT)}
    <a class="btn-danger">test</a>
    {if $COMPONENTRIGHTEDIT AND $COMPONENTRIGHTTEXT != ''}
        {if $COMPONENTROLLRIGHT}
            <a href="{$COMPONENTRIGHTREMOVELINK}" class="btn btn-success racoretooltip" title="Recht entfernen"><i class="icon-minus-sign"></i></a>
        {else}
            <a href="{$COMPONENTRIGHTADDLINK}" class="btn btn-danger racoretooltip" title="Recht hinzufügen"><i class="icon-plus-sign"></i></a>
        {/if}
    {/if}
{/if}
