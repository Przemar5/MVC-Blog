function Form(data)
{
    if (formValues == undefined)
        var formValues = new Array('username', 'email', 'message', 'id', 'post_id', 'parent_id');

    if (validationRules == undefined)
        var validationRules = {
            'username': {
                'required': {'msg': 'Username is required.'},
                'min': {'args': [6], 'msg': 'Username must be equal or longer than 6 characters.'},
                'max': {'args': [150], 'msg': 'Username cannot be longer than 150 characters.'},
                'regex': {'args': ['[0-9a-zA-Z\\ \\@\\+\\/\\?\\!\\$\\_\\-]+'], 'msg': 'Username contains illegal characters.'},
            },
            'email': {
                'required': {'msg': 'Email address is required.'},
                'min': {'args': [6], 'msg': 'Email address must be equal or longer than 6 characters.'},
                'max': {'args': [150], 'msg': 'Email address cannot be longer than 150 characters.'},
                'regex': {'args': ['[0-9a-zA-Z\\ \\@\\+\\/\\?\\!\\$\\_\\-\\.\\,]+'], 'msg': 'Email address contains illegal characters.'},
            },
            'message': {
                'required': {'msg': 'Comment body is required.'},
                'min': {'args': [6], 'msg': 'Comment must be equal or longer than 6 characters.'},
            },
            'post_id': {
                'required': {'msg': ''},
                'numeric': {'msg': ''},
            },
            'parent_id': {
                'required': {'msg': ''},
                'numeric': {'msg': ''},
            }
        }

    this.post_id = null;
    this.parent_id = null;
    this.id = null;
    this.submitValue = null;
    this.token = null;
	this.url = null;
    this.formData = {};
    this.errors = new Array();
    this.view = new Array();
	this.passed = new Array();
	this.valid = false;

	
    this.constructor = function(data)
    {
		this.passed = this.arrayToDictionary(formValues, false);
        this.validation = new Validator();
    }

    this.createForm = function(post_id, parent_id, id, submitValue, token, url)
    {
        this.post_id = post_id;
        this.parent_id = parent_id;
        this.id = id;
        this.submitValue = submitValue;
        this.token = token;
		this.url = url;
		this.view = View.element({tag: 'form', method: 'post', action: url, class: 'mb-3'});
		$(this.view).submit()
		
        row = View.element({tag: 'div', class: 'row mt-0 mb-5'});
        $(row).append(this.createMiniInput('username', 'text', 'Username'));
        $(row).append(this.createMiniInput('email', 'email', 'Email'));
        $(row).append(this.createMiniInput('message', 'textarea', 'Comment', '', true));

        this.view.idInput = View.element({tag: 'input', type: 'hidden', name: 'id', value: this.id, class: 'is-valid'});
        $(this.view).append(this.view.idInput);
        this.view.postIdInput = View.element({tag: 'input', type: 'hidden', name: 'post_id', value: this.post_id, class: 'is-valid'});
        $(this.view).append(this.view.postIdInput);
        this.view.parentIdInput = View.element({tag: 'input', type: 'hidden', name: 'parent_id', value: this.parent_id, class: 'is-valid'});
        $(this.view).append(this.view.parentIdInput);
		
		col = View.element({tag: 'div', class: 'col-sm-6'});
		$(col).append(this.createSubmitButton())
		$(row).append(col);
		
		col = View.element({tag: 'div', class: 'col-sm-6'});
        this.view.clear = View.element({tag: 'input', type: 'clear', value: 'Clear', class: 'btn btn-block btn-danger mt-2'});
		$(col).append(this.view.clear);
        $(row).append(col);
		
		$(this.view).append(row);

        return this.view;
    }

    this.createMiniInput = function(name, type, text, value = '', full = false, rows = '6')
    {
		colSpan = (full) ? 'col-sm-12' : 'col-sm-6';
		
        formGroup = View.element({tag: 'div', class: colSpan + ' form-group mb-0'});
        label = View.element({tag: 'label', class: 'w-100'});
        $(label).append(View.element({tag: 'small', text: text}));
		inputName = name + 'Input';
		
		if (type != 'textarea')
		{
			this.view[inputName] = View.element({tag: 'input', type: type, name: name, value: value, class: 'form-control form-control-sm'});
		}
		else 
		{
			this.view[inputName] = View.element({tag: type, name: name, text: value, rows: rows, class: 'form-control form-control-sm'});
		}

		$(this.view[inputName]).keyup({
			validation: this.validation,
			rules: validationRules[name],
			valid: this.valid
		}, function(e) {
			if (e.data.validation.checkForField($(e.target).val().trim(), e.data.rules)) 
			{
				$(e.target).addClass('is-valid');
				$(e.target).removeClass('is-invalid');
				$(e.target).next().removeClass('text-danger');
				$(e.target).next().text('');
			}
			else 
			{
				$(e.target).removeClass('is-valid');
				$(e.target).addClass('is-invalid');
				$(e.target).next().addClass('text-danger');
				$(e.target).next().text(e.data.validation.currentError);
			}
        });
        $(label).append(this.view[inputName]);
        $(label).append(View.element({tag: 'small', class: 'small help-block', text: ''}));
		$(formGroup).append(label);
		
		return formGroup;
    }
	
	this.createSubmitButton = function()
	{
        this.view.submit = View.element({tag: 'input', type: 'submit', value: this.submitValue, class: 'btn btn-block btn-success mt-2'});		
		
		return this.view.submit;
	}
	
	this.addSubmitEvent = function(url)
	{
		if (!this.checkIfPassed())
		{
			alert('Cannot submit');
			return false;
		}
		
		$.ajax({
			type: 'POST',
			url: this.url,
			data: $(this.view).serializeArray()
		});
		
		return true;
	}
	
	this.arrayToDictionary = function(arr, initValue)
	{
		newArr = {};
		
		for (i in arr)
		{
			newArr[arr[i]] = initValue;
		}
		
		return newArr;
	}
	
	this.checkIfPassed = function()
	{
		this.passed = true;
		this.valid = true;
		
		for (i in formValues)
		{
			valid = $(this.view[i]).hasClass('is-valid')
			
			if (!valid)
				this.valid = false;
			
			this.passed[formValues[i]] = valid;
		}
		
		return this.valid;
	}

    this.populate = function(data, func = 'extractProperty')
    {
        console.log(data)

        for (i in data)
        {
            this[func](data[i]);
        }
    }

    this.populateForm = function(data)
    {
        this.populate(data, 'extractFormData');
    }

    this.extractProperty = function(data)
    {
        console.log(data)
        // if (this.hasOwnProperty())
    }

    this.extractFormData = function(obj)
    {
        if (formValues.indexOf(obj.name) != -1)
        {
            this.formData[obj.name] = obj.value;
        }
    }

    this.check = function()
    {
        if (this.validation.check(this.formData, validationRules))
            return true;

        this.errors = this.validation.errors;

        return false;
    }

    alert('Form class works');

    this.constructor(data);
}