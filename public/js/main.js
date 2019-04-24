$(document).ready(function() {
    $('#multipleSelect').select2({
        width: 'resolve'
    });
});
$(function() {
    $('.select2-selection__rendered').css({ margin: '3 15 7 10' });
});

// TODO refactor
$(document).ready(function () {
    $(document).on("click", ".js-edit-comment", function () {
        let comment_id = $(this).data("id");
        let comment_body = $(this).data("body");
        let comment_url = $(this).data("url");

        let bodyWrapper = $(".js-comment-body"); /// TRY USE PARENT INSTEAD OF LOOP
        bodyWrapper.each(function () {
            if ($(this).data("id") === comment_id)
            {
                $(this).replaceWith("<div class=\"form-group js-update-body\">\n" +
                    "                <label for=\"comment-content\"><b>Edited comment:</b></label>\n" +
                    "                <textarea id=\"comment-content\"\n" +
                    "                          name=\"body\" required\n" +
                    "                          rows=\"2\" \n" +
                    "                          class=\"form-control form-control-lg js-edit-body\">" + comment_body + "</textarea></div>");
            }
        });

        let urlWrapper = $(".js-comment-url");
        urlWrapper.each(function () {
            if ($(this).data("id") === comment_id)
            {
                $(this).replaceWith("<div class=\"form-group js-update-url\">\n" +
                    "                <textarea placeholder=\"Enter url\"\n" +
                    "                          id=\"comment-content\"\n" +
                    "                          name=\"url\"\n" +
                    "                          rows=\"2\" spellcheck=\"false\"\n" +
                    "                          class=\"form-control form-control-lg js-edit-url\">" + comment_url + "</textarea></div>");
            }
        });

        $(this).replaceWith("<button type='button' class='btn btn-success js-update-comment text white' data-id=" + comment_id + ">Save changes</button>");

        $(document).on("click", "button.js-update-comment", function() { // IN SEPARATE FUNCTION
            if ($(this).data("id") === comment_id) {
                let editedBody = $(this).parent().find(".js-edit-body").val();
                let editedUrl = $(this).parent().find(".js-edit-url").val();
                let token = $("meta[name='csrf-token']").attr("content");

                let parent = $(this).parent();
                $.ajax({
                    url: '/comments/update',
                    type: 'PUT',
                    data: {
                        "id": comment_id,
                        "body": editedBody,
                        "url": editedUrl,
                        "_token": token
                    },
                    success: function (response) {
                        if (response.status === 200) {
                            $(".js-update-body", parent).replaceWith("<p class='js-comment-body' data-id=" + comment_id + ">" + editedBody + "</p>");
                            $(".js-update-url", parent).replaceWith("<p class='js-comment-url' data-id=" + comment_id + ">" + editedUrl + "</p>");
                            $(".js-update-comment", parent).replaceWith("<button type='button' class='btn btn-info js-edit-comment text-white' data-id=" + comment_id + " data-body=" + editedBody + " data-url=" + editedUrl + ">Edit</button>");

                            $(".message-container").fadeIn();
                            $(".message-container > div:first-child").removeClass("alert-danger").addClass("alert-success");
                            $(".message").text(response.message);
                            setTimeout(function () {
                                $(".message-container").fadeOut()
                            }, 1700);
                            //showMessage();

                        }
                        else if (response.status === 422) {
                            $(".message-container").fadeIn();
                            $(".message-container > div:first-child").removeClass("alert-success").addClass("alert-danger");
                            $(".message").text(response.message);
                            setTimeout(function () {
                                $(".message-container").fadeOut()
                            }, 1800);
                        }
                    },
                    error: function () {
                        alert('Something went wrong');
                    }
                });
            }

        });

    });
});

function showMessage() {

}

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
                    location.reload(); // INSTEAD OF SIMPLE RELOAD REMOVE DELETED ELEMENT VIA JQUERY
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
                success: function () {
                    location.reload();

                }
            });
        }
    });
});

