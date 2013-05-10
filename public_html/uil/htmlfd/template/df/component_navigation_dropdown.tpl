<!-- df/component_navigation_dropdown.tpl -->
<nav class="top-bar">
    <section class="top-bar-section">
        <ul class="title-area">
            {foreach $larrDaten AS $lstrKey => $larrPoint}
                <li class="name">
                    <a href="{$larrPoint.link}"> {$larrPoint.label}</a>
                </li>
            {/foreach}
        </ul>
    </section>

</nav>


