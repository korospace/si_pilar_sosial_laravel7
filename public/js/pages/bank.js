$token = $.cookie("jwt_token");

/**
 * 1. Initial Bank Table
 */
function initialDataTableBank(params) {
    $("#tableBank").DataTable({
        "bDestroy": true,
        "serverSide": true,
        "processing": true,
        "pageLength": 10,
        "order": [[0, 'asc']],
        "ajax": {
            "url": `${BASE_URL}/api/v1/bank/datatable`,
            "beforeSend": function(xhr){
                xhr.setRequestHeader('token', $token);
            }
        },
        "columns": [
            { data: 'no', width: '10%' },
            { data: 'name' },
            { data: 'code', width: '15%' },
            { data: 'action', width: '15%' },
        ],
        "columnDefs": [
            {
                "targets": [0,2,3],
                "className": "text-center vertical-center",
            },
            {
                "targets": [1],
                "className": "text-left vertical-center",
            },
        ],
    }).buttons().container().appendTo('#tableBank_wrapper .col-md-6:eq(0)');
}

initialDataTableBank();

/**
 * 2. Delete Bank
 */
function deleteBank(el, event, id) {
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
                url: `${BASE_URL}/api/v1/bank/delete/${id}`,
                headers	: {
                    'token': $.cookie("jwt_token"),
                },
            }).then(response => {
                initialDataTableBank();

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
