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
    setMinHeightelement('#daftar_berita .card_title_news');
}, 100);

// ----------------------------------------
//            Get Pagination Articles
// ----------------------------------------

// -- fetch data
const fetch_data = (page, seach_term) => {
    if(seach_term === undefined){
        seach_term = "";
    }
    $.ajax({
        url:`${BASE_URL}/api/v1/articles_pagination?page=${page}&seach_term=${seach_term}`,
        success:function(data){
            $('#daftar_berita #card_wraper').html('');
            $('#daftar_berita #card_wraper').html(data);

            setTimeout(() => {
                setMinHeightelement('#daftar_berita .card_title_news');
            }, 100);
        }
    })
}

// -- search
$('#search_wraper #search').on('keyup', function(){
    var seach_term = $('#search_wraper #search').val();
    var page = $('#hidden_page').val();

    fetch_data(page, seach_term);
});

// -- page on click
$('#daftar_berita').on('click', '.pager a', function(event){
    event.preventDefault();

    var page = $(this).attr('href').split('page=')[1];
    $('#hidden_page').val(page);

    var search = $('#search_wraper #search').val();

    fetch_data(page, search);
});
