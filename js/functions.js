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

function submitDeal() {
    // so the time is here, let's add this thing
    // first we need to get all of the elements we will need
    title = document.getElementsByName('title')[0];
    company = document.getElementsByName('company')[0];
    price = document.getElementsByName('price')[0];
    end_date = document.getElementsByName('end_date')[0];
    deal_text = document.getElementsByName('deal_text')[0];
    address = document.getElementsByName('address')[0];
    tags = document.getElementsByName('tags')[0];
    // now we need to get ready to submit a post request to the handler script
    req = getXmlHttp();
    req.onreadystatechange = function() {
        if (req.readyState == 4) {
            if (req.status == 200) {
                alert(res.responseText.trim());   
            }
        }
    };
    url = "php/add_deal.php";
    params = "";
    req.open('POST', url, true);
    req.send(params);
    
}

function alertValidateFail(input) {
    // we need to alert of failed validation to the user
    input.setAttribute('style', 'border: 1px solid red;');
    input.onclick = function() {
        input.setAttribute('style', 'border: 1px solid #284F31;');   
    };
}

function parseResponse(type, resp) {
    // parse the response from a PHP autosuggest script
    var resp_parts = new Array();
    var suggestion;
    switch(type) {
        // suggest a company name to the script
        case 'company_name':
            try {
                resp_parts = resp.split(',');
                // all we really care about it name so set it and return
                suggestion = resp_parts[1];
            }
            catch(e) {
                suggestion = 0;   
            }
            break;
        
    }
    // return out suggestion
    return suggestion;
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
        if (type == 'button') {
            //input.style="width:75px;";
        }
    }
    else {
        input.value = val;
    }
    input.setAttribute('onClick', 'clearInput(this)');
    input.onkeyup = 
    function() {
        // this is where we will run error checking and other functions on the fields
        // so we need to know what input we are dealing with to handle it properly
        switch (input.name) {
            // we handle error checking different by field obviously
            case 'company':
                // company needs to have auto suggest of other companies in DB
                if (input.value.length < 3) {
                    // too early for auto suggest yet
                    return;
                }
                req = getXmlHttp();
                req.onreadystatechange = function() {
                    if (req.readyState == 4) {
                        if (req.status == 200) {
                            var suggestion;
                            suggestion = parseResponse('company_name', req.responseText.trim());
                            if (suggestion != 0) {
                                input.value = suggestion;   
                            }
                        }
                    }
                };
                req.open('GET', 'php/get_companies.php?q=' + input.value, true);
                req.send(null);
                break;
            
            // this will handle onkeyup for price
            case 'price':
                break;
                
            // handle the tag suggest feauture, oh god
            case 'tags':
                break;                
        }
    };
    // end of onkeyup functions
    // now we go to onblur error checking functions
    input.onblur = function() {
        switch(input.name){
            // handle the onblur functions for the fields based on name   
            case 'price':
                // we will see if they entered a valid price
                try {
                    regex = /([\d]+)\.([\d]{2})/;
                    m = regex.exec(input.value);
                    // m will be an array like (2.25),(2),(25)
                    price = m[0];
                }
                catch(e) {
                    // probably the user provided a whole number
                    regex = /([\d]+)/;;
                    m = regex.exec(input.value);
                    // m will be an array like (2),(2)
                    if (m) {
                        price = m[0];
                        input.value = price + ".00";
                    }
                    else {
                        // this means the price did not pass validation, let the user know
                        alertValidateFail(input);
                    }
                }
                break;
        }
    };
    // end of onblur functions
    // return the input element to the function
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
    price  = addDealInput('price', 'text', 'Price..'); // we'll populate options later
    // figure out how we want to set this in the layout
    end_date = addDealInput('end_date', 'text', 'End date..');
    deal_text = addDealInput('deal_text', 'textarea', 'About deal..');
    address = addDealInput('address', 'text', 'Address..');
    tags = addDealInput('tags', 'text', 'Tags..');
    submit = document.createElement('input');
    submit.type = 'button';
    submit.value = "add deal";
    submit.setAttribute('style', 'width:75px;padding:0;height:30px;margin-bottom:0;');
    submit.setAttribute('onClick', "submitDeal()");
    // let's try adding these to the div
    div.appendChild(title);
    div.appendChild(company);
    div.appendChild(price);
    div.appendChild(end_date);
    div.appendChild(deal_text);
    div.appendChild(address);
    div.appendChild(tags);
    div.appendChild(submit);
    // once we're all done add the box to the page
    document.body.appendChild(div);
}

function dealFadeIn() {
    // use ajax to update the page with the deal with no refresh and submit deal to DB   
}
