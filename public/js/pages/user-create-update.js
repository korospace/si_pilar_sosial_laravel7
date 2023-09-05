// auto complete - Site
let autoComplete = false;
$("#formCreateUpdateUser #site").autoComplete({
    resolver: 'ajax',
    noResultsText:'No results',
    events: {
        search: function (qry, callback) {
            $.ajax(
                {
                    url: `${BASE_URL}/api/v1/autocomplete/site`,
                    data: { 'name': qry},
                    headers: {
                        'token': $.cookie("jwt_token"),
                    },
                }
            ).done(function (res) {
                callback(res.data);
            });
        },
    },
});

$('#formCreateUpdateUser #site').on('input', function () {
    $('#formCreateUpdateUser #site_id').val('')
});

$('#formCreateUpdateUser #site').on('autocomplete.select', function (evt, item) {
    $('#formCreateUpdateUser #site_id').val(item.id)

    $(`#formCreateUpdateTksk #site_id-error`).html('');

    autoComplete = true;
});

// level - on change
$('#formCreateUpdateUser #level_id').on('input', function () {
    if ($(this).val() == '1' || $(this).val() == '') {
        $('#formCreateUpdateUser .form-group-site').hide();
    }
    else {
        $('#formCreateUpdateUser .form-group-site').show();
    }
});

if ($('#formCreateUpdateUser #level_id').val() == 1) {
    $('#formCreateUpdateUser .form-group-site').hide();
}
else {
    $('#formCreateUpdateUser .form-group-site').show();
}

//  clear error when keydown
$("#formCreateUpdateUser input").on('keydown', function () {
    $(this).removeClass('is-invalid');
    $(`#formCreateUpdateUser #${$(this).attr('name')}-error`).html('');
})

// form submit
$('#formCreateUpdateUser').validate({
    rules: {
        name: {
            required: true,
        },
        email: {
            required: true,
        },
        site_id: {
            required: true,
        },
        level_id: {
            required: true,
        },
        password: {
            required: true,
        },
    },
    messages: {
        name: {
            required: "nama harus diisi",
        },
        email: {
            required: "email harus diisi",
        },
        site_id: {
            required: "site harus diisi",
        },
        level_id: {
            required: "hak akses harus diisi",
        },
        password: {
            required: "password harus diisi",
        },
    },
    errorElement: 'span',
    errorPlacement: function (error, element) {
        error.addClass('invalid-feedback');
        element.closest('.form-group').append(error);
    },
    highlight: function (element, errorClass, validClass) {
        if ($(element).attr('name') == "site_id") {
            $('#formCreateUpdateUser #site').addClass('is-invalid');
        } else {
            $(element).addClass('is-invalid');
        }
    },
    unhighlight: function (element, errorClass, validClass) {
        if ($(element).attr('name') == "site_id") {
            $('#formCreateUpdateUser #site').removeClass('is-invalid');
        } else {
            $(element).removeClass('is-invalid');
        }
    }
});

$(document).on('keydown', function(event) {
    if (event.keyCode === 13) {
        if (autoComplete == false) {
            saveData()
        }
        else {
            autoComplete = false;
        }
    }
});

function saveData() {
    if ($("form#formCreateUpdateUser").valid()) {
        showLoadingSpinner();

        let form   = new FormData(document.querySelector('#formCreateUpdateUser'));
        let userId = form.get('id');

        $.ajax({
            type: userId ? "PUT": "POST",
            url: `${BASE_URL}/api/v1/user/${userId ? 'update' : 'create'}`,
            data: form,
            cache: false,
            processData:false,
            contentType: false,
            headers		: {
                'token': $.cookie("jwt_token"),
            },
            success:function(data) {
                hideLoadingSpinner();

                showToast(`user berhasil <b>${userId ? 'diedit' : 'ditambah'}..!</b>`,'success');

                if (userId == null) {
                    $("#formCreateUpdateUser input").val('');
                    $("#formCreateUpdateUser select").val('');
                    $('#formCreateUpdateUser .form-group-site').hide();
                }
                else {
                    $("#formCreateUpdateUser input[type=password]").val('');
                }
            },
            error:function(data) {
                hideLoadingSpinner();

                if (data.status == 400) {
                    let errors = data.responseJSON.data.errors;

                    for (const key in errors) {
                        $(`#formCreateUpdateUser #${key}`).addClass('is-invalid');
                        $(`#formCreateUpdateUser #${key}-error`).html(errors[key][0]);
                    }
                }
                else if (data.status >= 500) {
                    showToast('kesalahan pada <b>server</b>','danger');
                }
            }
        });
    }
}
