function Comment(data)
{
    this.id = data.id;
    this.username = data.username;
    this.email = data.email;
    this.message = data.message;
    this.user_id = data.user_id;
    this.created_at = data.created_at;
    this.updated_at = data.updated_at;
    this.deleted = data.deleted;
    this.post_id = data.post_id;
    this.parent_id = data.parent_id;
    this.subcomments_count = data.subcomments_count;
    this.view = null;
    this.btnLoadSubcomments = null;
    this.commentBody = null;
    this.btnShowMore = null;
    this.subcommentsArea = null;

    var COMMENTS_PER_LOAD;
    var ROOT = 'http://localhost/files/projects/NewBlog/comments/';

    this.prepareLink = function()
    {
        var url = window.location.href;
        var pattern = /()*[]/;
    }

    this.prepareDisplay = function()
    {
        this.prepareLink();

        comment = `<div class="card my-4 comment">
                    <div class="card-body comment__inner">
                        <h3 class="comment__header">
                            ${this.username}
                            <small class="h6 d-inline italic ml-2">
                                <em>
                                    ${this.email}
                                    created at ${this.created_at}
                                </em>
                            </small>
                        
                            <div class="pull-right">
                                <a href="${ROOT}edit/${this.id}" class="btn btn-sm btn-primary">Edit</a>
                                <a href="${ROOT}delete/${this.id}" class="btn btn-sm btn-danger">Delete</a>
                            </div>
                        </h3>
    
                        <h2>${this.id}</h2>
    
                        <p class="comment__body">
                            ${this.message}
                        </p>
                        
                        <div class="comments"></div>
                    </div>
                </div>`;

        return comment;
    }

    this.prepareView = function()
    {
        this.view = View.element({tag: 'div', class: 'card my-4 comment comment-' + this.id});
        commentInner = View.element({tag: 'div', class: 'card-body comment__inner'});
        this.view.append(commentInner);
        commentHeader = View.element({tag: 'h3', text: '' + this.username, class: 'comment__header'});
        commentInner.append(commentHeader);
        commentHeaderSmall = View.element({tag: 'small', class: 'h6 d-inline italic ml-2'});
        commentHeader.append(commentHeaderSmall);
        commentHeaderEm = View.element({tag: 'em', text: this.email + ' created at ' + this.created_at})
        commentHeaderSmall.append(commentHeaderEm);
        commentHeaderBtns = View.element({tag: 'div', class: 'pull-right'});
        commentHeader.append(commentHeaderBtns);
        commentBtnEdit = View.element({tag: 'a', href: ROOT + 'edit/' + this.id, class: 'btn btn-sm btn-primary', text: 'Edit'});
        commentHeaderBtns.append(commentBtnEdit);
        commentBtnDelete = View.element({tag: 'a', href: ROOT + 'delete/' + this.id, class: 'btn btn-sm btn-danger ml-2', text: 'Delete'});
        commentHeaderBtns.append(commentBtnDelete);

        // Tmp
        commentId = View.element({tag: 'h2', text: this.id});
        commentInner.append(commentId);

        this.commentBody = View.element({tag: 'p', text: this.message, class: 'comment__body mb-4'});
        commentInner.append(this.commentBody);

        if (this.message.length > 10)
            this.truncateComment();

        commentInner.append(this.showMore());

        this.subcommentsArea = View.element({tag: 'div', class: 'comments'});
        commentInner.append(this.subcommentsArea);

        if (this.subcomments_count > 0)
        {
            this.btnLoadSubcomments = new BtnLoad(this.post_id, this.id);
            commentInner.append(this.btnLoadSubcomments);
        }

        return this.view;
    }

    this.showMore = function()
    {
        commentBody = this.commentBody;
        this.btnShowMore = View.element({tag: 'button', text: 'Show More', class: 'btn btn-muted btn-expand'});
        $(this.btnShowMore).click(function(e) {
            btn = $(e.target);

            if (btn.hasClass('comment-convoluted'))
            {
                btn.toggleClass('comment-convoluted');
                btn.text = 'Test';
            }

            alert('ok');
        });

        return this.btnShowMore;
    }

    this.loadMore = function(url)
    {
        $.get(url, function () {
                alert("success");
            })
            .done(function (data) {
                var comments = JSON.parse(data);

                for (var i = 0; i < comments.length; i++) {
                    comment = new Comment(comments[i]);
                    this.subcommentsArea.append(comment.prepareView());
                }
            });
    }

    this.truncateComment = function()
    {
        $(this.commentBody).addClass('comment-convoluted');
    }

    this.toggleShowMore = function()
    {

    }

    this.display = function()
    {
        comments = $('#comments');
        this.prepareView();
        comments.append(this.view);
    }

    this.display();
}