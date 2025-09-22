'use strict';
$(document).ready(function() {
    $(".task-right-header-statuses").on('click', function() {
        $(".taskboard-right-statuses").slideToggle();
    });

    $(".task-right-header-stats").on('click', function() {
        $(".taskboard-right-stats").slideToggle();
    });

    $(".task-right-header-categories").on('click', function() {
        $(".taskboard-right-categories").slideToggle();
    });

    $(".task-right-header-types").on('click', function() {
        $(".taskboard-right-types").slideToggle();
    });

    $('.collapse').on('shown.bs.collapse', function() {
        $(this).parent().find(".fa-angle-up").removeClass("fa-angle-up").addClass("fa-angle-down");
    }).on('hidden.bs.collapse', function() {
        $(this).parent().find(".fa-angle-down").removeClass("fa-angle-down").addClass("fa-angle-up");
    });

});
