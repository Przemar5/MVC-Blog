$(document).ready(function() {
    const btnLoadComments = document.getElementById('loadComments');

    // btnLoadComments.addEventListener('click', function(e) {
    //     return false;
    //     e.preventDefault();
    //     alert(1);
    // });


    $('#loadComments').click(function(e) {
        alert(1)
        re = /((?<=\/)posts\/show\/)|(\?comments\=)/i;
        alert(this);

        url = this.getAttribute('href').split(re);

        urlParts = url.filter(function(e) {
                return e != undefined;
            })
            .filter(function(e) {
                re = /(posts\/show\/)|(\?comments\=)/i;
                return !e.match(re);
            });

        console.log(urlParts);


        // alert(urlParts[0] + 'comments/load/' + urlParts[1] + '/' + urlParts[2])
        var xhr = $.get(urlParts[0] + 'comments/load/' + urlParts[1] + '/' + urlParts[2], function() {
                alert( "success" );
            })
            .done(function(data) {
                alert( "second success" );
                alert(data);
            })
            .fail(function() {
                alert( "error" );
            })
            .always(function() {
                alert( "finished" );
            });

        // $.ajax({
        //         url: urlParts[0] + 'comments/load/' + urlParts[1] + '/' + urlParts[2],
        //         data: data,
        //     })
        //     .done(function(data) {
        //         alert('SEND')
        //     })

        return false;
        e.preventDefault();
    })
    //     location = window.location.href;
    //     url = $(this).attr('href').replace(/(?<=\/)show\/[\w\d\-]+(?=\?[^\/]+)/, 'load_comments');
    //
    //     // $.ajax({
    //     //     url: url,
    //     //     alert('DONE')
    //     // });
    //
    //     // $.get(url, function(data) {
    //     //     alert('Data loaded: ' + data);
    //     // });
    // });
});

