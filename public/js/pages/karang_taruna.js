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
        url: `${BASE_URL}/api/v1/karang_taruna/info_status`,
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
getInfoStatus()

/**
 * Initial Karang Taruna Table
 * ---------------------------
 */
let filterTahun  = "";
let filterSiteId = "";
let filterSite   = "";
let filterStatus = "";

function initialDataTableKarangTaruna(params) {
    filterTahun  = $("#modal-filter-karang_taruna select[name='year_filter']").val();
    filterSiteId = $("#modal-filter-karang_taruna input[name='site_filter_id']").val();
    filterSite   = $("#modal-filter-karang_taruna input[name='site_filter']").val();
    filterStatus = $("#modal-filter-karang_taruna select[name='status_filter']").val();

    $("#tableKarangTaruna").DataTable({
        "bDestroy": true,
        "serverSide": true,
        "processing": true,
        "responsive": true,
        "autoWidth": false,
        "pageLength": 10,
        "order": [[0, 'asc']],
        "ajax": {
            "type": "POST",
            "url": `${BASE_URL}/api/v1/karang_taruna/datatable`,
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
            { data: 'nama', width: '15%' },
            { data: 'nama_ketua' },
            { data: 'program_unggulan' },
            { data: 'status', width: '10%' },
            { data: 'action', width: '10%' },
        ],
        "columnDefs": [
            {
                "targets": [0,1,2,3,4,5,6,7],
                "className": "text-center align-middle",
            },
        ],
    }).buttons().container().appendTo('#tableKarangTaruna_wrapper .col-md-6:eq(0)');
}

initialDataTableKarangTaruna();

/**
 * Filter
 * =========================
 */
// -- run filter karang_taruna --
function run_filter_karang_taruna() {
    initialDataTableKarangTaruna();
    update_ket_filter_karang_taruna()
    $(`#modal-filter-karang_taruna`).modal('hide');
    $('.select2bs4').select2('close')
}
// -- clear filter karang_taruna --
function clear_filter_karang_taruna() {
    initialDataTableKarangTaruna();
    update_ket_filter_karang_taruna()
    $(`#modal-filter-karang_taruna`).modal('hide');
}
// -- update keterangan --
function update_ket_filter_karang_taruna() {
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
$("#formFilterKarangTaruna").on('keydown', function(event) {
    if (event.keyCode === 13) {
        if (autoComplete == false) {
            run_filter_karang_taruna()
        }
        else {
            autoComplete = false;
        }
    }
});

/**
 * Form Import Logic
 * =========================
 */
// -- clear form - data
$("a[data-target='#modal-import-karang_taruna']").on('click', function () {
    $("#formImportKarangTaruna .alert").hide();
    $("#formImportKarangTaruna input").val('');
    $("#formImportKarangTaruna select").val('').change();
    $("input").removeClass('is-invalid');
    $("select").removeClass('is-invalid');
    $("span.invalid-feedback").html('');
})

// -- clear form - alert
$("#formImportKarangTaruna button.close").on('click', function () {
    $("#formImportKarangTaruna .alert").hide();
})

// -- clear form - error when keydown
$("#formImportKarangTaruna input").on('keydown', function () {
    $(this).removeClass('is-invalid');
    $(`#formImportKarangTaruna #${$(this).attr('name')}-error`).html('');
})
$("#formImportKarangTaruna input").on('change', function () {
    $(this).removeClass('is-invalid');
    $(`#formImportKarangTaruna #${$(this).attr('name')}-error`).html('');
})

// -- form validation
$('#formImportKarangTaruna').validate({
    rules: {
        site_id: {
            required: true,
        },
        year: {
            required: true,
        },
        file_karang_taruna: {
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
        file_karang_taruna: {
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
            $('#formImportKarangTaruna #site').addClass('is-invalid');
        } else {
            $(element).addClass('is-invalid');
        }
    },
    unhighlight: function (element, errorClass, validClass) {
        if ($(element).attr('name') == "site_id") {
            $('#formImportKarangTaruna #site').removeClass('is-invalid');
        } else {
            $(element).removeClass('is-invalid');
        }
    }
});

// -- form submit
$('#formImportKarangTaruna').on('keydown', function(event) {
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
    $("#formImportKarangTaruna .alert").hide();

    if ($('#formImportKarangTaruna').valid()) {
        showLoadingSpinner();

        let form = new FormData(document.querySelector('#formImportKarangTaruna'));

        $.ajax({
            type: "POST",
            url: `${BASE_URL}/api/v1/karang_taruna/import`,
            data: form,
            cache: false,
            processData:false,
            contentType: false,
            headers		: {
                'token': $token,
            },
            success:function(data) {
                hideLoadingSpinner();
                $("#formImportKarangTaruna input").val('');
                $("#formImportKarangTaruna select").val('').change();

                $("#formImportKarangTaruna .alert span").html(data.message);
                $("#formImportKarangTaruna .alert").show();

                initialDataTableKarangTaruna();
                getInfoStatus();
            },
            error:function(data) {
                hideLoadingSpinner();

                if (data.status == 400) {
                    let errors = data.responseJSON.data.errors;

                    for (const key in errors) {
                        $(`#formImportKarangTaruna #${key}`).addClass('is-invalid');
                        $(`#formImportKarangTaruna #${key}-error`).css('display', 'inline');
                        $(`#formImportKarangTaruna #${key}-error`).html(errors[key][0]);
                    }
                }
                else if (data.status >= 500) {
                    showToast('kesalahan pada <b>server</b>','danger');
                }
            }
        });
    }
}
