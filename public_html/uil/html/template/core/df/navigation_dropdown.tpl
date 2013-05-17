<!-- df/component_navigation_dropdown.tpl -->
<div class="navbar navbar-static-top">
    <div class="navbar-inner">
        <div class="container">
            <a class="btn btn-navbar" data-toggle="collapse" data-target=".navbar-responsive-collapse">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </a>

                <div class="nav-collapse collapse navbar-responsive-collapse">

                    <div class="text-center vertical-align-center">
                        <h1>
                            <p>
                                <i class="icon-3x icon-lock"></i>
                                privateweb (Prototype)
                            </p>
                        </h1>
                    </div>
                    <br>
                    <ul class="nav">
                        {$lnumIndex = 0}
                        {$larrMenu = array()}
                        {$larrKey = array()}
                        {$larrValue = array()}
                        {foreach $larrDaten AS $lstrKey => $larrPoint}
                            <li class="dropdown">
                                {if $larrPoint.child|@count > 0}
                                    <a class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" role="button" href="{$larrPoint.link}">{$larrPoint.label} <b class="caret"></b></a>
                                    {$larrMenu[$lnumIndex] = $larrPoint.child}
                                    {include "$navigationMenuTemplate"}
                                {else}
                                    <a href="{$larrPoint.link}"><i class="icon-1x {$larrPoint.icon}"></i> {$larrPoint.label}</a>
                                {/if}
                            </li>
                        {/foreach}
                    </ul>
                </div>

        </div>
    </div>
</div>


