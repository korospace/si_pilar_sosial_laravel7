$token = $.cookie("jwt_token");

/**
 * 1. Initial User Table
 */
function initialDataTableUser(params) {
    $("#tableUser")
        .DataTable({
            bDestroy: true,
            serverSide: true,
            processing: true,
            responsive: true,
            autoWidth: false,
            pageLength: 10,
            order: [[0, "asc"]],
            ajax: {
                url: `${BASE_URL}/api/v1/user/datatable`,
                beforeSend: function (xhr) {
                    xhr.setRequestHeader("token", $token);
                },
            },
            columns: [
                { data: "no", width: "10%" },
                { data: "email" },
                { data: "name" },
                { data: "user_level.name" },
                { data: "site" },
                { data: "action", width: "15%" },
            ],
            columnDefs: [
                {
                    targets: [0, 3, 4, 5],
                    className: "align-middle text-center",
                },
                {
                    targets: [1, 2],
                    className: "align-middle",
                },
            ],
        })
        .buttons()
        .container()
        .appendTo("#tableUser_wrapper .col-md-6:eq(0)");
}

initialDataTableUser();

/**
 * 2. Delete User
 */
function deleteUser(el, event, id) {
    event.preventDefault();

    Swal.fire({
        title: "ANDA YAKIN?",
        text: `data akan terhapus permanen`,
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "iya",
        preConfirm: () => {
            return $.ajax({
                type: "DELETE",
                url: `${BASE_URL}/api/v1/user/delete/${id}`,
                headers: {
                    token: $.cookie("jwt_token"),
                },
            })
                .then((response) => {
                    initialDataTableUser();

                    Swal.fire({
                        title: "Sukses!",
                        text: "Data berhasil dihapus.",
                        icon: "success",
                    });
                })
                .catch((data) => {
                    if (data.status >= 500) {
                        Swal.showValidationMessage(
                            `terjadi kesalahan pada server!`
                        );
                    } else {
                        Swal.showValidationMessage(data.responseJSON.message);
                    }
                });
        },
        allowOutsideClick: () => !Swal.isLoading(),
    });
}
