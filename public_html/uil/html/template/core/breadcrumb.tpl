<!-- core/breadcrumb.tpl -->
{if isset($larrBreadcrumb)}
    <ul class="breadcrumb">
        {foreach $larrBreadcrumb AS $larrData}
            <li>
                {$larrData.label} <span class="divider">/</span>
            </li>
        {/foreach}
    </ul>
{/if}