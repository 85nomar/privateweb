<!-- core/df/right_form.tpl -->
<div class="span12" id="content">

    {include 'uil/html/template/core/breadcrumb.tpl'}

    <form class="form-horizontal" action="{$strFormAction}" method="POST">
        <input type="hidden" name="numRightID" value="{$larrDaten.numBulID}">

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
            <label class="control-label" for="strCode">{$L_CODE}</label>
            <div class="controls">
                <div class="input-append">
                    <input type="text" name="strCode" value="{$larrDaten.strCode}">
                    <span title="test" class="add-on racoretooltip"><i class="icon-question-sign"></i></span>
                </div>
            </div>
        </div>

        <div class="form-actions">
                    <input class="btn btn-primary" type="submit" value="{$L_SPEICHERN}">
        </div>


    </form>

</div>