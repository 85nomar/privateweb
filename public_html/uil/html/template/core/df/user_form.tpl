<!-- core/df/user_form.tpl -->
<div class="span12" id="content">

    {include "$strBreadcrumbTemplate"}

    <form class="form-horizontal" action="{$strFormAction}" method="POST">
        <input type="hidden" name="numUserID" value="{$larrDaten.numUserID}">

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
            <label class="control-label" for="strPassword">{$L_PASSWORD}</label>
            <div class="controls">
                <div class="input-append">
                    <input type="password" name="strPassword" maxlength="{$larrDaten.strPasswordMaxLength}" value="{$larrDaten.strPassword}">
                    <span title="{$larrDaten.strPasswordHelptext}" class="add-on racoretooltip"><i class="icon-question-sign"></i></span>
                </div>
            </div>
        </div>

        <div class="control-group">
            <label class="control-label" for="strRoll">{$L_ROLLE}</label>
            <div class="controls">
                {foreach $larrDaten.larrRollen AS $larrRolle}
                    <label class="checkbox">
                        <input name="arrRoll[]" type="checkbox" value="{$larrRolle.numRollID}" {if $larrRolle.selected}checked{/if}>{$larrRolle.strName}
                    </label>
                {/foreach}
            </div>
        </div>

        <div class="form-actions">
            <input class="btn btn-primary" type="submit" value="{$L_SPEICHERN}">
        </div>


    </form>

</div>