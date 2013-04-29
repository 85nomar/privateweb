<!-- df/component_list.tpl -->
<div class="span12" id="content">

    <ul class="breadcrumb">
        {foreach $larrBreadcrumb AS $larrData}
            <li>
                {$larrData.label} <span class="divider">/</span>
            </li>
        {/foreach}
    </ul>

    <div class="row-fluid actionbar">
        <div class="span4 table-action"><br>
            <div class="btn-group">
                {foreach $larrButtons AS $larrButton}
                    {$COMPONENTRIGHT = $larrButton.booRight}
                    {$COMPONENTRIGHTEDIT = $larrButton.booRightEdit}
                    {$COMPONENTROLLRIGHT = $larrButton.booRollRight}
                    {$COMPONENTRIGHTTEXT = $larrButton.strRight}
                    {$COMPONENTRIGHTADDLINK = $larrButton.strRightAddLink}
                    {$COMPONENTRIGHTREMOVELINK = $larrButton.strRightRemoveLink}
                    {if $COMPONENTRIGHT}
                        <a class="btn btn" data-post='{$larrButton.datapost}' href='{$larrButton.link}'><i class="{$larrButton.icon}"></i> {$larrButton.label}</a>
                        {include file='uil/html/template/smarty/df/component_right_button.tpl'}
                    {/if}
                {/foreach}
                {if $isSortable}
                    <a id="sortsavebutton" class="btn disabled" data-post='true' href='{$larrSort.link}'><i class="icon-save"></i> {$larrSort.label}</a>
                {/if}
            </div>
        </div>
        <div class="span4 pagination pagination-centered">
            <ul>
                <li><a href="#"><i class="icon-double-angle-left"></i></a></li>
                <li><a href="#">1</a></li>
                <li><a href="#">2</a></li>
                <li><a href="#"><i class="icon-double-angle-right"></i></a></li>
            </ul>
        </div>
        <div class="span4 input-prepend input-append text-right"><br>
                <span id="anzahlgefunden" class="add-on">Anzahl: xyz</span>
                <input type='text' id="searchfield">
                <button type="button" id="searchbutton" class="btn">search</button>
            </form>

        </div>
    </div>
    <br>



    <table class="table table-bordered table-striped {if $isSortable}sortable{/if} table-hover">

        <thead>
        {if $larrHeader|@count > 0}
            <tr>
                {foreach $larrHeader AS $larrSpalte}
                    {if isset($larrSpalte.display) AND $larrSpalte.display == 1}
                        <th>{$larrSpalte.label}</th>
                    {elseif isset($larrSpalte.display) AND $larrSpalte.display == 2}
                        <th class="hidden-desktop hidden-tablet hidden-phone hidden-print">{$larrSpalte.label}</th>
                    {/if}
                {/foreach}
            </tr>
        {/if}
        </thead>

        <tbody>
        {$data_order = -1}
        {foreach $larrDaten AS $larrRow}
            {$data_order = $data_order + 1}
            <tr>
                {foreach $larrRow AS $larrSpalte}
                    {if isset($larrSpalte.display) AND $larrSpalte.display == 1}
                        {if isset($larrSpalte.label)}
                            <td>{$larrSpalte.label}</td>
                        {/if}
                    {elseif isset($larrSpalte.display) AND $larrSpalte.display == 2}
                        <td data-order="{$data_order}" class="hidden-desktop hidden-tablet hidden-phone hidden-print"><input type='text' name="numOrder" value="{$larrSpalte.label}"></td>
                    {elseif isset($larrSpalte[0])}
                        <td nowrap>
                            <div class="icons">
                                {foreach $larrSpalte AS $larrIcon}
                                    {if isset($larrIcon.display) AND $larrIcon.display == 1}
                                        <a href="{$larrIcon.link}" class="racoretooltip" title="{$larrIcon.label}">
                                            {if $larrIcon.icon != ''}
                                                <i class="{$larrIcon.icon}"></i>
                                            {else}
                                                {$larrIcon.label}
                                            {/if}
                                        </a>
                                    {/if}

                                {/foreach}
                            </div>
                        </td>
                    {/if}
                {/foreach}
            </tr>
        {/foreach}
        </tbody>

    </table>
</div>