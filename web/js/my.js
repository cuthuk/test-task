$(function() {
    dialog = $("#add-dialog-form").dialog({ autoOpen: false });
    dialog.find("form").on("submit", function(e) {
        e.preventDefault();
        $parentId = $(this).find("#parent_id").val();
        $data = $(this).serialize();
        $.ajax({
            type: 'POST',
            url: '/comment/add',
            data: $data
        }).done(function (data) {
            $("#children_" + $parentId).append(data);
            $("#add-dialog-form").dialog('close');
        }).fail(function (data) {
            alert(data.responseText);
        });
    });

    dialogEdit = $("#edit-dialog-form").dialog({ autoOpen: false });
    dialogEdit.find("form").on("submit", function(e) {
        e.preventDefault();
        $commentId = $(this).find("#id").val();
        $comment = $(this).find("#comment").val();
        $data = $(this).serialize();
        $.ajax({
            type: 'POST',
            url: '/comment/edit',
            data: $data
        }).done(function (data) {
            $("#comment_" + $commentId).find("#message").html($comment);
            $("#edit-dialog-form").dialog('close');
        }).fail(function (data) {
            alert(data.responseText);
        });
    });

    $("body")
        .on('click',"button[class=expand]", function(e){
            e.preventDefault();
            $parentId = $(this).attr('data-comment-id');
            $.get('comment/get-childs',
                {parentId: $parentId},
                function (data) {
                    $("#children_"+$parentId).html(data);
                });
        })
        .on('click', "button[class=add]", function(e){
            e.preventDefault();
            $("#add-dialog-form").find("form")[0].reset();
            $parentId = $(this).attr('data-comment-id');
            $("#parent_id").val($parentId);
            $("#add-dialog-form").dialog('open');
        })
        .on('click', "button[class=edit]", function(e){
            e.preventDefault();
            $("#edit-dialog-form").find("form")[0].reset();
            $commentId = $(this).attr('data-comment-id');
            $("#edit-dialog-form").find("#id").val($commentId);
            $message = $("#comment_" + $commentId).find("#message").html();
            $("#edit-dialog-form").find("#comment").val($message);
            $("#edit-dialog-form").dialog('open');
        })
        .on('click', "button[class=delete]", function(e){
            e.preventDefault();
            $id = $(this).attr('data-comment-id');
            $data = {id: $id};
            $.ajax({
                type: 'POST',
                url: '/comment/delete',
                data: $data
            }).done(function (data) {
                $("#comment_" + $id).remove();
            }).fail(function (data) {
                alert(data.responseText);
            });
        })
    ;

});
