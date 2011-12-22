function dealSuggest() {
    // AJAX deal suggest
    // first we need to determine the type of deals we are going to bring back
    var deals, footers = [];
    var company, location, price, type, req, resp, deal_objects;
    this.createDealHtml = function(deal_objects) {
        // make the HTML code for the deals 
        for (var i = 0; i < deal_objects.length; i++) {
            // iterate the deal objects and build some deals
            // model the way it is built of deal.html
            var deals = [];
            var container, title, title_link, info_container, byline, info_table, tr, price, end_info, text, footer, tags;
            // first the container
            container = document.createElement('div');
            container.setAttribute('class', 'deal');
            // the title
            title = document.createElement('h1');
            title.setAttribute('class', 'deal_title');
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
            // build up the little deal info table
            info_table = document.createElement('table');
            info_table.border = 0;
            tr = document.createElement('tr');
            price = document.createElement('td');
            end_info = document.createElement('td');
            price.innerHTML = "$" + deal_objects[i].deal_price;
            end_info.innerHTML = "Ends: " + deal_objects[i].deal_end_date;
            tr.appendChild(price);
            tr.appendChild(end_info);
            info_table.appendChild(tr);
            info_container.appendChild(info_table);
            // make the deal text container
            text = document.createElement('div');
            text.innerHTML = deal_objects[i].deal_text;
            console.log(text.innerHTML);
            // append to the container
            container.appendChild(title);
            container.appendChild(info_container);
            container.appendChild(text);
            // that should be all we need for the deal object
            // now we need to append the deal
            deals.push(container);
        } // end of deal object iteration
        // now return the deals as HTML objects
        return deals;
    };
    
    this.createDealFooters = function(deal_objects) {
        var footers = [];
        for (var i = 0; i < deal_objects.length; i++) {
            var footer, tags;
            footer = document.createElement('div');
            footer.setAttribute('class', 'deal_footer');
            tags = deal_objects[i].tags.split(',');
            for (var ctrl = 0; ctrl < tags.length; ctrl++) {
                var a;
                a = document.createElement('a');
                a.href = "/deal_site/?tag=" + tags[ctrl];
                a.innerHTML = tags[ctrl];
                footer.appendChild(a);
                footers.push(footer);
            }
        }
        return footers;
    };
    
    this.displayDeals = function(deals, footers) {
        var div = document.getElementById('deals_container');
        div.innerHTML = ""; // blank it out
        console.log(deals);
        console.log(footers);
        for (var i = 0; i < deals.length; i++) {
            div.appendChild(deals[i]);
            div.appendChild(footers[i]);
        }
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
                footers = createDealFooters(deal_objects);
                displayDeals(deals, footers);
                // deals now contains a bunch of HTML objects for the page
            }
        };
        req.open('GET', 'php/deal_suggest.php?company='+company+'&location=&price=&type=', true);
        req.send(null);
    }

}// end of dealSuggest()






