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

function clearInput(input) {
	// clear a specified input box of it's defualt value when a user clicks the field
    if (input.value.indexOf('..') != -1) {
        // so the script only clears the inputs if they are the default IE hav the whaterver.. format
        // this is a hack job I know but we will improve the JS later
        input.value = ''; 
    }
    else {
        return 0;
    }
}

function setInputVal(name, val) {
	// set the input box to display specified text as it's 'value'
    var input;
	input = document.getElementsByName(name)[0];
	input.value = val;
}

function validateDate(input) {
    // validate a date passed to the function, return 0 or 1 for fail or success respectively
    // I didn't want to do this but PHP is honestly much more robust for this date checking business thanks to
    // strtotime(), seriously that function will save your life
    var req = getXmlHttp();
    var resp;
    req.onreadystatechange = function() {
        if (req.readyState == 4) {
            if (req.status == 200) {
                resp = req.responseText.trim();
                if (resp == 'fail') {
                    alertValidateFail(input);   
                }
                else {
                    input.value = resp;   
                }
            }
        }
    };
    url = "php/helpers.php?do=validate_date&date=" + input.value;
    req.open('GET', url, true);
    req.send(null);
}

function getSuggest(type, input, e) {
    // some internal functions
    var div;
    this.getSuggestSpans = function() {
        // get the spans if any and return them
        var spans;
        div = document.getElementById('suggest_div');
        spans = div.getElementsByTagName('span');
        return spans;
    };
    this.setHighlight = function(spans, lit, change) {
        var pos;
        if (lit == undefined) {
            // we know it's the first highlight    
            if (change == -1) {
                spans[spans.length - 1].className = 'highlight';
            }
            else if (change == 1) {
                spans[0].className = 'highlight';
            }
        }
        else {
            spans[lit].className = '';
            if (lit == 0 && change == -1) {
                pos = (spans.length - 1);
            }
            else if (lit == (spans.length - 1) && change == 1) {
                pos = 0;   
            }
            else {
                pos = lit + change; 
            }
            spans[pos].className = 'highlight';
        }
    };
    if (document.getElementById('suggest_div') && e != undefined) {
        // if there is a suggest div we should check for arrow key presses
        // arrow keys are like this, left:37, up:38, right:39, down:40, we are only worried about 38 and 40, plus enter(13)
        var lit, spans;
        spans = this.getSuggestSpans();
        for (var i = 0; i < spans.length; i++) {
            if (spans[i].className == 'highlight') {
                lit = i;
            }
        }
        switch (e.keyCode) {
            // determine how the box highlighting will shift
            case 38:
                // the user has presssed the up arrow
                this.setHighlight(spans, lit, -1);
                break;
                
            case 40:
                // the user has pressed down
                this.setHighlight(spans, lit, 1);
                break;
                
            case 13:
                // the user has pressed enter
                input.value = spans[lit].innerHTML;
                var div = document.getElementById('suggest_div');
                div.parentNode.removeChild(div);
                break;
        }
        return;
    } // end of arrow handling
    // on to the function
    if (input.value.length < 3) {
         // too early for auto suggest yet
        return;
    }
    var req;
    var suggestion = [];
    req = getXmlHttp();
    req.onreadystatechange = function() {
        if (req.readyState == 4) {
            if (req.status == 200) {
                suggestion = parseResponse(type, req.responseText.trim());
                if (suggestion.length != 0) {
                    // we need to make a little div they can key through and select values
                    try {
                        div = document.getElementById('suggest_div');   
                        div.innerHTML = "";
                    }
                    catch(e) {
                        div = document.createElement('div');
                        div.id = "suggest_div";
                    }
                    // build the html
                    var i;
                   // console.log(suggestion);
                    for (i = 0; i < suggestion.length; i++) {
                        var span;
                        span = document.createElement('span');
                        span.innerHTML = suggestion[i];
                        span.onclick = function() {
                            input.value = this.innerHTML;
                            this.parentNode.parentNode.removeChild(this.parentNode);
                        };
                        div.appendChild(span);
                    }
                    input.parentElement.appendChild(div);
                }
            }
        }
    };
    var url;
    switch(type) {
        case 'company_name':
            url = "php/get_companies.php?q=" + input.value;
            break;
        // on to the tag filtering case 
        case 'type_filter':
            // this is really just get by tag
            url = "php/get_tags.php?q=" + input.value;
            break; 
    }
    req.open('GET', url, true);
    req.send(null);    
}

// seperate these into seperate modules, functions.js is getting too crowded

function submitDeal() {
    // so the time is here, let's add this thing
    // first we need to get all of the elements we will need
    title = document.getElementsByName('title')[0].value;
    company = document.getElementsByName('company')[1].value;
    price = document.getElementsByName('price')[0].value;
    start_date = document.getElementsByName('start_date')[0].value;
    end_date = document.getElementsByName('end_date')[0].value;
    deal_text = document.getElementsByName('deal_text')[0].value;
    address = document.getElementsByName('address')[0].value;
    tags = document.getElementsByName('tags')[0].value;
    // now we need to get ready to submit a post request to the handler script
    req = getXmlHttp();
    req.onreadystatechange = function() {
        if (req.readyState == 4) {   
            if (req.status == 200) {
                console.log(req.responseText.trim());   
            }
        }
    };
    url = "php/add_deal.php";
    params = "deal_title="+title+"&company="+company+"+&price="+price+"&start_date="+start_date+"&end_date="+end_date+"&deal_text="+deal_text+"&address="+address+"&tags="+tags;
    req.open('POST', url, true);
    req.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    req.send(params);
    console.log('Tried to add deal..');
}

function alertValidateFail(input) {
    // we need to alert of failed validation to the user
    input.setAttribute('style', 'border: 1px solid red;');
    input.onfocus = function() {
        input.setAttribute('style', 'border: 1px solid #284F31;');   
        input.setAttribute('error', '0');
    };
    if (input.name == 'deal_text') {
        // we will want to set the inner html to be reflective of error as well
        input.innerHTML = "Please enter a simple description of this offer";
    }
    // finally we will set the error property to true so the form can't be submitted as is
    input.setAttribute('error', '1');
}

function parseResponse(type, resp) {
    // parse the response from a PHP autosuggest script
    //var resp_parts = new Array();
    var suggestion;
    var companies = [];
    switch(type) {
        // suggest a company name to the script
        case 'company_name':
            var names = [];
            try {
                companies = resp.split(';');
                for (var i = 0; i < companies.length; i++) {
                    company_parts = companies[i].split(',');
                    if (company_parts[1] != undefined) {
                        names.push(company_parts[1]);
                    }
                }
                suggestion = names;
            }
            catch(e) {
                suggestion = 0;   
            }
            break;   
        // next type suggest case
        case 'type_filter':
            var tags = [];
            var tag_names = [];
            try {
                tags = resp.split(';');
                for (i = 0; i < tags.length; i++) {
                    if (tags[i] != "" && tags[i] != undefined) {
                        tag_names.push(tags[i].split(',')[1]);
                    }
                }
                suggestion = tag_names;
            }
            catch(e) {
                console.log(e);
                suggestion = 0;
            }
            break;
    }
    // return our suggestion
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
    input.setAttribute('onFocus', 'clearInput(this)');
    // style overrides
    switch (input.name) {
        case 'title':
            input.setAttribute('style', 'margin-top: -20px;');
            break;
    }
    // go to the various blur, focus, keyup etc functions
    input.onkeyup = 
    function() {
        // this is where we will run error checking and other functions on the fields
        // so we need to know what input we are dealing with to handle it properly
        switch (input.name) {
            // we handle error checking different by field obviously
            case 'company':
                getSuggest('company_name', input, event);
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
    // we will set onclicks
    input.onclick = function() {
        switch(input.name) {
            case 'password':
                input.type = "password";
                break;
        }
        // end of onclick functions switch
    };
    // now we go to onblur error checking functions
    input.onblur = function() {
        //console.log('onblur hit for: ' + input.name);
        switch(input.name) {
            // handle the onblur functions for the fields based on name   
            case 'price':
                // we will see if they entered a valid price
                try {
                    regex = /([\d]+)\.([\d]{2})/;
                    m = regex.exec(input.value);
                    // m will be an array like (2.25),(2),(25)
                    price_value = m[0];
                }
                catch(e) {
                    // probably the user provided a whole number
                    regex = /([\d]+)/;;
                    m = regex.exec(input.value);
                    // m will be an array like (2),(2)
                    if (m) {
                        price_value = m[0];
                        input.value = price_value + ".00";
                    }
                    else {
                        // this means the price did not pass validation, let the user know
                        alertValidateFail(input);
                    }
                }
                break;
            
            // start_date onblur stuff
            case 'start_date':
                if (input.value.toLowerCase().indexOf('any') != -1) {
                    input.value = "any";
                }
                else if (input.value == 'Start date..') {
                    // the user never attempted to change the date
                    input.value = "any";
                }
                else if (input.value.toLowerCase().indexOf('now') != -1) {
                    input.value = "any";   
                }
                else if (input.value.trim() == '') {
                    // they blanked it out
                    input.value = "any";
                }
                // now the case where eval dates
                else {
                    // this means we should attempt to parse and verify this date
                    validateDate(input);
                }
                    
                // end of start_date onblur functions
                break;
            
            // end_date onblur stuff 
            case 'end_date':
                // we will make an array with words that will trigger replacement to 'indefinite'
                // should be much cleaner than the above method used for start_date
                replace = ['never','indefinite','none','n/a','end date..','always'];
                for (var i = 0; i < replace.length; i++) {
                    if (input.value.toLowerCase().indexOf(replace[i]) != -1) {
                        // replace the users input with 'indefinite'
                        input.value = 'indefinite';
                        // we will terminate the case here since we obviously won't need to validate this as a date
                        break;
                    }
                    else if (input.value.trim() == '') {
                        input.value = 'indefinite';
                        break;
                    }
                    else {
                        if (input.value == 'indefinite') {
                            break;
                            // not sure why we need this, the flow should prevent validation of this
                            // field for invalid but whatever
                        }
                        validateDate(input);   
                    }
                }
                // if we are still running after the loop then we will(attempt to) validate this
                break;
            
            // deal about onbur stuff
            case 'deal_text':
                // same as above just check out the user input
                replace = ['about deal..'];
                for (var i = 0; i < replace.length; i++) {
                    if (input.value.toLowerCase().indexOf(replace[i]) != -1) {
                        // this box we don't really replace, we demand a deal about XD
                        alertValidateFail(input);
                        break;
                    }
                    else if (input.value.trim() == '') {
                        // same thing, not acceptable input
                        alertValidateFail(input);
                    }
                } 
        }
    };
    // end of onblur functions
    // onload functions for inputs
    input.onload = function() {
        console.log('onload for: ' + input.name);
        switch (input.name) {
            case 'tags':
                // we need to check to see if there is an auto add tag in the url
                url = document.location;
                if (url.indexOf('?tag') != -1) {
                    // we can auto add a tag in the field for the user
                    tag_pos = url.indexOf('?tag');
                    tag = url.substr(tag_post + 5);
                    input.value = tag + ", ";
                }
                break;
        }
        // end of onload switch statement for inputs
    };
    // end of the onload input functions
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
    // create the little exit button
    exit = document.createElement('span');
    exit.setAttribute('class','exit_button');
    exit.innerHTML = "X";
    exit.onclick = function(){document.body.removeChild(dim);document.body.removeChild(div);};
    // we need to build the inputs
    title = addDealInput('title', 'text', 'Deal Title..');
    company = addDealInput('company', 'text', 'Company..');
    price  = addDealInput('price', 'text', 'Price..'); // we'll populate options later
    start_date = addDealInput('start_date', 'text', 'Start date..');
    // figure out how we want to set this in the layout
    end_date = addDealInput('end_date', 'text', 'End date..');
    deal_text = addDealInput('deal_text', 'textarea', 'About deal..');
    address = addDealInput('address', 'text', 'Address..');
    tags = addDealInput('tags', 'text', 'Tags..');
    url = document.location.href;
    if (url.indexOf('?tag') != -1) { // see if we have a tag defined for the default
        // we can auto add a tag in the field for the user
        tag_pos = url.indexOf('?tag');
        tag = url.substr(tag_pos + 5);
        tags.value = tag + ", ";
    }
    submit = document.createElement('input');
    submit.type = 'button';
    submit.value = "add deal";
    submit.name = "submit";
    submit.setAttribute('style', 'width:15%;min-width:75px;padding:0;height:30px;margin-bottom:0;margin-left:42.5%;');
    submit.setAttribute('onClick', "submitDeal()");
    // let's try adding these to the div
    div.appendChild(exit);
    div.appendChild(title);
    div.appendChild(company);
    div.appendChild(price);
    div.appendChild(start_date);
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
