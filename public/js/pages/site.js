$token = $.cookie("jwt_token");

/**
 * 1. Initial Site Table
 */
function initialDataTableSite(params) {
    $("#tableSite").DataTable({
        "bDestroy": true,
        "serverSide": true,
        "processing": true,
        "pageLength": 10,
        "order": [[0, 'asc']],
        "ajax": {
            "url": `${BASE_URL}/api/v1/site/datatable`,
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
                "targets": [0,2],
                "className": "text-center",
            },
        ],
    }).buttons().container().appendTo('#tableSite_wrapper .col-md-6:eq(0)');
}

initialDataTableSite();

/**
 * 2. Delete Site
 */
function deleteSite(el, event, id) {
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
                url: `${BASE_URL}/api/v1/site/delete/${id}`,
                headers	: {
                    'token': $.cookie("jwt_token"),
                },
            }).then(response => {
                initialDataTableSite();

                Swal.fire({
                  title: 'Sukses!',
                  text: 'Data berhasil dihapus.',
                  icon: 'success'
                });
            }).catch(data => {
                console.log(data);
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
