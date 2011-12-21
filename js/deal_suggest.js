function dealSuggest() {
    // AJAX deal suggest
    // first we need to determine the type of deals we are going to bring back
    var company, location, price, type, req, resp;
    try {
        company = document.getElementsByName('company')[0].valie;
    }
    catch(e) {
        company = undefined;   
    }
    try {
        location = document.getElementsByName('location')[0].value;   
    }
    catch(e) {
        location = undefined;   
    }
    try {
        price = document.getElementsByName('price')[0].value;   
    }
    catch(e) {
        price = undefined;
    }
    try {
        type = document.getElementsByName('type')[0].value;
    }
    catch(e) {
        type = undefined;   
    }
    // now we go through each one and see what ones were defined
    if (company) {
        // we will do a company suggest for the deals  
        req = getXmlHttp();
        req.onreadystatechange = function() {
            if (req.readyState == 4 && req.status == 200) {
                // we're good to parse results   
                resp = req.responseText.trim();
            }
        };
        req.open('GET', 'php/deal_suggest.php?company='+company+'&location=&price=&type=', true);
        req.send(null);
    }


}






