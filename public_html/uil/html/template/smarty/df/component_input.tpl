<!-- df/component_input.tpl -->
{if isset($ONLYHIDDEN)}
    <input name="{$COMPONENTID}" type="hidden" id="{$COMPONENTID}" value="{$COMPONENTVALUE}">
{else}
    <div class="control-group">
        <label class="control-label" for="{$COMPONENTID}">{$COMPONENTLABEL}</label>
        <div class="controls">
            <div class="input-append">
                <input name="{$COMPONENTID}"
                       type="{if isset($COMPONENTTYPE)}{$COMPONENTTYPE}{else}text{/if}"
                       id="{$COMPONENTID}"
                       value="{$COMPONENTVALUE}"
                       {if $COMPONENTMAXLENGTH > 0}maxlength="{$COMPONENTMAXLENGTH}"{/if}>
                <span title="{$COMPONENTHELPTEXT}" class="add-on racoretooltip">&nbsp;<i class="icon-question-sign"></i></span>
            </div>
        </div>
    </div>
{/if}
