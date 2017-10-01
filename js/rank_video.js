jQuery(document).ready(function($){
   /*  if (jQuery("input[name=rank_key]").is(":checked")) {

 alert('hi');
     }*/
     jQuery('input:radio[name=rank_key]').change(function() {
       var rank=this.id;
        
        //var ajaxurl = '/admin-ajax.php';

                    jQuery.ajax({
                     type:'POST',
                     data:{action:'rank_check',rank:rank},
                   url: ajaxurl,
                  success: function(value) {
                    if(value !='no'){
                       var value2='Please Set '+value; 
                       alert(value2);
                       jQuery('#'+rank).removeAttr('checked');
                    }
                  }
       
   

     });
      

     });
    // Post function
    /*function checkTitle(title, id,post_type) {
        var data = {
            action: 'title_check',
            post_title: title,
            post_type: post_type,
            post_id: id
        };

        //var ajaxurl = 'wp-admin/admin-ajax.php';
        $.post(ajaxurl, data, function(response) {
            $('#message').remove();
            $('#poststuff').prepend('<div id=\"message\" class=\"updated fade\"><p>'+response+'</p></div>');
        }); 
    };

    // Add button to "Check Titles" below title field in post editor
    //$('#edit-slug-box').append('<span id="check-title-btn"><a class="button" href="#">Check Title</a></span>');

    // Click function to initiate post function  bind('blur keyup', function(e)
    $('#title').bind('blur keyup', function(e) {
        var title = $('#title').val();
        var id = $('#post_ID').val();
        var post_type = $('#post_type').val();
        checkTitle(title, id,post_type);
    });*/

});
