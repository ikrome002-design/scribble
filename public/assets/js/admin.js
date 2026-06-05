$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

//addeven after three numbers
$(".input-add-dash").keyup(function () {
    ele = $(this).val().split('-').join('');
    let finalVal = ele.match(/.{1,3}/g).join('-');
    $(this).val(finalVal);
})
