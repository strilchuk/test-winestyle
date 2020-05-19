$(document).ready(function() {
    var baseUrl = 'http://winestyle-test.loc';
    var isMobile = false
    if( /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ) {
        isMobile = true
    }

    var minSize = "min"
    if (isMobile)
        minSize = "mic"

    $.get( "/images", function(data) {
        $.each(data, function( index, value ) {
            if (index == 10) {
                return false;
            }

            $('#pic-container').append(`
            <div class="col-sm-6 col-md-4">
                <div class="thumbnail">
                    <a onclick='createTempGallery(\"${value}\", \"${isMobile}\")' href="javascript:void(0)">
                        <img src="/generator?name=${value}&size=${minSize}" alt="Park">
                    </a>
                    <div class="caption">
                        <h3>${value}</h3>
                    </div>
                </div>
            </div>`)
        })
    })
    .fail(function() {
        alert( "Произошла непредвиденная ошибка, но мы уже работаем. Приносим извинения за неудобства." )
    })

})

function createTempGallery(imageName, isMobile){

    var sizes = ['min', 'med','big']
    if (isMobile == 'true')
        sizes = ['mic','min', 'med']

    $('.container').append(`<div class="popup-gallery" style="display: none"></div>`)

    var elements = ''
    $.each(sizes, function( index, value ) {
        $('.popup-gallery').append(`
            <a href="/generator?name=${imageName}&size=${value}" title="${imageName} ${value}">
                <img src="/generator?name=${imageName}&size=${value}">
            </a>
        `)
    });

    $('.popup-gallery').magnificPopup({
        delegate: 'a',
        type: 'image',
        tLoading: 'Загрузка изображения #%curr%...',
        mainClass: 'mfp-img-mobile',
        gallery: {
            enabled: true,
            navigateByImgClick: true,
            preload: [0,1]
        },
        image: {
            tError: '<a href="%url%" #%curr%</a>  не может быть загружена.'
        }
    }).magnificPopup('open')

    $('.mfp-close, .mfp-bg, .mfp-wrap').click(function () {
        $('.popup-gallery').remove()
    });
}