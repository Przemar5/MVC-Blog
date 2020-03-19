function Validator()
{
    this.fields = new Array();
    this.data = new Array();
    this.rules = new Array();
    this.errors = new Array();
    this.currentField = null;
	this.currentError = null;
    this.passed = null;

    this.check = function(data, rules)
    {
        this.fields = Object.keys(data);
        this.data = data;
        this.rules = rules;
        this.errors = [];

        this.passed = true;

        for (i in this.data)
        {
            this.data[i] = this.data[i].trim();
            this.currentField = i;

            if (!this.checkForField(this.data[i], rules[i]))
                this.passed = false;
        }

        return this.passed;
    }

    this.checkForField = function(value, rules)
    {
        for (rule in rules)
        {
            if (this.hasOwnProperty(rule))
            {
                arg1 = null;
                arg2 = null;

                if (rules[rule].args != undefined && rules[rule].args != null)
                {
                    arg1 = rules[rule].args[0];
                    arg2 = (rules[rule].args[1] != undefined && rules[rule].args[1] != null)
                        ? rules[rule].args[1] : null;
                }

                if (!this[rule](value, arg1, arg2))
                {
					this.currentError = rules[rule].msg;
                    this.errors[this.currentField] = rules[rule].msg;

                    return false;
                }
            }
        }

        return true;
    }

    this.required = function(value)
    {
        return value != undefined && value != null && value != '';
    }

    this.min = function(value, min)
    {	
        if ((typeof value == 'string' || value instanceof String) && (min = parseInt(min)) != 'NaN')
        {
            return value.length >= min;
        }

        return false;
    }

    this.max = function(value, max)
    {
        if ((typeof value == 'string' || value instanceof String) && (max = parseInt(max)) != 'NaN')
        {
            return value.length <= max;
        }

        return false;
    }

    this.between = function(value, min, max)
    {
        if ((typeof value == 'string' || value instanceof String) && (min = parseInt(min)) != 'NaN' && (max = parseInt(max))  != 'NaN')
        {
            return value.length >= min && value.length <= max;
        }

        return false;
    }

    this.numeric = function(value)
    {
        //return parseFloat(value) != 'NaN';
    }

    this.regex = function(value, regex)
    {
        if ((typeof value == 'string' || value instanceof String) &&
            (typeof regex == 'string' || regex instanceof String))
        {
            regex = new RegExp('^' + this.addSlashes(regex) + '$');

            return regex.test(value);
        }

        return false;
    }
	
	this.addSlashes = function(string)
	{
		return string;
	}
}