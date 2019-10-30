$(function () {
    $(".video-services").each(function () {
        $(this).click(function (e) {
            $(this).html('<iframe width="' + $(this).data('width') + '" height="' + $(this).data('height') + '" src="' + $(this).data('url') + '" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>').addClass('video-services--clicked')
        })
    })
});
