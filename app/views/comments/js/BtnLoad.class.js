function BtnLoad(post_id, parent_id)
{
    var COMMENTS_PER_LOAD = 5;
    var ROOT = 'http://localhost/files/projects/NewBlog/comments/';
    var count;

    this.post_id = post_id;
    this.parent_id = parent_id;
    var btn;

    this.constructor = function ()
    {
        btn = View.element({tag: 'a',
            href: ROOT + 'load?post=' + this.post_id + '&parent=' + this.parent_id + '&comments=' + 5,
            class: 'btn btn-block btn-default btn-load', text: 'Load More Comments'});
        $(btn).addClass('btn btn-primary btn-load');
        $(btn).attr('href', ROOT + 'load?post=' + this.post_id + '&parent=' + this.parent_id + '&comments=' + COMMENTS_PER_LOAD);
        $(btn).click(function(e) {
            subcommentsArea = $(e.target).closest('.comment').find('.comments').first();

            if (subcommentsArea[0] == undefined || subcommentsArea[0] == null)
                subcommentsArea = $(document).find('.comments').first();

            $.get($(e.target).attr('href'))
                .done(function(data) {
                    var comments = JSON.parse(data);
                    subcommentsArea = $(e.target).closest('.comment').find('.comments').first();

                    if (subcommentsArea[0] == undefined || subcommentsArea[0] == null)
                        subcommentsArea = $('body').find('.comments').first();

                    for (var i = 0; i < comments.length; i++) {
                        comment = new Comment(comments[i]);
                        subcommentsArea.append(comment.prepareView());
                    }

                    if (comments.length < COMMENTS_PER_LOAD)
                    {
                        $(e.target).addClass('d-none');
                    }
                    else
                    {
                        incrementCommentsCount();
                    }
                });


            e.preventDefault();
            return false;
        });

        count = 0;

        return btn;
    }

    var countSubcomments = function()
    {
        return $(this.btn).closest('.comment').find('.comments').first().children('.comment').length ||
                $(document).find('.comments').first().children('.comment').length;
    }

    var incrementCommentsCount = function()
    {
        url = $(btn).attr('href');
        re = /(?!comments\=)\d+$/;
        oldNumber = parseInt(re.exec(url));
        newNumber = oldNumber + COMMENTS_PER_LOAD;
        url = url.replace(re, newNumber);
        $(btn).attr('href', url);
    }

    return this.constructor();
}