let checkboxes = document.querySelectorAll("button.btn.btn-outline-secondary.select-multiple");

checkboxes.forEach(checkbox => {
    checkbox.addEventListener('click', function(event) {
        let className = checkbox.className;
        let parent_row = checkbox.parentElement.parentElement;

        if(className === "btn btn-outline-secondary select-multiple"){
            checkbox.setAttribute('class', 'btn btn-outline-secondary select-multiple checked');

            if(parent_row.className.search('replenishment') != -1) 
                parent_row.children[5].firstElementChild.firstElementChild.removeAttribute('hidden');
            
        } else {
            checkbox.setAttribute('class', 'btn btn-outline-secondary select-multiple');

            if(parent_row.className.search('replenishment') != -1) 
                parent_row.children[5].firstElementChild.firstElementChild.setAttribute('hidden','true');                
        }
    })
})

let radioboxes = document.querySelectorAll("button.btn.btn-outline-secondary.select-one");

radioboxes.forEach(radiobox => {
    radiobox.addEventListener('click', function(event) {
        let className = radiobox.className;

        if(className === "btn btn-outline-secondary select-one"){
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

let mainButton = document.querySelector("button.btn.btn-secondary");

if (mainButton != null) {
    console.log(document.querySelectorAll("div.main-container > .row").length);
    console.log(document.querySelectorAll("div.main-container > .row"));

    if (document.querySelectorAll("div.main-container > .row").length <= 1)
        mainButton.disabled = true;
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
    return Object.keys(data).map(function(k) {
        return encodeURIComponent(k) + '=' + encodeURIComponent(data[k])
    }).join('&');
}
