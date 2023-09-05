/**
 * Disable All Input
 */
if ($("#formCreateUpdateLks #id").val() != null && $("#formCreateUpdateLks #level_id").val() != 1) {
    $('#formCreateUpdateLks input, #formCreateUpdateLks select').prop('disabled', true);
}

/**
 * Date Picker - initialization
 */
moment.locale('id');
$("#formCreateUpdateLks .tgl").daterangepicker({
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

if ($("#formCreateUpdateLks #id").val() == null) {
    $("#formCreateUpdateLks .tgl").val('');
}

/**
 * Auto Complete - Site
 */
let autoComplete = false;

$("#formCreateUpdateLks #site").autoComplete({
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

$('#formCreateUpdateLks #site').on('input', function () {
    $('#formCreateUpdateLks #site_id').val('')
    region_id = $("#formCreateUpdateLks #region_id").val()
});

$('#formCreateUpdateLks #site').on('autocomplete.select', function (evt, item) {
    $('#formCreateUpdateLks #site_id').val(item.id)
    region_id = item.region_id

    $(`#formCreateUpdateTksk #site_id-error`).html('');

    autoComplete = true;
});

/**
 * Auto Complete - Kelurahan
 */
$("#formCreateUpdateLks #alamat_kelurahan").autoComplete({
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

$('#formCreateUpdateLks #alamat_kelurahan').on('input', function () {
    $('#formCreateUpdateLks #alamat_kelurahan_hide').val('')
});

$('#formCreateUpdateLks #alamat_kelurahan').on('autocomplete.select', function (evt, item) {
    $('#formCreateUpdateLks #alamat_kelurahan_hide').val(item.text)
    $(`#formCreateUpdateLks #alamat_kelurahan_hide-error`).html('');

    autoComplete = true;
});

/**
 * Auto Complete - Kecamatan
 */
$("#formCreateUpdateLks #alamat_kecamatan").autoComplete({
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

$('#formCreateUpdateLks #alamat_kecamatan').on('input', function () {
    $('#formCreateUpdateLks #alamat_kecamatan_hide').val('')
});

$('#formCreateUpdateLks #alamat_kecamatan').on('autocomplete.select', function (evt, item) {
    $('#formCreateUpdateLks #alamat_kecamatan_hide').val(item.text)
    $(`#formCreateUpdateLks #alamat_kecamatan_hide-error`).html('');

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
$("#formCreateUpdateLks input").on('keydown', function () {
    $(this).removeClass('is-invalid');
    $(`#formCreateUpdateLks #${$(this).attr('name')}-error`).html('');
})
$("#formCreateUpdateLks select").on('change', function () {
    $(this).removeClass('is-invalid');
    $(`#formCreateUpdateLks #${$(this).attr('name')}-error`).html('');
})

// form submit
$('#formCreateUpdateLks').validate({
    rules: {
        site_id: {
            required: true
        },
        status: {
            required: true
        },
        nama: {
            required: true
        },
        nama_ketua: {
            required: true
        },
        no_telp_yayasan: {
            required: true
        },
        npwp: {
            required: true
        },
        jenis_layanan: {
            required: true
        },
        akreditasi: {
            required: true
        },
        akreditasi_tgl: {
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
        alamat_kecamatan_hide: {
            required: true
        },
        akta_pendirian_nomor: {
            required: true
        },
        akta_pendirian_tgl: {
            required: true
        },
        sk_hukumham_pendirian_nomor: {
            required: true
        },
        sk_hukumham_pendirian_tgl: {
            required: true
        },
        akta_perubahan_nomor: {
            required: true
        },
        akta_perubahan_tgl: {
            required: true
        },
        sk_hukumham_perubahan_nomor: {
            required: true
        },
        sk_hukumham_perubahan_tgl: {
            required: true
        },
        sk_domisili_yayasan_nomor: {
            required: true
        },
        sk_domisili_yayasan_tgl_terbit: {
            required: true
        },
        sk_domisili_yayasan_masa_berlaku: {
            required: true
        },
        tanda_daftar_yayasan_nomor: {
            required: true
        },
        tanda_daftar_yayasan_tgl_terbit: {
            required: true
        },
        tanda_daftar_yayasan_masa_berlaku: {
            required: true
        },
        izin_kegiatan_yayasan_nomor: {
            required: true
        },
        izin_kegiatan_yayasan_tgl_terbit: {
            required: true
        },
        izin_kegiatan_yayasan_masa_berlaku: {
            required: true
        },
        induk_berusaha_nomor: {
            required: true
        },
        induk_berusaha_tgl: {
            required: true
        }
    },
    messages: {
        site_id: {
            required: "Field Site harus diisi."
        },
        status: {
            required: "Field Status harus diisi."
        },
        nama: {
            required: "Field Nama harus diisi."
        },
        nama_ketua: {
            required: "Field Ketua harus diisi."
        },
        no_telp_yayasan: {
            required: "Field Telepon harus diisi."
        },
        npwp: {
            required: "Field NPWP harus diisi."
        },
        jenis_layanan: {
            required: "Field Jenis Layanan harus diisi."
        },
        akreditasi: {
            required: "Field Akreditasi harus diisi."
        },
        akreditasi_tgl: {
            required: "harus diisi."
        },
        alamat_jalan: {
            required: "Field Jalan harus diisi."
        },
        alamat_rt: {
            required: "Field RT harus diisi."
        },
        alamat_rw: {
            required: "Field RW harus diisi."
        },
        alamat_kelurahan_hide: {
            required: "Field Kelurahan harus diisi."
        },
        alamat_kecamatan_hide: {
            required: "Field Kecamatan harus diisi."
        },
        akta_pendirian_nomor: {
            required: "harus diisi."
        },
        akta_pendirian_tgl: {
            required: "harus diisi."
        },
        sk_hukumham_pendirian_nomor: {
            required: "harus diisi."
        },
        sk_hukumham_pendirian_tgl: {
            required: "harus diisi."
        },
        akta_perubahan_nomor: {
            required: "harus diisi."
        },
        akta_perubahan_tgl: {
            required: "harus diisi."
        },
        sk_hukumham_perubahan_nomor: {
            required: "harus diisi."
        },
        sk_hukumham_perubahan_tgl: {
            required: "harus diisi."
        },
        sk_domisili_yayasan_nomor: {
            required: "Field Nomor harus diisi."
        },
        sk_domisili_yayasan_tgl_terbit: {
            required: "Field Tanggal harus diisi."
        },
        sk_domisili_yayasan_masa_berlaku: {
            required: "Field Masa Berlaku harus diisi."
        },
        tanda_daftar_yayasan_nomor: {
            required: "Field Nomor diisi."
        },
        tanda_daftar_yayasan_tgl_terbit: {
            required: "Field Tanggal diisi."
        },
        tanda_daftar_yayasan_masa_berlaku: {
            required: "Field Masa Berlaku diisi."
        },
        izin_kegiatan_yayasan_nomor: {
            required: "Field Nomor diisi."
        },
        izin_kegiatan_yayasan_tgl_terbit: {
            required: "Field Tanggal diisi."
        },
        izin_kegiatan_yayasan_masa_berlaku: {
            required: "Field Masa Berlaku diisi."
        },
        induk_berusaha_nomor: {
            required: "Field Nomor diisi."
        },
        induk_berusaha_tgl: {
            required: "Field Tanggal diisi."
        }
    },
    errorElement: 'span',
    errorPlacement: function (error, element) {
        error.addClass('invalid-feedback');
        element.closest('.form-group').append(error);
    },
    highlight: function (element, errorClass, validClass) {
        if ($(element).attr('name') == "site_id") {
            $('#formCreateUpdateLks #site').addClass('is-invalid');
        }
        if ($(element).attr('name') == "alamat_kelurahan_hide") {
            $('#formCreateUpdateLks #alamat_kelurahan').addClass('is-invalid');
        }
        if ($(element).attr('name') == "alamat_kecamatan_hide") {
            $('#formCreateUpdateLks #alamat_kecamatan').addClass('is-invalid');
        }
        else {
            $(element).addClass('is-invalid');
        }
    },
    unhighlight: function (element, errorClass, validClass) {
        if ($(element).attr('name') == "site_id") {
            $('#formCreateUpdateLks #site').removeClass('is-invalid');
        }
        if ($(element).attr('name') == "alamat_kelurahan_hide") {
            $('#formCreateUpdateLks #alamat_kelurahan').removeClass('is-invalid');
        }
        if ($(element).attr('name') == "alamat_kecamatan_hide") {
            $('#formCreateUpdateLks #alamat_kecamatan').removeClass('is-invalid');
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
    if ($('#formCreateUpdateLks').valid()) {
        let form  = new FormData(document.querySelector('#formCreateUpdateLks'));
        let lksId = form.get('id');

        Swal.fire({
            title: 'ANDA YAKIN?',
            text: `pastikan data sudah benar`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'iya',
            preConfirm: () => {
                return $.ajax({
                    type: lksId ? "PUT": "POST",
                    url: `${BASE_URL}/api/v1/lks/${lksId ? 'update' : 'create'}`,
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
                        if (lksId == null) {
                            $("#formCreateUpdateLks input").val('');
                            $("#formCreateUpdateLks select").val('').change();
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
                            $(`#formCreateUpdateLks #${key}`).addClass('is-invalid');
                            $(`#formCreateUpdateLks #${key}-error`).html(errors[key][0]);
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
 * Verifikasi LKS
 */
function verifLKS(el, event, status, id) {
    Swal.fire({
        title: 'ANDA YAKIN?',
        text: `data tidak akan bisa diubah`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'iya',
        preConfirm: () => {
            return $.ajax({
                type: "PUT",
                url: `${BASE_URL}/api/v1/lks/verif?status=${status}&id=${id}`,
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
