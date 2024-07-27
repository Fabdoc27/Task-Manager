// jQuery
; (function ($) {
    $(document).ready(function () {
        $(".complete").on("click", function () {
            var id = $(this).data("task");
            $("#ctask").val(id);
            $("#completeform").submit();
        });

        $(".delete").on("click", function () {
            if (confirm("Are you sure to delete this task?")) {
                var id = $(this).data("task");
                $("#dtask").val(id);
                $("#deleteform").submit();
            }
        });

        $(".incomplete").on("click", function () {
            var id = $(this).data("task");
            $("#itask").val(id);
            $("#incompleteform").submit();
        });

        $("#bulksubmit").on("click", function () {
            if ($("#bulkaction").val() == 'bulkdelete') {
                if (!confirm("Are you sure to delete all task?")) {
                    return false;
                }
            }
        });
    });
})(jQuery);