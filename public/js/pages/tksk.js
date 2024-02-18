$token = $.cookie("jwt_token");
let autoComplete = false;

/**
 * Select 2
 * ----------------------
 */
$('.select2bs4').select2({
    theme: 'bootstrap4',
    width: '100%'
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
        url: `${BASE_URL}/api/v1/tksk/info_status`,
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
 * Initial Tksk Table
 * --------------------
 */
let filterTahun  = "";
let filterSiteId = "";
let filterSite   = "";
let filterStatus = "";

function initialDataTableTksk(params) {
    filterTahun  = $("#modal-filter-tksk select[name='year_filter']").val();
    filterSiteId = $("#modal-filter-tksk input[name='site_filter_id']").val();
    filterSite   = $("#modal-filter-tksk input[name='site_filter']").val();
    filterStatus = $("#modal-filter-tksk select[name='status_filter']").val();

    $("#tableTksk").DataTable({
        "bDestroy": true,
        "serverSide": true,
        "processing": true,
        "responsive": true,
        "autoWidth": false,
        "pageLength": 10,
        "order": [[0, 'asc']],
        "ajax": {
            "type": "POST",
            "url": `${BASE_URL}/api/v1/tksk/datatable`,
            "data": function (d) {
                d.year = filterTahun;
                d.site_id = filterSiteId;
                d.status = filterStatus;
                return d;
            },
            "beforeSend": function(xhr){
                xhr.setRequestHeader('token', $token);
            }
        },
        "columns": [
            { data: 'no', width: '5%' },
            { data: 'year', width: '8%' },
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
                "targets": [0,1,2,3,4,5,6,7,8],
                "className": "text-center align-middle",
            },
        ],
    }).buttons().container().appendTo('#tableTksk_wrapper .col-md-6:eq(0)');
}

initialDataTableTksk();

/**
 * Filter
 * =========================
 */
// -- run filter tksk --
function run_filter_tksk() {
    initialDataTableTksk();
    update_ket_filter_tksk()
    $(`#modal-filter-tksk`).modal('hide');
    $('.select2bs4').select2('close')
}
// -- clear filter tksk --
function clear_filter_tksk() {
    initialDataTableTksk();
    update_ket_filter_tksk()
    $(`#modal-filter-tksk`).modal('hide');
}
// -- update keterangan --
function update_ket_filter_tksk() {
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
$("#formFilterTksk").on('keydown', function(event) {
    if (event.keyCode === 13) {
        if (autoComplete == false) {
            run_filter_tksk()
        }
        else {
            autoComplete = false;
        }
    }
});

/**
 * Form Import Tksk Logic
 * =========================
 */
// -- clear form - data
$("a[data-target='#modal-import-tksk']").on('click', function () {
    $("#formImportTksk .alert").hide();
    $("#formImportTksk input").val('');
    $("#formImportTksk select").val('').change();
    $("input").removeClass('is-invalid');
    $("select").removeClass('is-invalid');
    $("span.invalid-feedback").html('');
})

// -- clear form - alert
$("#formImportTksk button.close").on('click', function () {
    $("#formImportTksk .alert").hide();
})

// -- clear form - error when keydown
$("#formImportTksk input").on('keydown', function () {
    $(this).removeClass('is-invalid');
    $(`#formImportTksk #${$(this).attr('name')}-error`).html('');
})
$("#formImportTksk input").on('change', function () {
    $(this).removeClass('is-invalid');
    $(`#formImportTksk #${$(this).attr('name')}-error`).html('');
})

// -- form validation
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
            required: "wilayah harus diisi",
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

// -- form submit
$("#formImportTksk").on('keydown', function(event) {
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
    $("#formImportTksk .alert").hide();

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
                'token': $token,
            },
            success:function(data) {
                hideLoadingSpinner();
                $("#formImportTksk input").val('');
                $("#formImportTksk select").val('').change();

                $("#formImportTksk .alert span").html(data.message);
                $("#formImportTksk .alert").show();

                initialDataTableTksk();
                getInfoStatus();
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
