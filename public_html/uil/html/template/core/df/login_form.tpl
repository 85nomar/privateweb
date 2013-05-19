<!-- core/df/login_form.tpl -->
<div class="span4">&nbsp;</div>
<div class="span4" id="content">

    <form class="form-horizontal" action="{$strFormAction}" method="POST">
        <input type="hidden" name="numConfigID" value="{$larrDaten.numConfigID}">
        <div class="control-group">
            <label class="control-label" for="strName">{$L_NAME}</label>
            <div class="controls">
                <div class="input-append">
                    <input type="text" id="strName" name="strName">
                </div>
            </div>
        </div>

        <div class="control-group">
            <label class="control-label" for="strPassword">{$L_PASSWORD}</label>
            <div class="controls">
                <div class="input-append">
                    <input type="password" id="strPassword" name="strPassword">
                </div>
            </div>
        </div>

        <div class="control-group">
            <div class="controls">
                <input class="btn btn-primary" type="submit" value="{$L_SPEICHERN}">
            </div>
        </div>
    </form>
</div>
<div class="span4">&nbsp;</div>