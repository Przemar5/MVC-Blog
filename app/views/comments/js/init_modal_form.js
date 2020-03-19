var modalCommentForm

$(document).ready(function() {
	var createModal = function()
	{
		modal = View.element({tag: 'div', class: 'modal fade', id: 'modalForm', tabindex: '-1', role: 'dialog', 'aria-labelledby': 'modalFormLabel', 'aria-hidden': 'true'});
		modalInner = View.element({tag: 'div', class: 'modal-dialog', role: 'document'});
		modalContent = View.element({tag: 'div', class: 'modal-content'});
		
		modalHeader = View.element({tag: 'div', class: 'modal-header'});
		h5 = View.element({tag: 'h5', class: 'modal-title', id: 'modalFormLabel', text: 'Edit comment'});
		$(modalHeader).append(h5);
		btnClose = View.element({tag: 'button', type: 'button', class: 'close', 'data-dismiss': 'modal', 'aria-label': 'Close'});
		span = View.element({tag: 'span', 'aria-hidden': 'true', text: '&times;'});
		$(btnClose).append(span);
		$(modalHeader).append(btnClose);
		$(modalContent).append(modalHeader);
		
		modalBody = View.element({tag: 'div', class: 'modal-body'});
		
		modalCommentForm = new Form();
		
		$(modalBody).append(modalCommentForm.createForm('', '', '', 'Edit Comment', '', ''))
		$(modalContent).append(modalBody);
		
		$(modalInner).append(modalContent);
		$(modal).append(modalInner);
		
		$('#modalArea').append(modal);
	}
	
	var updateModalFormFields = function(username, email, message, id, post_id, parent_id, submitValue, url)
	{
		alert('ok')
	}
	
	createModal();
	modalBtn = View.element({tag: 'button', type: 'button', class: 'd-none', 'data-toggle': 'modal', 'data-target': '#modalForm', text: 'launch modal'});

	$('#modalArea').prepend(modalBtn);
});