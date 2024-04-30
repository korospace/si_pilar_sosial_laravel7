// auto complete - Nama Site
let autoComplete = false;

if ($('#formCreateUpdateSite #id').val() == null) {
    $("#formCreateUpdateSite #name").autoComplete({
        resolver: 'ajax',
        noResultsText:'No results',
        events: {
            search: function (qry, callback) {
                $.ajax(
                    {
                        url: `${BASE_URL}/api/v1/autocomplete/region`,
                        data: { 'name': qry, 'type': 'kabupaten-kota'},
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

    $('#formCreateUpdateSite #name').on('input', function () {
        $('#formCreateUpdateSite #region_id').val('')
    });

    $('#formCreateUpdateSite #name').on('autocomplete.select', function (evt, item) {
        $('#formCreateUpdateSite #region_id').val(item.id)

        autoComplete = true;
    });
}

//  clear error when keydown
$("#formCreateUpdateSite input").on('keydown', function () {
    $(this).removeClass('is-invalid');
    $(`#formCreateUpdateSite #${$(this).attr('name')}-error`).html('');
})

// form submit
$('#formCreateUpdateSite').validate({
    rules: {
        region_id: {
            required: true,
        },
        name: {
            required: true,
        },
    },
    messages: {
        region_id: {
            required: "kode wilayah harus diisi",
        },
        name: {
            required: "nama site harus diisi",
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
        if (autoComplete == false) {
            saveData()
        }
        else {
            autoComplete = false;
        }
    }
});

function saveData() {
    if ($("form#formCreateUpdateSite").valid()) {
        showLoadingSpinner();

        let form   = new FormData(document.querySelector('#formCreateUpdateSite'));
        let siteId = form.get('id');

        $.ajax({
            type: siteId ? "PUT": "POST",
            url: `${BASE_URL}/api/v1/site/${siteId ? 'update' : 'create'}`,
            data: form,
            cache: false,
            processData:false,
            contentType: false,
            headers		: {
                'token': $.cookie("jwt_token"),
            },
            success:function(data) {
                hideLoadingSpinner();

                showToast(`site berhasil <b>${siteId ? 'diedit' : 'ditambah'}..!</b>`,'success');

                if (siteId == null) {
                    $("#formCreateUpdateSite input").val('');
                }
            },
            error:function(data) {
                hideLoadingSpinner();

                if (data.status == 400) {
                    let errors = data.responseJSON.data.errors;

                    for (const key in errors) {
                        $(`#formCreateUpdateSite #${key}`).addClass('is-invalid');
                        $(`#formCreateUpdateSite #${key}-error`).html(errors[key][0]);
                    }
                }
                else if (data.status >= 500) {
                    showToast('kesalahan pada <b>server</b>','danger');
                }
            }
        });
    }
}
