var mainForm;

$(document).ready(function() {
	
    var incrementOffsetCount = function(num = 1)
    {
        re = /(?!offset\=)\d+$/;
        oldNumber = (parseInt(re.exec(url)[0]) != 'NaN') ? parseInt(re.exec(url)[0]) : 0;
        newNumber = oldNumber + 1;
		url = url.replace(re, '' + newNumber);
		
		return url;
    }
	
	var initCommentForm = function(url)
	{
		mainForm = new Form();
		post_id = $('#post_id').val();
		
		$('#commentFormArea').append(mainForm.createForm(post_id, 0));
		$('#commentFormArea').find('form').first().submit({
			form: mainForm,
			url: url,
			post_id: post_id,
			incrementOffsetCount: incrementOffsetCount
		
		}, function(e) {
			
			if (e.data.form.addSubmitEvent(e.data.url))
			{
				url = ROOT + 'load?post=' + e.data.post_id + '&parent=0&comments=1&offset=0';

				$.get(url, function(data) {
					data = JSON.parse(data);
					comment = new Comment(data[0]);
					commentsArea = $(document).find('.comments').first();

					if (subcommentsArea.children('.comment').length > 0)
						commentsArea.prepend(comment.prepareView());
					else
						commentsArea.append(comment.prepareView());

					btnLoad = $(document).find('.btn-load').last();
					url = btnLoad.attr('href');
					btnLoad.attr('href', e.data.incrementOffsetCount(url, 1));
				});
			}
			
			
			e.preventDefault();
			return false;
		});
	}
	
	ROOT = 'http://localhost/files/projects/NewBlog/comments/'
	url = ROOT + 'create';
	initCommentForm(url);
});