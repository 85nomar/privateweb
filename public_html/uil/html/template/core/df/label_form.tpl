<!-- core/df/right_form.tpl -->
<div class="span12" id="content">

    {include "$strBreadcrumbTemplate"}

    <form class="form-horizontal" action="{$strFormAction}" method="POST">
        <input type="hidden" name="numLabelID" value="{$larrDaten.numLabelID}">

        <div class="control-group">
            <label class="control-label" for="strName">{$L_BUSINESSLAYER}</label>
            <div class="controls">
                <div class="input-append">
                    <select name="numBulID">
                        {foreach $larrDaten.numBulID AS $larrValue}
                            <option value="{$larrValue.code}" {if $larrValue.selected == 1}selected="selected"{/if}>
                                {$larrValue.value}
                            </option>
                        {/foreach}
                    </select>
                </div>
            </div>
        </div>


        <div class="control-group">
            <label class="control-label" for="strName">{$L_NAME}</label>
            <div class="controls">
                <div class="input-append">
                    <input type="text" name="strName" maxlength="{$larrDaten.strNameMaxLength}" value="{$larrDaten.strName}">
                    <span title="{$larrDaten.strNameHelptext}" class="add-on racoretooltip"><i class="icon-question-sign"></i></span>
                </div>
            </div>
        </div>

        <div class="control-group">
            <label class="control-label" for="strCode">{$L_LABEL}</label>
            <div class="controls">
                <div class="input-append">
                    <textarea name="strLabel" id='strLabel'>{$larrDaten.strLabel}</textarea>
                 </div>
            </div>
        </div>

        <div class="form-actions">
            <input class="btn btn-primary" type="submit" value="{$L_SPEICHERN}">
        </div>

    </form>

</div>