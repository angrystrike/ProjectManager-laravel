$(document).ready(function() {
    $('#multipleSelect').select2({
        width: 'resolve'
    });
});

$(function() {
    $('.select2-selection__rendered').css({ margin: '3 15 7 10' });
});

$(document).ready(function () {
    $(document).on("click", ".js-delete-friend", function () {
        let result = confirm("Are you sure you wish to delete him from your friend list?");
        if (result) {
            let friend_id = $(this).data("friend_id");
            let token = $("meta[name='csrf-token']").attr("content");
            let parent = $(this).closest("li");
            $.ajax({
                url: '/deleteFriend',
                type: 'DELETE',
                data: {
                    "friend_id": friend_id,
                    "_token": token
                },
                success: function (response) {
                    showMessage(".col-sm-10", "success", response.message);
                    parent.remove();
                },
                error: function (response) {
                    showMessage(".col-sm-10", "danger", response.responseJSON.message);
                }
            });
        }
    });
});

$(document).ready(function () {
    $(".js-add-to-friends").click(function () {
        let recipient_id = $(this).data("recipient_id");
        let token = $("meta[name='csrf-token']").attr("content");
        let btn = $(this);
        $.ajax({
            url: '/addToFriends',
            type: 'POST',
            data: {
                "recipient_id": recipient_id,
                "_token": token
            },
            success: function (response) {
                showMessage(".col-sm-10", "success", response.message);
                btn.remove();
            },
            error: function (response) {
                showMessage(".col-sm-10", "danger", response.responseJSON.message);
            }
        });
    });
});

$(document).ready(function () {
   $(document).on("click", ".js-cancel-request", function () {
       let recipient_id = $(this).data("recipient_id");
       let token = $("meta[name='csrf-token']").attr("content");
       let parent = $(this).closest("li");
       $.ajax({
           url: 'cancelFriendRequest',
           type: 'DELETE',
           data: {
               "recipient_id": recipient_id,
               "_token": token
           },
           success: function (response) {
               showMessage(".col-sm-10", "success", response.message);
               parent.remove();
           },
           error: function (response) {
               showMessage(".col-sm-10", "danger", response.responseJSON.message);
           }
       });
   });
});

$(document).ready(function () {
    $(".js-accept-friend").click(function () {
        let sender_id = $(this).data("sender_id");
        let token = $("meta[name='csrf-token']").attr("content");
        let parent = $(this).closest("li");
        $.ajax({
            url: '/acceptFriend',
            type: 'POST',
            data: {
                "sender_id": sender_id,
                "_token": token
            },
            success: function (response) {
                parent.remove();
                showMessage(".col-sm-10", "success", response.message);
                let newLi = "<li class='list-group-item'>" +
                                "<a href='/users/" + response.accepted_friend_id + "'>" + response.accepted_friend_email + "</a>" +
                                "<button type='button' class='btn btn-danger float-right margin-btn js-delete-friend' data-friend_id="+ response.accepted_friend_id+">Remove</button>" +
                                "<button type='button' class='btn btn-success float-right margin-btn'>Write</button></li>" +
                             "</li>";
                if ($("#friends ul").length) {
                    $("#friends ul").append(newLi);
                }
                else {
                    $("#friends p").replaceWith("<ul class='list-unstyled'>" + newLi + "</ul>");
                }
                
            },
            error: function (response) {
                showMessage(".col-sm-10", "danger", response.responseJSON.message);
            }
        });
    }) ;
});

$(document).ready(function () {
    $(document).on("click", ".js-deny-friend", function () {
        let sender_id = $(this).data("sender_id");
        let token = $("meta[name='csrf-token']").attr("content");
        let parent = $(this).closest("li");
        $.ajax({
            url: '/denyFriend',
            type: 'POST',
            data: {
                "sender_id": sender_id,
                "_token": token
            },
            success: function (response) {
                parent.remove();
                showMessage(".col-sm-10", "success", response.message);
            },
            error: function (response) {
                showMessage(".col-sm-10", "danger", response.responseJSON.message);
            }
        });
    });
});

function showMessage(selector, type, text) {
    $(selector).prepend("<div class='alert alert-" + type + " alert-dismissible'>" +
        "<button type='button' class='close' data-dismiss='alert' aria-label='Close'>" +
        "<span aria-hidden='true'>&times;</span>" +
        "</button>" +
        "<strong>"+ text +"</strong>" +
        "</div>");
}

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
                showMessage("#messageBox", "success", response.message);
            },
            error: function (response) {
                showMessage("#messageBox", "danger", response.responseJSON.message);
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
                    "_token": token
                },
                success: function (response) {
                    showMessage("#messageBox", "success", response.message);
                    parent.remove();
                },
                error: function () {
                    showMessage("#messageBox", "danger", response.responseJSON.message);
                }
            });
        }
    });
});

$(document).ready(function () {
    $(".js-delete-task-member").click(function () {
        let result = confirm("Are you sure you wish to remove this member?");
        if (result) {
            let task_id = $(this).data("task_id");
            let user_id = $(this).data("user_id");
            let token = $("meta[name='csrf-token']").attr("content");
            let parent = $(this).closest("li");
            $.ajax({
                url: "member/" + task_id + "/" + user_id,
                type: 'DELETE',
                data: {
                    "_token": token
                },
                success: function (response) {
                    parent.remove();
                    showMessage(".col-sm-9", "success", response.message);
                },
                error: function (response) {
                    showMessage(".col-sm-9", "danger", response.responseJSON.message);
                }
            });
        }
    });
});

$(document).ready(function () {
    $(".js-delete-project-member").click(function () {
        let result = confirm("Are you sure you wish to remove this member?");
        if (result) {
            let project_id = $(this).data("project_id");
            let user_id = $(this).data("user_id");
            let token = $("meta[name='csrf-token']").attr("content");
            let parent = $(this).closest("li");
            $.ajax({
                url: "member/" + project_id + "/" + user_id,
                type: 'DELETE',
                data: {
                    "_token": token
                },
                success: function (response) {
                    parent.remove();
                    showMessage(".col-sm-9", "success", response.message);
                },
                error: function (response) {
                    showMessage(".col-sm-9", "danger", response.responseJSON.message);
                }
            });
        }
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
            let parent = $(this).closest("li");
            $.ajax({
                url: "/users/" + user_id,
                type: 'DELETE',
                data: {
                    "_token": token
                },
                dataType: 'html',
                success: function () {
                    parent.remove();
                },
                error: function (response) {
                    alert(response.responseJSON.message);
                }
            });
        }
    });
});

$(document).ready(function () {
    $(document).on("click", ".js-delete-conversation", function () {
        let result = confirm('Are you sure you wish to delete this conversation');
        if (result) {
            let thread_id = $(this).data("id");
            let token = $("meta[name='csrf-token']").attr("content");
            let parent = $(this).closest(".col-sm-6");
            $.ajax({
                url: "/threads/" + thread_id,
                type: 'DELETE',
                data: {
                    "_token": token
                },
                success: function (response) {
                    if (response.status === '401') {
                        alert(response.message)
                    }
                    else {
                        parent.remove();
                    }
                },
                error: function (response) {
                    alert(response.responseJSON.message);
                }
            });
        }
    });
});

