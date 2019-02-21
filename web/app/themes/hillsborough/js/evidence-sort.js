jQuery(document).ready(function($) {
    $('.sortable').sortable({
        'axis': "y",
        'update': function(event, ui) {
            var data = {
                action: "reorder_evidence",
                values: $('.sortable').sortable('serialize')
            };
            $.post(custom_sort.ajaxurl, data, function(response) {
            });
            return true;
        }
    });
    $('.refresh-sortable').on("click", function(e) {
        e.preventDefault();
        $('.sortable').stop().fadeOut().sortable("refresh").fadeIn();
    });
});