<!-- core/right_list.tpl -->
<div class="span12" id="content">

    {include 'uil/html/template/core/breadcrumb.tpl'}

    <div class="row-fluid actionbar">
        <div class="span4 table-action"><br>
            <div class="btn-group">

                    <!-- @TODO : Rechteabfrage integrieren -->
                    <a class="btn" href="{$G_BASELINK}&strAction=insertMask"><i class="icon-file"></i> {$L_NEU}</a>
                    {include 'uil/html/template/core/rightaddremovebutton.tpl'}

            </div>
        </div>
        <div class="span4 pagination pagination-centered">
            <ul>
                <li>Pagination fehlt</li>
            </ul>
        </div>
        <div class="span4 input-prepend input-append text-right"><br>

        </div>
    </div>

    <table class="table table-bordered table-striped table-hover">

        <colgroup>
            <col width="10%">
            <col width="20%">
            <col width="30%">
            <col width="20%">
            <col width="20%">
        </colgroup>

        <thead>
            <tr>
                <th>{$L_ID}</th>
                <th>{$L_BUSINESSLAYER}</th>
                <th>{$L_NAME}</th>
                <th colspan="2">{$L_CODE}</th>
            </tr>
        </thead>

        <tbody>
            {if $larrDaten|@count > 0}
                {foreach $larrDaten AS $larrValue}
                    <tr>
                        <td>{$larrValue.numRightID}</td>
                        <td>{$larrValue.numBulID}</td>
                        <td>{$larrValue.strName}</td>
                        <td>{$larrValue.strCode}</td>
                        <td>
                            <div class="icons">
                                <a class="racoretooltip" title="{$L_BEARBEITEN}" href="{$G_BASELINK}&strAction=updateMask&numRightID={$larrValue.numRightID}">
                                    <i class="icon-edit"></i>
                                </a>
                                &nbsp;&nbsp;
                                <a class="racoretooltip" title="{$L_LOESCHEN}" href="{$G_BASELINK}&strAction=delete&numRightID={$larrValue.numRightID}">
                                    <i class="icon-trash"></i>
                                </a>
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