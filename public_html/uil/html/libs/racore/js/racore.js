/**
 * Initialisierung
 */
$('.racoretooltip').tooltip({
   trigger: 'hover',
   html: true,
   placement: 'left'
});


/**
 * Aufruf der Plugins
 */
$(document).ready(function () {

    /**
     * Tabellensortierungs-Plugin
     */
    $(this).filter('table').tablesort();

    /**
     * Pagination-Plugin
     */
    $(this).filter('table').pagination();


});