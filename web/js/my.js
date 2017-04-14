$(function() {
    dialog = $("#add-dialog-form").dialog({ autoOpen: false });
    dialog.find("form").on("submit", function(e) {
        e.preventDefault();
        $parentId = $("#comment_parent_id").val();
        $comment = $(this).find("#comment").val();
        $data = {comment:$comment,parent_id: $parentId};
        $.ajax({
            type: 'POST',
            url: '/comment/add',
            data: $data
        }).done(function (data) {
            $("#children_" + $parentId).append(data)
        }).fail(function (data) {
            alert(data.responseText);
        });
    });

    $("button[class=expand]").on('click', function(e){
       e.preventDefault();
       $parentId = $(this).attr('data-comment-id');
        $.get('comment/get-childs',
            {parentId: $parentId},
            function (data) {
                $("#children_"+$parentId).html(data);
                initButtonActions();
            });
    });

    initButtonActions();

});

function initButtonActions() {
    $("button[class=add]").on('click', function(e){
        e.preventDefault();
        $parentId = $(this).attr('data-comment-id');
        $("#comment_parent_id").val($parentId);
        $("#add-dialog-form").dialog('open');
    });

    $("button[class=edit]").on('click', function(e){
        e.preventDefault();
        $parentId = $(this).attr('data-comment-id');
        $.get('comment/get-childs',
            {parentId: $parentId},
            function (data) {
                $("#children_"+$parentId).html(data);
            });
    });

    $("button[class=delete]").on('click', function(e){
        e.preventDefault();
        $parentId = $(this).attr('data-comment-id');
        $.get('comment/get-childs',
            {parentId: $parentId},
            function (data) {
                $("#children_"+$parentId).html(data);
            });
    });
}