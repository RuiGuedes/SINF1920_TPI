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

        if (insufficient_stock.length === 0 && sales_Ids.length > 0) {
            document.body.style.cursor = 'wait';
            sendAjaxRequest.call(this, 'post', '/manager/createPickingWave', {ids: sales_Ids}, createPickingWaveHandler);
        } else if(insufficient_stock.length !== 0) {
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
    document.body.style.cursor = 'default';
    setCookie('error_info', 'New Picking Wave added !', 1);
    window.location.replace("/manager/pickingWaves");
}

let qntRangeLimit = document.getElementsByClassName('quantity buttons_added');

for(let i = 0; i < qntRangeLimit.length; i++) {
    qntRangeLimit[i].children[1].addEventListener('change', function () {
        let minValue = parseInt(this.getAttribute('min'));
        let maxValue = parseInt(this.getAttribute('max'));

        if(this.value < minValue) this.value = minValue;
        else if(this.value > maxValue) this.value = maxValue;
    })
}

let createPO = document.getElementById('create-PO');

if (createPO !== null) {
    for(let i = 0; i < createPO.length; i++) {
        createPO[i].addEventListener('click', function (event) {
            event.preventDefault();
            let data = {};

            for(let j = 0; j < qntRangeLimit.length; j++) {
                if(qntRangeLimit[j].getAttribute('hidden') == null) {
                    let productID = qntRangeLimit[j].parentElement.parentElement.children[0].textContent;
                    data[productID] = parseInt(qntRangeLimit[j].children[1].value);
                }
            }
            document.body.style.cursor = 'wait';
            sendAjaxRequest.call(this, 'post', '/manager/replenishment/create-purchase-order', data, redirectToPurchaseOrdersPage)
        });
    }
}

function redirectToPurchaseOrdersPage() {
    if (this.status !== 200) return;
    document.body.style.cursor = 'default';
    setCookie('error_info', 'New Purchase Orders added !', 1);
    window.location.replace('/manager/purchaseOrders');
}

let allocate = document.getElementById('allocate');

if (allocate !== null) {
    for(let j = 0; j < allocate.length; j++) {
        allocate[j].addEventListener('click', function (event) {
            event.preventDefault();
            let data = [];

            let checked_buttons = document.getElementsByClassName('btn btn-outline-secondary select-multiple checked');

            for (let i = 0; i < checked_buttons.length; i++) {
                let purchase = checked_buttons[i].parentElement.parentElement.firstElementChild;
                let purchaseOrderId = purchase.firstElementChild.firstElementChild.textContent;

                data.push(purchaseOrderId);
            }
            document.body.style.cursor = 'wait';
            sendAjaxRequest.call(this, 'post', '/manager/replenishment/allocate-purchase-order', {'purchase_orders' : data}, redirectToReplenishmentPage)
        });
    }
}

function redirectToReplenishmentPage() {
    if (this.status !== 200) return;
    document.body.style.cursor = 'default';
    setCookie('error_info', 'New stock allocated !', 1);
    window.location.replace('/manager/replenishment');
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

/////////////
// COOKIES //
/////////////

function getCookie(name) {
    let v = document.cookie.match('(^|;) ?' + name + '=([^;]*)(;|$)');
    return v ? v[2] : null;
}

function setCookie(name, value, days) {
    let d = new Date;
    d.setTime(d.getTime() + 24*60*60*1000*days);
    document.cookie = name + "=" + value + ";path=/;expires=" + d.toGMTString();
}

function deleteCookie(name) {
    setCookie(name, '', -1);
}

function checkCookie() {
    let cookie = getCookie('error_info');

    if(cookie !== null) {
        document.getElementsByTagName('body')[0].innerHTML = '<div class="alert alert-success">' +
            cookie + '</div>' + document.getElementsByTagName('body')[0].innerHTML;
    }

    deleteCookie('error_info');
}

checkCookie();

