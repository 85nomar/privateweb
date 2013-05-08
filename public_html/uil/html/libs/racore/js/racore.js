/**
 * Initialisierung
 */
$('.racoretooltip').tooltip({
   trigger: 'hover',
   html: true,
   placement: function(context, source) {
       var position = $(source).position();
       $this = $(this);
       if (position.left > 515) {
           return "left";
       }
       if (position.left < 515) {
           return "right";
       }
       if (position.top < 110){
           return "bottom";
       }
       return "top";
   },
    delay: { show: 200, hide: 500 }
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