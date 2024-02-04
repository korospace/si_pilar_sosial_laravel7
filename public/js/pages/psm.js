$token = $.cookie("jwt_token");
let autoComplete = false;

/**
 * Select 2
 * ----------------------
 */
$('.select2bs4').select2({
    theme: 'bootstrap4'
})
$('.select2bs4').on('select2:select', function(e) {
    $(this).removeClass('is-invalid');
    autoComplete = true;
});

/**
 * Auto Complete - Site
 * --------------------
 */
$(".ac_site").autoComplete({
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
                        'token': $token,
                    },
                }
            ).done(function (res) {
                callback(res.data);
            });
        },
    },
});

$('.ac_site').on('input', function () {
    $(this).prev().val('')
});

$('.ac_site').on('autocomplete.select', function (evt, item) {
    $(this).prev().val(item.id)
    autoComplete = true;
});

/**
 * Get Info Status
 * --------------------
 */
function getInfoStatus() {
    $.ajax({
        type: "GET",
        url: `${BASE_URL}/api/v1/psm/info_status`,
        headers		: {
            'token': $token,
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
 * Initial Psm Table
 */
let filterTahun  = "";
let filterSiteId = "";
let filterSite   = "";
let filterStatus = "";

function initialDataTablePsm(params) {
    filterTahun  = $("#modal-filter-psm select[name='year_filter']").val();
    filterSiteId = $("#modal-filter-psm input[name='site_filter_id']").val();
    filterSite   = $("#modal-filter-psm input[name='site_filter']").val();
    filterStatus = $("#modal-filter-psm select[name='status_filter']").val();

    $("#tablePsm").DataTable({
        "bDestroy": true,
        "serverSide": true,
        "processing": true,
        "responsive": true,
        "autoWidth": false,
        "pageLength": 10,
        "order": [[0, 'asc']],
        "ajax": {
            "url": `${BASE_URL}/api/v1/psm/datatable?year=${filterTahun}&site_id=${filterSiteId}&status=${filterStatus}`,
            "beforeSend": function(xhr){
                xhr.setRequestHeader('token', $token);
            }
        },
        "columns": [
            { data: 'no', width: '5%' },
            { data: 'id_psm' },
            { data: 'year', width: '8%' },
            { data: 'nama' },
            { data: 'tempat_tugas' },
            { data: 'jenis_kelamin' },
            { data: 'status', width: '10%' },
            { data: 'action', width: '10%' },
        ],
        "columnDefs": [
            {
                "targets": [0,1,2,3,4,5,6,7],
                "className": "text-center align-middle",
            },
        ],
    }).buttons().container().appendTo('#tablePsm_wrapper .col-md-6:eq(0)');
}

initialDataTablePsm();

/**
 * Filter
 * =========================
 */
// -- run filter psm --
function run_filter_psm() {
    initialDataTablePsm();
    update_ket_filter_psm()
    $(`#modal-filter-psm`).modal('hide');
    $('.select2bs4').select2('close')
}
// -- clear filter psm --
function clear_filter_psm() {
    initialDataTablePsm();
    update_ket_filter_psm()
    $(`#modal-filter-psm`).modal('hide');
}
// -- update keterangan --
function update_ket_filter_psm() {
    let str_ket = "";

    if (filterTahun) {
        str_ket += "<b>tahun: </b>"+filterTahun
    }
    if (filterSiteId) {
        str_ket += filterTahun ? " - <b>wilayah: </b>"+filterSite : "<b>wilayah: </b>"+filterSite
    }
    if (filterStatus) {
        str_ket += filterTahun || filterSiteId ? " - <b>status: </b>"+filterStatus : "<b>status: </b>"+filterStatus
    }

    $(".ket-filter").html(str_ket)
}

// -- form submit
$("#formFilterPsm").on('keydown', function(event) {
    if (event.keyCode === 13) {
        if (autoComplete == false) {
            run_filter_psm()
        }
        else {
            autoComplete = false;
        }
    }
});

/**
 * Form Import Psm Logic
 * =========================
 */
// -- clear form - data
$("a[data-target='#modal-import-psm']").on('click', function () {
    $("input").removeClass('is-invalid');
    $("select").removeClass('is-invalid');
    $("span.invalid-feedback").html('');
    $("#formImportPsm .alert").hide();
    $("#formImportPsm input").val('');
    $("#formImportPsm select").val('').change();
})

// -- clear form - alert
$("#formImportPsm button.close").on('click', function () {
    $("#formImportPsm .alert").hide();
})

// -- clear form - error when keydown
$("#formImportPsm input").on('keydown', function () {
    $(this).removeClass('is-invalid');
    $(`#formImportPsm #${$(this).attr('name')}-error`).html('');
})
$("#formImportPsm input").on('change', function () {
    $(this).removeClass('is-invalid');
    $(`#formImportPsm #${$(this).attr('name')}-error`).html('');
})

// -- form validation
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

// -- form submit
$('#formImportPsm').on('keydown', function(event) {
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
    $("#formImportPsm .alert").hide();

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
                'token': $token,
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
