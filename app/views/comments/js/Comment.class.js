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
    this.form = null;

    var COMMENTS_PER_LOAD;
    var ROOT = 'http://localhost/files/projects/NewBlog/comments/';

    this.prepareLink = function()
    {
        var url = window.location.href;
        var pattern = /()*[]/;
    }

    this.prepareView = function()
    {
        this.view = View.element({tag: 'div', class: 'card my-4 comment comment-' + this.id});
        commentInner = View.element({tag: 'div', class: 'card-body comment__inner'});
        this.view.append(commentInner);
        commentHeader = View.element({tag: 'h3', class: 'comment__header'});
		commentUsername = View.element({tag: 'span', text: '' + this.username, class: 'comment__username'});
		commentHeader.append(commentUsername);
        commentInner.append(commentHeader);
        commentHeaderSmall = View.element({tag: 'small', class: 'h6 d-inline italic ml-2'});
        commentHeader.append(commentHeaderSmall);
		commentEmail = View.element({tag: 'span', text: this.email, class: 'comment__email'});
		commentCreatedAt = View.element({tag: 'span', text: ' created at ' + this.created_at, class: 'comment__created_at'});
        commentHeaderEm = View.element({tag: 'em'});
		commentHeaderEm.append(commentEmail);
		commentHeaderEm.append(commentCreatedAt);
        commentHeaderSmall.append(commentHeaderEm);
		
        commentHeaderBtns = View.element({tag: 'div', class: 'pull-right'});
        commentHeader.append(commentHeaderBtns);
		commentHeaderBtns.append(this.createEditCommentBtn());
        commentHeaderBtns.append(this.createDeleteCommentBtn());

        // Tmp
        commentId = View.element({tag: 'h2', text: this.id});
        commentInner.append(commentId);

        this.commentBody = View.element({tag: 'p', text: this.message, class: 'comment__message mb-4'});
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
            commentInner.append(this.btnLoadSubcomments.view);
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

            btn.closest('.comment').find('.comment__message').first().toggleClass('comment-convoluted');
            btn.toggleClass('btn-expanded');
        });

        return this.btnShowMore;
    }
	
	// Temp because of no possibility to increment load btn offset
	var incrementLoadOffset = function(url, num = 1)
	{
        re = /(?!offset\=)\d+$/;
        oldNumber = (parseInt(re.exec(url)) != 'NaN') ? parseInt(re.exec(url)) : 0;
        newNumber = oldNumber + num;
        
		return url.replace(re, newNumber);
	}
	
	this.refreshDisplay = function(arr, form)
	{
		for (i in arr)
		{
			$(this.view).find('.comment__' + arr[i]).first().text($(form).find('[name="' + arr[i] + '"]').val());
		}
	}
	
	this.createEditCommentBtn = function()
	{
		commentBtnEdit = View.element({tag: 'button', type: 'button', 'data-toggle': 'modal', 'data-target': '#modalForm', class: 'btn btn-sm btn-primary', text: 'Edit'});
        $(commentBtnEdit).click({
			username: this.username,
			email: this.email,
			message: this.message,
			id: this.id,
            post_id: this.post_id,
            parent_id: this.parent_id,
            submitValue: 'Edit Comment',
			modalCommentForm: modalCommentForm,
			self: this
			
		}, function(e) {
			$(e.data.modalCommentForm.view).attr('action', ROOT + 'edit/' + e.data.id);
			e.data.modalCommentForm.url = ROOT + 'edit/' + e.data.id;
			
			updateValue = function(fieldName, value)
			{
				elem = $(e.data.modalCommentForm.view).find('[name="' + fieldName + '"]');
				
				(elem.prop('tagName') == 'INPUT') ? elem.attr('value', value) : elem.text(value);
			}
			
			updateMultiple = function(arr)
			{
				for (i in arr)
				{
					updateValue(arr[i], e.data[arr[i]]);
				}
			}

			updateMultiple(['username', 'email', 'message', 'id', 'post_id', 'parent_id']);
			submit = $(e.data.modalCommentForm.view).find('[type="submit"]').attr('value', e.data.submitValue);
			
			$(e.data.modalCommentForm.view).submit({
				form: e.data.modalCommentForm,
				url: ROOT + 'edit/' + e.data.id,
				self: e.data.self
				
			}, function(e) {
				
				if (e.data.form.addSubmitEvent(e.data.url))
				{
					e.data.self.refreshDisplay(['username', 'email', 'message'], e.data.form.view);
					$(e.target).closest('.modal').modal('hide');
				}
				
				e.preventDefault();
				return false;
			});
		});
		
		return commentBtnEdit;
	}

	this.createDeleteCommentBtn = function() 
	{
        commentBtnDelete = View.element({tag: 'button', class: 'btn btn-sm btn-danger ml-2', text: 'Delete'});
		$(commentBtnDelete).click({
			id: this.id
		}, function(e) {
			if (window.confirm('Are You sure You want to delete this comment?'))
			{
				$.post(ROOT + 'delete/' + e.data.id);
			}
		});
		
		return commentBtnDelete;
	}

    this.createAddCommentBtn = function()
    {
        this.btnAddComment = View.element({tag: 'a', href: ROOT + 'create?post=' + this.post_id + '&parent=' + this.id,
            class: 'btn btn-sm btn-primary mb-3', text: 'Add Comment'});

        $(this.btnAddComment).click({
            post_id: this.post_id,
            parent_id: this.id,
            submitValue: 'Add Comment',
            form: this.form,
			subcommentsArea: this.subcommentsArea,
			btnLoad: this.btnLoadSubcomments
			
        }, function(e) {
            formObj = new Form();
            form = formObj.createForm(e.data.post_id, e.data.parent_id, null, e.data.submitValue, null, ROOT + 'create');

			btnLoad = e.data.btnLoad;
			
            $(e.target).closest('.comment').find('.comment__add-div').first().append(form);
            $(e.target).closest('.comment').find('form').first().submit({
				formObject: formObj,
				btnLoad: btnLoad
				
			}, function(e) {
				subcommentsArea = $(e.target).closest('.comment').find('.comments').first();
				
				if (e.data.formObject.addSubmitEvent())
				{
					url = ROOT + 'load?post=' + e.data.formObject.post_id + '&parent=' + e.data.formObject.parent_id + '&comments=1&offset=0';

					$.get(url, function(data) {
						data = JSON.parse(data);
						comment = new Comment(data[0]);
						
						if (subcommentsArea.children('.comment').length > 0)
							subcommentsArea.prepend(comment.prepareView());
						else
							subcommentsArea.append(comment.prepareView());

						btnLoad = $(e.target).closest('.comment').find('.btn-load').last();
						url = btnLoad.attr('href');
						btnLoad.attr('href', incrementLoadOffset(url, 1));
					});
				}
				
				e.preventDefault();
				return false;
			});

            $(e.target).hide();

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
                //
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