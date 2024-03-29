// Quantity Input
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

let qntRangeLimit = document.getElementsByClassName('quantity buttons_added');
for (let i = 0; i < qntRangeLimit.length; i++) {
    qntRangeLimit[i].children[1].addEventListener('change', function () {
        let minValue = parseInt(this.getAttribute('min'));
        let maxValue = parseInt(this.getAttribute('max'));

        if (this.value < minValue) this.value = minValue;
        else if (this.value > maxValue) this.value = maxValue;
    })
}

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
    if ((document.querySelectorAll("div.main-container > .row").length <= 1) &&
        (document.querySelectorAll("div.main-container.with-ground") == null))
        mainButton.disabled = true;
}

let create_wave = document.getElementById('create_wave');
if (create_wave != null) {
    create_wave.addEventListener('click', function (event) {
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
        } else if (insufficient_stock.length !== 0) {
            let body = '<p>The existing stock is insufficient for all the selected orders.</p> ' +
                '<p>Lack of stock of:</p> <ul>';

            insufficient_stock.forEach(id => {
                body += '<li>' + id + '</li>'
            });
            body += '</ul>';
            activeModal('Insufficient Stock', body);
        }
    })
}

function createPickingWaveHandler() {
    if (this.status !== 200) return;
    document.body.style.cursor = 'default';
    setCookie('error_info', 'New Picking Wave added !', 1);
    window.location.assign("/manager/pickingWaves");
}

let createPO = document.getElementById('create-PO');
if (createPO !== null) {
    createPO.addEventListener('click', function (event) {
        event.preventDefault();
        let data = {};

        for (let j = 0; j < qntRangeLimit.length; j++) {
            if (qntRangeLimit[j].getAttribute('hidden') == null) {
                let productID = qntRangeLimit[j].parentElement.parentElement.children[0].textContent;
                data[productID] = parseInt(qntRangeLimit[j].children[1].value);
            }
        }
        document.body.style.cursor = 'wait';
        sendAjaxRequest.call(this, 'post', '/manager/replenishment/create-purchase-order', data, redirectToPurchaseOrdersPage)
    });

}

function redirectToPurchaseOrdersPage() {
    if (this.status !== 200) return;
    document.body.style.cursor = 'default';
    setCookie('error_info', 'New Purchase Orders added !', 1);
    window.location.assign('/manager/purchaseOrders');
}

let allocate = document.getElementById('allocate');
if (allocate !== null) {
    allocate.addEventListener('click', function (event) {
        event.preventDefault();
        let data = [];

        let checked_buttons = document.getElementsByClassName('btn btn-outline-secondary select-multiple checked');

        for (let i = 0; i < checked_buttons.length; i++) {
            let purchase = checked_buttons[i].parentElement.parentElement.firstElementChild;
            let purchaseOrderId = purchase.firstElementChild.firstElementChild.textContent;

            data.push(purchaseOrderId);
        }
        document.body.style.cursor = 'wait';
        sendAjaxRequest.call(this, 'post', '/manager/replenishment/allocate-purchase-order', {'purchase_orders': data}, redirectToReplenishmentPage)
    });
}

function redirectToReplenishmentPage() {
    if (this.status !== 200) return;
    document.body.style.cursor = 'default';
    setCookie('error_info', 'New stock allocated !', 1);
    window.location.assign('/manager/replenishment');
}

let init_picking_route = document.getElementById('init-picking-route');
if (init_picking_route != null) {
    init_picking_route.addEventListener('click', function (event) {
        event.preventDefault();

        let checked_button = document.getElementsByClassName('btn btn-outline-secondary select-one checked')

        if (checked_button.length !== 1) {
            activeModal('Selected Picking Wave', 'To continue you should select ' +
                (checked_button.length > 1 ? 'only' : '') + ' one Picking Wave');
        } else {
            window.location.assign('/clerk/pickingRoute/' +
                checked_button[0].parentElement.parentElement.firstElementChild.firstElementChild.firstElementChild.textContent);
        }
    })
}

let complete_route = document.getElementById('complete-route');
if (complete_route != null) {
    complete_route.addEventListener('click', function (event) {
        event.preventDefault();

        let products_rows = Array.from(document.getElementsByClassName('row text-center py-2'));
        let products = {};
        let status = true;
        products_rows.forEach(row => {
            switch (row.lastElementChild.firstElementChild.selectedIndex) {
                case 0:
                    activeModal('Already collected all products?',
                        '<p>Did not collect all products. The product ' + row.children[1].textContent +
                        ' have a <i>No Picked</i> status.</p>Collect all the existing products before complete the route.');
                    status = false;
                    break;
                case 1:
                    if (row.children[3].textContent !== row.children[4].getElementsByClassName('input-text qty text')[0].value) {
                        activeModal('Wrong picked quantity',
                            '<p>The product ' + row.children[1].textContent +
                            ' have a wrong picked quantity.</p>Check the picked quantity to complete the route.');
                        status = false;
                    }
                    break;
                case 2:
                    break;
            }
            products[row.id] = [
                row.children[4].getElementsByClassName('input-text qty text')[0].value,
                row.lastElementChild.firstElementChild.selectedIndex
            ];
        });
        if (status) {
            document.body.style.cursor = 'wait';
            sendAjaxRequest.call(this, 'post', window.location.pathname + '/complete', products, redirectToWorkerPickingWavesPage)
        }
    })
}

function redirectToWorkerPickingWavesPage() {
    if (this.status !== 200) return;
    document.body.style.cursor = 'default';
    setCookie('error_info', 'Picking Wave Completed !', 1);
    window.location.assign('/clerk/pickingWaves');
}

let init_packing_wave = document.getElementById('selected-packing-wave');
if (init_packing_wave != null) {
    init_packing_wave.addEventListener('click', function (event) {
        event.preventDefault();

        let checked_button = document.getElementsByClassName('btn btn-outline-secondary select-one checked');

        if (checked_button.length !== 1) {
            activeModal('Selected Packing Wave', 'To continue you should select ' +
                (checked_button.length > 1 ? 'only' : '') + ' one Packing Wave');
        } else {
            window.location.assign('/clerk/packing/' +
                checked_button[0].parentElement.parentElement.firstElementChild.firstElementChild.firstElementChild.textContent);
        }
    })
}

let pack = document.getElementById('pack-order');
if (pack != null) {
    pack.addEventListener('click', function (event) {
        event.preventDefault();

        let checked_button = Array.from(document.getElementsByClassName('btn btn-outline-secondary select-multiple checked'));
        let data = [];
        let status = false;
        checked_button.forEach(button => {
            let order_id = button.parentElement.parentElement.firstElementChild.firstElementChild.firstElementChild.textContent;
            let products = Array.from(document.getElementById('row-id-' + order_id).
            getElementsByClassName('row text-center py-2')).slice(1);

            products.forEach(product => {
                if (parseInt(product.children[3].textContent) > parseInt(product.children[4].textContent)){
                    activeModal('Selected Order for Packing','To package an order, you must select an ' +
                        'order whose products have the picked quantity equal to the desired quantity.');
                    status = true;
                }
            });
            data.push(order_id);
        });

        if (!status) {
            document.body.style.cursor = 'wait';
            sendAjaxRequest.call(this, 'post', window.location.pathname + '/pack', {'data': data}, packOrderHandler)
        }
    })
}

let remove_pack_order = document.getElementById('remove-packing-order');
if (remove_pack_order != null) {
    remove_pack_order.addEventListener('click', function (event) {
        event.preventDefault();

        let checked_button = Array.from(document.getElementsByClassName('btn btn-outline-secondary select-multiple checked'));
        let data = {
            'orders_id': [],
            'products': []
        };
        checked_button.forEach(button => {
            let order_id = button.parentElement.parentElement.firstElementChild.firstElementChild.firstElementChild.textContent;
            let products = Array.from(document.getElementById('row-id-' + order_id).
            getElementsByClassName('row text-center py-2')).slice(1);

            products.forEach(product => {
                data.products.push(product.children[0].textContent, product.children[4].textContent);
            });
            data.orders_id.push(order_id);
        });

        document.body.style.cursor = 'wait';
        sendAjaxRequest.call(this, 'post', window.location.pathname + '/removeOrder', data, packOrderHandler)
    })
}

function packOrderHandler() {
    if (this.status !== 200) return;
    document.body.style.cursor = 'default';

    let orders_id = JSON.parse(this.responseText);

    if (document.getElementsByClassName('main-container')[0].children.length - (orders_id.length * 2) === 2) {
        setCookie('error_info', 'Packing Wave Completed !', 1);
        window.location.assign('/clerk/packingWaves');
        return;
    }

    let all_buttons = Array.from(document.getElementsByClassName('btn btn-outline-secondary select-multiple'));

    all_buttons.forEach(button => {
        let order = button.parentElement.parentElement;
        let id = order.firstElementChild.firstElementChild.firstElementChild.textContent;

        if (orders_id.includes(id)) {
            let collapse_id = order.firstElementChild.getAttribute('href').substring(1);
            order.remove();
            document.getElementById(collapse_id).remove();
        }
    })
}

// MODAL //

function activeModal(title, body) {
    let modal = $('#alert-modal');
    let modal_title = document.getElementsByClassName('modal-title')[0]
    let modal_body = document.getElementsByClassName('modal-body')[0];

    modal_title.innerHTML = title;
    modal_body.style = 'font-size: 14px;'
    modal_body.innerHTML = body;
    modal.modal('show');
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
    d.setTime(d.getTime() + 24 * 60 * 60 * 1000 * days);
    document.cookie = name + "=" + value + ";path=/;expires=" + d.toGMTString();
}

function deleteCookie(name) {
    setCookie(name, '', -1);
}

function checkCookie() {
    let cookie = getCookie('error_info');
    let div_message = document.getElementById('success-alert');

    if (cookie != null && div_message != null) {
        div_message.removeAttribute('hidden');
        div_message.innerHTML = cookie;
    }

    deleteCookie('error_info');
}

checkCookie();

////////////////
// Table Sort //
////////////////

let replenishment_table = document.getElementsByClassName('products-list')[0];

if (replenishment_table != null) {
    let replenishment_header = Array.from(document.getElementsByClassName('header')[0].children).slice(0,5);
    let replenishment_rows = Array.from(replenishment_table.getElementsByClassName('replenishment'));

    for (let i = 0; i < replenishment_header.length; i++) {
        replenishment_header[i].addEventListener('click', function (event) {
            event.preventDefault();

            let order_attribute = this.getAttribute('order');
            if (order_attribute != null) {
                this.setAttribute('order', (parseInt(order_attribute) + 1) % 2);
                this.getElementsByTagName('span')[(parseInt(order_attribute) + 1) % 2].removeAttribute('hidden');
                this.getElementsByTagName('span')[parseInt(order_attribute)].setAttribute('hidden', true);
            }
            else {
                replenishment_header.forEach(row => {
                    if (row.getAttribute('order') != null) {
                        row.getElementsByTagName('span')[0].setAttribute('hidden', true);
                        row.getElementsByTagName('span')[1].setAttribute('hidden', true);
                        row.removeAttribute('order');
                    }
                });
                this.setAttribute('order', 0);
                this.getElementsByTagName('span')[0].removeAttribute('hidden');
            }

            let products_list = [];
            for (let index = 0 ; index < replenishment_rows.length ; index++) {
                if (i === 3)
                    products_list.push([index, parseInt(replenishment_rows[index].children[i].textContent)]);
                else
                    products_list.push([index, replenishment_rows[index].children[i].textContent]);
            }

            products_list.sort(function (a, b) {
                if (a[1] > b[1]) return 1;
                else if (a[1] < b[1]) return -1;
                else return 0;
            });

            if (parseInt(this.getAttribute('order')))
                products_list.reverse();

            replenishment_table.innerHTML = '';
            for (let j = 0 ; j < products_list.length ; j++) {
                let row = replenishment_rows[products_list[j][0]];
                replenishment_table.appendChild(row);
            }
        })
    }
}

