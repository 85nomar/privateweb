<!-- df/component_textarea.tpl -->
<div class="control-group">
    <label class="control-label" for="{$COMPONENTID}">{$COMPONENTLABEL}</label>
    <div class="controls">
        <div class="input-append">
            <textarea name="{$COMPONENTID}"
                      id="{$COMPONENTID}"
                      {if $COMPONENTMAXLENGTH > 0}maxlength="{$COMPONENTMAXLENGTH}"{/if}>{$COMPONENTVALUE}</textarea>
            {if $COMPONENTHELPTEXT != ''}<span title="{$COMPONENTHELPTEXT}" class="add-on racoretooltip">&nbsp;<i class="icon-question-sign"></i></span>{/if}
        </div>
    </div>
</div>
