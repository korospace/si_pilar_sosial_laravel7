$(function () {
    //  clear error when keydown
    $("#formCreateUpdateLayananLks input").on('keydown', function () {
        $(this).removeClass('is-invalid');
        $(`#formCreateUpdateLayananLks #${$(this).attr('name')}-error`).html('');
    })

    // form submit
    $('#formCreateUpdateLayananLks')
        .submit(function(e) {
            e.preventDefault();
        })
        .validate({
            rules: {
                name: {
                    required: true,
                },
            },
            messages: {
                name: {
                    required: "nama layanan harus diisi",
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

                let form   = new FormData(document.querySelector('#formCreateUpdateLayananLks'));
                let layananLksId = form.get('id');

                $.ajax({
                    type: layananLksId ? "PUT": "POST",
                    url: `${BASE_URL}/api/v1/layanan_lks/${layananLksId ? 'update' : 'create'}`,
                    data: form,
                    cache: false,
                    processData:false,
                    contentType: false,
                    headers		: {
                        'token': $.cookie("jwt_token"),
                    },
                    success:function(data) {
                        hideLoadingSpinner();

                        showToast(`layanan berhasil <b>${layananLksId ? 'diedit' : 'ditambah'}..!</b>`,'success');

                        if (layananLksId == null) {
                            $("#formCreateUpdateLayananLks input").val('');
                        }
                    },
                    error:function(data) {
                        hideLoadingSpinner();

                        if (data.status == 400) {
                            let errors = data.responseJSON.data.errors;

                            for (const key in errors) {
                                $(`#formCreateUpdateLayananLks #${key}`).addClass('is-invalid');
                                $(`#formCreateUpdateLayananLks #${key}-error`).html(errors[key][0]);
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
