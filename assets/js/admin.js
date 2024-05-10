jQuery(document).ready(function($) {
    var file_frame;
    var imagesContainer = $('#us-gallery-images-container ul.us-gallery-images');

    $('.us-add-gallery-images').on('click', function(e) {
        e.preventDefault();

        if (file_frame) {
            file_frame.open();
            return;
        }

        file_frame = wp.media.frames.file_frame = wp.media({
            title: 'Select Images',
            button: {
                text: 'Add to Gallery'
            },
            multiple: true
        });

        file_frame.on('select', function() {
            var attachments = file_frame.state().get('selection').toJSON();
            attachments.forEach(function(attachment) {
                imagesContainer.append('<li data-attachment-id="' + attachment.id + '"><img src="' + attachment.sizes.thumbnail.url + '" alt=""><a href="#" class="us-remove-image">x</a></li>');
            });
            updateImageIds();
        });

        file_frame.open();
    });

    // Remove image
    $(document).on('click', '.us-remove-image', function(e) {
        e.preventDefault();
        $(this).parent().remove();
        updateImageIds();
    });

    // Sortable images
    imagesContainer.sortable({
        update: function() {
            updateImageIds();
        }
    });

    function updateImageIds() {
        var imageIds = [];
        imagesContainer.find('li').each(function() {
            imageIds.push($(this).data('attachment-id'));
        });
        $('#us-gallery-images').val(imageIds.join(','));
    }

    // Initialize the hidden input
    updateImageIds();
});
