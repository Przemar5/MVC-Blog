function BtnLoad(post_id, parent_id)
{
    var COMMENTS_PER_LOAD = 5;
    var ROOT = 'http://localhost/files/projects/NewBlog/comments/';
    var count;
	
    this.post_id = post_id;
    this.parent_id = parent_id;
	this.offset = 0;
	this.count = COMMENTS_PER_LOAD;
	this.view;
    var btn;

    this.constructor = function ()
    {
        this.view = View.element({tag: 'a',
            href: ROOT + 'load?post=' + this.post_id + '&parent=' + this.parent_id + '&comments=' + this.count + '&offset=' + 0,
            class: 'btn btn-block btn-default btn-load', text: 'Load More Comments'});
        $(this.view).addClass('btn btn-primary btn-load');
        $(this.view).attr('href', ROOT + 'load?post=' + this.post_id + '&parent=' + this.parent_id + '&comments=' + this.count + '&offset=' + this.offset);
        $(this.view).click(function(e) {
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
                        incrementOffsetCount();
                    }
                });

            e.preventDefault();
            return false;
        });

        count = 0;
		
		btn = this.view;
    }

    var countSubcomments = function()
    {
        return $(this.view).closest('.comment').find('.comments').first().children('.comment').length ||
                $(document).find('.comments').first().children('.comment').length;
    }

    var incrementOffsetCount = function(num = COMMENTS_PER_LOAD)
    {
        url = $(btn).attr('href');
        re = /(?!offset\=)\d+$/;
        oldNumber = (parseInt(re.exec(url)) != 'NaN') ? parseInt(re.exec(url)) : 0;
        newNumber = oldNumber + num;
        url = url.replace(re, newNumber);
        $(btn).attr('href', url);
    }
	
	this.incrementOffsetCount = function(num = COMMENTS_PER_LOAD)
    {
        url = $(btn).attr('href');
        re = /(?!offset\=)\d+$/;
        oldNumber = (parseInt(re.exec(url)) != 'NaN') ? parseInt(re.exec(url)) : 0;
        newNumber = oldNumber + num;
        url = url.replace(re, newNumber);
        $(btn).attr('href', url);
    }

    this.constructor();
}