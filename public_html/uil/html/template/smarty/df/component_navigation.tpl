<!-- df/component_navigation.tpl -->
<div class="well span3" style="max-width: 340px; padding: 8px 0;" id="navigation">
    <ul class="nav nav-list">
        {foreach $larrDaten AS $lstrKey => $larrPoint}
            <li><a href="{$larrPoint.link}">{$larrPoint.label}</a></li>
        {/foreach}
    </ul>
</div>
