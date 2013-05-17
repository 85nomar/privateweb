<!-- core/df/menu_form.tpl -->
<div class="span12" id="content">

    {include "$strBreadcrumbTemplate"}

    <form class="form-horizontal" action="{$strFormAction}" method="POST">
        <input type="hidden" name="numMenuID" value="{$larrDaten.numMenuID}">
        <input type="hidden" name="numOrder" value="{$larrDaten.numOrder}">

        <div class="control-group">
            <label class="control-label" for="numParentMenuID">{$L_PARENT}</label>
            <div class="controls">
                <div class="input-append">
                    <select id="numParentMenuID" name="numParentMenuID">
                        {foreach $larrDaten.numParentMenuID AS $larrValue}
                            <option value="{$larrValue.code}" {if $larrValue.selected == 1}selected="selected"{/if}>
                                {$larrValue.value}
                            </option>
                        {/foreach}
                    </select>
                 </div>
            </div>
        </div>

        <div class="control-group">
            <label class="control-label" for="numBulID">{$L_BUSINESSLAYER}</label>
            <div class="controls">
                <div class="input-append">
                    <select id="numBulID" name="numBulID">
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
                    <input type="text" id="strName" name="strName" maxlength="{$larrDaten.strNameMaxLength}" value="{$larrDaten.strName}">
                    <span title="{$larrDaten.strNameHelptext}" class="add-on racoretooltip"><i class="icon-question-sign"></i></span>
                </div>
            </div>
        </div>

        <div class="control-group">
            <label class="control-label" for="strLink">{$L_LINK}</label>
            <div class="controls">
                <div class="input-append">
                    <input type="text" id="strLink" name="strLink" maxlength="{$larrDaten.strLinkMaxLength}" value="{$larrDaten.strLink}">
                    <span title="{$larrDaten.strLinkHelptext}" class="add-on racoretooltip"><i class="icon-question-sign"></i></span>
                </div>
            </div>
        </div>

        <div class="control-group">
            <label class="control-label" for="strIcon">{$L_ICON}</label>
            <div class="controls">
                <div class="input-append">
                    <input type="text" id="strIcon" name="strIcon" maxlength="{$larrDaten.strIconMaxLength}" value="{$larrDaten.strIcon}">
                    <span title="{$larrDaten.strIconHelptext}" class="add-on racoretooltip"><i class="icon-question-sign"></i></span>
                </div>
            </div>
        </div>

        <div class="control-group">
            <label class="control-label" for="strRight">{$L_RECHT}</label>
            <div class="controls">
                <div class="input-append">
                    <input type="text" id="strRight" name="strRight" maxlength="{$larrDaten.strRightMaxLength}" value="{$larrDaten.strRight}">
                    <span title="{$larrDaten.strRightHelptext}" class="add-on racoretooltip"><i class="icon-question-sign"></i></span>
                </div>
            </div>
        </div>


        <div class="form-actions">
            <input class="btn btn-primary" type="submit" value="{$L_SPEICHERN}">
        </div>


    </form>

</div>