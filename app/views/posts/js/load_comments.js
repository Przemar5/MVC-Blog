$(document).ready(function() {
//     const btnLoadComments = document.getElementById('loadComments');
//     var commentsNumber;
//     var commentsPerLoad = 5;
//     var disabled = false;
//
//
//     $('#loadComments').click(function(e) {
//
//         if (!disabled) {
//             var url = this.getAttribute('href');
//
//             var xhr = $.get(url, function () {
//                     alert("success");
//                 })
//                 .done(function (data) {
//                     commentsNumber = $('.comment').length;
//                     var newUrl = incrementCommentsNumberUrl(url);
//
//                     if (data.length == 0)
//                     {
//                         disableLoadMore();
//                         appendLoadedAllMessage();
//                         return false;
//                     }
//
//                     parseComment(data);
//
//                     btnLoadComments.setAttribute('href', newUrl);
//                 });
//         }
//
//         return false;
//         e.preventDefault();
//     })
//
//     var parseComment = function (data)
//     {
//         var comments = JSON.parse(data);
//
//         for (var i = 0; i < comments.length; i++) {
//             Comment(comments[i]);
//         }
//     }
//
//     var splitUrl = function (url)
//     {
//         var re = /((?<=\/)posts\/show\/)|(\?comments\=)/i;
//         url = url.split(re);
//
//         return url.filter(function (e) {
//                 return e != undefined;
//             })
//             .filter(function (e) {
//                 re = /(posts\/show\/)|(\?comments\=)/i;
//                 return !e.match(re);
//             });
//     }
//
//     var incrementCommentsNumberUrl = function(url)
//     {
//         re = /(?!(\?|\&)comments\=)\d+/i;
//         commentsNumber = parseInt(re.exec(url));
//         commentsNumber += commentsPerLoad;
//
//         return url.replace(re, commentsNumber);
//     }
//
//     var disableLoadMore = function()
//     {
//         alert('disabled');
//         $(btnLoadComments).css('display', 'none');
//     }
//
//     var appendLoadedAllMessage = function()
//     {
//         msg = document.createElement('h3');
//         msg.innerText = 'There are no more comments.';
//         $(btnLoadComments).after(msg);
//     }
//
//     var makeQuery = function()
//     {
//         var url = this.getAttribute('href');
//
//         var xhr = $.get(url, function () {
//             alert("success");
//         })
//             .done(function (data) {
//                 commentsNumber = $('.comment').length;
//                 var newUrl = incrementCommentsNumberUrl(url);
//
//                 if (data.length == 0)
//                 {
//                     disableLoadMore();
//                     appendLoadedAllMessage();
//                     return false;
//                 }
//
//                 parseComment(data);
//
//                 btnLoadComments.setAttribute('href', newUrl);
//             });
//     }
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