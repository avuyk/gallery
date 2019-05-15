$( document ).ready(function() {
    $('.custom-file-input').on('change', function(event){

        // set file input placeholder to filename of selected file
        var inputFile = event.currentTarget;
        $(inputFile).parent()
            .find('.custom-file-label')
            .html(inputFile.files[0].name);

        // set title from filename
        var rawFilename = inputFile.value.replace(/^.*[\\\/]/, '').replace(/\.[^/.]+$/, '');
        var newTitle = rawFilename.replace(/[^a-z0-9-\s]/gi, '').replace(/[_\s]/g, '-').toLowerCase();
        $('.js-set-title').val(newTitle);
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
                    var $alert = '<div class="alert alert-danger mt-5" role="alert">' +
                        'Could not remove the image, please try again later.' +
                        '<button type="button" class="close" data-dismiss="alert" aria-label="Close">\n' +
                        '  <span aria-hidden="true">&times;</span>\n' +
                        '</button>' +
                        '</div>';
                    $($alert).hide().insertAfter('h1').slideDown();
                    $trashIconElement.removeClass('fa-spin')
                          .removeClass('fa-spinner')
                          .addClass('fa-trash');
                },
                204: function( ) {
                    var $alert = '<div class="alert alert-success mt-5" role="alert">' +
                        'You removed the image succesfully.' +
                        '<button type="button" class="close" data-dismiss="alert" aria-label="Close">\n' +
                        '  <span aria-hidden="true">&times;</span>\n' +
                        '</button>'+
                        '</div>';
                    $($alert).hide().insertAfter('h1').slideDown();
                },
            }
        }).done(function() {
            $tableRow.delay(1000).fadeOut();
        });
    });

    // perform client side validation for checkboxes
    $('input[type=checkbox]').on('change',function (){
        if ($('input[type=checkbox]:checked').length === 0) {
            $('.checkbox-validation').html( "**Please select at least one checkbox");
        }
        else{
            $('.checkbox-validation').html("");
        }
    });

});