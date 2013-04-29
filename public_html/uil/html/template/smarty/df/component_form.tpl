<!-- df/component_form.tpl -->
<div class="span12" id="content">

    <ul class="breadcrumb">
        {foreach $larrBreadcrumb AS $larrData}
            <li>
                {if $larrData.link == ''}
                    {$larrData.label}
                {else}
                    <a href="{$larrData.link}">{$larrData.label}</a>
                    <span class="divider">/</span>
                {/if}
            </li>
        {/foreach}
    </ul>

    <form class="form-horizontal" action="{$lstrFormAction}" method="POST">
        {foreach $larrControls AS $larrControl}
            {$larrControl.data}
        {/foreach}
    </form>

</div>