$token = $.cookie("jwt_token");

/**
 * 1. Initial Article Table
 */
function initialDataTableArticle(params) {
    $("#tableArticle").DataTable({
        "bDestroy": true,
        "serverSide": true,
        "processing": true,
        "responsive": true,
        "autoWidth": false,
        "pageLength": 10,
        "order": [[0, 'asc']],
        "ajax": {
            "url": `${BASE_URL}/api/v1/crudarticle/datatable`,
            "beforeSend": function(xhr){
                xhr.setRequestHeader('token', $token);
            }
        },
        "columns": [
            { data: 'no', width: '10%' },
            { data: 'thumbnail', width: '20%' },
            { data: 'title',},
            { data: 'status', width: '10%' },
            { data: 'creator.name', width: '10%' },
            { data: 'action', width: '10%' },
        ],
        "columnDefs": [
            {
                "targets": [1,3,4,5],
                "className": "text-center",
            },
            {
                "targets": [0],
                "className": "align-middle text-center",
            },
        ],
    }).buttons().container().appendTo('#tableArticle_wrapper .col-md-6:eq(0)');
}

initialDataTableArticle();

/**
 * 2. Delete Article
 */
function deleteArticle(el, event, id) {
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
                url: `${BASE_URL}/api/v1/crudarticle/delete/${id}`,
                headers	: {
                    'token': $.cookie("jwt_token"),
                },
            }).then(response => {
                initialDataTableArticle();

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
