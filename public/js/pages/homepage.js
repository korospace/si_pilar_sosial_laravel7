// --------------------------------------
//              Set Min Height
// --------------------------------------
function setMinHeightelement(className) {
    var maxLogoHeight = 0;
    $(className).each(function() {
        var logoHeight = $(this).outerHeight();
        if (logoHeight > maxLogoHeight) {
            maxLogoHeight = logoHeight;
        }
    });

    // Mengatur min-height untuk semua logo_wraper
    $(className).css("min-height", maxLogoHeight + "px");
}

setTimeout(() => {
    setMinHeightelement('#pilar_container .logo_wraper');
    setMinHeightelement('#pilar_container .title_wraper');
    setMinHeightelement('#pilar_container .desc_wraper');
}, 100);

// --------------------------------------
//            Get Pilar Counter
// --------------------------------------
function getPilarCounter() {
    $.ajax({
        type: "GET",
        url: `${BASE_URL}/api/v1/pilar_counter`,
        success:function(data) {
            let list = data.data;

            for (const key in list) {
                let tr   = "";
                let rows = list[key];

                rows.forEach(row => {
                    tr +=`<div class="d-flex pb-2">
                        <div style="flex: 1">${row.title}</div>
                        <div>${row.total}</div>
                    </div>`;
                });

                $(`#pilar_container #${key}.counter_wraper`).html(`
                    <div class="w-100" style="font-size: 12px;">
                        ${tr}
                    </div>
                `);
            }
        },
        error:function(data) {
            console.log(data);
        }
    });
}
getPilarCounter()

// --------------------------------------
//         Get Latest Articles
// --------------------------------------
function getLatestArticles() {
    $.ajax({
        type: "GET",
        url: `${BASE_URL}/api/v1/articles_latest`,
        success:function(data) {
            moment.locale('id');
            let cards = "";
            let rows  = data.data;

            rows.forEach(row => {
                cards += `
                    <div class="col-md-6 col-xl-3 mb-4 mb-xl-0">
                        <div class="card h-100 shadow" style="overflow: hidden;border-radius: 10px 10px 10px 10px">
                            <div style="background-image:url('${row.public_thumbnail}');background-position: center;background-size: cover;overflow: hidden;border-radius: 0 0 10px 10px;">
                                <img src="${BASE_URL}/images/default-thumbnail.webp" alt="" class="w-100" style="opacity: 0;">
                            </div>
                            <a href='${BASE_URL}/berita/${row.slug}' class="card_title_news">
                                <p>${row.title}</p>
                            </a>
                            <div class='hr_wraper'>
                                <hr class='overlay' />
                            </div>
                            <div class='creator_releasedate'>
                                <p>
                                    <i class="fa fa-user pr-1"></i>
                                    ${row.creator.name}
                                </p>
                                <p>
                                    <i class="fa fa-calendar pr-1"></i>
                                    ${moment.unix(row.release_date).format('MMMM DD, YYYY HH:mm')}
                                </p>
                            </div>
                            <div class="card_excerpt_news">
                                <p>${row.excerpt}</p>
                            </div>
                            <a href='${BASE_URL}/berita/${row.slug}' class='detail_link'>selengkapnya...</a>
                        </div>
                    </div>
                `;
            });

            $("#berita_terbaru #card_wraper").html(cards);

            setTimeout(() => {
                setMinHeightelement('#berita_terbaru .card_title_news');
            }, 100);
        },
        error:function(data) {
            console.log(data);
        }
    });
}

getLatestArticles();
