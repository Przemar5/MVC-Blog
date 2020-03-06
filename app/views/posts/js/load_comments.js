$(document).ready(function() {
    const btnLoadComments = document.getElementById('loadComments');

    $(btnLoadComments).click(function(e) {
        //return false;
        e.preventDefault();

        url = $(this).attr('href').replace(/(?<=\/)[\w\d\-]+(?=\?[^\/]+)/, 'load_comments');
        
        // $.get({
        //     url: url,
        //     data: data,
        //     success: success,
        //     dataType: dataType
        // });
        alert(url);
    });
});

