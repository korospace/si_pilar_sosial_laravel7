$token = $.cookie("jwt_token");

/**
 * Initial Tksk Table
 */
function initialDataTableTksk(params) {
    $("#tableTksk").DataTable({
        "bDestroy": true,
        "serverSide": true,
        "processing": true,
        "pageLength": 10,
        "order": [[0, 'asc']],
        "ajax": {
            "url": `${BASE_URL}/api/v1/tksk/datatable`,
            "beforeSend": function(xhr){
                xhr.setRequestHeader('token', $token);
            }
        },
        "columns": [
            { data: 'no', width: '5%' },
            { data: 'no_urut', width: '8%' },
            { data: 'no_induk_anggota', width: '15%' },
            { data: 'nama' },
            { data: 'tempat_tugas' },
            { data: 'jenis_kelamin' },
            { data: 'status', width: '10%' },
            { data: 'action', width: '10%' },
        ],
        "columnDefs": [
            {
                "targets": [0,1,2,3,4,5,6,7],
                "className": "text-center vertical-center",
            },
        ],
    }).buttons().container().appendTo('#tableTksk_wrapper .col-md-6:eq(0)');
}

initialDataTableTksk();

/**
 * Get Info Status
 */
$.ajax({
    type: "GET",
    url: `${BASE_URL}/api/v1/tksk/info_status`,
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

/**
 * Auto Complete - Site
 */
let autoComplete = false;
$("#formImportTksk #site").autoComplete({
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

$('#formImportTksk #site').on('input', function () {
    $('#formImportTksk #site_id').val('')
});

$('#formImportTksk #site').on('autocomplete.select', function (evt, item) {
    $('#formImportTksk #site_id').val(item.id)

    $(`#formImportTksk #site_id-error`).html('');

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
 * Form Import Tksk Logic
 */
$("a[data-target='#modal-import-tksk']").on('click', function () {
    $("#formImportTksk .alert").hide();
    $("#formImportTksk input").val('');
    $("#formImportTksk select").val('').change();
    $("input").removeClass('is-invalid');
    $("select").removeClass('is-invalid');
    $("span.invalid-feedback").html('');
})
$("#formImportTksk button.close").on('click', function () {
    $("#formImportTksk .alert").hide();
})

//  clear error when keydown
$("#formImportTksk input").on('keydown', function () {
    $(this).removeClass('is-invalid');
    $(`#formImportTksk #${$(this).attr('name')}-error`).html('');
})
$("#formImportTksk input").on('change', function () {
    $(this).removeClass('is-invalid');
    $(`#formImportTksk #${$(this).attr('name')}-error`).html('');
})

// form submit
$('#formImportTksk').validate({
    rules: {
        site_id: {
            required: true,
        },
        year: {
            required: true,
        },
        file_tksk: {
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
        file_tksk: {
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
            $('#formImportTksk #site').addClass('is-invalid');
        } else {
            $(element).addClass('is-invalid');
        }
    },
    unhighlight: function (element, errorClass, validClass) {
        if ($(element).attr('name') == "site_id") {
            $('#formImportTksk #site').removeClass('is-invalid');
        } else {
            $(element).removeClass('is-invalid');
        }
    },
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
    if ($('#formImportTksk').valid()) {
        showLoadingSpinner();

        let form = new FormData(document.querySelector('#formImportTksk'));

        $.ajax({
            type: "POST",
            url: `${BASE_URL}/api/v1/tksk/import`,
            data: form,
            cache: false,
            processData:false,
            contentType: false,
            headers		: {
                'token': $.cookie("jwt_token"),
            },
            success:function(data) {
                hideLoadingSpinner();
                $("#formImportTksk input").val('');

                $("#formImportTksk .alert span").html(data.message);
                $("#formImportTksk .alert").show();

                initialDataTableTksk();
            },
            error:function(data) {
                hideLoadingSpinner();

                if (data.status == 400) {
                    let errors = data.responseJSON.data.errors;

                    for (const key in errors) {
                        $(`#formImportTksk #${key}`).addClass('is-invalid');
                        $(`#formImportTksk #${key}-error`).html(errors[key][0]);
                    }
                }
                else if (data.status >= 500) {
                    showToast('kesalahan pada <b>server</b>','danger');
                }
            }
        });
    }
}
