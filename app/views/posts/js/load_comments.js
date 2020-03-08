$(document).ready(function() {
    const btnLoadComments = document.getElementById('loadComments');

    // btnLoadComments.addEventListener('click', function(e) {
    //     return false;
    //     e.preventDefault();
    //     alert(1);
    // });


    $('#loadComments').click(function(e) {
        var re = /((?<=\/)posts\/show\/)|(\?comments\=)/i;
        var url = this.getAttribute('href').split(re);

        urlParts = url.filter(function(e) {
                return e != undefined;
            })
            .filter(function(e) {
                re = /(posts\/show\/)|(\?comments\=)/i;
                return !e.match(re);
            });

        // alert(urlParts[0] + 'comments/load/' + urlParts[1] + '/' + urlParts[2])
        xhr = $.get(urlParts[0] + 'comments/load/' + urlParts[1] + '/' + urlParts[2], function() {
                alert( "success" );
            })
            .done(function(data) {
                parseComment(data);
            })

        return false;
        e.preventDefault();
    })

    var parseComment = function(data) {
        var comments = JSON.parse(data);
        console.log(comments);

        for (var i = 0; i < comments.length; i++)
        {
            console.log(comments[i]);

            comment = new Comment();
            comment.populate(comments[i]);
        }
    }
});


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

// comment = new Comment();
// comment.display();