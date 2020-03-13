$(document).ready(function() {
    const btnLoadComments = document.getElementById('loadComments');
    var commentsNumber;
    var commentsPerLoad = 5;
    var disabled = false;


    $('#loadComments').click(function (e) {

        if (!disabled) {
            var url = this.getAttribute('href');

            var xhr = $.get(url, function () {
                    alert("success");
                })
                .done(function (data) {
                    commentsNumber = $('.comment').length;
                    var newUrl = incrementCommentsNumberUrl(url);

                    if (data.length == 0)
                    {
                        disableLoadMore();
                        appendLoadedAllMessage();
                        return false;
                    }

                    parseComment(data);

                    btnLoadComments.setAttribute('href', newUrl);
                });
        }

        return false;
        e.preventDefault();
    })

    var parseComment = function (data)
    {
        var comments = JSON.parse(data);

        for (var i = 0; i < comments.length; i++) {
            Comment(comments[i]);
        }
    }

    var splitUrl = function (url)
    {
        var re = /((?<=\/)posts\/show\/)|(\?comments\=)/i;
        url = url.split(re);

        return url.filter(function (e) {
                return e != undefined;
            })
            .filter(function (e) {
                re = /(posts\/show\/)|(\?comments\=)/i;
                return !e.match(re);
            });
    }

    var incrementCommentsNumberUrl = function(url)
    {
        re = /(?!(\?|\&)comments\=)\d+/i;
        commentsNumber = parseInt(re.exec(url));
        commentsNumber += commentsPerLoad;

        return url.replace(re, commentsNumber);
    }

    var disableLoadMore = function()
    {
        alert('disabled')
        $(btnLoadComments).css('display', 'none');
    }

    var appendLoadedAllMessage = function()
    {
        msg = document.createElement('h3');
        msg.innerText = 'There are no more comments.';
        $(btnLoadComments).after(msg);
    }

    Comment = function(data)
    {
        this.id = data.id;
        this.username = data.username;
        this.email = data.email;
        this.message = data.message;
        this.user_id = data.user_id;
        this.created_at = data.created_at;
        this.updated_at = data.updated_at;
        this.deleted = data.deleted;
        this.parent_id = data.parent_id;
        var ROOT = 'http://localhost/files/projects/NewBlog/comments/';

        this.prepareLink = function()
        {
            var url = window.location.href;
            var pattern = /()*[]/;

            console.log(url);
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

                    <a href="${ROOT}load/${this.id}?" class="btn btn-block btn-default
                    t">
                        Load More 
                    </a>
                </div>
            </div>`;
        }


        this.display = function()
        {
            comments = $('#comments');
            comment = document.createElement('div');
            comment.innerText = this.prepareDisplay();
            comments.append(comment);
        }

        display();
    }


});




// class Comment
// {
//     constructor(data)
//     {
//         console.log(data);
//
//         // for (prop in data) {
//         //     console.log(prop)
//         //     if (this.hasOwnProperty(prop) && prop != undefined) {
//         //         this[prop] = data.prop;
//         //         console.log(prop + ': ' + this[prop])
//         //     }
//         // }
//     }
// }


var Comment = (function() {
    // this.id, this.email, this.message, this.user_id, this.created_at, this.updated_at, this.post_id, this.parent_comment_id;
    var id = 1, email, message, user_id, created_at, updated_at, post_id, parent_comment_id;
    var test = 1;

    function CommentConstructor() {
        alert('ok');

        // for (prop in data) {
        //     if (this.hasOwnProperty(prop) && prop != undefined) {
        //         this[prop] = data.prop;
        //         console.log(prop + ': ' + this[prop])
        //     }
        // }
    }

    Comment.prototype.populate = function(data) {
        console.log(data);

        for (prop in data) {
            console.log(prop)
            if (this.hasOwnProperty(prop) && prop != undefined) {
                this[prop] = data.prop;
                console.log(prop + ': ' + this[prop])
            }
        }
    }

    Comment.prototype.display = function() {

    }

    return CommentConstructor();
}());
