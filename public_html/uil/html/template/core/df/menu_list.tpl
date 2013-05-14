<!-- core/bul_list.tpl -->
<div class="span12" id="content">

    {include "$strBreadcrumbTemplate"}

    <div class="row-fluid actionbar">
        <div class="span4 table-action"><br>
            {if $R_COREMENU_INSERT}
                <a class="btn" href="{$G_BASELINK}&strAction=insertMask"><i class="icon-file"></i> {$L_NEU}</a>
                {include "$strRightButtonTemplate" HASRIGHT=$RROLL_COREMENU_INSERT RIGHT='INSERT'}
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
            <col width="20%">
            <col width="20%">
            <col width="20%">
            <col width="15%">
            <col width="15%">
        </colgroup>

        <thead>
            <tr>
                <th>{$L_ID}</th>
                <th>{$L_PARENT}</th>
                <th>{$L_BUSINESSLAYER}</th>
                <th>{$L_NAME}</th>
                <th colspan="2">{$L_RECHT}</th>
            </tr>
        </thead>

        <tbody>
            {if $larrDaten|@count > 0}
                {foreach $larrDaten AS $larrValue}
                    <tr>
                        <td>{$larrValue.numMenuID}</td>
                        <td>{$larrValue.strParentMenuName}</td>
                        <td>{$larrValue.strBulName}</td>
                        <td>{$larrValue.strName}</td>
                        <td>{$larrValue.strRight}</td>
                        <td>
                            <div class="icons">

                                {if $R_COREMENU_UPDATE}
                                    <a class="racoretooltip" title="{$L_BEARBEITEN}" href="{$G_BASELINK}&strAction=updateMask&numMenuID={$larrValue.numMenuID}">
                                        <i class="icon-edit"></i>
                                    </a>
                                    {include "$strRightButtonTemplate" HASRIGHT=$RROLL_COREMENU_UPDATE RIGHT='UPDATE'}
                                {/if}

                                {if $R_COREMENU_DELETE}
                                    <a class="racoretooltip" title="{$L_LOESCHEN}" href="{$G_BASELINK}&strAction=delete&numMenuID={$larrValue.numMenuID}">
                                        <i class="icon-trash"></i>
                                    </a>
                                    {include "$strRightButtonTemplate" HASRIGHT=$RROLL_COREMENU_DELETE RIGHT='DELETE'}
                                {/if}

                            </div>
                        </td>
                    </tr>
                {/foreach}
            {else}
                <tr>
                    <td colspan="6">{$L_KEINEDATEN}</td>
                </tr>
            {/if}
        </tbody>

    </table>

</div>