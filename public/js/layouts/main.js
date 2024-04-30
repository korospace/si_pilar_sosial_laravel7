/**
 * Password Preview
 * ====================
 */
$('.pass_show').show();
$('.pass_hide').hide();
$('.pass_show').on('click', function () {
    $(this).parent().find('input').attr('type', 'text')
    $(this).prev().show();
    $(this).hide();
})
$('.pass_hide').on('click', function () {
    $(this).parent().find('input').attr('type', 'password')
    $(this).next().show();
    $(this).hide();
})

/**
 * Filter
 * ====================
 */
// -- btn filter --
$(".btn-filter").on('click', function () {
    const uniqid = $(this).data('uniqid');

    $(`#${uniqid}`).modal('show');
})
// -- btn filter --
$(".btn-clear-filter").on('click', function () {
    const formElement = $(this).parent().parent();
    formElement.find('select').val('').change();
})

/**
 * Filter Input Number Only
 * ------------------------
 */
function filterNumbers(selectors) {
    const elements = document.querySelectorAll(selectors);

    elements.forEach(element => {
        element.addEventListener('input', function() {
            this.value = this.value.replace(/\D/g, '');
        });
    });
}
