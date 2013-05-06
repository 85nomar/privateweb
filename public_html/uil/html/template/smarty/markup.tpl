<!DOCTYPE html>
<html lang='de' xmlns="http://www.w3.org/1999/html">
    <head>
        <title>{$PAGETITLE}</title>
        <meta http-equiv='content-type' content='text/html; charset=utf-8'>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="robots" content="noindex,nofollow">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <link rel="stylesheet" href="uil/core/html/libs/bootstrap-2.3.1/css/bootstrap.min.css"  media="screen">
        <link rel="stylesheet" href="uil/core/html/libs/bootstrap-2.3.1/css/bootstrap-responsive.min.css"  media="screen">
        <link rel="Stylesheet" href="uil/core/html/libs/jquery-1.9.1/css/jqueryui-1.10.2.min.css"  />
        <link rel="stylesheet" href="uil/core/html/libs/fontawesome-3.0.2/css/font-awesome.min.css">
        <link rel="stylesheet" href="uil/html/libs/racore/css/racore.css">
        {if $cssdebugger==1}
            <link rel="stylesheet" href="uil/core/html/libs/holmes-1.0.45/css/holmes.css"  media="screen">
        {/if}
     </head>
    <body {if $cssdebugger==1}class="holmes-debug"{/if}>

    <br>
    <div class="container-fluid">

        <div class="container" style="width: auto;">
            {$NAVIGATION}
        </div>

        {if $ERRORMESSAGE != ''}
            <div class="row-fluid" id="errorMessage">
                <div class="alert alert-error alert-block">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    <i class="icon-exclamation-sign"></i> {$ERRORMESSAGE}
                </div>
            </div>
        {/if}
        {if $WARNINGMESSAGE != ''}
            <div class="row-fluid" id="errorMessage">
                <div class="alert alert-block">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    <i class="icon-info-sign"></i> {$WARNINGMESSAGE}
                </div>
            </div>
        {/if}
        {if $SUCCESSMESSAGE != ''}
            <div class="row-fluid" id="errorMessage">
                <div class="alert alert-success alert-block">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    <i class="icon-ok"></i> {$SUCCESSMESSAGE}
                </div>
            </div>
        {/if}
        {if $SYSTEMERRORMESSAGE != ''}
            <div class="row-fluid" id="errorMessage">
                <div class="alert alert-error alert-block">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    {$SYSTEMERRORMESSAGE}
                </div>
            </div>
        {/if}

        <div class="row-fluid">
            {$CONTENT}
        </div>


    </div>
    <script type="text/javascript" src="uil/core/html/libs/jquery-1.9.1/js/jquery-1.9.1.js"></script>
    <script type="text/javascript" src="uil/core/html/libs/jquery-1.9.1/js/jquery-ui-1.10.2.min.js"></script>
    <script type="text/javascript" src="uil/core/html/libs/bootstrap-2.3.1/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="uil/html/libs/racore/js/racore.js"></script>
    <script type="text/javascript" src="uil/html/libs/racore/js/plugins/tablesort.plugins.js"></script>
    <script type="text/javascript" src="uil/html/libs/racore/js/plugins/pagination.plugins.js"></script>
    </body>
</html>