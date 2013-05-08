<!-- core/right_list.tpl -->
<div class="span12" id="content">

    {include 'uil/html/template/core/breadcrumb.tpl'}

    <div class="row-fluid actionbar">
        <div class="span4 table-action"><br>
            <div class="btn-group">

                {if $R_COREROLL_INSERT}
                    <a class="btn" href="{$G_BASELINK}&strAction=insertMask"><i class="icon-file"></i> {$L_NEU}</a>
                    {include 'uil/html/template/core/rightaddremovebutton.tpl' HASRIGHT=$RROLL_COREROLL_INSERT RIGHT='INSERT'}
                {/if}

            </div>
        </div>
        <div class="span4 pagination pagination-centered">
            <ul>
                <li>Pagination fehlt</li>
            </ul>
        </div>
        <div class="input-prepend input-append pull-right"><br>
            <span id="anzahlgefunden" class="add-on text-right span4">Anzahl: 0</span>
            <input type='text' id="searchfield" class="input-medium">
            <button type="button" id="searchbutton" class="btn">{$L_SUCHEN}</button>
        </div>
    </div>

    <table class="table table-bordered table-striped table-hover">

        <colgroup>
            <col width="10%">
            <col width="30%">
            <col width="40%">
            <col width="20%">
        </colgroup>

        <thead>
            <tr>
                <th>{$L_ID}</th>
                <th>{$L_KUERZEL}</th>
                <th colspan="2">{$L_NAME}</th>
            </tr>
        </thead>

        <tbody>
            {if $larrDaten|@count > 0}
                {foreach $larrDaten AS $larrValue}
                    <tr>
                        <td>{$larrValue.numRollID}</td>
                        <td>{$larrValue.strKuerzel}</td>
                        <td>{$larrValue.strName}</td>
                        <td>
                            <div class="icons">

                                {if $R_COREROLL_UPDATE}
                                    <a class="racoretooltip" title="{$L_BEARBEITEN}" href="{$G_BASELINK}&strAction=updateMask&numRollID={$larrValue.numRollID}">
                                        <i class="icon-edit"></i>
                                    </a>
                                    {include 'uil/html/template/core/rightaddremovebutton.tpl' HASRIGHT=$RROLL_COREROLL_UPDATE RIGHT='UPDATE'}
                                {/if}

                                {if $R_COREROLL_DELETE}
                                    <a class="racoretooltip" title="{$L_LOESCHEN}" href="{$G_BASELINK}&strAction=delete&numRollID={$larrValue.numRollID}">
                                        <i class="icon-trash"></i>
                                    </a>
                                    {include 'uil/html/template/core/rightaddremovebutton.tpl' HASRIGHT=$RROLL_COREROLL_DELETE RIGHT='DELETE'}
                                {/if}

                                {if $R_COREROLL_DELETE}
                                    <a title="{$L_ROLLESIMULIEREN}" href="{$G_BASELINK}&strAction=simulateroll&numRollID={$larrValue.numRollID}">
                                       <i class="icon-desktop"></i>
                                    </a>
                                {/if}

                            </div>


                        </td>
                    </tr>
                {/foreach}
            {else}
                <tr>
                    <td colspan="3">{$L_KEINEDATEN}</td>
                </tr>
            {/if}
        </tbody>

    </table>

</div>