function dealSuggest() {
    // AJAX deal suggest
    // first we need to determine the type of deals we are going to bring back
    var company, location, price, type, req, resp, deal_objects, deals;
    this.createDealHtml = function(deal_objects) {
        // make the HTML code for the deals 
        var deals = [];
        for (var i = 0; i < deal_objects.length; i++) {
            // iterate the deal objects and build some deals
            // model the way it is built of deal.html
            var container, title, title_link, info_container, byline;
            // first the container
            container = document.createElement('div');
            container.setAttribute('class', 'deal');
            // the title
            title = document.createElement('h1');
            title_link = document.createElement('a');
            title_link.href = "/deal_site/?deal_id=" + deal_objects[i].deal_id;
            title_link.innerHTML = deal_objects[i].deal_title;
            title.appendChild(title_link);
            // now we go to the deal_info div
            info_container = document.createElement('div');
            info_container.setAttribute('class', 'deal_info');
            byline = document.createElement('span');
            byline.setAttribute('class', 'byline');
            byline.innerHTML = deal_objects[i].deal_poster_id + " on " + deal_objects[i].deal_post_date;
            info_container.appendChild(byline);
            
            // append to the container
            container.appendChild(title);
            container.appendChild(info_container);
            console.log(container);
        }
        // now return the deals as HTML objects
        return deals;
    };
    req = getXmlHttp();
    try {
        company = document.getElementsByName('company')[0].value;
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
        req.onreadystatechange = function() {
            if (req.readyState == 4 && req.status == 200) {
                // we're good to parse results   
                resp = req.responseText.trim();
                deal_objects = JSON.parse(resp);
                deals = createDealHtml(deal_objects);
            }
        };
        req.open('GET', 'php/deal_suggest.php?company='+company+'&location=&price=&type=', true);
        req.send(null);
    }


}






