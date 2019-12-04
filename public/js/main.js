function wcqib_refresh_quantity_increments() {
    jQuery("div.quantity:not(.buttons_added), td.quantity:not(.buttons_added)").each(function (a, b) {
        var c = jQuery(b);
        c.addClass("buttons_added"), c.children().first().before('<input type="button" value="-" class="minus" />'), c.children().last().after('<input type="button" value="+" class="plus" />')
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
    b && "" !== b && "NaN" !== b || (b = 0), "" !== c && "NaN" !== c || (c = ""), "" !== d && "NaN" !== d || (d = 0), "any" !== e && "" !== e && void 0 !== e && "NaN" !== parseFloat(e) || (e = 1), jQuery(this).is(".plus") ? c && b >= c ? a.val(c) : a.val((b + parseFloat(e)).toFixed(e.getDecimals())) : d && b <= d ? a.val(d) : b > 0 && a.val((b - parseFloat(e)).toFixed(e.getDecimals())), a.trigger("change")
});

let checkboxes = document.querySelectorAll("button.btn.btn-outline-secondary.select-multiple");

checkboxes.forEach(checkbox => {
    checkbox.addEventListener('click', function (event) {
        let className = checkbox.className;
        let parent_row = checkbox.parentElement.parentElement;

        if (className === "btn btn-outline-secondary select-multiple") {
            checkbox.setAttribute('class', 'btn btn-outline-secondary select-multiple checked');

            if (parent_row.className.search('replenishment') != -1)
                parent_row.children[5].firstElementChild.removeAttribute('hidden');

        } else {
            checkbox.setAttribute('class', 'btn btn-outline-secondary select-multiple');

            if (parent_row.className.search('replenishment') != -1)
                parent_row.children[5].firstElementChild.setAttribute('hidden', 'true');
        }
    })
})

let radioboxes = document.querySelectorAll("button.btn.btn-outline-secondary.select-one");

radioboxes.forEach(radiobox => {
    radiobox.addEventListener('click', function (event) {
        let className = radiobox.className;

        if (className === "btn btn-outline-secondary select-one") {
            radiobox.setAttribute('class', 'btn btn-outline-secondary checked select-one');

            radioboxes.forEach(r => {
                if (r != radiobox)
                    r.setAttribute('class', 'btn btn-outline-secondary select-one');
            })
        } else {
            radiobox.setAttribute('class', 'btn btn-outline-secondary select-one');
        }
    })
})

let mainButton = document.querySelector("div.main-container button.btn.btn-secondary");

if (mainButton != null) {
    if (document.querySelectorAll("div.main-container > .row").length <= 1)
        mainButton.disabled = true;
}

let qntRangeLimit = document.getElementsByClassName('quantity buttons_added')

for(let i = 0; i < qntRangeLimit.length; i++) {
    qntRangeLimit[i].children[1].addEventListener('change', function () {
        let minValue = this.getAttribute('min');
        let maxValue = this.getAttribute('max');

        if(this.value < minValue) this.value = minValue;
        else if(this.value > maxValue) this.value = maxValue;
    })
}

let createPO = document.getElementsByClassName('btn btn-secondary')

for(let i = 0; i < createPO.length; i++) {
    createPO[i].addEventListener('click', function (event) {
        event.preventDefault();
        let data = {};

        for(let j = 0; j < qntRangeLimit.length; j++) {
            if(qntRangeLimit[j].getAttribute('hidden') == null) {
                let productID = qntRangeLimit[j].parentElement.parentElement.children[0].textContent;
                let qnt = qntRangeLimit[j].children[1].value;
                data[productID] = qnt
            }
        }
        console.log(data);

        sendAjaxRequest.call(this, 'post', '/manager/replenishment/create-purchase-order', data, redirectToPurchaseOrdersPage)
    });
}

function redirectToPurchaseOrdersPage() {
    if (this.status !== 200) return;

    window.location.replace('/manager/purchaseOrders');
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
