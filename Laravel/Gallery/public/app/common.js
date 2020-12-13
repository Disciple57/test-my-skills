/**
 * Fires when the initial HTML document has been completely loaded
 *
 * @param fn function
 */
function domReady(fn) {
    // If we're early to the party
    document.addEventListener("DOMContentLoaded", fn);
    // If late; I mean on time.
    if (document.readyState === "interactive" || document.readyState === "complete") {
        fn();
    }
}

domReady(function () {
    let csrf_token = document.querySelector('meta[name="csrf-token"]')['content'],
        buttons = {
            modal_hide: document.querySelector('.btn-close'),
            upload: document.querySelector('#upload'),
        },
        template = document.querySelector('#list'),
        list_parent = document.querySelector('#list-parent');

    /**
     * Inspected url
     *
     * @param url string
     * @return string
     */
    function urlSanitize(url) {
        return url.replace(/(^|[^:])[/]{2,}/, '$1/', url)
    }

    /**
     * Simulate a click event.
     *
     * @public
     * @param {Element} elem  the element to simulate a click on
     */
    function simulateClick(elem) {
        let evt = new MouseEvent('click', {
            bubbles: true,
            cancelable: true,
            view: window
        });
        elem.dispatchEvent(evt);
    }

    /**
     * Ajax request
     *
     * @param method string
     * @param data object/null
     * @param url string
     */
    function apiRequest(method, data, url) {

        // Prevent action from launching during request
        document.removeEventListener('click', action, false);

        let xhr = new XMLHttpRequest();

        switch (method) {
            case 'GET':
                xhr.addEventListener("load", function (evt) {
                    render(JSON.parse(evt.target.response));
                    document.addEventListener('click', action, false);
                }, false);
                break;

            default:
                xhr.addEventListener("load", function () {
                    simulateClick(buttons.modal_hide);
                    clearForm('#form');
                    apiRequest('GET', null, apiURL);
                }, false);
        }

        xhr.open(method, urlSanitize(url));
        xhr.setRequestHeader("X-Requested-With", "XMLHttpRequest");
        xhr.setRequestHeader("X-CSRF-TOKEN", csrf_token);
        xhr.send(data);
    }

    /**
     * Get form data
     *
     * @param parent string
     * @returns {FormData}
     */
    function getForm(parent) {
        let elem_parent = document.querySelector(parent),
            elem_inputs = elem_parent.querySelectorAll('input[type="file"]'),
            form = new FormData();
        if (elem_inputs) {
            Array.prototype.forEach.call(elem_inputs, function (tag) {
                Array.prototype.forEach.call(tag.files, function (file) {
                    form.append(tag.name, file);
                });
            });
        }
        return form;
    }

    /**
     * Clear inputs value
     *
     * @param parent string
     */
    function clearForm(parent) {
        let elem_parent = document.querySelector(parent),
            elem_inputs = elem_parent.querySelectorAll('input');

        if (elem_inputs) {
            Array.prototype.forEach.call(elem_inputs, function (tag) {
                tag.value = '';
            });
        }
        return form;
    }

    /**
     * Rendering data from request
     *
     * @param data
     */
    function render(data) {
        list_parent.innerHTML = '';
        data.object.forEach(function (item) {
            let list = template.content.cloneNode(true),
                file = item.name + '.' + item.extension,
                elem = list.querySelectorAll('[data-id]');

            Array.prototype.forEach.call(elem, function (tag) {
                tag.setAttribute('data-id', item.id);
            });

            list.querySelector('[data-item="name"]').innerText = file;
            list.querySelector('.fill').setAttribute('src', storageDIR + '/' + file + '?' + item.updated_at);
            list.querySelector('[data-item="info"]').innerText = 'Size: ' + (item.info.size / 1024).toFixed(1) + ' kb';

            if (typeof item.info.dimension !== 'undefined') {
                list.querySelector('[data-item="info"]').innerText += ' Dimension: ' + item.info.dimension + ' px';
            }

            list_parent.appendChild(list);
        });
    }

    /**
     * Start action
     *
     * @param event
     */
    function action(event) {
        let el = event.target;

        if (el.matches('.modal-show')) {
            let action = el.getAttribute('data-button'),
                data_id = el.getAttribute('data-id');
            buttons.upload.setAttribute('data-action', action);

            if (data_id) {
                buttons.upload.setAttribute('data-id', data_id);
            }
        }

        if (el.matches('[data-action="store"]')) {
            let data = getForm('#form');
            apiRequest('POST', data, apiURL);
        }

        if (el.matches('[data-action="update"]')) {
            let data = getForm('#form'),
                data_id = el.getAttribute('data-id');

            data.append('_method', 'PUT');
            apiRequest('POST', data, urlSanitize(apiURL + '/' + data_id));
        }

        if (el.matches('[data-action="destroy"]')) {
            let data_id = el.getAttribute('data-id');
            apiRequest('DELETE', null, urlSanitize(apiURL + '/' + data_id));
        }
    }

    document.addEventListener('click', action, false);
    apiRequest('GET', null, apiURL);
})