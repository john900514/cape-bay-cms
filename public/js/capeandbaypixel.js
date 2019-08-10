(function() {
    var api = {}; // use this object to store all of your library functions
    var pixelId = null;
    var clientId = null;
    var data = {}; // use this object to store the data you're collecting and sending

    // if your pixel will be used in multiple places, unique pixel ids will be crucial to
    // identify which piece of data came from which place
    api.init = function(pId) {
        clientId = pId;
    };

    // include a function for each type of data you want to collect and add it to your data object.
    // if we're trying to collect Shippy's name, company, and position, we'll have the following
    // functions which should take in an object with key and value as argument (this will form your
    // query parameters):
    api.preloadStore = function (m) {
        data['club_id'] = m;
        data['preloaded_club'] = true;
        console.log('Applying ClubID');
    };

    api.preloadPromoCode = function (m) {
        data['promocode'] = m;
        data['preloaded_promo'] = true;
        console.log('Applying promo code');
    };

    api.Conversion = function(p) {
        data['Conversion'] = p;
    };

    api.setPixelId = function(p) {
        data['pixel_id'] = p;
    };

    api.lead_id = function(p) {
        data['lead_id'] = p;
    };

    api.pageName = function(pn) {
        data['pageName'] = pn;
        console.log('Cape and Bay...away!');
    };

    api.performNext = function() {
        var command = window.capenpizza.q[0];

        if(command != undefined) {
            var func = command[0];
            var parameters = command[1];
            if (typeof api[func] === 'function') {
                execute();
            }
            else {
                var command = window.capenpizza.q.shift();


            }
        }
        else {

        }
    };

    // include a function to turn all the data you've collected in the data object into query
    // parameters to append to the url for the pixel on your server
    api.toQueryString = function() {
        var s = [];
        Object.keys(data).forEach(function(key) {
            s.push(key + "=" + encodeURIComponent(data[key]));
        });
        return s.join("&");
    };

    // include a function to add the query parameters to your pixel url and to finally append
    // the resulting pixel URL to your document
    api.send = function() {
        var pixel = document.createElement("img");
        pixel.setAttribute("class", "cnbpx");
        pixel.setAttribute("style", "width:1px;");
        var queryParams = api.toQueryString();
        pixel.src = "https://anchor.capeandbay.com/pizza/" + clientId + "?" + queryParams;
        document.body.appendChild(pixel);
    };

    // pull functions off of the global queue and execute them
    var execute = function() {
        // while the global queue is not empty, remove the first element and execute the
        // function with the parameter it provides
        // (assuming that the queued element is a 2 element list of the form
        // [function, parameters])
        var command = window.capenpizza.q[0];
        if(command !== undefined) {
            var func = command[0];
            var parameters = command[1];
            if (typeof api[func] === 'function') {
                api[func].call(window, parameters);
                window.capenpizza.q.shift();

                if(window.capenpizza.q.length == 0) {
                    api.send();
                }
                else {
                    api.performNext();
                }

            } else {
                window.capenpizza.q.shift();
                //console.error("Invalid function specified: " + func);
                if(window.capenpizza.q.length == 0) {
                    api.send();
                }
            }
        }

    };

    execute();
})();
