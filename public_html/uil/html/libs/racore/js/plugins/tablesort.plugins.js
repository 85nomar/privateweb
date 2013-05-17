/**
 * Tabellen-Sortierung - Plugin
 *
 * @author      Raffael Wyss <raffael.wyss@gmail.com>
 * @since       16.04.2013
 * @version     1.0.0
 * @copyright   Copyright (c) 2013, Raffael Wyss
 * @package     Plugin
 */
(function ($) {

    var sizehelper = function(event, ui) {
        ui.children().each(function() {
            $(this).width($(this).width());
        });
        return ui;
    }

    var updatetablesort = function(event, ui) {
        $data = $(this).pagination('getPageData');
        $tmpdata = $.makeArray();
        $newdata = $.makeArray();

        /**
         * Hauptsortierung vornehmen (nur von aktueller Seite)
         */
        var index = Number($('table.sortable tbody tr td:first-child').attr('sortfrom'));
        $('table.sortable tbody tr').each(function() {
            var i = $.inArray(this, $data);
            $this = $(this);
            $child = $this.children('td:first-child');
            $child.attr('data-order', index);
            $tmpdata[index] = $this[0];
            index = index + 1;
        });

        /**
         * Sortierung für das Komplette Objekt übernehmen
         */
        $data.each(function() {
            $this = $(this);
            $child = $this.children('td:first-child');
            var index = $child.attr('data-order');
            var i = $.inArray(this, $tmpdata);
            if (i > -1) {
                $newdata[index] = $tmpdata[i];
            } else {
                $newdata[index] = $this[0];
            }
        });
        $(this).pagination('setPageData', $($newdata));
        $('#sortsavebutton').removeClass('disabled');

    }

    var methods = {

        init : function (properties) {

            /**
             * Speicherung abfangen
             */
            $("a[data-post='true']").on('click', function() {
                $this = $(this);
                $('body').append('<form name="datasort"></form>');
                var $form = $("form[name='datasort']");
                $form.attr('action', $this.attr('href'));
                $form.attr('method', 'post');
                var numOrder = 0;
                $(this).pagination('getPageData').each(function() {
                    $thisobj = $($(this)[0]).find("input[name='numOrder']");
                    var id = $thisobj.val();
                    var dataorder = $thisobj.closest('td').attr('data-order');
                    $form.append('<input type="hidden" name="numOrder_'+dataorder+'" value="'+id+'">');
                });
                $form.submit();
                return false;
            });

            /**
             * Sortierungszeichen bei jeder Zeile anfügen
             */
            $('table.sortable tbody tr').each(function() {
                $this = $(this);
                $icons = $this.children('td:last-child').find('div.icons');
                if ($('#searchfield').val() == '') {
                    $icons.children('i.icon-reorder').remove();
                    $iconreorder = $icons.append('<i class="icon-reorder"></i>');
                    $('table.sortable tbody').sortable({
                        helper: sizehelper,
                        update: updatetablesort
                    }).disableSelection();
                    $('table.sortable tbody').sortable('enable');
                } else {
                    $icons.children('i.icon-reorder').remove();
                    $('table.sortable tbody').sortable('disable');
                }
            });
        }
    }

    /**
     * Plugin Initialisierung
     *
     * @param method
     * @returns {*}
     */
    $.fn.tablesort = function(method) {
        if (methods[method]) {
            return methods[method].apply(this);
        } else {
            methods.init.apply(this, arguments);
        }
    }

})(jQuery);

