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
    this.btnAddComment = null;
    this.btnLoadSubcomments = null;
    this.commentBody = null;
    this.btnShowMore = null;
    this.addCommentDiv = null;
    this.addCommentForm = null;
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

        if (this.message.length > 500)
        {
            this.truncateComment();
            commentInner.append(this.showMore());
        }

        this.addCommentDiv = View.element({tag: 'div', class: 'comment__add-div'});
        commentInner.append(this.addCommentDiv);
        commentInner.append(this.createAddCommentBtn());
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
        this.btnShowMore = View.element({tag: 'button', text: 'Expand', class: 'btn-text btn-expand mb-2'});
        $(this.btnShowMore).click(function(e) {
            btn = $(e.target);

            if (btn.hasClass('btn-expanded'))
                btn.text('Expand');
            else
                btn.text('Hide');

            btn.closest('.comment').find('.comment__body').first().toggleClass('comment-convoluted');
            btn.toggleClass('btn-expanded');
        });

        return this.btnShowMore;
    }

    this.createAddCommentBtn = function()
    {
        this.btnAddComment = View.element({tag: 'a', href: ROOT + 'create?post=' + this.post_id + '&parent=' + this.id,
            class: 'btn btn-sm btn-primary mb-3', text: 'Add Comment'});
        $(this.btnAddComment).on('click', function(e) {
            loadForm = new Promise(function(resolve, reject) {
                addCommentDiv = $(e.target).closest('.comment').find('.comment__add-div').first();
                addCommentDiv.addClass('mb-4');

                addCommentDiv.load(ROOT + 'form');
                $(e.target).addClass('d-none');

                setTimeout(function() {
                    addCommentDiv.find('input[type="submit"]').val('Add Comment');
                    addCommentDiv.find('input[name="post_id"]').val('Add Comment');
                    addCommentDiv.find('input[name="parent_id"]').val('Add Comment');
                    addCommentDiv.find('input[type="submit"]').val('Add Comment');
                }, 300);
            });

            loadForm.then(function() {
                addCommentDiv = $(e.target).closest('.comment').find('.comment__add-div').first();
                // addCommentDiv.find('submit').first().val('Add Comment');
                console.log(addCommentDiv[0].innerHTML)
            });


            e.preventDefault();
            return false;
        });

        return this.btnAddComment;
    }

    prepareForm = function()
    {

    }

    toggleCommentForm = function()
    {
        //addCommentDiv = $(e.target).closest('.comment');

        //console.log(addCommentDiv);
        // if (addCommentForm == null)
        // {
        //     console.log(addCommentDiv[0]);
        //     $(addCommentDiv).load(ROOT + 'partials/form.php');
        // }
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

    this.display = function()
    {
        comments = $('#comments');
        this.prepareView();
        comments.append(this.view);
    }

    this.display();
}