<!-- core/df/bul_form.tpl -->
<div class="span12" id="content">

    {include "$strBreadcrumbTemplate"}

    <form class="form-horizontal" action="{$strFormAction}" method="POST">
        <input type="hidden" name="numConfigID" value="{$larrDaten.numConfigID}">

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
            <label class="control-label" for="strName">{$L_VALUE}</label>
            <div class="controls">
                <div class="input-append">
                    <input type="text" name="strValue" maxlength="{$larrDaten.strValueMaxLength}" value="{$larrDaten.strValue}">
                    <span title="{$larrDaten.strValueHelptext}" class="add-on racoretooltip"><i class="icon-question-sign"></i></span>
                </div>
            </div>
        </div>

        <div class="form-actions">
            <input class="btn btn-primary" type="submit" value="{$L_SPEICHERN}">
        </div>

    </form>

</div>