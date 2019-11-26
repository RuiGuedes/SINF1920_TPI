let checkboxes = document.querySelectorAll("button.btn.btn-outline-secondary");

checkboxes.forEach(checkbox => {
    checkbox.addEventListener('click', function(event) {
        let className = checkbox.className;

        if(className === "btn btn-outline-secondary")
            checkbox.setAttribute('class', 'btn btn-outline-secondary checked');
        else 
            checkbox.setAttribute('class', 'btn btn-outline-secondary');
    })
})



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
    return Object.keys(data).map(function(k) {
        return encodeURIComponent(k) + '=' + encodeURIComponent(data[k])
    }).join('&');
}
