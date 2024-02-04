$(function () {

    /*
        LOGIN
    */
    // vaidate from
    $('#formLogin')
        .submit(function(e) {
            e.preventDefault();
        })
        .validate({
            rules: {
                email: {
                    required: true,
                },
                password: {
                    required: true,
                },
            },
            messages: {
                email: {
                    required: "masukan email terlebih dahulu",
                },
                password: {
                    required: "masukan password terlebih dahulu",
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
                $("#formLogin input").removeClass('is-invalid');

                let formLogin = new FormData(document.querySelector('#formLogin'));

                $.ajax({
                    type: "POST",
                    url: `${BASE_URL}/api/v1/login_cookie`,
                    data: formLogin,
                    cache: false,
                    processData:false,
                    contentType: false,
                    success:function(data) {
                        hideLoadingSpinner();

                        showToast("login <b>berhasil..!</b>",'success');

                        setTimeout(() => {
                            window.location.href = `${BASE_URL}/dashboard`;
                        }, 600);
                    },
                    error:function(data) {
                        hideLoadingSpinner();

                        if (data.status == 401) {
                            $("#formLogin input").addClass('is-invalid');
                            showToast("<b>username</b> atau <b>password</b> tidak valid",'warning');
                        }
                        else if (data.status >= 500) {
                            showToast('kesalahan pada <b>server</b>','danger');
                        }
                    }
                });
            }
        });

    /*
        FORGOT PASSWORD
    */
    // clear input
    $("a[data-target='#modal-forgot-pass']").on('click', function () {
        $("#formForgotPass input").val('');
        $("#formForgotPass input").removeClass('is-invalid');
        $("#formForgotPass span.invalid-feedback").html('');
    })

    //  clear error when keydown
    $("#formForgotPass input").on('keydown', function () {
        $(this).removeClass('is-invalid');
        $(`#formForgotPass #${$(this).attr('name')}-error`).html('');
    })

    // vaidate from
    $('#formForgotPass')
        .submit(function(e) {
            e.preventDefault();
        })
        .validate({
            rules: {
                email: {
                    required: true,
                },
            },
            messages: {
                email: {
                    required: "masukan email terlebih dahulu",
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
                $("#formForgotPass input").removeClass('is-invalid');

                let form = new FormData(document.querySelector('#formForgotPass'));

                $.ajax({
                    type: "POST",
                    url: `${BASE_URL}/api/v1/forgot_pass`,
                    data: form,
                    cache: false,
                    processData:false,
                    contentType: false,
                    success:function(data) {
                        hideLoadingSpinner();
                        $("#formForgotPass input").val('');
                        $("#modal-forgot-pass").modal('hide');
                        showToast("password baru berhasil <b>dikirim..!</b>",'success');
                    },
                    error:function(data) {
                        hideLoadingSpinner();

                        if (data.status == 400) {
                            let errors = data.responseJSON.data.errors;

                            for (const key in errors) {
                                $(`#formForgotPass #${key}`).addClass('is-invalid');
                                $(`#formForgotPass #${key}-error`).html(errors[key][0]);
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
