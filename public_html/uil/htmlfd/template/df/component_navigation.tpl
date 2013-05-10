<!-- df/component_navigation.tpl -->
<nav class="top-bar">
    <ul class="nav nav-list">
        {foreach $larrDaten AS $lstrKey => $larrPoint}
            <li><a href="{$larrPoint.link}">{$larrPoint.label}</a></li>
        {/foreach}
    </ul>
</nav>


