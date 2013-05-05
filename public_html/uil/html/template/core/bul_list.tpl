<!-- core/bul_list.tpl -->
<div class="span12" id="content">

    {include 'uil/html/template/core/breadcrumb.tpl'}

    <div class="row-fluid actionbar">
        <div class="span4 table-action"><br>
            <div class="btn-group">
                <a class="btn" href="{$G_BASELINK}&strAction=insertMask"><i class="icon-file"></i> {$L_NEU}</a>
            </div>
        </div>
        <div class="span4 pagination pagination-centered">
            <ul>
                <li>test</li>
            </ul>
        </div>
        <div class="span4 input-prepend input-append text-right"><br>

        </div>
    </div>

    <table class="table table-bordered table-striped table-hover">

        <colgroup>
            <col width="10%">
            <col width="70%">
            <col width="20%">
        </colgroup>

        <thead>
            <tr>
                <th>{$L_ID}</th>
                <th colspan="2">{$L_NAME}</th>
            </tr>
        </thead>

        <tbody>
            {if $larrDaten|@count > 0}
                {foreach $larrDaten AS $larrValue}
                    <tr>
                        <td>{$larrValue.numBulID}</td>
                        <td>{$larrValue.strName}</td>
                        <td>
                            <div class="icons">
                                <a class="racoretooltip" title="{$L_BEARBEITEN}" href="{$G_BASELINK}&strAction=updateMask&numBulID={$larrValue.numBulID}">
                                    <i class="icon-edit"></i>
                                </a>
                                <a class="racoretooltip" title="{$L_LOESCHEN}" href="{$G_BASELINK}&strAction=delete&numBulID={$larrValue.numBulID}">
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