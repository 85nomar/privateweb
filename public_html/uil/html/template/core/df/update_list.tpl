<!-- core/df/update_list.tpl -->
<div class="span12" id="content">

    {include "$strBreadcrumbTemplate"}


    <div class="row-fluid actionbar">
        <div class="span4">
            &nbsp;
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
            <col width="70%">
            <col width="20%">
        </colgroup>

        <thead>
            <tr>
                <th colspan="2">{$L_VERSION}</th>
            </tr>
        </thead>

        <tbody>
            {if $larrDaten|@count > 0}
                {foreach $larrDaten.arrTags AS $arrTag}
                    <tr {if trim($arrTag.version) == $larrDaten.strVersion}class="racoretooltiptop success" title="{$L_AKTUELLEVERSION}"{elseif $arrTag.updaterescue}class="racoretooltiptop warning" title="{$L_ALTEVERSION}"{/if}>
                        <td>{$arrTag.version}</td>
                        <td>
                            <div class="icons">

                                {if $R_COREUPDATE_ZUGANG AND trim($arrTag.version) != $larrDaten.strVersion AND $arrTag.updaterescue}
                                    <a class="racoretooltip" title="{$L_UPDATERESCUE}" href="{$G_BASELINK}&strAction=updaterescue&strTag={trim($arrTag.version)}">
                                        <i class="icon-ambulance"></i>
                                    </a>
                                    {include "$strRightButtonTemplate" HASRIGHT=$RROLL_COREUPDATE_ZUGANG RIGHT='ZUGANG'}
                                {/if}

                                {if $R_COREUPDATE_ZUGANG AND trim($arrTag.version) != $larrDaten.strVersion AND !$arrTag.updaterescue}
                                    <a class="racoretooltip" title="{$L_UPDATEACTION}" href="{$G_BASELINK}&strAction=updateaction&strTag={trim($arrTag.version)}&strTagOld={trim($larrDaten.strVersion)}">
                                        <i class="icon-plus-sign"></i>
                                    </a>
                                    {include "$strRightButtonTemplate" HASRIGHT=$RROLL_COREUPDATE_ZUGANG RIGHT='ZUGANG'}
                                {/if}

                            </div>
                        </td>
                    </tr>
                {/foreach}
            {else}
                <tr>
                    <td colspan="2>{$L_KEINEDATEN}</td>
                </tr>
            {/if}
        </tbody>

    </table>

</div>