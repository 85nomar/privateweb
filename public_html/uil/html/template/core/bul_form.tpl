<!-- core/df/bul_form.tpl -->
<div class="span12" id="content">

    {include 'uil/html/template/core/breadcrumb.tpl'}

    <form class="form-horizontal" action="{$strFormAction}" method="POST">
        <input type="hidden" name="numBulID" value="{$larrDaten.numBulID}">

        <div class="control-group">
            <label class="control-label" for="strName">name</label>
            <div class="controls">
                <div class="input-append">
                    <input type="text" name="strName" value="{$larrDaten.strName}">
                    <span title="test" class="add-on racoretooltip"><i class="icon-question-sign"></i></span>
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