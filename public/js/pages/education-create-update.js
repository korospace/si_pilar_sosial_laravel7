$(function () {
    //  clear error when keydown
    $("#formCreateUpdateEducation input").on('keydown', function () {
        $(this).removeClass('is-invalid');
        $(`#formCreateUpdateEducation #${$(this).attr('name')}-error`).html('');
    })

    // form submit
    $('#formCreateUpdateEducation')
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
                    required: "nama pendidikan harus diisi",
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

                let form   = new FormData(document.querySelector('#formCreateUpdateEducation'));
                let educationId = form.get('id');

                $.ajax({
                    type: educationId ? "PUT": "POST",
                    url: `${BASE_URL}/api/v1/education/${educationId ? 'update' : 'create'}`,
                    data: form,
                    cache: false,
                    processData:false,
                    contentType: false,
                    headers		: {
                        'token': $.cookie("jwt_token"),
                    },
                    success:function(data) {
                        hideLoadingSpinner();

                        showToast(`pendidikan berhasil <b>${educationId ? 'diedit' : 'ditambah'}..!</b>`,'success');

                        if (educationId == null) {
                            $("#formCreateUpdateEducation input").val('');
                        }
                    },
                    error:function(data) {
                        hideLoadingSpinner();

                        if (data.status == 400) {
                            let errors = data.responseJSON.data.errors;

                            for (const key in errors) {
                                $(`#formCreateUpdateEducation #${key}`).addClass('is-invalid');
                                $(`#formCreateUpdateEducation #${key}-error`).html(errors[key][0]);
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
