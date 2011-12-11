if(typeof(String.prototype.trim) === "undefined")
{
    String.prototype.trim = function() 
    {
        return String(this).replace(/^\s+|\s+$/g, '');
    };
}

function getXmlHttp() {
    if (window.XMLHttpRequest) {
        // for most sane browsers
        try {
            req = new XMLHttpRequest();   
        } 
        catch(e) {
            req = false; // we didn't get the connection
        }
    }
    else if (window.ActiveXObject) {
        try {
            req = new ActiveXObject("Microsoft.XMLHTTP");
        }
        catch(e) {
            req = false;
        }
    }
    // we should have a xmlhttp object by now hopefully, return it
    return req;
}

function clearInput(name) {
	// clear a specified input box of it's defualt value when a user clicks the field
    try {
        var input;
	    input = document.getElementsByName(name)[0];
	    input.value = ''; // blank out the input value
    }
    catch(e) {
        name.value = '';  
    }
}

function setInputVal(name, val) {
	// set the input box to display specified text as it's 'value'
    var input;
	input = document.getElementsByName(name)[0];
	input.value = val;
}

function addDealInput(name, type, val) {
    var input;
    if (type == 'text') {
        input = document.createElement('input');
    }
    else if (type == 'textarea') {
        input = document.createElement('textarea');   
    }
    else if (type == 'select') {
        input = document.createElement('select');   
    }
    else if (type == 'button') {
        input = document.createElement('button');   
    }
    input.name = name;
    input.type = type;
    if (type == 'textarea' || type == 'button') {
        input.innerHTML = val;   
    }
    else {
        input.value = val;
    }
    input.setAttribute('onClick', 'clearInput(this)');
    return input;
}

function displayAddDeal() {
    // display the add deal box with the various inputs etc
    // we will do the whole web 2.0ish background fade same window popup deal nonsense 
    // kill the lights
    dim = document.createElement('div');
    dim.id = "dim_page";
    dim.innerHTML = "&nbsp;";
    dim.onclick = function(){document.body.removeChild(dim);document.body.removeChild(div);};
    document.body.appendChild(dim);
    // create the markup
    div = document.createElement('div');
    div.id = "add_deal_box";
    // we need to build the inputs
    title = addDealInput('title', 'text', 'Deal Title..');
    company = addDealInput('company', 'text', 'Company..');
    price  = addDealInput('price', 'select'); // we'll populate options later
    // figure out how we want to set this in the layout
    end_date = addDealInput('end_date', 'text', 'End Date..');
    deal_text = addDealInput('deal_text', 'textarea', 'About Deal..');
    address = addDealInput('address', 'text', 'Address..');
    submit = addDealInput('submit', 'button', 'Add Deal');
    // let's try adding these to the div
    div.appendChild(title);
    div.appendChild(company);
    div.appendChild(price);
    div.appendChild(end_date);
    div.appendChild(deal_text);
    div.appendChild(address);
    div.appendChild(submit);
    // once we're all done add the box to the page
    document.body.appendChild(div);
}

function dealFadeIn() {
    // use ajax to update the page with the deal with no refresh and submit deal to DB   
}
