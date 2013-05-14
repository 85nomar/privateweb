<!-- df/component_navigation_menu.tpl -->
<ul class="dropdown-menu" role="menu">
    {foreach $larrMenu[$lnumIndex] AS $lstrSubCKey => $larrSubCPoint}
        {if $larrSubCPoint.child|@count > 0}
            <li class="dropdown-submenu">

                <a tab-index="-1" href="{$larrSubCPoint.link}">{$larrSubCPoint.label}</a>
            {$lnumIndex = $lnumIndex + 1}
            {$larrMenu[$lnumIndex] = $larrSubCPoint.child}

            {include 'uil/html/template/smarty/df/component_navigation_menu.tpl'}
            {$lnumIndex = $lnumIndex - 1}
            </li>
        {else}
            <li class="dropdown">
                {if isset($larrSubCPoint.hasrightedit) AND $larrSubCPoint.hasrightedit}
                    {if $larrSubCPoint.hasright}
                        <a href="{$larrSubCPoint.strRightRemoveLink}">
                            <i class="icon-minus-sign"></i>
                            {if $larrSubCPoint.icon === ''}
                                <i class="icon-sign-blank icon-hidden"></i>
                            {else}
                                <i class="icon-1x {$larrSubCPoint.icon}"></i>
                            {/if}
                            {$larrSubCPoint.label} entfernen
                        </a>
                    {else}
                        <a href="{$larrSubCPoint.strRightAddLink}">
                            <i class="icon-plus-sign"></i>
                            {if $larrSubCPoint.icon === ''}
                                <i class="icon-sign-blank icon-hidden"></i>
                            {else}
                                <i class="icon-1x {$larrSubCPoint.icon}"></i>
                            {/if}
                            {$larrSubCPoint.label} hinzufügen
                        </a>
                    {/if}
                    <a href="{$larrSubCPoint.link}">
                        <i class="icon-sign-blank icon-hidden"></i>
                        {if $larrSubCPoint.icon === ''}
                            <i class="icon-sign-blank icon-hidden"></i>
                        {else}
                            <i class="icon-1x {$larrSubCPoint.icon}"></i>
                        {/if}
                        {$larrSubCPoint.label}
                    </a>
                {else}
                    <a href="{$larrSubCPoint.link}">
                        {if $larrSubCPoint.icon === ''}
                            <i class="icon-sign-blank icon-hidden"></i>
                        {else}
                            <i class="icon-1x {$larrSubCPoint.icon}"></i>
                        {/if}
                        {$larrSubCPoint.label}
                    </a>
                {/if}
            </li>
        {/if}
    {/foreach}
</ul>