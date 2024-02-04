/**
 * Disable All Input
 */
if ($("#formCreateUpdateTksk #id").val() != null && $("#formCreateUpdateTksk #level_id").val() != 1) {
    $('#formCreateUpdateTksk input, #formCreateUpdateTksk select').prop('disabled', true);
}

/**
 * Age Counter
 */
function calculateAge(dateString) {
    const currentDate = moment();
    const providedDate = moment(dateString, 'DD MMMM YYYY'); // Menggunakan format bahasa Indonesia

    const yearsDiff = currentDate.diff(providedDate, 'years');
    const monthsDiff = currentDate.diff(providedDate, 'months') % 12;
    const daysDiff = currentDate.diff(providedDate, 'days') % 30; // Perkiraan hari dalam sebulan

    $("#formCreateUpdateTksk #usia").val(yearsDiff);
}

/**
 * Date Picker - Tanggal Lahir
 */
moment.locale('id');
$("#formCreateUpdateTksk #tanggal_lahir").daterangepicker({
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

if ($("#formCreateUpdateTksk #id").val() == null) {
    $("#formCreateUpdateTksk #tanggal_lahir").val('');
}
else {
    calculateAge($("#formCreateUpdateTksk #tanggal_lahir").val());
}

$("#formCreateUpdateTksk #tanggal_lahir").on('change', function () {
    calculateAge($(this).val());
})

/**
 * Auto Complete - Site
 */
let autoComplete = false;
let region_id = $("#formCreateUpdateTksk #region_id").val();

$("#formCreateUpdateTksk #site").autoComplete({
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

$('#formCreateUpdateTksk #site').on('input', function () {
    $('#formCreateUpdateTksk #site_id').val('')
    region_id = $("#formCreateUpdateTksk #region_id").val()

    $('#formCreateUpdateTksk #tempat_tugas').val('')
    $('#formCreateUpdateTksk #tempat_tugas_hide').val('')
});

$('#formCreateUpdateTksk #site').on('autocomplete.select', function (evt, item) {
    $('#formCreateUpdateTksk #site_id').val(item.id)
    region_id = item.region_id

    $(this).removeClass('is-invalid');
    $(`#formCreateUpdateTksk #site_id-error`).html('');

    autoComplete = true;
});

/**
 * Auto Complete - Tempat Tugas
 */
$("#formCreateUpdateTksk #tempat_tugas").autoComplete({
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

$('#formCreateUpdateTksk #tempat_tugas').on('input', function () {
    $('#formCreateUpdateTksk #tempat_tugas_hide').val('')
});

$('#formCreateUpdateTksk #tempat_tugas').on('autocomplete.select', function (evt, item) {
    $('#formCreateUpdateTksk #tempat_tugas_hide').val(item.text)

    autoComplete = true;
});

/**
 * Auto Complete - Kelurahan
 */
$("#formCreateUpdateTksk #alamat_kelurahan").autoComplete({
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

$('#formCreateUpdateTksk #alamat_kelurahan').on('input', function () {
    $('#formCreateUpdateTksk #alamat_kelurahan_hide').val('')
});

$('#formCreateUpdateTksk #alamat_kelurahan').on('autocomplete.select', function (evt, item) {
    $('#formCreateUpdateTksk #alamat_kelurahan_hide').val(item.text)

    autoComplete = true;
});

/**
 * Auto Complete - Bank
 */
$("#formCreateUpdateTksk #nama_bank").autoComplete({
    resolver: 'ajax',
    noResultsText:'No results',
    events: {
        search: function (qry, callback) {
            $.ajax(
                {
                    url: `${BASE_URL}/api/v1/autocomplete/bank`,
                    data: { 'name': qry, },
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

$('#formCreateUpdateTksk #nama_bank').on('input', function () {
    $('#formCreateUpdateTksk #nama_bank_hide').val('')
});

$('#formCreateUpdateTksk #nama_bank').on('autocomplete.select', function (evt, item) {
    $('#formCreateUpdateTksk #nama_bank_hide').val(item.text)

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
 * Form Tksk Logic
 */

//  clear error when keydown
$("#formCreateUpdateTksk input").on('keydown', function () {
    $(this).removeClass('is-invalid');
    $(`#formCreateUpdateTksk #${$(this).attr('name')}-error`).html('');
})
$("#formCreateUpdateTksk select").on('change', function () {
    $(this).removeClass('is-invalid');
    $(`#formCreateUpdateTksk #${$(this).attr('name')}-error`).html('');
})

// form submit
$('#formCreateUpdateTksk').validate({
    rules: {
        year: {
            required: true
        },
        no_induk_anggota: {
            required: true
        },
        tempat_tugas_hide: {
            required: true
        },
        nama: {
            required: true
        },
        nama_ibu_kandung: {
            required: true
        },
        nik: {
            required: true
        },
        telepon: {
            required: true
        },
        tempat_lahir: {
            required: true
        },
        tanggal_lahir: {
            required: true
        },
        pendidikan_terakhir: {
            required: true
        },
        jenis_kelamin: {
            required: true
        },
        no_kartu_registrasi: {
            required: true
        },
        alamat_jalan: {
            required: true
        },
        alamat_rt: {
            required: true
        },
        alamat_rw: {
            required: true
        },
        alamat_kelurahan_hide: {
            required: true
        },
        nama_di_rekening: {
            required: true
        },
        no_rekening: {
            required: true
        },
        nama_bank_hide: {
            required: true
        },
        tahun_pengangkatan_pertama: {
            required: true
        },
        nosk_pengangkatan_pertama: {
            required: true
        },
        pejabat_pengangkatan_pertama: {
            required: true
        },
        tahun_pengangkatan_terakhir: {
            required: true
        },
        nosk_pengangkatan_terakhir: {
            required: true
        },
        pejabat_pengangkatan_terakhir: {
            required: true
        },
        site_id: {
            required: true
        },
        status: {
            required: true
        }
    },
    messages: {
        year: {
            required: "Data tahun harus disi"
        },
        no_induk_anggota: {
            required: "No. Induk Anggota harus diisi"
        },
        tempat_tugas_hide: {
            required: "wilayah tidak valid"
        },
        nama: {
            required: "Nama harus diisi"
        },
        nama_ibu_kandung: {
            required: "Nama Ibu Kandung harus diisi"
        },
        nik: {
            required: "NIK harus diisi"
        },
        telepon: {
            required: "No. Telepon harus diisi"
        },
        tempat_lahir: {
            required: "Tempat Lahir harus diisi"
        },
        tanggal_lahir: {
            required: "Tanggal Lahir harus diisi"
        },
        pendidikan_terakhir: {
            required: "Pendidikan Terakhir harus diisi"
        },
        jenis_kelamin: {
            required: "Jenis Kelamin harus diisi"
        },
        no_kartu_registrasi: {
            required: "No. Kartu Registrasi harus diisi"
        },
        alamat_jalan: {
            required: "Alamat Jalan harus diisi"
        },
        alamat_rt: {
            required: "Alamat RT harus diisi"
        },
        alamat_rw: {
            required: "Alamat RW harus diisi"
        },
        alamat_kelurahan_hide: {
            required: "Kelurahan tidak valid"
        },
        nama_di_rekening: {
            required: "Nama Di Rekening harus diisi"
        },
        no_rekening: {
            required: "No. Rekening harus diisi"
        },
        nama_bank_hide: {
            required: "Nama Bank tidak valid"
        },
        tahun_pengangkatan_pertama: {
            required: "Tahun Pengangkatan Pertama harus diisi"
        },
        nosk_pengangkatan_pertama: {
            required: "No. SK Pengangkatan Pertama harus diisi"
        },
        pejabat_pengangkatan_pertama: {
            required: "Pejabat Pengangkatan Pertama harus diisi"
        },
        tahun_pengangkatan_terakhir: {
            required: "Tahun Pengangkatan Terakhir harus diisi"
        },
        nosk_pengangkatan_terakhir: {
            required: "No. SK Pengangkatan Terakhir harus diisi"
        },
        pejabat_pengangkatan_terakhir: {
            required: "Pejabat Pengangkatan Terakhir harus diisi"
        },
        site_id: {
            required: "Site harus diisi"
        },
        status: {
            required: "Status harus diisi"
        }
    },
    errorElement: 'span',
    errorPlacement: function (error, element) {
        error.addClass('invalid-feedback');
        element.closest('.form-group').append(error);
    },
    highlight: function (element, errorClass, validClass) {
        if ($(element).attr('name') == "site_id") {
            $('#formCreateUpdateTksk #site').addClass('is-invalid');
        }
        else if ($(element).attr('name') == "tempat_tugas_hide") {
            $('#formCreateUpdateTksk #tempat_tugas').addClass('is-invalid');
        }
        else if ($(element).attr('name') == "alamat_kelurahan_hide") {
            $('#formCreateUpdateTksk #alamat_kelurahan').addClass('is-invalid');
        }
        else if ($(element).attr('name') == "nama_bank_hide") {
            $('#formCreateUpdateTksk #nama_bank').addClass('is-invalid');
        }
        else {
            $(element).addClass('is-invalid');
        }
    },
    unhighlight: function (element, errorClass, validClass) {
        if ($(element).attr('name') == "site_id") {
            $('#formCreateUpdateTksk #site').removeClass('is-invalid');
        }
        else if ($(element).attr('name') == "tempat_tugas_hide") {
            $('#formCreateUpdateTksk #tempat_tugas').removeClass('is-invalid');
        }
        else if ($(element).attr('name') == "alamat_kelurahan_hide") {
            $('#formCreateUpdateTksk #alamat_kelurahan').removeClass('is-invalid');
        }
        else if ($(element).attr('name') == "nama_bank_hide") {
            $('#formCreateUpdateTksk #nama_bank').removeClass('is-invalid');
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
    if ($('#formCreateUpdateTksk').valid()) {
        let form   = new FormData(document.querySelector('#formCreateUpdateTksk'));
        let userId = form.get('id');

        Swal.fire({
            title: 'ANDA YAKIN?',
            text: `pastikan data sudah benar`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'iya',
            preConfirm: () => {
                return $.ajax({
                    type: userId ? "PUT": "POST",
                    url: `${BASE_URL}/api/v1/tksk/${userId ? 'update' : 'create'}`,
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
                        if (userId == null) {
                            $("#formCreateUpdateTksk input").val('');
                            $("#formCreateUpdateTksk select").val('').change();
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
                            $(`#formCreateUpdateTksk #${key}`).addClass('is-invalid');
                            $(`#formCreateUpdateTksk #${key}-error`).html(errors[key][0]);
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
 * Verifikasi TKSK
 */
function verifTKSK(el, event, status, id) {
    Swal.fire({
        title: 'ANDA YAKIN?',
        text: `data tidak akan bisa diubah`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'iya',
        preConfirm: () => {
            return $.ajax({
                type: "PUT",
                url: `${BASE_URL}/api/v1/tksk/verif?status=${status}&id=${id}`,
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
