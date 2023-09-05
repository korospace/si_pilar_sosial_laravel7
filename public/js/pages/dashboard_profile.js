// form submit
$('#formEditProfile').validate({
    rules: {
        name: {
            required: true,
        },
        email: {
            required: true,
        }
    },
    messages: {
        name: {
            required: "nama harus diisi",
        },
        email: {
            required: "email harus diisi",
        },
    },
    errorElement: 'span',
    errorPlacement: function (error, element) {
        error.addClass('invalid-feedback');
        element.closest('.form-group').append(error);
    },
    highlight: function (element, errorClass, validClass) {
        $(element).addClass('is-invalid');
    },
    unhighlight: function (element, errorClass, validClass) {
        $(element).removeClass('is-invalid');
    }
});

$(document).on('keydown', function(event) {
    if (event.keyCode === 13) {
        saveData()
    }
});

function saveData() {
    if ($("form#formEditProfile").valid()) {
        showLoadingSpinner();

        let form   = new FormData(document.querySelector('#formEditProfile'));

        $.ajax({
            type: "PUT",
            url: `${BASE_URL}/api/v1/edit_profile`,
            data: form,
            cache: false,
            processData:false,
            contentType: false,
            headers		: {
                'token': $.cookie("jwt_token"),
            },
            success:function(data) {
                hideLoadingSpinner();

                showToast(`profile berhasil <b>disimpan..!</b>`,'success');

                $("#formEditProfile input[type=password]").val('');
            },
            error:function(data) {
                hideLoadingSpinner();

                if (data.status == 400) {
                    let errors = data.responseJSON.data.errors;

                    for (const key in errors) {
                        $(`#formEditProfile #${key}`).addClass('is-invalid');
                        $(`#formEditProfile #${key}-error`).html(errors[key][0]);
                    }
                }
                else if (data.status >= 500) {
                    showToast('kesalahan pada <b>server</b>','danger');
                }
            }
        });
    }
}
