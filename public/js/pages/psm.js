$token = $.cookie("jwt_token");

/**
 * Initial Psm Table
 */
function initialDataTablePsm(params) {
    $("#tablePsm").DataTable({
        "bDestroy": true,
        "serverSide": true,
        "processing": true,
        "pageLength": 10,
        "order": [[0, 'asc']],
        "ajax": {
            "url": `${BASE_URL}/api/v1/psm/datatable`,
            "beforeSend": function(xhr){
                xhr.setRequestHeader('token', $token);
            }
        },
        "columns": [
            { data: 'no', width: '5%' },
            { data: 'id_psm' },
            { data: 'nama' },
            { data: 'tempat_tugas' },
            { data: 'jenis_kelamin' },
            { data: 'status', width: '10%' },
            { data: 'action', width: '10%' },
        ],
        "columnDefs": [
            {
                "targets": [0,1,2,3,4,5,6],
                "className": "text-center vertical-center",
            },
        ],
    }).buttons().container().appendTo('#tablePsm_wrapper .col-md-6:eq(0)');
}

initialDataTablePsm();

/**
 * Get Info Status
 */
function getInfoStatus() {
    $.ajax({
        type: "GET",
        url: `${BASE_URL}/api/v1/psm/info_status`,
        headers		: {
            'token': $.cookie("jwt_token"),
        },
        success:function(data) {
            let infoStatus = data[0];

            for (const key in infoStatus) {
                $(`#status_${key}`).html(infoStatus[key])
            }
        },
        error:function(data) {
            if (data.status >= 500) {
                showToast('gagal menampilkan <b>info status</b>','danger');
            }
        }
    });
}
getInfoStatus();

/**
 * Auto Complete - Site
 */
let autoComplete = false;
$("#formImportPsm #site").autoComplete({
    resolver: 'ajax',
    noResultsText:'No results',
    minLength: 0,
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

$('#formImportPsm #site').on('input', function () {
    $('#formImportPsm #site_id').val('')
});

$('#formImportPsm #site').on('autocomplete.select', function (evt, item) {
    $('#formImportPsm #site_id').val(item.id)

    $(`#formImportPsm #site_id-error`).html('');

    autoComplete = true;
});

/**
 * Select 2
 */
$('.select2bs4').select2({
    theme: 'bootstrap4'
})
$('.select2bs4').on('select2:select', function(e) {
    $(this).removeClass('is-invalid');

    autoComplete = true;
});

/**
 * Form Import Psm Logic
 */
$("a[data-target='#modal-import-psm']").on('click', function () {
    $("input").removeClass('is-invalid');
    $("select").removeClass('is-invalid');
    $("span.invalid-feedback").html('');
    $("#formImportPsm .alert").hide();
    $("#formImportPsm input").val('');
    $("#formImportPsm select").val('').change();
})
$("#formImportPsm button.close").on('click', function () {
    $("#formImportPsm .alert").hide();
})

//  clear error when keydown
$("#formImportPsm input").on('keydown', function () {
    $(this).removeClass('is-invalid');
    $(`#formImportPsm #${$(this).attr('name')}-error`).html('');
})
$("#formImportPsm input").on('change', function () {
    $(this).removeClass('is-invalid');
    $(`#formImportPsm #${$(this).attr('name')}-error`).html('');
})

// form submit
$('#formImportPsm').validate({
    rules: {
        site_id: {
            required: true,
        },
        year: {
            required: true,
        },
        file_psm: {
            required: true,
        },
    },
    messages: {
        site_id: {
            required: "site harus diisi",
        },
        year: {
            required: "tahun harus diisi",
        },
        file_psm: {
            required: "file harus diisi",
        },
    },
    errorElement: 'span',
    errorPlacement: function (error, element) {
        error.addClass('invalid-feedback');
        element.closest('.form-group').append(error);
    },
    highlight: function (element, errorClass, validClass) {
        if ($(element).attr('name') == "site_id") {
            $('#formImportPsm #site').addClass('is-invalid');
        } else {
            $(element).addClass('is-invalid');
        }
    },
    unhighlight: function (element, errorClass, validClass) {
        if ($(element).attr('name') == "site_id") {
            $('#formImportPsm #site').removeClass('is-invalid');
        } else {
            $(element).removeClass('is-invalid');
        }
    }
});

$(document).on('keydown', function(event) {
    if (event.keyCode === 13) {
        if (autoComplete == false) {
            saveImport()
        }
        else {
            autoComplete = false;
        }
    }
});

function saveImport() {
    if ($('#formImportPsm').valid()) {
        showLoadingSpinner();

        let form = new FormData(document.querySelector('#formImportPsm'));

        $.ajax({
            type: "POST",
            url: `${BASE_URL}/api/v1/psm/import`,
            data: form,
            cache: false,
            processData:false,
            contentType: false,
            headers		: {
                'token': $.cookie("jwt_token"),
            },
            success:function(data) {
                hideLoadingSpinner();
                $("#formImportPsm input").val('');
                $("#formImportPsm select").val('').change();

                $("#formImportPsm .alert span").html(data.message);
                $("#formImportPsm .alert").show();

                initialDataTablePsm();
                getInfoStatus();
            },
            error:function(data) {
                hideLoadingSpinner();

                if (data.status == 400) {
                    let errors = data.responseJSON.data.errors;

                    for (const key in errors) {
                        $(`#formImportPsm #${key}`).addClass('is-invalid');
                        $(`#formImportPsm #${key}-error`).html(errors[key][0]);
                    }
                }
                else if (data.status >= 500) {
                    showToast('kesalahan pada <b>server</b>','danger');
                }
            }
        });
    }
}
