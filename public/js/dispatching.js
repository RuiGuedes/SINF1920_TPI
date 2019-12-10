const ENDPOINT_CLERK_DISPATCHING = '/clerk/dispatching';

function main() {
    setup();
    addSubmitListener();
}

function setup() {
    let csrfToken = $('meta[name="csrf-token"]').attr('content');
    $.ajaxSetup({headers: { 'X-CSRF-TOKEN': csrfToken}});
}

function getIds() {
    let ids = [];

    $('#dispatch-div').find('button.select-multiple[class*="checked"]').each(function () {
        let id = $(this).data('id');
        ids.push(id);
    });

    return ids;
}

function addSubmitListener() {
    let btn = $('#dispatching-btn');

    btn.click(() => {
        postIds(getIds());
    });
}

function postIds(ids) {
    if (ids.length === 0) return;

    $.post(ENDPOINT_CLERK_DISPATCHING, {'ids': ids}, () => {
        location.reload();
    });
}

main();