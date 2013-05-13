<!-- core/df/validtyp_form.tpl -->
<div class="span12" id="content">

    {include 'uil/html/template/core/breadcrumb.tpl'}

    <form class="form-horizontal" action="{$strFormAction}" method="POST">
        <input type="hidden" name="numValidTypID" value="{$larrDaten.numValidTypID}">

        <div class="control-group">
            <label class="control-label" for="strCode">{$L_CODE}</label>
            <div class="controls">
                <div class="input-append">
                    <input type="text" maxlength="{$larrDaten.strCodeMaxLength}" name="strCode" value="{$larrDaten.strCode}">
                    <span title="{$larrDaten.strCodeHelptext}" class="add-on racoretooltip"><i class="icon-question-sign"></i></span>
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
            <label class="control-label" for="strRegex">{$L_REGEX}</label>
            <div class="controls">
                <div class="input-append">
                    <textarea name="strRegex" id='strRegex'>{$larrDaten.strRegex}</textarea>
                </div>
            </div>
        </div>

        <div class="form-actions">
            <input class="btn btn-primary" type="submit" value="{$L_SPEICHERN}">
        </div>

    </form>

</div>