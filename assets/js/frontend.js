jQuery(document).ready(function($) {
    $('.us-gallery').each(function() {
        var galleryId = $(this).attr('id');
        $('#' + galleryId + ' .us-gallery-link').magnificPopup({
            type: 'image',
            gallery: {
                enabled: true
            },
            image: {
                titleSrc: function(item) {
                    return item.el.closest('figure').find('.us-gallery-caption').text() || '';
                }
            }
        });
    });

    // Keyboard navigation for gallery images
    $('.us-gallery').on('keydown', function(e) {
        var $current = $(document.activeElement);
        var $items = $(this).find('.us-gallery-link');
        var index = $items.index($current);

        switch (e.key) {
            case 'ArrowLeft':
                index = index > 0 ? index - 1 : $items.length - 1;
                $items.eq(index).focus();
                e.preventDefault();
                break;
            case 'ArrowRight':
                index = index < $items.length - 1 ? index + 1 : 0;
                $items.eq(index).focus();
                e.preventDefault();
                break;
            case 'Enter':
                if ($current.hasClass('us-gallery-link')) {
                    $current.click();
                }
                e.preventDefault();
                break;
            default:
                break;
        }
    });

    // Set tabindex for accessibility
    $('.us-gallery-link').attr('tabindex', '0');
});
