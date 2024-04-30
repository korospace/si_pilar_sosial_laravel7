// Fungsi untuk mengisi data detail artikel
function fillArticleData(data) {
    $('#blog-share').removeClass('d-none');
    $('#blog-date').removeClass('skeleton');
    $('#blog-date').html(`<i class="fa fa-calendar text-secondary text-xxs mr-2"></i>${data.public_release_date}`);
    $('#blog-penulis').removeClass('skeleton');
    $('#blog-content').html(data.body);

    let docHref = window.location.href;

    $('.share-whatsapp').attr('href', `https://api.whatsapp.com/send?text=${data.title} ${docHref}`);
}

// Mengambil detail artikel
$.ajax({
    url: `${BASE_URL}/api/v1/articles_detail/${SLUG}`,
    method: 'GET',
    success: function (res) {
        let response = res.data;
        fillArticleData(response);
    },
    error: function (err) {
        if (err.status == 500) {
            showToast(`<strong>Ups...</strong> terjadi kesalahan pada server, silahkan refresh halaman.`,'danger');
        }
    }
});

// Mengisi item terkait
$.ajax({
    url: `${BASE_URL}/api/v1/articles_recomendation/${SLUG}`,
    method: 'GET',
    success: function (res) {
        let elBerita = '';
        let allBerita = res.data;

        allBerita.forEach(b => {
            elBerita += `<a id="single-post" href="${BASE_URL}/berita/${b.slug}" class="col-12 col-sm-6 col-lg-12 mb-4">
                <div class="position-relative">
                    <img src="${BASE_URL}/images/default-thumbnail.webp" alt="thumbnail" class="w-100 position-relative" style="z-index: 1;opacity:0;">
                    <img src="${b.public_thumbnail}" alt="thumbnail" class="w-100 h-100 position-absolute img-thumbnail" style="z-index: 10;left:0;">
                </div>
                <div class="content mt-3">
                    <h5 class="text-dark text-capitalize" style="opacity: 0.8;display: -webkit-box;-webkit-line-clamp: 2;-webkit-box-orient: vertical;overflow: hidden;text-overflow: ellipsis;">
                        ${b.title}
                    </h5>
                    <h6 class="text-secondary mt-1" style="font-size: 13px;">
                        <i class="fa fa-calendar" aria-hidden="true" style="font-size: 10px;"></i> ${b.public_release_date}
                    </h6>
                </div>
            </a>`;
        });

        $('#blog-recommended').html(elBerita);
    },
    error: function (err) {
        $('#blog-recommended').html('');
    }
});
