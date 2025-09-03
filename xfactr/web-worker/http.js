/**
 * Httprequest
 */
onmessage = function (event) {
    // Get event data
    const eventData = JSON.parse(event.data);

    // Configure url with query params
    let url;
    if (eventData.queryparams) {
        const params = object.keys(eventData.queryparams).map(key => key + '=' + eventData.queryparams[key])
        url = eventData.url + '?' + params;
    } else {
        url = eventData.url
    }

    // Set XML Http request
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function () {
        if (xhttp.readyState == 4) {

            // Form data to webworker
            let msg = {
                status: 'SUCCESS',
                contType: xhttp.getResponseHeader('Content-Type'),
                data: xhttp.response
            };

            // If Failure,
            if (xhttp.status != 200) {
                msg.status = 'FAILURE'
            }

            // Respond to webworker
            postMessage(JSON.stringify(msg));
        }
    };

    // Send request
    xhttp.open(eventData.method, url, true);

    // Set Headers
    if (eventData.headers.length > 0) {
        eventData.headers.forEach(element => {
            xhttp.setRequestHeader(element.key, element.value)
        })
    }

    // Send
    if (eventData.method === 'GET') {
        xhttp.send();
    } else {
        if (eventData.body) {
            xhttp.send(eventData.body);
        } else {
            xhttp.send();
        }
    }
}
