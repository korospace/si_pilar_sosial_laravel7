$(function () {
    //  clear error when keydown
    $("#formCreateUpdateBank input").on('keydown', function () {
        $(this).removeClass('is-invalid');
        $(`#formCreateUpdateBank #${$(this).attr('name')}-error`).html('');
    })

    // form submit
    $('#formCreateUpdateBank')
        .submit(function(e) {
            e.preventDefault();
        })
        .validate({
            rules: {
                name: {
                    required: true,
                },
                code: {
                    required: true,
                },
            },
            messages: {
                name: {
                    required: "nama bank harus diisi",
                },
                code: {
                    required: "code bank harus diisi",
                },
            },
            errorElement: 'span',
            errorPlacement: function (error, element) {
                error.addClass('invalid-feedback');
                element.closest('.form-group').append(error);
            },
            highlight: function (element, errorClass, validClass) {
                $(element).addClass('is-invalid');
            },
            unhighlight: function (element, errorClass, validClass) {
                $(element).removeClass('is-invalid');
            },
            submitHandler: function () {
                showLoadingSpinner();

                let form   = new FormData(document.querySelector('#formCreateUpdateBank'));
                let bankId = form.get('id');

                $.ajax({
                    type: bankId ? "PUT": "POST",
                    url: `${BASE_URL}/api/v1/bank/${bankId ? 'update' : 'create'}`,
                    data: form,
                    cache: false,
                    processData:false,
                    contentType: false,
                    headers		: {
                        'token': $.cookie("jwt_token"),
                    },
                    success:function(data) {
                        hideLoadingSpinner();

                        showToast(`bank berhasil <b>${bankId ? 'diedit' : 'ditambah'}..!</b>`,'success');

                        if (bankId == null) {
                            $("#formCreateUpdateBank input").val('');
                        }
                    },
                    error:function(data) {
                        hideLoadingSpinner();

                        if (data.status == 400) {
                            let errors = data.responseJSON.data.errors;

                            for (const key in errors) {
                                $(`#formCreateUpdateBank #${key}`).addClass('is-invalid');
                                $(`#formCreateUpdateBank #${key}-error`).html(errors[key][0]);
                            }
                        }
                        else if (data.status >= 500) {
                            showToast('kesalahan pada <b>server</b>','danger');
                        }
                    }
                });
            }
        });
});
