jQuery( document ).ready( function($) {
    $(document).on( 'click', '.delete-post', function() {
        var id = $(this).data('id');
        var nonce = $(this).data('nonce');
        var post = $(this).parents('.post:first');
        $.ajax({
            type: 'post',
            url: ajaxurl,
            data: {
                action: 'my_delete_post',
                nonce: nonce,
                id: id
            },
            success: function( result ) {
                
                if( result ) {
                    //jQuery('#'+result).hide();
                    jQuery('#'+result).html('<div class="wpcf7-response-output wpcf7-display-none wpcf7-validation-errors" style="display: block;">Delete successfully....</div>').fadeOut(3000);
                }
            }
        })
        return false;
    })
     $(document).on( 'click', '.delete-product', function() {

        var id = $(this).data('id');
        $.ajax({
            type: 'post',
            url: ajaxurl,
            data: {
                action: 'remove_product',
                id: id
            },
            success: function( result ) {
                //alert(result);
                if( result) {
                    //jQuery('#'+result).hide();
                    jQuery('#'+result).html('<div class="wpcf7-response-output wpcf7-display-none wpcf7-validation-errors" style="display: block;">Delete successfully....</div>').fadeOut(3000);
                }
            }
        })
        return false;
    })
})