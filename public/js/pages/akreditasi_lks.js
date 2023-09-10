$token = $.cookie("jwt_token");

/**
 * 1. Initial Akreditasi LKS Table
 */
function initialDataTableAkreditasiLks(params) {
    $("#tableAkreditasiLks").DataTable({
        "bDestroy": true,
        "serverSide": true,
        "processing": true,
        "responsive": true,
        "autoWidth": false,
        "pageLength": 10,
        "order": [[0, 'asc']],
        "ajax": {
            "url": `${BASE_URL}/api/v1/akreditasi_lks/datatable`,
            "beforeSend": function(xhr){
                xhr.setRequestHeader('token', $token);
            }
        },
        "columns": [
            { data: 'no', width: '10%' },
            { data: 'name' },
            { data: 'action', width: '15%' },
        ],
        "columnDefs": [
            {
                "targets": [0,1,2],
                "className": "align-middle text-center",
            },
        ],
    }).buttons().container().appendTo('#tableAkreditasiLks_wrapper .col-md-6:eq(0)');
}

initialDataTableAkreditasiLks();

/**
 * 2. Delete Akreditasi LKS
 */
function deleteAkreditasiLks(el, event, id) {
    event.preventDefault();

    Swal.fire({
        title: 'ANDA YAKIN?',
        text: `data akan terhapus permanen`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'iya',
        preConfirm: () => {
            return $.ajax({
                type: "DELETE",
                url: `${BASE_URL}/api/v1/akreditasi_lks/delete/${id}`,
                headers	: {
                    'token': $.cookie("jwt_token"),
                },
            }).then(response => {
                initialDataTableAkreditasiLks();

                Swal.fire({
                  title: 'Sukses!',
                  text: 'Data berhasil dihapus.',
                  icon: 'success'
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
