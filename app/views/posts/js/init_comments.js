$(document).ready(function() {
    var rootComments = $(document).find('.comments');
    var post_id = $('#post_id').attr('value');
    var loadMore = new BtnLoad(post_id, 0);
    loadMore.click();
    rootComments.after(loadMore);
});