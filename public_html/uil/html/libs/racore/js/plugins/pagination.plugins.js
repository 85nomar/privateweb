/**
 * Pagination - Plugin
 *
 * @author      Raffael Wyss <raffael.wyss@gmail.com>
 * @since       17.04.2013
 * @version     1.0.0
 * @copyright   Copyright (c) 2013, Raffael Wyss
 * @package     Plugin
 */
(function ($) {

    var pagedata; // = Alle daten
    var pagedataview; // = Daten wo angezeigt bzw. gesucht wurden
    var pages;    // = Anzahl Seiten
    var perpage = 10; // = Anzahl Einträge pro Seite
    var maxviewpages = 4; // = Max. Anzuzeigende Seite in der Pagination

    var methods = {

        getPageData: function() {
            return pagedata;
        },

        setPageData: function(data) {
            pagedata = data;
            pagedataview = pagedata;
        },

        init: function () {
            pagedata = $('table tbody tr').detach();
            pagedataview = pagedata;
            pages = Math.ceil(pagedataview.length / perpage);
            $(this).pagination('events');
            $(this).pagination('buildPagination', 1);
            $(this).pagination('showPage', 1);
        },

        events: function() {

            $(".icon-search").on('click', function() {
                $(this).pagination('search', $('#searchfield').val());
                $(this).tablesort('init');
            });
            $("#searchfield").on('keypress', function(event) {
                if ( event.which == 13) {
                    $(".icon-search").trigger('click');
                }
            });

        },

        buildPagination: function(page) {
            var pagination = $('div.pagination ul');
            var from = page - Math.ceil(maxviewpages / 2);
            if (from < 1) {
                from = 1;
            }
            if ((from + maxviewpages) > pages) {
                from = pages - maxviewpages;
            }
            if (from < 1) {
                from = 1;
            }
            var to = from + maxviewpages;
            if (to > pages) {
                to = pages;
            }
            pagination.empty();
            if (pages > maxviewpages) {
                pagination.append('<li><a><i class="icon-double-angle-left"></i></a></li>');
                pagination.append('<li><a><i class="icon-angle-left"></i></a></li>');
            }
            if (page == 1) {
                pagination.find('i.icon-double-angle-left').closest('li').addClass('disabled');
                pagination.find('i.icon-angle-left').closest('li').addClass('disabled');
            }
            for (var actpage = from; actpage  < to + 1; actpage++) {
                var actclass = '';
                if (actpage == page) {
                    actclass = 'active';
                }
                pagination.append('<li class="'+actclass+'"><a href="#">'+actpage+'</a></li>');
            }
            if (pages > maxviewpages) {
                pagination.append('<li><a href="#"><i class="icon-angle-right"></i></a></li>');
                pagination.append('<li><a href="#"><i class="icon-double-angle-right"></i></a></li>');
            }
            if (page == pages) {
                pagination.find('i.icon-angle-right').closest('li').addClass('disabled');
                pagination.find('i.icon-double-angle-right').closest('li').addClass('disabled');
            }
            if (pages == 1) {
                pagination.empty();
            }

            $('div.pagination ul li:not(.disabled) a').bind('click', function() {
                var nextpage = Number($(this).text());
                if (nextpage == 0) {
                    actpage = Number($('div.pagination ul li.active').text());
                    if($(this).find('i.icon-angle-left').length > 0) {
                        nextpage = actpage - 1;
                    } else if($(this).find('i.icon-angle-right').length > 0) {
                        nextpage = actpage + 1;
                    } else if($(this).find('i.icon-double-angle-left').length > 0) {
                        nextpage = 1;
                    } else {
                        nextpage = pages;
                    }
                    if (nextpage < 1) {
                        nextpage = 1;
                    }
                    if (nextpage > pages) {
                        nextpage = pages;
                    }
                }
                $(this).pagination('showPage', nextpage);
                $(this).pagination('buildPagination', nextpage);
                return false;
            });


        },

        showPage: function(page) {

            var from = (page * perpage) - perpage;
            if (from >= pagedataview.length) {
                from = pagedataview.length - perpage;
            }
            var to = from + perpage;
            var actualpage = pagedataview.slice(from, to);
            actualpage.each(function() {
               $(this).children('td:first-child').attr('sortfrom', from);
               $(this).children('td:first-child').attr('sortto', to);
            });
            $('table tbody').empty();
            $('table tbody').append(actualpage);
            var anzahl = pagedataview.length;
            var total = pagedata.length;
            var anzahltext = anzahl;
            if (total != anzahl) {
                anzahltext = anzahltext + ' / '+ '<span class="text-info">'+total+'</span>';
            }
            $('#anzahlgefunden').html('Anzahl: '+anzahltext);
        },

        search: function(suchbegriff) {
            var data = $();
            pagedata.each(function() {
                var zeilentext = $(this).text();
                var regex = new RegExp('('+suchbegriff+')+', 'g');
                if (zeilentext.match(regex)) {
                    data.push(this);
                }
            });
            pagedataview = data;
            pages = Math.ceil(pagedataview.length / perpage);
            $(this).pagination('buildPagination', 1);
            $(this).pagination('showPage', 1);
        }
    }


    /**
     * Plugin Initialisierung
     *
     * @param method
     * @returns {*}
     */
    $.fn.pagination = function(method) {
        if (methods[method]) {
            return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
        } else {
            methods.init.apply(this, arguments);
        }
    }

})(jQuery);

