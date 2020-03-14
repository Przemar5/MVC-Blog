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
    this.subcommentsArea = null;

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
                    <div class="card-body">
                        <h3>
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
    
                        <p>
                            ${this.message}
                        </p>
                        
                        <div class="subcomments"></div>
                    </div>
                </div>`;

        return comment;
    }

    this.prepareView = function()
    {
        this.view = View.element({tag: 'div', class: 'card my-4 comment'});
        commentInner = View.element({tag: 'div', class: 'card-body'});
        this.view.append(commentInner);
        commentHeader = View.element({tag: 'h3', text: '' + this.username});
        commentInner.append(commentHeader);
        commentHeaderSmall = View.element({tag: 'small', class: 'h6 d-inline italic ml-2'});
        commentHeader.append(commentHeaderSmall);
        commentHeaderEm = View.element({tag: 'em', text: this.email + ' created at ' + this.created_at})
        commentHeaderSmall.append(commentHeaderEm);
        commentHeaderBtns = View.element({tag: 'div', class: 'pull-right'});
        commentHeader.append(commentHeaderBtns);
        commentBtnEdit = View.element({tag: 'a', href: ROOT + 'edit/' + this.id, class: 'btn btn-sm btn-primary', text: 'Edit'});
        commentHeaderBtns.append(commentBtnEdit);
        commentBtnDelete = View.element({tag: 'a', href: ROOT + 'delete/' + this.id, class: 'btn btn-sm btn-danger', text: 'Delete'});
        commentHeaderBtns.append(commentBtnDelete);

        // Tmp
        commentId = View.element({tag: 'h2', text: this.id});
        commentInner.append(commentId);

        commentBody = View.element({tag: 'p', text: this.message})
        commentInner.append(commentBody);
        this.subcommentsArea = View.element({tag: 'div', class: 'subcomments'});
        commentInner.append(this.subcommentsArea);

        if (this.subcomments_count > 0)
        {
            this.btnLoadSubcomments = View.element({tag: 'a',
                                            href: ROOT + 'load?post=' + this.post_id + '&parent=' + this.id + '&comments=' + 5,
                                            class: 'btn btn-block btn-default', text: 'Load More'});
            $(this.btnLoadSubcomments).click(function(e) {
                this.subcommentsArea.append(loadMore(e.target.getAttribute('href')));

                e.preventDefault();
                return false;
            });
            commentInner.append(this.btnLoadSubcomments);
        }

        body = $('body');
        body.append(this.view);

        return this.view;
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

    console.log(this.prepareView());

    this.prepareLoadBtn = function()
    {
        btnLoad = document.createElement('a');
        $(btnLoad).addClass('btn btn-block btn-default btn-load');
        $(btnLoad).text('Load More');
        $(btnLoad).attr('href', ROOT + 'load?post=' + this.post_id + '&parent=' + this.id + '&comments=' + this.subcomments_count);
        $(btnLoad).click(function(e) {
            // makeQuery();

            e.preventDefault();
            return false;
        });
        console.log(btnLoad);
    }

    this.prepareLoadBtn();

    this.display = function()
    {
        comments = $('#comments');
        comment = document.createElement('div');
        comment.innerText = this.prepareDisplay();
        comments.append(comment);
    }

    display();
}

a = function()
{
    alert('WORKS')
}

// data = '[{"id":165,"username":"przemar5","email":"przemar5@o2.pl","message":" hjnnhnbiu hjhbjbk","user_id":1,"created_at":"2020-03-10 20:45:02","updated_at":null,"deleted":0},{"id":164,"username":"przemar5","email":"przemar5@o2.pl","message":" hjnnhnbiu hjhbjbk","user_id":1,"created_at":"2020-03-10 20:44:49","updated_at":null,"deleted":0},{"id":163,"username":"przemar5","email":"przemar5@o2.pl","message":" hjnnhnbiu hjhbjbk","user_id":1,"created_at":"2020-03-10 20:44:01","updated_at":null,"deleted":0},{"id":162,"username":"przemar5","email":"przemar5@o2.pl","message":" hjnnhnbiu hjhbjbk","user_id":1,"created_at":"2020-03-10 20:43:53","updated_at":null,"deleted":0},{"id":161,"username":"przemar5","email":"przemar5@o2.pl","message":" hjnnhnbiu hjhbjbk","user_id":1,"created_at":"2020-03-10 20:42:39","updated_at":null,"deleted":0}]';
// for (commentData in commentsData)
// {
//     comment = new Comment(commentData);
// }
//
// commentsData = JSON.parse(data);
//
// console.log(comments);
//
// alert('ok');
//
// comment = new Comment();
// comment.display();