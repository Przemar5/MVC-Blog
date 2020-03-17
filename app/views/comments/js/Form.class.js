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
                'regex': {'args': ['[0-9a-zA-Z\ \@\+\/\?\!\$\_\-]+'], 'msg': 'Username contains illegal characters.'},
            },
            'email': {
                'required': {'msg': 'Email address is required.'},
                'min': {'args': [6], 'msg': 'Email address must be equal or longer than 6 characters.'},
                'max': {'args': [150], 'msg': 'Email address cannot be longer than 150 characters.'},
                'regex': {'args': ['[0-9a-zA-Z\ \@\+\/\?\!\$\_\-\.\,]+'], 'msg': 'Email address contains illegal characters.'},
            },
            'message': {
                'required': {'msg': 'Comment body is required.'},
                'min': {'args': [6], 'msg': 'Email address must be equal or longer than 6 characters.'},
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
    this.formData = {};
    this.errors = new Array();
    this.view = new Array();

    this.constructor = function(data)
    {
        this.validation = new Validator();
    }

    this.createForm = function(post_id, parent_id, id, submitValue, token)
    {
        this.post_id = post_id;
        this.parent_id = parent_id;
        this.id = id;
        this.submitValue = submitValue;
        this.token = token;

        this.view = View.element({tag: 'form', method: 'post'});

        row = View.element({tag: 'div', class: 'row my-0'});

        formGroup = View.element({tag: 'div', class: 'col-sm-6 form-group mb-0'});
        label = View.element({tag: 'label', class: 'w-100'});
        $(label).append(View.element({tag: 'small', text: 'Username'}));
        this.view.usernameInput = View.element({tag: 'input', type: 'text', name: 'username', value: '', class: 'form-control form-control-sm'});
        $(this.view.usernameInput).keyup(function(e) {
            // $(e.target).alert('works!')
        });
        $(label).append(this.view.usernameInput);
        $(label).append(View.element({tag: 'small', class: 'small help-block', text: 'Some text.'}));
        $(formGroup).append(label);
        $(row).append(formGroup);

        formGroup = View.element({tag: 'div', class: 'col-sm-6 form-group mb-0'});
        label = View.element({tag: 'label', class: 'w-100'});
        $(label).append(View.element({tag: 'small', text: 'Email'}));
        this.view.emailInput = View.element({tag: 'input', type: 'email', name: 'email', value: '', class: 'form-control form-control-sm'});
        $(label).append(this.view.emailInput);
        $(label).append(View.element({tag: 'small', class: 'small help-block', text: 'Some text.'}));
        $(formGroup).append(label);
        $(row).append(formGroup);

        formGroup = View.element({tag: 'div', class: 'col-sm-12 form-group mt-0'});
        label = View.element({tag: 'label', class: 'w-100'});
        $(label).append(View.element({tag: 'small', text: 'Comment'}));
        this.view.messageInput = View.element({tag: 'textarea', name: 'message', text: '', rows: '6', class: 'form-control form-control-sm'});
        $(label).append(this.view.messageInput);
        $(label).append(View.element({tag: 'small', class: 'small help-block', text: 'Some text.'}));
        $(formGroup).append(label);
        $(row).append(formGroup);

        $(this.view).append(row);

        this.view.idInput = View.element({tag: 'input', type: 'hidden', name: 'id', value: this.id});
        $(this.view).append(this.view.idInput);
        this.view.postIdInput = View.element({tag: 'input', type: 'hidden', name: 'post_id', value: this.post_id});
        $(this.view).append(this.view.postIdInput);
        this.view.parentIdInput = View.element({tag: 'input', type: 'hidden', name: 'id', value: this.parent_id});
        $(this.view).append(this.view.parentIdInput);
        this.view.submit = View.element({tag: 'input', type: 'submit', value: this.submitValue, class: 'btn btn-block btn-primary'});
        $(this.view).append(this.view.submit);
        this.view.clear = View.element({tag: 'input', type: 'clear', value: 'Clear', class: 'btn btn-block btn-danger'});
        $(this.view).append(this.view.clear);
        // $(this.view).insert(this.createMiniInput('username', 'text', 'Username'));

        console.log(this.view)

        return this.view;
    }

    this.createMiniInput = function(name, type, text)
    {
        // this.view[name] = View.element({tag: 'input', type: type, value: ''})
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