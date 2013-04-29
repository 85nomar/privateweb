{if (isset($COMPONENTRIGHTEDIT) AND $COMPONENTRIGHTEDIT == 1) OR $COMPONENTROLLRIGHT == 1}
    <div class="control-group">
        <div class="controls input-append">
            <button type="{$COMPONENTTYPE}" class="btn {$COMPONENTICON}">{$COMPONENTLABEL}</button>
            {include file='uil/html/template/smarty/df/component_right_button.tpl'}
        </div>
    </div>
{/if}
