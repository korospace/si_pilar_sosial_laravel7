let user_level_id = $("#formCreateUpdateKarangTaruna #level_id").val();

/**
 * Disable Input
 * -----------------------------
 */
// -- disable all input when update AND non admin --
if ($("#formCreateUpdateKarangTaruna #id").val() != null && user_level_id != 1) {
    $('#formCreateUpdateKarangTaruna input, #formCreateUpdateKarangTaruna select').prop('disabled', true);
}
// -- disable autocomplete wilayah when non admin --
if (user_level_id != 1) {
    $('#formCreateUpdateKarangTaruna #site').prop('disabled', true);
}
else {
    EnableAutoCompleteWilayah()
}

/**
 * Date Picker
 * -----------------------------------
 */
// -- initialization --
moment.locale('id');
$("#formCreateUpdateKarangTaruna .tgl").daterangepicker({
    singleDatePicker: true,
    // showDropdowns: true, // Opsional, menampilkan dropdown untuk memilih bulan dan tahun
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
    },
});

// -- reset date when create --
if ($("#formCreateUpdateKarangTaruna #id").val() == null) {
    $("#formCreateUpdateKarangTaruna .tgl").val('');
}

/**
 * Auto Complete - Site
 */
let autoComplete = false;
let region_id = $("#formCreateUpdateKarangTaruna #region_id").val();

function EnableAutoCompleteWilayah() {
    $("#formCreateUpdateKarangTaruna #site").autoComplete({
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

    $('#formCreateUpdateKarangTaruna #site').on('input', function () {
        $('#formCreateUpdateKarangTaruna #site_id').val('')
        region_id = $("#formCreateUpdateKarangTaruna #region_id").val()
    });

    $('#formCreateUpdateKarangTaruna #site').on('autocomplete.select', function (evt, item) {
        $('#formCreateUpdateKarangTaruna #site_id').val(item.id)
        region_id = item.region_id

        $(`#formCreateUpdateKarangTaruna #site_id-error`).html('');

        autoComplete = true;
    });
}

/**
 * Auto Complete - Kelurahan
 */
$("#formCreateUpdateKarangTaruna #alamat_kelurahan").autoComplete({
    resolver: 'ajax',
    noResultsText:'No results',
    events: {
        search: function (qry, callback) {
            $.ajax(
                {
                    url: `${BASE_URL}/api/v1/autocomplete/region`,
                    data: { 'name': qry, 'type': 'kelurahan'},
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

$('#formCreateUpdateKarangTaruna #alamat_kelurahan').on('input', function () {
    $('#formCreateUpdateKarangTaruna #alamat_kelurahan_hide').val('')
});

$('#formCreateUpdateKarangTaruna #alamat_kelurahan').on('autocomplete.select', function (evt, item) {
    $('#formCreateUpdateKarangTaruna #alamat_kelurahan_hide').val(item.text)
    $(`#formCreateUpdateKarangTaruna #alamat_kelurahan_hide-error`).html('');

    autoComplete = true;
});

/**
 * Auto Complete - Kecamatan
 */
$("#formCreateUpdateKarangTaruna #alamat_kecamatan").autoComplete({
    resolver: 'ajax',
    noResultsText:'No results',
    events: {
        search: function (qry, callback) {
            $.ajax(
                {
                    url: `${BASE_URL}/api/v1/autocomplete/region`,
                    data: { 'name': qry, 'type': 'kecamatan' },
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

$('#formCreateUpdateKarangTaruna #alamat_kecamatan').on('input', function () {
    $('#formCreateUpdateKarangTaruna #alamat_kecamatan_hide').val('')
});

$('#formCreateUpdateKarangTaruna #alamat_kecamatan').on('autocomplete.select', function (evt, item) {
    $('#formCreateUpdateKarangTaruna #alamat_kecamatan_hide').val(item.text)
    $(`#formCreateUpdateKarangTaruna #alamat_kecamatan_hide-error`).html('');

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
$("#formCreateUpdateKarangTaruna input").on('keydown', function () {
    $(this).removeClass('is-invalid');
    $(`#formCreateUpdateKarangTaruna #${$(this).attr('name')}-error`).html('');
})
$("#formCreateUpdateKarangTaruna select").on('change', function () {
    $(this).removeClass('is-invalid');
    $(`#formCreateUpdateKarangTaruna #${$(this).attr('name')}-error`).html('');
})

// kepengurusan
$("#formCreateUpdateKarangTaruna #kepengurusan_status").on('change', function () {
    if ($(this).val() == "sudah terbentuk") {
        $(`#formCreateUpdateKarangTaruna .col-kepengurusan`).show();
    }
    else{
        $(`#formCreateUpdateKarangTaruna .col-kepengurusan`).hide();
    }
})
$("#formCreateUpdateKarangTaruna #kepengurusan_status").change()

// form submit
$('#formCreateUpdateKarangTaruna').validate({
    rules: {
        site_id: {
            required: true,
        },
        year: {
            required: true
        },
        status: {
            required: true,
        },
        nama: {
            required: true,
        },
        nama_ketua: {
            required: true,
        },
        telepon: {
            required: true,
        },
        keaktifan_status: {
            required: true,
        },
        program_unggulan: {
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
        alamat_kelurahan_hide: {
            required: true,
        },
        alamat_kecamatan_hide: {
            required: true,
        },
        kepengurusan_status: {
            required: true,
        },
        kepengurusan_sk_tgl: {
            required: true,
        },
        kepengurusan_periode_tahun: {
            required: true,
        },
        kepengurusan_jumlah: {
            required: true,
        },
        kepengurusan_pejabat: {
            required: true,
        },
    },
    messages: {
        site_id: {
            required: "Wilayah harus diisi",
        },
        year: {
            required: "Data tahun harus disi"
        },
        status: {
            required: "Status harus diisi",
        },
        nama: {
            required: "Nama harus diisi",
        },
        nama_ketua: {
            required: "Nama Ketua harus diisi",
        },
        telepon: {
            required: "Telepon harus diisi",
        },
        keaktifan_status: {
            required: "Status Keaktifan harus diisi",
        },
        program_unggulan: {
            required: "Program Unggulan harus diisi",
        },
        alamat_jalan: {
            required: "Alamat Jalan harus diisi",
        },
        alamat_rt: {
            required: "Alamat RT harus diisi",
        },
        alamat_rw: {
            required: "Alamat RW harus diisi",
        },
        alamat_kelurahan_hide: {
            required: "Alamat Kelurahan harus diisi",
        },
        alamat_kecamatan_hide: {
            required: "Alamat Kecamatan harus diisi",
        },
        kepengurusan_status: {
            required: "Status Kepengurusan harus diisi",
        },
        kepengurusan_sk_tgl: {
            required: "Tanggal SK harus diisi",
        },
        kepengurusan_periode_tahun: {
            required: "Periode Tahun harus diisi",
        },
        kepengurusan_jumlah: {
            required: "Jumlah Kepengurusan harus diisi",
        },
        kepengurusan_pejabat: {
            required: "Pejabat Kepengurusan harus diisi",
        },
    },
    errorElement: 'span',
    errorPlacement: function (error, element) {
        error.addClass('invalid-feedback');
        element.closest('.form-group').append(error);
    },
    highlight: function (element, errorClass, validClass) {
        if ($(element).attr('name') == "site_id") {
            $('#formCreateUpdateKarangTaruna #site').addClass('is-invalid');
        }
        if ($(element).attr('name') == "alamat_kelurahan_hide") {
            $('#formCreateUpdateKarangTaruna #alamat_kelurahan').addClass('is-invalid');
        }
        if ($(element).attr('name') == "alamat_kecamatan_hide") {
            $('#formCreateUpdateKarangTaruna #alamat_kecamatan').addClass('is-invalid');
        }
        else {
            $(element).addClass('is-invalid');
        }
    },
    unhighlight: function (element, errorClass, validClass) {
        if ($(element).attr('name') == "site_id") {
            $('#formCreateUpdateKarangTaruna #site').removeClass('is-invalid');
        }
        if ($(element).attr('name') == "alamat_kelurahan_hide") {
            $('#formCreateUpdateKarangTaruna #alamat_kelurahan').removeClass('is-invalid');
        }
        if ($(element).attr('name') == "alamat_kecamatan_hide") {
            $('#formCreateUpdateKarangTaruna #alamat_kecamatan').removeClass('is-invalid');
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
    if ($('#formCreateUpdateKarangTaruna').valid()) {
        let form  = new FormData(document.querySelector('#formCreateUpdateKarangTaruna'));
        let ktId = form.get('id');

        Swal.fire({
            title: 'ANDA YAKIN?',
            text: `pastikan data sudah benar`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'iya',
            preConfirm: () => {
                return $.ajax({
                    type: ktId ? "PUT": "POST",
                    url: `${BASE_URL}/api/v1/karang_taruna/${ktId ? 'update' : 'create'}`,
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
                        if (ktId == null) {
                            if (user_level_id == 1) {
                                $("#formCreateUpdateKarangTaruna input").val('');
                            }
                            else {
                                $("#formCreateUpdateKarangTaruna input").not("#site").val('');
                            }
                            $("#formCreateUpdateKarangTaruna select").val('').change();
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
                            $(`#formCreateUpdateKarangTaruna #${key}`).addClass('is-invalid');
                            $(`#formCreateUpdateKarangTaruna #${key}-error`).html(errors[key][0]);
                        }
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
function verifKarangTaruna(el, event, status, id) {
    Swal.fire({
        title: 'ANDA YAKIN?',
        text: `data tidak akan bisa diubah`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'iya',
        preConfirm: () => {
            return $.ajax({
                type: "PUT",
                url: `${BASE_URL}/api/v1/karang_taruna/verif?status=${status}&id=${id}`,
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
