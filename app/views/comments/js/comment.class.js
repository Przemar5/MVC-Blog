function Comment(comment) {
    var id, username, email, message, user_id, created_at, updated_at, post_id, parent_comment_id;

    this.populate = function(data) {
        for (prop in data) {
            if (this.hasOwnProperty(prop)) {

            }
        }
    }

    this.display = function() {

    }

    return this;
}

data = '[{"id":165,"username":"przemar5","email":"przemar5@o2.pl","message":" hjnnhnbiu hjhbjbk","user_id":1,"created_at":"2020-03-10 20:45:02","updated_at":null,"deleted":0},{"id":164,"username":"przemar5","email":"przemar5@o2.pl","message":" hjnnhnbiu hjhbjbk","user_id":1,"created_at":"2020-03-10 20:44:49","updated_at":null,"deleted":0},{"id":163,"username":"przemar5","email":"przemar5@o2.pl","message":" hjnnhnbiu hjhbjbk","user_id":1,"created_at":"2020-03-10 20:44:01","updated_at":null,"deleted":0},{"id":162,"username":"przemar5","email":"przemar5@o2.pl","message":" hjnnhnbiu hjhbjbk","user_id":1,"created_at":"2020-03-10 20:43:53","updated_at":null,"deleted":0},{"id":161,"username":"przemar5","email":"przemar5@o2.pl","message":" hjnnhnbiu hjhbjbk","user_id":1,"created_at":"2020-03-10 20:42:39","updated_at":null,"deleted":0}]';
commentsData = JSON.parse(data);

for (commentData in commentsData)
{
    comment = new Comment(commentData);
}

console.log(comments);

alert('ok');

comment = new Comment();
comment.display();