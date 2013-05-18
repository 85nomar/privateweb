<!-- core/df/update_list.tpl -->
<div class="span12" id="content">

    {include "$strBreadcrumbTemplate"}

    <div class="row-fluid actionbar">
        <div class="span4 table-action"><br>
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
                <th colspan="2">{$L_NAME}</th>
            </tr>
        </thead>

        <tbody>
            {if $larrDaten|@count > 0}
                {foreach $larrDaten AS $strTag}
                    <tr>
                        <td>{$strTag}</td>
                        <td>
                            <div class="icons">

                                {if $R_COREUPDATE_ZUGANG}
                                    <a class="racoretooltip" title="{$L_UPDATEACTION}" href="{$G_BASELINK}&strAction=update&strTag={trim($strTag)}">
                                        <i class="icon-plane"></i>
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