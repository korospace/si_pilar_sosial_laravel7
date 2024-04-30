$(function () {
    //  clear error when keydown
    $("#formCreateUpdateAkreditasiLks input").on('keydown', function () {
        $(this).removeClass('is-invalid');
        $(`#formCreateUpdateAkreditasiLks #${$(this).attr('name')}-error`).html('');
    })

    // form submit
    $('#formCreateUpdateAkreditasiLks')
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
                    required: "nama akreditasi harus diisi",
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

                let form   = new FormData(document.querySelector('#formCreateUpdateAkreditasiLks'));
                let akreditasiLksId = form.get('id');

                $.ajax({
                    type: akreditasiLksId ? "PUT": "POST",
                    url: `${BASE_URL}/api/v1/akreditasi_lks/${akreditasiLksId ? 'update' : 'create'}`,
                    data: form,
                    cache: false,
                    processData:false,
                    contentType: false,
                    headers		: {
                        'token': $.cookie("jwt_token"),
                    },
                    success:function(data) {
                        hideLoadingSpinner();

                        showToast(`akreditasi berhasil <b>${akreditasiLksId ? 'diedit' : 'ditambah'}..!</b>`,'success');

                        if (akreditasiLksId == null) {
                            $("#formCreateUpdateAkreditasiLks input").val('');
                        }
                    },
                    error:function(data) {
                        hideLoadingSpinner();

                        if (data.status == 400) {
                            let errors = data.responseJSON.data.errors;

                            for (const key in errors) {
                                $(`#formCreateUpdateAkreditasiLks #${key}`).addClass('is-invalid');
                                $(`#formCreateUpdateAkreditasiLks #${key}-error`).html(errors[key][0]);
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
