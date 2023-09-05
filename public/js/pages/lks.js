$token = $.cookie("jwt_token");

/**
 * Initial Lks Table
 */
function initialDataTableLks(params) {
    $("#tableLks").DataTable({
        "bDestroy": true,
        "serverSide": true,
        "processing": true,
        "pageLength": 10,
        "order": [[0, 'asc']],
        "ajax": {
            "url": `${BASE_URL}/api/v1/lks/datatable`,
            "beforeSend": function(xhr){
                xhr.setRequestHeader('token', $token);
            }
        },
        "columns": [
            { data: 'no', width: '5%' },
            { data: 'no_urut', width: '8%' },
            { data: 'nama', width: '15%' },
            { data: 'nama_ketua' },
            { data: 'jenis_layanan' },
            { data: 'akreditasi' },
            { data: 'status', width: '10%' },
            { data: 'action', width: '10%' },
        ],
        "columnDefs": [
            {
                "targets": [0,1,2,3,4,5,6,7],
                "className": "text-center vertical-center",
            },
        ],
    }).buttons().container().appendTo('#tableLks_wrapper .col-md-6:eq(0)');
}

initialDataTableLks();

/**
 * Get Info Status
 */
function getInfoStatus() {
    $.ajax({
        type: "GET",
        url: `${BASE_URL}/api/v1/lks/info_status`,
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
getInfoStatus()

/**
 * Auto Complete - Site
 */
let autoComplete = false;
$("#formImportLks #site").autoComplete({
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

$('#formImportLks #site').on('input', function () {
    $('#formImportLks #site_id').val('')
});

$('#formImportLks #site').on('autocomplete.select', function (evt, item) {
    $('#formImportLks #site_id').val(item.id)

    $(`#formImportLks #site_id-error`).html('');

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
 * Form Import Lks Logic
 */
$("a[data-target='#modal-import-lks']").on('click', function () {
    $("#formImportLks .alert").hide();
    $("#formImportLks input").val('');
    $("#formImportLks select").val('').change();
    $("input").removeClass('is-invalid');
    $("select").removeClass('is-invalid');
    $("span.invalid-feedback").html('');
})
$("#formImportLks button.close").on('click', function () {
    $("#formImportLks .alert").hide();
})

//  clear error when keydown
$("#formImportLks input").on('keydown', function () {
    $(this).removeClass('is-invalid');
    $(`#formImportLks #${$(this).attr('name')}-error`).html('');
})
$("#formImportLks input").on('change', function () {
    $(this).removeClass('is-invalid');
    $(`#formImportLks #${$(this).attr('name')}-error`).html('');
})

// form submit
$('#formImportLks').validate({
    rules: {
        site_id: {
            required: true,
        },
        year: {
            required: true,
        },
        file_lks: {
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
        file_lks: {
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
            $('#formImportLks #site').addClass('is-invalid');
        } else {
            $(element).addClass('is-invalid');
        }
    },
    unhighlight: function (element, errorClass, validClass) {
        if ($(element).attr('name') == "site_id") {
            $('#formImportLks #site').removeClass('is-invalid');
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
    if ($('#formImportLks').valid()) {
        showLoadingSpinner();

        let form = new FormData(document.querySelector('#formImportLks'));

        $.ajax({
            type: "POST",
            url: `${BASE_URL}/api/v1/lks/import`,
            data: form,
            cache: false,
            processData:false,
            contentType: false,
            headers		: {
                'token': $.cookie("jwt_token"),
            },
            success:function(data) {
                hideLoadingSpinner();
                $("#formImportLks input").val('');
                $("#formImportLks select").val('').change();

                $("#formImportLks .alert span").html(data.message);
                $("#formImportLks .alert").show();

                initialDataTableLks();
                getInfoStatus();
            },
            error:function(data) {
                hideLoadingSpinner();

                if (data.status == 400) {
                    let errors = data.responseJSON.data.errors;

                    for (const key in errors) {
                        $(`#formImportLks #${key}`).addClass('is-invalid');
                        $(`#formImportLks #${key}-error`).html(errors[key][0]);
                    }
                }
                else if (data.status >= 500) {
                    showToast('kesalahan pada <b>server</b>','danger');
                }
            }
        });
    }
}
