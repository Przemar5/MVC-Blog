View = {
    element: function(data)
    {
        elem = document.createElement(data.tag);
        elem.innerHTML = (data.text != undefined) ? data.text : '';

        data.tag = null;
        data.text = null;

        for (key in data)
        {
            if (key != 'tag' || key != 'text')
                $(elem).attr(key, data[key]);
        }

        return elem;
    },
}

// console.log(View.element({tag: 'a', href: 'test', text: 'generated link', class: 'text text-center'}));