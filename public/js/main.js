function wcqib_refresh_quantity_increments() {
    jQuery("div.quantity:not(.buttons_added), td.quantity:not(.buttons_added)").each(function (a, b) {
        var c = jQuery(b);
        c.addClass("buttons_added"),
            c.children().first().before('<input type="button" value="-" class="minus" />'),
            c.children().last().after('<input type="button" value="+" class="plus" />')
    })
}

String.prototype.getDecimals || (String.prototype.getDecimals = function () {
    var a = this,
        b = ("" + a).match(/(?:\.(\d+))?(?:[eE]([+-]?\d+))?$/);
    return b ? Math.max(0, (b[1] ? b[1].length : 0) - (b[2] ? +b[2] : 0)) : 0
}), jQuery(document).ready(function () {
    wcqib_refresh_quantity_increments()
}), jQuery(document).on("updated_wc_div", function () {
    wcqib_refresh_quantity_increments()
}), jQuery(document).on("click", ".plus, .minus", function () {
    var a = jQuery(this).closest(".quantity").find(".qty"),
        b = parseFloat(a.val()),
        c = parseFloat(a.attr("max")),
        d = parseFloat(a.attr("min")),
        e = a.attr("step");
    b && "" !== b && "NaN" !== b || (b = 0), "" !== c && "NaN" !== c || (c = ""), "" !== d && "NaN" !== d || (d = 0),
    "any" !== e && "" !== e && void 0 !== e && "NaN" !== parseFloat(e) || (e = 1),
        jQuery(this).is(".plus") ? c
        && b >= c ? a.val(c) : a.val((b + parseFloat(e)).toFixed(e.getDecimals())) : d
        && b <= d ? a.val(d) : b > 0 && a.val((b - parseFloat(e)).toFixed(e.getDecimals())), a.trigger("change")
});

let checkboxes = document.querySelectorAll("button.btn.btn-outline-secondary.select-multiple");

checkboxes.forEach(checkbox => {
    checkbox.addEventListener('click', function (event) {
        let className = checkbox.className;
        let parent_row = checkbox.parentElement.parentElement;

        if (className === "btn btn-outline-secondary select-multiple") {
            checkbox.setAttribute('class', 'btn btn-outline-secondary select-multiple checked');

            if (parent_row.className.search('replenishment') !== -1)
                parent_row.children[5].firstElementChild.removeAttribute('hidden');

        } else {
            checkbox.setAttribute('class', 'btn btn-outline-secondary select-multiple');

            if (parent_row.className.search('replenishment') !== -1)
                parent_row.children[5].firstElementChild.setAttribute('hidden', 'true');
        }
    })
});

let radio_boxes = document.querySelectorAll("button.btn.btn-outline-secondary.select-one");

radio_boxes.forEach(radio_box => {
    radio_box.addEventListener('click', function (event) {
        let className = radio_box.className;

        if (className === "btn btn-outline-secondary select-one") {
            radio_box.setAttribute('class', 'btn btn-outline-secondary checked select-one');

            radio_boxes.forEach(r => {
                if (r !== radio_box)
                    r.setAttribute('class', 'btn btn-outline-secondary select-one');
            })
        } else {
            radio_box.setAttribute('class', 'btn btn-outline-secondary select-one');
        }
    })
});

let mainButton = document.querySelector("div.main-container button.btn.btn-secondary");

if (mainButton != null) {
    if (document.querySelectorAll("div.main-container > .row").length <= 1)
        mainButton.disabled = true;
}

let create_wave = document.getElementById('create_wave');

if (create_wave != null) {
    create_wave.firstElementChild.addEventListener('click', function(event) {
        event.preventDefault();

        let checked_buttons = document.getElementsByClassName('btn btn-outline-secondary select-multiple checked');
        let sales_Ids = [];
        let insufficient_stock = [];

        let all_products = [];

        for (let i = 0; i < checked_buttons.length; i++) {
            let sale = checked_buttons[i].parentElement.parentElement.firstElementChild;
            sales_Ids.push(sale.firstElementChild.firstElementChild.textContent);

            let products = document.querySelector(sale.getAttribute('href')).firstElementChild;
            products = Array.from(products.children).slice(1,);

            products.forEach(product_line => {
                let product_id = product_line.firstElementChild.textContent;
                let product_qnt = parseInt(product_line.children[3].textContent);
                let product_stock = parseInt(product_line.children[4].textContent);

                if (all_products[product_id] === undefined) {
                    all_products[product_id] = [product_id, product_qnt, product_stock]
                } else {
                    all_products[product_id][1] += product_qnt;
                }
            });
        }

        for (let id in all_products) {
            if (all_products.hasOwnProperty(id)) {
                if (all_products[id][1] > all_products[id][2])
                    insufficient_stock.push(all_products[id][0]);
            }
        }

        if (insufficient_stock.length === 0 && sales_Ids.length > 0)
            sendAjaxRequest.call(this, 'post', '/manager/createPickingWave', {ids: sales_Ids}, createPickingWaveHandler);
        else if(insufficient_stock.length !== 0) {
            let modal = $('#alert-modal');
            let modal_title = document.getElementsByClassName('modal-title')[0];
            console.log(modal_title);
            let modal_body = document.getElementsByClassName('modal-body')[0];

            modal_title.innerHTML = 'Insufficient Stock';
            modal_body.innerHTML = '<p>The existing stock is insufficient for all the selected orders.</p> ' +
                '<p>Lack of stock of:</p>' +
                '<ul>';

            insufficient_stock.forEach(id => {modal_body.innerHTML += '<li>' + id + '</li>'})
            modal_body.innerHTML += '</ul>';
            modal.modal('show');
        }
    })
}

function createPickingWaveHandler() {
    if (this.status !== 200) return;
    
    window.location.replace("/manager/pickingWaves");
}


//////////
// AJAX //
//////////

function sendAjaxRequest(method, url, data, handler) {
    let request = new XMLHttpRequest();

    request.prototype = this;
    request.open(method, url, true);
    request.setRequestHeader('X-CSRF-TOKEN', document.querySelector('meta[name="csrf-token"]').content);
    request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    request.addEventListener('load', handler);
    request.send(encodeForAjax(data));
}

function encodeForAjax(data) {
    if (data == null) return null;
    return Object.keys(data).map(function (k) {
        return encodeURIComponent(k) + '=' + encodeURIComponent(data[k])
    }).join('&');
}
