<!-- core/df/right_form.tpl -->
<div class="span12" id="content">

    {include 'uil/html/template/core/breadcrumb.tpl'}

    <form class="form-horizontal" action="{$strFormAction}" method="POST">
        <input type="hidden" name="numMenuID" value="{$larrDaten.numMenuID}">
        <input type="hidden" name="numOrder" value="{$larrDaten.numOrder}">

        <div class="control-group">
            <label class="control-label" for="numParentMenuID">{$L_PARENT}</label>
            <div class="controls">
                <div class="input-append">
                    <select name="numParentMenuID">
                        {foreach $larrDaten.numParentMenuID AS $larrValue}
                            <option value="{$larrValue.code}" {if $larrValue.selected == 1}selected="selected"{/if}>
                                {$larrValue.value}
                            </option>
                        {/foreach}
                    </select>
                    <span title="test" class="add-on racoretooltip"><i class="icon-question-sign"></i></span>
                </div>
            </div>
        </div>

        <div class="control-group">
            <label class="control-label" for="numBulID">{$L_BUSINESSLAYER}</label>
            <div class="controls">
                <div class="input-append">
                    <select name="numBulID">
                        {foreach $larrDaten.numBulID AS $larrValue}
                            <option value="{$larrValue.code}" {if $larrValue.selected == 1}selected="selected"{/if}>
                                {$larrValue.value}
                            </option>
                        {/foreach}
                    </select>
                    <span title="test" class="add-on racoretooltip"><i class="icon-question-sign"></i></span>
                </div>
            </div>
        </div>

        <div class="control-group">
            <label class="control-label" for="strName">{$L_NAME}</label>
            <div class="controls">
                <div class="input-append">
                    <input type="text" name="strName" value="{$larrDaten.strName}">
                    <span title="test" class="add-on racoretooltip"><i class="icon-question-sign"></i></span>
                </div>
            </div>
        </div>

        <div class="control-group">
            <label class="control-label" for="strLink">{$L_LINK}</label>
            <div class="controls">
                <div class="input-append">
                    <input type="text" name="strLink" value="{$larrDaten.strLink}">
                    <span title="test" class="add-on racoretooltip"><i class="icon-question-sign"></i></span>
                </div>
            </div>
        </div>

        <div class="control-group">
            <label class="control-label" for="strIcon">{$L_ICON}</label>
            <div class="controls">
                <div class="input-append">
                    <input type="text" name="strIcon" value="{$larrDaten.strIcon}">
                    <span title="test" class="add-on racoretooltip"><i class="icon-question-sign"></i></span>
                </div>
            </div>
        </div>

        <div class="control-group">
            <label class="control-label" for="strRight">{$L_RECHT}</label>
            <div class="controls">
                <div class="input-append">
                    <input type="text" name="strRight" value="{$larrDaten.strRight}">
                    <span title="test" class="add-on racoretooltip"><i class="icon-question-sign"></i></span>
                </div>
            </div>
        </div>


        <div class="form-actions">
            <input class="btn btn-primary" type="submit" value="{$L_SPEICHERN}">
        </div>


    </form>

</div>