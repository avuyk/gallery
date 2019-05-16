$( document ).ready(function() {
    $('.custom-file-input').on('change', function(event){

        // Set file input placeholder to filename of selected file
        var $fileInputElement = event.currentTarget;
        $($fileInputElement).parent()
            .find('.custom-file-label')
            .html($fileInputElement.files[0].name);

        // Set title from filename
        var $rawFilename = $fileInputElement.value.replace(/^.*[\\\/]/, '').replace(/\.[^/.]+$/, '');
        var $newTitle = $rawFilename.replace(/[^a-z0-9-\s]/gi, '').replace(/[_\s]/g, '-').toLowerCase();
        $('.js-set-title').val($newTitle);
    });

    // Call API to perform delete action when clicking trash icon
    $('.js-delete-image').on('click', function(e) {
        e.preventDefault();
        var $tableRow = $(this).closest('.js-file-item');
        var $trashIconElement = $(this).find('.fa-trash');
        $trashIconElement.removeClass('fa-trash')
             .addClass('fa-spinner')
             .addClass('fa-spin');
        $.ajax({
            url: $(this).data('url'),
            method: 'DELETE',
            statusCode: {
                417: function( ) {
                    var $oldAlertOnErrorElement = $('.alert-danger');
                    if( typeof $oldAlertOnErrorElement !== 'undefined' ) {
                        $($oldAlertOnErrorElement).remove();
                    }
                    var $alertOnErrorElement = '<div class="alert alert-danger mt-5" role="alert">' +
                        'Could not remove the image, please try again later.' +
                        '<button type="button" class="close" data-dismiss="alert" aria-label="Close">\n' +
                        '  <span aria-hidden="true">&times;</span>\n' +
                        '</button>' +
                        '</div>';
                    $($alertOnErrorElement).hide().insertAfter('h1').slideDown();
                    $trashIconElement.removeClass('fa-spin')
                          .removeClass('fa-spinner')
                          .addClass('fa-trash');
                },
                204: function( ) {
                    var $oldAlertOnSuccessElement = $('.alert-success');
                    if( typeof $oldAlertOnSuccessElement !== 'undefined' ) {
                        $($oldAlertOnSuccessElement).remove();
                    }
                    var $alertOnSuccessElement = '<div class="alert alert-success mt-5" role="alert">' +
                        'You removed the image succesfully.' +
                        '<button type="button" class="close" data-dismiss="alert" aria-label="Close">\n' +
                        '  <span aria-hidden="true">&times;</span>\n' +
                        '</button>'+
                        '</div>';
                    $($alertOnSuccessElement).hide().insertAfter('h1').slideDown();
                },
            }
        }).done(function() {
            $tableRow.delay(1000).fadeOut();
        });
    });

    // Set class 'active' on active menu item and parent element
    $activeElement = $('a[href="' + this.location.pathname + '"]');
    $activeElement.addClass('active');
    $activeElement.closest('.dropdown').addClass('active');
    // Show to screen readers which page is active
    $activeElement.append('<span class="sr-only">(current)</span>');

    // Put footer down
    var docHeight = $(window).height();
    var footerHeight = $('.js-footer').height();
    var footerTop = $('.js-footer').position().top + footerHeight;
    if (footerTop < docHeight)
        $('.js-footer').css('margin-top', 10+ (docHeight - footerTop) + 'px');
});
