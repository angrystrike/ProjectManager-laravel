$(document).ready(function() {
    $('#multipleSelect').select2({
        width: 'resolve'
    });
});

$(function() {
    $('.select2-selection__rendered').css({ margin: '3 15 7 10' });
});

$(document).ready(function () {
   $(document).on("click", ".js-edit-comment", function () {
       let comment_id = $(this).data("id");
       $(this).closest(".card-body").find(".js-view-comment-section").addClass("hidden");
       $(this).closest(".card-body").find(".js-edit-comment-section").removeClass("hidden");
       $(this).replaceWith("<button type='button' class='btn btn-success js-update-comment text white' data-id=" + comment_id + ">Save changes</button>");
   });

});

$(document).ready(function () {
   $(document).on("click", ".js-update-comment", function () {
       let comment_id = $(this).data("id");
       let comment_body = $(this).closest(".card-body").find(".js-edit-comment-section #comment-body").val();
       let comment_url = $(this).closest(".card-body").find(".js-edit-comment-section #comment-url").val();
       let token = $("meta[name='csrf-token']").attr("content");

       let parent = $(this).closest(".card-body");
       let updBtn = $(this);
       $.ajax ({
           url: '/comments/update',
           type: 'PUT',
           data: {
               "id": comment_id,
               "body": comment_body,
               "url": comment_url,
               "_token": token
           },
           success: function (response) {
                   parent.find(".js-edit-comment-section").addClass("hidden");
                   parent.find(".js-view-comment-section").removeClass("hidden");
                   parent.find(".js-comment-body").text(comment_body);
                   parent.find(".js-comment-url").text(comment_url);
                   updBtn.replaceWith("<button type='button' class='btn btn-info js-edit-comment text-white' data-id=" + comment_id + ">Edit</button>");
                   $(".message-container").fadeIn();
                   $(".message-container > div:first-child").removeClass("alert-danger").addClass("alert-success");
                   $(".message").text(response.message);
                   setTimeout(function () {
                       $(".message-container").fadeOut()
                   }, 1700);
           },
           error: function (response) {
               if (response.status === 422) {
                   $(".message-container").fadeIn();
                   $(".message-container > div:first-child").removeClass("alert-success").addClass("alert-danger");
                   $(".message").text(response.responseJSON.message);
                   setTimeout(function () {
                       $(".message-container").fadeOut()
                   }, 1800);
               }
           }
       });
   });
});

$(document).ready(function () {
    $(".js-delete-comment").click(function () {
        let result = confirm("Are you sure you wish to delete this comment?");
        if (result) {
            let comment_id = $(this).data("id");
            let token = $("meta[name='csrf-token']").attr("content");
            let parent = $(this).closest(".card");
            $.ajax({
                url: "/comments/" + comment_id,
                type: 'DELETE',
                data: {
                    "id": comment_id,
                    "_token": token,
                },
                success: function (response) {
                    if (response.status === 200) {
                        $(".message-container").fadeIn();
                        $(".message-container > div:first-child").removeClass("alert-danger").addClass("alert-success");
                        $(".message").text(response.message);
                        setTimeout(function () {
                            $(".message-container").fadeOut()
                        }, 1700);
                        parent.remove();
                        $("br").remove();
                    }
                },
                error: function () {
                    alert("Something went wrong");
                }
            });
        }
    });
});

$(document).ready(function () {
    $(".js-delete-member").click(function () {
        let result = confirm("Are you sure you wish to delete this member?");
        if (result) {
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
        }
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

