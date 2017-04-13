$(function() {
    $("button[class=expand]").on('click', function(e){
       e.preventDefault();
       $parentId = $(this).attr('data-comment-id');
        $.get('comment/get-childs',
            {parentId: $parentId},
            function (data) {
                $("#children_"+$parentId).html(data);
            });
    });
})