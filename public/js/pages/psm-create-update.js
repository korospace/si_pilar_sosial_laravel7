let user_level_id = $("#formCreateUpdatePsm #level_id").val();

/**
 * Only Number
 * -----------------------------
 */
filterNumbers([ // layouts/main.js
    "#formCreateUpdatePsm #nik",
    "#formCreateUpdatePsm #telepon",
    "#formCreateUpdatePsm #alamat_rt",
    "#formCreateUpdatePsm #alamat_rw",
])

/**
 * Disable Input
 * -----------------------------
 */
// -- disable all input when update AND non admin --
if ($("#formCreateUpdatePsm #id").val() != null && user_level_id != 1) {
    $('#formCreateUpdatePsm input, #formCreateUpdatePsm select').prop('disabled', true);
}
// -- disable autocomplete wilayah when non admin --
if (user_level_id != 1) {
    $('#formCreateUpdatePsm #site').prop('disabled', true);
}
else {
    EnableAutoCompleteWilayah()
}

/**
 * Date Picker - initialization - tgl lahir
 */
moment.locale('id');
$("#formCreateUpdatePsm .tgl").daterangepicker({
    singleDatePicker: true,
    showDropdowns: true, // Opsional, menampilkan dropdown untuk memilih bulan dan tahun
    "locale": {
        "format": "DD MMMM YYYY",
        "separator": " - ",
        "applyLabel": "Simpan",
        "cancelLabel": "Tutup",
        "customRangeLabel": "Custom",
        "daysOfWeek": ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'],
        "monthNames": [
            'Januari',
            'Februari',
            'Maret',
            'April',
            'Mei',
            'Juni',
            'Juli',
            'Agustus',
            'September',
            'Oktober',
            'November',
            'Desember'
        ],
        "firstDay": 1
    }
});

if ($("#formCreateUpdatePsm #id").val() == null) {
    $("#formCreateUpdatePsm .tgl").val('');
}

/**
 * Auto Complete - Site
 */
let autoComplete = false;
let region_id = $("#formCreateUpdatePsm #region_id").val();

function EnableAutoCompleteWilayah() {
    $("#formCreateUpdatePsm #site").autoComplete({
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

    $('#formCreateUpdatePsm #site').on('input', function () {
        $('#formCreateUpdatePsm #site_id').val('')
        region_id = $("#formCreateUpdatePsm #region_id").val()

        $('#formCreateUpdatePsm #tempat_tugas_kelurahan').val('')
        $('#formCreateUpdatePsm #tempat_tugas_kelurahan_hide').val('')
        $('#formCreateUpdatePsm #tempat_tugas_kecamatan').val('')
        $('#formCreateUpdatePsm #tempat_tugas_kecamatan_hide').val('')
    });

    $('#formCreateUpdatePsm #site').on('autocomplete.select', function (evt, item) {
        $('#formCreateUpdatePsm #site_id').val(item.id)
        region_id = item.region_id

        $(`#formCreateUpdatePsm #site_id-error`).html('');

        autoComplete = true;
    });
}

/**
 * Auto Complete - Kecamatan
 */
$("#formCreateUpdatePsm #tempat_tugas_kecamatan").autoComplete({
    resolver: 'ajax',
    noResultsText:'No results',
    events: {
        search: function (qry, callback) {
            $.ajax(
                {
                    url: `${BASE_URL}/api/v1/autocomplete/region`,
                    data: { 'name': qry, 'type': 'kecamatan', 'region_id': region_id},
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

$('#formCreateUpdatePsm #tempat_tugas_kecamatan').on('input', function () {
    $('#formCreateUpdatePsm #tempat_tugas_kecamatan_hide').val('')

    $('#formCreateUpdatePsm #tempat_tugas_kelurahan').val('')
    $('#formCreateUpdatePsm #tempat_tugas_kelurahan_hide').val('')
});

$('#formCreateUpdatePsm #tempat_tugas_kecamatan').on('autocomplete.select', function (evt, item) {
    $('#formCreateUpdatePsm #tempat_tugas_kecamatan_hide').val(item.text)
    region_id = item.id

    $(`#formCreateUpdateTksk #tempat_tugas_kecamatan_hide-error`).html('');

    autoComplete = true;
});

/**
 * Auto Complete - Kelurahan
 */
$("#formCreateUpdatePsm #tempat_tugas_kelurahan").autoComplete({
    resolver: 'ajax',
    noResultsText:'No results',
    events: {
        search: function (qry, callback) {
            $.ajax(
                {
                    url: `${BASE_URL}/api/v1/autocomplete/region`,
                    data: { 'name': qry, 'type': 'kelurahan', 'region_id': region_id},
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

$('#formCreateUpdatePsm #tempat_tugas_kelurahan').on('input', function () {
    $('#formCreateUpdatePsm #tempat_tugas_kelurahan_hide').val('')
});

$('#formCreateUpdatePsm #tempat_tugas_kelurahan').on('autocomplete.select', function (evt, item) {
    $('#formCreateUpdatePsm #tempat_tugas_kelurahan_hide').val(item.text)
    $(`#formCreateUpdatePsm #tempat_tugas_kelurahan_hide-error`).html('');

    autoComplete = true;
});

/**
 * Select 2
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
 * Form Logic
 */
//  clear error when keydown
$("#formCreateUpdatePsm input").on('keydown', function () {
    $(this).removeClass('is-invalid');
    $(`#formCreateUpdatePsm #${$(this).attr('name')}-error`).html('');
})
$("#formCreateUpdatePsm select").on('change', function () {
    $(this).removeClass('is-invalid');
    $(`#formCreateUpdatePsm #${$(this).attr('name')}-error`).html('');
})

// sertifikasi
$("#formCreateUpdatePsm #sertifikasi_status").on('change', function () {
    if ($(this).val() == "sudah") {
        $(`#formCreateUpdatePsm .col-sertifikasi`).show();
    }
    else{
        $(`#formCreateUpdatePsm .col-sertifikasi`).hide();
    }
})
$("#formCreateUpdatePsm #sertifikasi_status").change()

// form submit
$('#formCreateUpdatePsm').validate({
    rules: {
        year: {
            required: true
        },
        site_id: {
            required: true,
        },
        status: {
            required: true,
        },
        nama: {
            required: true,
        },
        nik: {
            required: true,
            minlength: 16,
            maxlength: 16,
            digits: true
        },
        tempat_lahir: {
            required: true,
        },
        tanggal_lahir: {
            required: true,
        },
        jenis_kelamin: {
            required: true,
        },
        tempat_tugas_kelurahan_hide: {
            required: true,
        },
        tempat_tugas_kecamatan_hide: {
            required: true,
        },
        alamat_jalan: {
            required: true,
        },
        alamat_rt: {
            required: true,
        },
        alamat_rw: {
            required: true,
        },
        tingkatan_diklat: {
            required: true,
        },
        sertifikasi_status: {
            required: true,
        },
        sertifikasi_tahun: {
            required: true,
        },
        telepon: {
            required: true,
            minlength: 10,
            maxlength: 12,
            digits: true
        },
        pendidikan_terakhir: {
            required: true,
        },
        kondisi_existing: {
            required: true,
        },
    },
    messages: {
        year: {
            required: "Data tahun harus disi"
        },
        site_id: {
            required: "Wilayah harus diisi",
        },
        status: {
            required: "Status harus diisi",
        },
        nama: {
            required: "Nama harus diisi",
        },
        nik: {
            required: "NIK harus diisi",
            minlength: "NIK minimal 16 digit",
            maxlength: "NIK maximal 16 digit",
            digits: "NIK harus angka"
        },
        tempat_lahir: {
            required: "Tempat lahir harus diisi",
        },
        tanggal_lahir: {
            required: "Tanggal lahir harus diisi",
        },
        jenis_kelamin: {
            required: "Jenis kelamin harus diisi",
        },
        tempat_tugas_kelurahan_hide: {
            required: "wilayah tidak valid",
        },
        tempat_tugas_kecamatan_hide: {
            required: "wilayah tidak valid",
        },
        alamat_jalan: {
            required: "Alamat jalan harus diisi",
        },
        alamat_rt: {
            required: "Alamat RT harus diisi",
        },
        alamat_rw: {
            required: "Alamat RW harus diisi",
        },
        tingkatan_diklat: {
            required: "Tingkatan diklat harus diisi",
        },
        sertifikasi_status: {
            required: "Status sertifikasi harus diisi",
        },
        sertifikasi_tahun: {
            required: "Tahun sertifikasi harus diisi",
        },
        telepon: {
            required: "No. HP harus diisi",
            minlength: "No. HP minimal 10 digit",
            maxlength: "No. HP maximal 12 digit",
            digits: "No. HP harus angka"
        },
        pendidikan_terakhir: {
            required: "Pendidikan terakhir harus diisi",
        },
        kondisi_existing: {
            required: "Kondisi existing harus diisi",
        },
    },
    errorElement: 'span',
    errorPlacement: function (error, element) {
        error.addClass('invalid-feedback');
        element.closest('.form-group').append(error);
    },
    highlight: function (element, errorClass, validClass) {
        if ($(element).attr('name') == "site_id") {
            $('#formCreateUpdatePsm #site').addClass('is-invalid');
        }
        if ($(element).attr('name') == "tempat_tugas_kelurahan_hide") {
            $('#formCreateUpdatePsm #tempat_tugas_kelurahan').addClass('is-invalid');
        }
        if ($(element).attr('name') == "tempat_tugas_kecamatan_hide") {
            $('#formCreateUpdatePsm #tempat_tugas_kecamatan').addClass('is-invalid');
        }
        else {
            $(element).addClass('is-invalid');
        }
    },
    unhighlight: function (element, errorClass, validClass) {
        if ($(element).attr('name') == "site_id") {
            $('#formCreateUpdatePsm #site').removeClass('is-invalid');
        }
        if ($(element).attr('name') == "tempat_tugas_kelurahan_hide") {
            $('#formCreateUpdatePsm #tempat_tugas_kelurahan').removeClass('is-invalid');
        }
        if ($(element).attr('name') == "tempat_tugas_kecamatan_hide") {
            $('#formCreateUpdatePsm #tempat_tugas_kecamatan').removeClass('is-invalid');
        }
        else {
            $(element).removeClass('is-invalid');
        }
    }
});

$(document).on('keydown', function(event) {
    if (event.keyCode === 13) {
        event.preventDefault();

        if (autoComplete == false) {
            saveData()
        }
        else {
            autoComplete = false;
        }
    }
});

function saveData() {
    if ($('#formCreateUpdatePsm').valid()) {
        let form  = new FormData(document.querySelector('#formCreateUpdatePsm'));
        let psmId = form.get('id');

        Swal.fire({
            title: 'ANDA YAKIN?',
            text: `pastikan data sudah benar`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'iya',
            preConfirm: () => {
                return $.ajax({
                    type: psmId ? "PUT": "POST",
                    url: `${BASE_URL}/api/v1/psm/${psmId ? 'update' : 'create'}`,
                    data: form,
                    cache: false,
                    processData:false,
                    contentType: false,
                    headers		: {
                        'token': $.cookie("jwt_token"),
                    },
                }).then(response => {
                    Swal.fire({
                    title: 'Sukses!',
                    text: 'Data berhasil tersimpan.',
                    icon: 'success'
                    }).then((result) => {
                        if (psmId == null) {
                            if (user_level_id == 1) {
                                $("#formCreateUpdatePsm input").val('');
                            }
                            else {
                                $("#formCreateUpdatePsm input").not("#site").val('');
                            }
                            $("#formCreateUpdatePsm select").val('').change();
                        }
                        else {
                            location.reload();
                        }
                    });
                }).catch(data => {
                    if (data.status == 400) {
                        Swal.close();
                        let errors = data.responseJSON.data.errors;

                        for (const key in errors) {
                            $(`#formCreateUpdatePsm #${key}`).addClass('is-invalid');
                            $(`#formCreateUpdatePsm #${key}-error`).html(errors[key][0]);
                        }

                        showToast('data <b>belum valid</b>. mohon periksa kembali!','warning');
                    }
                    else if (data.status >= 500) {
                        Swal.showValidationMessage(`terjadi kesalahan pada server!`)
                    }
                    else {
                        Swal.showValidationMessage(data.responseJSON.message)
                    }
                });
            },
            allowOutsideClick: () => !Swal.isLoading()
        })
    }
}

/**
 * Verifikasi
 */
function verifPSM(el, event, status, id) {
    Swal.fire({
        title: 'ANDA YAKIN?',
        text: `data tidak akan bisa diubah`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'iya',
        preConfirm: () => {
            return $.ajax({
                type: "PUT",
                url: `${BASE_URL}/api/v1/psm/verif?status=${status}&id=${id}`,
                headers	: {
                    'token': $.cookie("jwt_token"),
                },
            }).then(response => {
                Swal.fire({
                  title: 'Sukses!',
                  text: 'Data berhasil tersimpan.',
                  icon: 'success'
                }).then((result) => {
                    location.reload();
                });
            }).catch(data => {
                if (data.status >= 500) {
                    Swal.showValidationMessage(`terjadi kesalahan pada server!`)
                }
                else {
                    Swal.showValidationMessage(data.responseJSON.message)
                }
            });
        },
        allowOutsideClick: () => !Swal.isLoading()
    })
}
