/**
 * Resize function without multiple trigger
 *
 * Usage:
 * $(window).smartresize(function(){
 *     // code here
 * });
 */
(function($,sr){
    // debouncing function from John Hann
    // http://unscriptable.com/index.php/2009/03/20/debouncing-javascript-methods/
    var debounce = function (func, threshold, execAsap) {
        var timeout;

        return function debounced () {
            var obj = this, args = arguments;
            function delayed () {
                if (!execAsap)
                    func.apply(obj, args);
                timeout = null;
            }

            if (timeout)
                clearTimeout(timeout);
            else if (execAsap)
                func.apply(obj, args);

            timeout = setTimeout(delayed, threshold || 100);
        };
    };

    // smartresize
    jQuery.fn[sr] = function(fn){  return fn ? this.bind('resize', debounce(fn)) : this.trigger(sr); };

})(jQuery,'smartresize');
/**
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

var CURRENT_URL = window.location.href.split('#')[0].split('?')[0],
    $BODY = $('body'),
    $MENU_TOGGLE = $('#menu_toggle'),
    $SIDEBAR_MENU = $('#sidebar-menu'),
    $SIDEBAR_FOOTER = $('.sidebar-footer'),
    $LEFT_COL = $('.left_col'),
    $RIGHT_COL = $('.right_col'),
    $NAV_MENU = $('.nav_menu'),
    $FOOTER = $('footer');


/* DATERANGEPICKER */

function init_daterangepicker(text) {

    if( typeof ($.fn.daterangepicker) === 'undefined'){ return; }
    console.log('init_daterangepicker');

    var cb = function(start, end, label) {
        $(text + ' span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
    };

    var stored = localStorage.getItem('dashboardQuery');
    var start, end;
    if (stored) {
        stored = JSON.parse(stored);
        start = moment(stored.start);
        end = moment(stored.end);
    }

    if (!start) {
        start = moment().subtract(6, 'days');
    }
    if (!end) {
        end = moment();
    }

    var year = moment().subtract(365, 'days');
    var date = moment("20170312");
    if(date < year) {
        date = moment().subtract(365, 'days');
    }

    var optionSet1 = {
        startDate: start,
        endDate: end,
        minDate: '01/01/2012',
        maxDate: '12/31/2020',
        dateLimit: {
            days: 60
        },
        showDropdowns: true,
        showWeekNumbers: true,
        timePicker: false,
        timePickerIncrement: 1,
        timePicker12Hour: true,
        ranges: {
            'Today': [moment(), moment()],
            'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            'Last 7 Days': [moment().subtract(6, 'days'), moment()],
            'Last 30 Days': [moment().subtract(29, 'days'), moment()],
            'Year to Date': [date, moment()]
        },
        opens: 'center',
        buttonClasses: ['btn btn-default'],
        applyClass: 'btn-small btn-primary',
        cancelClass: 'btn-small',
        format: 'MM/DD/YYYY',
        separator: ' to ',
        locale: {
            applyLabel: 'Submit',
            cancelLabel: 'Clear',
            fromLabel: 'From',
            toLabel: 'To',
            customRangeLabel: 'Custom',
            daysOfWeek: ['Su', 'Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa'],
            monthNames: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
            firstDay: 1
        }
    };

    $(text + ' span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));

    $(text).daterangepicker(optionSet1, cb);
    $(text).on('show.daterangepicker', function() {
        console.log("show event fired");
    });
    $(text).on('hide.daterangepicker', function() {
        console.log("hide event fired");
    });
    $(text).on('apply.daterangepicker', function(ev, picker) {
        console.log("apply event fired, start/end dates are " + picker.startDate.format('YYYY-MM-DD') + " to " + picker.endDate.format('YYYY-MM-DD'));
        angular.element($(text)).scope().$apply(function($scope) {
            $scope.query.start = picker.startDate.format('YYYY-MM-DD');
            $scope.query.end = picker.endDate.format('YYYY-MM-DD');
        });

    });
    $(text).on('cancel.daterangepicker', function(ev, picker) {
        console.log("cancel event fired");
    });
    $('#options1').click(function() {
        $(text).data('daterangepicker').setOptions(optionSet1, cb);
    });
    $('#options2').click(function() {
        $(text).data('daterangepicker').setOptions(optionSet2, cb);
    });
    $('#destroy').click(function() {
        $(text).data('daterangepicker').remove();
    });

}

function init_daterangepicker_report(text) {
    console.log('init_daterangepicker_report');

    var cb = function(start, end, label) {
        $(text + ' span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
    };

    var optionSet1 = {
        startDate: moment().subtract(6, 'days'),
        endDate: moment(),
        alwaysShowCalendars: true,
        singleDatePicker: true,
        autoUpdateInput: true,
        autoapply: true
    };

    $(text).daterangepicker(optionSet1, cb);
}

function init_daterangepicker_single_call() {
    "undefined"!=typeof $.fn.daterangepicker&&(console.log("init_daterangepicker_single_call"),
        $("#single_cal1").daterangepicker( {
                singleDatePicker: !0, singleClasses: "picker_1"
            }
            , function(a, b, c) {
                console.log(a.toISOString(), b.toISOString(), c)
            }
        ),
        $("#single_cal2").daterangepicker( {
                singleDatePicker: !0, singleClasses: "picker_2"
            }
            , function(a, b, c) {
                console.log(a.toISOString(), b.toISOString(), c)
            }
        ))
}

function formatDate(date) {
    var d = new Date(date),
        month = '' + (d.getMonth() + 1),
        day = '' + d.getDate(),
        year = d.getFullYear();

    if (month.length < 2) month = '0' + month;
    if (day.length < 2) day = '0' + day;

    return [year, month, day].join('-');
}

// Speed up calls to hasOwnProperty
var hasOwnProperty = Object.prototype.hasOwnProperty;

function isEmpty(obj) {

    // null and undefined are "empty"
    if (obj == null) return true;

    // Assume if it has a length property with a non-zero value
    // that that property is correct.
    if (obj.length > 0)    return false;
    if (obj.length === 0)  return true;

    // If it isn't an object at this point
    // it is empty, but it can't be anything *but* empty
    // Is it empty?  Depends on your application.
    if (typeof obj !== "object") return true;

    // Otherwise, does it have any properties of its own?
    // Note that this doesn't handle
    // toString and valueOf enumeration bugs in IE < 9
    for (var key in obj) {
        if (hasOwnProperty.call(obj, key)) return false;
    }

    return true;
}

String.prototype.capitalizeFirstLetter = function() {
    return this.charAt(0).toUpperCase() + this.slice(1);
}

$(document).ready(function() {

    Chart.defaults.global.maintainAspectRatio = false;
    Chart.defaults.global.responsive = false;

});