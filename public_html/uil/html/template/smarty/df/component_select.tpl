<!-- df/component_select.tpl -->
<div class="control-group">
    <label class="control-label" for="{$COMPONENTID}">{$COMPONENTLABEL}</label>
    <div class="controls">
        <div class="input-append">
            <select name="{$COMPONENTID}" id="{$COMPONENTID}">
                {foreach $larrValues AS $larrValue}
                    <option value='{$larrValue.code}' {if $larrValue.selected == 1}selected="selected"{/if} >
                        {$larrValue.value}
                    </option>
                {/foreach}
            </select>
            {if $COMPONENTHELPTEXT != ''}<span title="{$COMPONENTHELPTEXT}" class="add-on racoretooltip">&nbsp;<i class="icon-question-sign"></i></span>{/if}
        </div>
    </div>
</div>
