// ------------------------------------------
//              Status On Change
// ------------------------------------------
$("#formCreateUpdateArticle #status").on('change', function () {

    if ($(this).val() == "release") {
        $(`#formCreateUpdateArticle .release_date_wraper`).removeClass('d-none');
    }
    else{
        $(`#formCreateUpdateArticle .release_date_wraper`).addClass('d-none');
    }
})

// ------------------------------------------
//              Datetime Picker
// ------------------------------------------
$('#formCreateUpdateArticle #release_date').datetimepicker({
    format:'d-m-Y H:i',
    step:5
});

// ------------------------------------------
//               Summernote
// ------------------------------------------
$('#formCreateUpdateArticle #body').summernote({
    toolbar: [
        ['style', ['style']],
        ['font', ['bold', 'underline', 'italic', 'clear']],
        ['fontname', ['fontname']],
        ['color', ['color']],
        ['para', ['ul', 'ol', 'paragraph']],
        ['table', ['table']],
        ['insert', ['link', 'picture']],
    ],
    minHeight: 600
})

// ------------------------------------------
//             Thumbnal Preview
// ------------------------------------------
let fileThumbnail    = "";
let defaultThumbnail = `${BASE_URL}/images/default-thumbnail.webp`;

const changeThumbPreview = (el) => {
    let imgPreview = "#thumbnail_preview";
    let imgType = el.files[0].type.split('/');

    // If file is not image
    if(!/image/.test(imgType[0])){
        showToast(`File yang anda upload <b>bukan gambar..!</b>`,'warning');

        el.value = "";
        return false;
    }
    // If image not in format
    else if(!["jpg","jpeg","png","webp"].includes(imgType[1])) {
        showToast(`<strong>Format gambar yang diperbolehkan -> jpg/jpeg/png/webp!</strong>`,'warning');

        el.value = "";
        return false;
    }
    else{
        const MAX_WIDTH  = 500;
        const MAX_HEIGHT = 308;
        const MIME_TYPE  = "image/webp";
        const QUALITY    = 0.9;
        const file       = el.files[0];
        const blobURL    = URL.createObjectURL(file);
        const img        = new Image();

        img.src    = blobURL;
        img.onload = function () {
            if(el.files[0].size < 2000000){
                fileThumbnail = el.files[0];
                document.querySelector(imgPreview).src = blobURL;
            }
            else{
                URL.revokeObjectURL(this.src);
                const [newWidth, newHeight] = calculateSize(img, MAX_WIDTH, MAX_HEIGHT);
                const canvas = document.createElement("canvas");
                canvas.width = newWidth;
                canvas.height = newHeight;
                const ctx = canvas.getContext("2d");
                ctx.drawImage(img, 0, 0, newWidth, newHeight);
                canvas.toBlob(
                    (blob) => {
                        // Handle the compressed image. es. upload or save in local state
                    },
                    MIME_TYPE,
                    QUALITY
                );

                document.querySelector(imgPreview).src = canvas.toDataURL();
                fileThumbnail = dataURLtoFile(canvas.toDataURL(),'thumbnail.webp');
            }
        }

        function calculateSize(img, maxWidth, maxHeight) {
            let width = img.width;
            let height = img.height;

            // calculate the width and height, constraining the proportions
            if (width > height) {
                if (width > maxWidth) {
                    height = Math.round((height * maxWidth) / width);
                    width = maxWidth;
                }
            } else {
                if (height > maxHeight) {
                    width = Math.round((width * maxHeight) / height);
                    height = maxHeight;
                }
            }
            return [width, height];
        }

        function dataURLtoFile(dataurl, filename) {

            var arr = dataurl.split(','),
                mime = arr[0].match(/:(.*?);/)[1],
                bstr = atob(arr[1]),
                n = bstr.length,
                u8arr = new Uint8Array(n);

            while(n--){
                u8arr[n] = bstr.charCodeAt(n);
            }

            return new File([u8arr], filename, {type:mime});
        }
    }
}

$('#formCreateUpdateArticle').validate({
    rules: {
        thumbnail: {
            required: true,
        },
        title: {
            required: true,
        },
        status: {
            required: true,
        },
        release_date: {
            required: true,
        },
    },
    messages: {
        thumbnail: {
            required: "harus diisi",
        },
        title: {
            required: "harus diisi",
        },
        status: {
            required: "harus diisi",
        },
        release_date: {
            required: "harus diisi",
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
    }
});

$(document).on('keydown', function(event) {
    if (event.keyCode === 13) {
        event.preventDefault();
        saveData()
    }
});

function saveData() {
    if ($('#formCreateUpdateArticle').valid() == false) {
        showToast(`input <b>belum lengkap..!</b>`,'warning');
    }
    else {
        let form      = new FormData(document.querySelector('#formCreateUpdateArticle'));
        let articleId = form.get('id');

        if (articleId == null) {
            form.set('thumbnail', fileThumbnail, fileThumbnail.name);
        }
        else {
            if (fileThumbnail) {
                form.set('newthumbnail', fileThumbnail, fileThumbnail.name);
            }
        }

        Swal.fire({
            title: 'ANDA YAKIN?',
            text: `pastikan data sudah benar`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'iya',
            preConfirm: () => {
                return $.ajax({
                    type: "POST",
                    url: `${BASE_URL}/api/v1/crudarticle/${articleId ? 'update' : 'create'}`,
                    data: form,
                    cache: false,
                    processData:false,
                    contentType: false,
                    headers		: {
                        'token': $.cookie("jwt_token"),
                    },
                }).then(response => {
                    Swal.fire({
                        title: 'Sukses!',
                        text: 'Article berhasil tersimpan.',
                        icon: 'success'
                    }).then((result) => {
                        if (articleId == null) {
                            $("#formCreateUpdateArticle input").val('');
                            $("#formCreateUpdateArticle select").val('').change();
                            $('#formCreateUpdateArticle #body').summernote('reset');
                            $("#formCreateUpdateArticle #thumbnail_preview").attr('src', defaultThumbnail);
                        }
                        else {
                            location.reload();
                        }
                    });
                }).catch(data => {
                    if (data.status == 400) {
                        showToast(`input <b>tidak valid..!</b>`,'warning');

                        Swal.close();
                        let errors = data.responseJSON.data.errors;

                        for (const key in errors) {
                            $(`#formCreateUpdateArticle #${key}`).addClass('is-invalid');
                            $(`#formCreateUpdateArticle #${key}-error`).html(errors[key][0]);
                        }
                    }
                    else if (data.status >= 500) {
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
}
