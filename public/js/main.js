$(document).ready(function() {
    $('#multipleSelect').select2({
        width: 'resolve'
    });
});
$(function() {
    $('.select2-selection__rendered').css({margin:'3 15 7 10'});
});

$(document).ready(function () {
    $(".js-delete-comment").click(function () {
        let comment_id = $(this).data("id");
        let token = $("meta[name='csrf-token']").attr("content");
        $.ajax({
            url: "/comments/" + comment_id,
            type: 'DELETE',
            data: {
                "id": comment_id,
                "_token": token,
            },
            success: function (response) {
                if (response.success) {
                    location.reload();
                }
            }
        });

    });
});

$(document).ready(function () {
    $(".js-delete-member").click(function () {
        let task_id = $(this).data("task_id");
        let user_id = $(this).data("user_id");
        let token = $("meta[name='csrf-token']").attr("content");
        $.ajax({
            url: "member/" + task_id + "/" + user_id,
            type: 'DELETE',
            data: {
                "task_id": task_id,
                "user_id": user_id,
                "_token": token,
            },
            success: function (response) {
                if (response.success) {
                    location.reload();
                }
            }
        });

    });
});


$(document).ready(function () {
    $(".js-delete-member").click(function () {
        let project_id = $(this).data("project_id");
        let user_id = $(this).data("user_id");
        let token = $("meta[name='csrf-token']").attr("content");
        $.ajax({
            url: "member/" + project_id + "/" + user_id,
            type: 'DELETE',
            data: {
                "project_id": project_id,
                "user_id": user_id,
                "_token": token,
            },
            success: function (response) {
                if (response.success) {
                    location.reload();
                }
            }
        });

    });
});

$(document).ready(function () {
    $(".js-delete").click(function () {
        let result = confirm('Are you sure you wish to delete it?');
        if (result) {
            $("#delete-form").submit();

        }
    });
});


$(document).ready(function () {
    $(".js-delete-user").click(function () {
        let result = confirm('Are you sure you wish to delete this User? (All info connected to this User will be deleted too)');
        if (result) {
            let user_id = $(this).data("id");
            let token = $("meta[name='csrf-token']").attr("content");
            $.ajax({
                url: "/users/" + user_id,
                type: 'DELETE',
                data: {
                    "id": user_id,
                    "_token": token,
                },
                dataType: 'html',
                success: function (response) {
                    location.reload();

                }
            });
        }
    });
});

