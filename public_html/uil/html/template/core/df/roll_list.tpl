<!-- core/right_list.tpl -->
<div class="span12" id="content">

    {include "$strBreadcrumbTemplate"}

    <div class="row-fluid actionbar">
        <div class="span4 table-action"><br>
            {if $R_COREROLL_INSERT}
                <a class="btn" href="{$G_BASELINK}&strAction=insertMask"><i class="icon-file"></i> {$L_NEU}</a>
                {include "$strRightButtonTemplate" HASRIGHT=$RROLL_COREROLL_INSERT RIGHT='INSERT'}
            {/if}
        </div>
        <div class="span4 table-pagination">
            <div class="pagination pagination-centered">
                <ul>
                    <li>Pagination fehlt</li>
                </ul>
            </div>
        </div>
        <div class="span4 table-search">
            <div class="input-prepend input-append pull-right"><br>
                <span id="anzahlgefunden" class="add-on">Anzahl: 0</span>
                <input type='search' id="searchfield" class="input-medium">
                <span class="add-on racoretooltip" title="{$L_SUCHEN}">
                    <i class="icon-search"></i>
                </span>
            </div>
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
                                    {include "$strRightButtonTemplate" HASRIGHT=$RROLL_COREROLL_UPDATE RIGHT='UPDATE'}
                                {/if}

                                {if $R_COREROLL_DELETE}
                                    <a class="racoretooltip" title="{$L_LOESCHEN}" href="{$G_BASELINK}&strAction=delete&numRollID={$larrValue.numRollID}">
                                        <i class="icon-trash"></i>
                                    </a>
                                    {include "$strRightButtonTemplate" HASRIGHT=$RROLL_COREROLL_DELETE RIGHT='DELETE'}
                                {/if}

                                {if $R_COREROLL_SIMULIEREN}
                                    <a class="racoretooltip" title="{$L_ROLLESIMULIEREN}" href="{$G_BASELINK}&strAction=simulateroll&numRollID={$larrValue.numRollID}">
                                       <i class="icon-desktop"></i>
                                    </a>
                                    {include "$strRightButtonTemplate" HASRIGHT=$RROLL_COREROLL_SIMULIEREN RIGHT='SIMULIEREN'}
                                {/if}

                            </div>


                        </td>
                    </tr>
                {/foreach}
            {else}
                <tr>
                    <td colspan="4">{$L_KEINEDATEN}</td>
                </tr>
            {/if}
        </tbody>

    </table>

</div>