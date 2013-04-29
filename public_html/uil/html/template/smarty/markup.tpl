<!DOCTYPE html>
<html lang='de' xmlns="http://www.w3.org/1999/html">
    <head>
        <title>{$PAGETITLE}</title>
        <meta http-equiv='content-type' content='text/html; charset=utf-8'>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="robots" content="noindex,nofollow">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <link href="uil/html/libs/bootstrap/css/bootstrap.min.css" rel="stylesheet" media="screen">
        <link href="uil/html/libs/bootstrap/css/bootstrap-responsive.min.css" rel="stylesheet" media="screen">
        <link type="text/css" href="uil/html/libs/jquery/css/jqueryui.min.css" rel="Stylesheet" />
        <link rel="stylesheet" href="uil/html/libs/fontawesome/css/font-awesome.min.css">
        <link rel="stylesheet" href="uil/html/libs/racore/css/racore.css">
        {if $cssdebugger==1}
            <link href="uil/html/libs/misc/css/holmes.min.css" rel="stylesheet" media="screen">
        {/if}
     </head>
    <body {if $cssdebugger==1}class="holmes-debug"{/if}>

    <div id="header">
        {$HEADER}
    </div>

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

        <div id="footer">
            {$FOOTER}
        </div>

    </div>
    <script type="text/javascript" src="uil/html/libs/jquery/js/jquery.js"></script>
    <script type="text/javascript" src="uil/html/libs/jquery/js/jqueryui.min.js"></script>
    <script src="uil/html/libs/bootstrap/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="uil/html/libs/racore/js/racore.js"></script>
    <script type="text/javascript" src="uil/html/libs/racore/js/plugins/tablesort.plugins.js"></script>
    <script type="text/javascript" src="uil/html/libs/racore/js/plugins/pagination.plugins.js"></script>
    </body>
</html>