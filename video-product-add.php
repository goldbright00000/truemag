<?php
/* Template Name: Content Creator Page */ 

?>

<?php if (!is_user_logged_in() ) {
  
 wp_redirect(home_url());

}
?>
<?php get_header();?>
<?php   //this code for only contect creater acess this page
 //$current_user   = wp_get_current_user();
 $userID=$current_user->ID;
   // $role_name      = $current_user->roles[0];
    // $people = array("content_creator", "administrator");
    // if (!in_array($role_name, $people)){
    //   echo'<script>
   //  jQuery(".page-template").css("display","none");
   //  window.location.href = "http://biggboss.info/premise";</script>';
    // }
      ?>
<?php 
$msg='';
    /*submit frontend post using this code*/
if($_POST['add_post']){  
    //inserts new post into 'uploads' database
       require_once(ABSPATH . "wp-admin" . '/includes/image.php');
        require_once(ABSPATH . "wp-admin" . '/includes/file.php');
        require_once(ABSPATH . "wp-admin" . '/includes/media.php');
     $uploaddir = wp_upload_dir();
    $file = $_FILES['featured' ];
    $uploadfile = $uploaddir['path'] . '/' . basename( $file['name'] );
    move_uploaded_file( $file['tmp_name'] , $uploadfile );
    $filename = basename( $uploadfile );
    $wp_filetype = wp_check_filetype(basename($filename), null );
    $attachment = array(
      'post_mime_type' => $wp_filetype['type'],
      'post_title' => preg_replace('/\.[^.]+$/', '', $filename),
      'post_content' => '',
      'post_status' => 'inherit',
      'menu_order' => $_i + 1000
      );
    $attach_id = wp_insert_attachment( $attachment, $uploadfile );
      $post_info = array(
            'post_title' => wp_strip_all_tags( $_POST['postTitle'] ),
            'post_content' => $_POST['postContent'],
            'post_type' => 'product',
            'post_status'=>'draft'
      );
  $pid = wp_insert_post( $post_info );
  update_post_meta($pid,'_thumbnail_id',$attach_id);
    set_post_thumbnail( $pid, $thumbnail_id );
    set_post_thumbnail_size( 300,300, true );
    add_post_meta( $_POST['merchandise_video_id'], 'merchandise_video_id', $pid, true );
    wp_set_post_terms( $pid, array(157,158), 'product_cat' );
    if(isset($_POST['crowdfund'])){
     add_post_meta( $pid, '_alg_crowdfunding_enabled','yes');
    
    
     add_post_meta($pid, '_alg_crowdfunding_startdate', $_POST['start_date'] );
     add_post_meta($pid, '_alg_crowdfunding_starttime', $_POST['start_time']);
     add_post_meta($pid, '_alg_crowdfunding_deadline',  $_POST['end_date']);
     add_post_meta($pid, '_alg_crowdfunding_deadline_time',$_POST['end_time']);
     add_post_meta($pid, '_alg_crowdfunding_goal_sum',$_POST['goal_price']);
     // add_post_meta($pid, '_alg_crowdfunding_product_open_price_enabled','yes');
         //add_post_meta($pid, '_alg_crowdfunding_product_open_price_default_price','100');
     
   }
    $price= intval(update_post_meta( $pid, '_regular_price', $_POST['_regular_price'] ));
    if($price){
      $email =get_bloginfo('admin_email');
      $post_title   = $_POST['postTitle'];
      $post_content = $_POST['postContent'];
      $header .= "MIME-Version: 1.0\n";
      $header .= "Content-Type: text/html; charset=utf-8\n";
      $message = "Post Title: $post_title\n";
      $message .= "Post content: $post_content\n";
      $subject = "Product content";
      $subject = "=?utf-8?B?" . base64_encode($subject) . "?=";
      $to = $email;
      // send the email using wp_mail()
      wp_mail($to, $subject, $message, $header);
      $msg= '<div class="wpcf7-response-output wpcf7-display-none wpcf7-validation-errors" style="display: block;">Your merchandise is now successfully added. It will be included in the shop after it is approved by admin.</div>';
    }
}

if($_POST['add_pledge']){  


  $post_info = array(
            'post_title' => wp_strip_all_tags( $_POST['PledgeTitle'] ),
            'post_content' => $_POST['PledgeContent'],
            'post_type' => 'product',
            'post_status'=>'publish',
            
      );
    $pid = wp_insert_post( $post_info );
    if($_POST['stock']){
      $_manage_stock= intval(update_post_meta( $pid, '_manage_stock','yes' ));
      $_stock= intval(update_post_meta( $pid, '_stock',$_POST['stock'] ));

    }   //stock
     $gold_points= intval(update_post_meta( $pid, 'gold_points',$_POST['gold'] ));
     $silver_points= intval(update_post_meta( $pid, 'silver_points',$_POST['silver'] ));
      add_post_meta( $pid, 'level_pledge', $_POST['level']);
       add_post_meta( $pid, '_end_date', $_POST['cut']); 
   if(isset($_POST['crowdfund'])){
     add_post_meta( $pid, '_alg_crowdfunding_enabled','yes');
    
    
     add_post_meta($pid, '_alg_crowdfunding_startdate', $_POST['start_date'] );
     add_post_meta($pid, '_alg_crowdfunding_starttime', $_POST['start_time']);
     add_post_meta($pid, '_alg_crowdfunding_deadline',  $_POST['end_date']);
     add_post_meta($pid, '_alg_crowdfunding_deadline_time',$_POST['end_time']);
     add_post_meta($pid, '_alg_crowdfunding_goal_sum',$_POST['goal_price']);
     // add_post_meta($pid, '_alg_crowdfunding_product_open_price_enabled','yes');
         //add_post_meta($pid, '_alg_crowdfunding_product_open_price_default_price','100');
     
   }
     wp_set_post_terms( $pid, array($_POST['Pledgetype'],157), 'product_cat' );
     $havemeta = get_post_meta($_POST['video_id'], 'pledge_id', true); 
     if($havemeta ){
      $havemeta=$havemeta.','.$pid;
      
      update_post_meta( $_POST['video_id'], 'pledge_id', $havemeta);
    } else{
    add_post_meta( $_POST['video_id'], 'pledge_id', $pid, true );
    }
      //Add Pledge id in video meta
     if($_POST['gold']){
      $price= intval(update_post_meta( $pid, '_regular_price', $_POST['gold'] ));
     } else{
      $price= intval(update_post_meta( $pid, '_regular_price', $_POST['_regular_price'] ));
     }
   
 $msg= '<div class="wpcf7-response-output wpcf7-display-none wpcf7-validation-errors" style="display: block;">Your Pledge is now successfully added. It will be included in the video Pledge list after it is approved by admin.</div>';

}
if($_POST['add_poll']){
 // print_r($_POST);
    $post_info = array(
            'post_title' => wp_strip_all_tags( $_POST['PledgeTitle'] ),
            'post_content' => $_POST['PledgeContent'],
            'post_type' => 'poll',
            'post_status'=>'publish',
            
      );
    $pid = wp_insert_post( $post_info );
    $poll_value=implode(',', $_POST['poll_value']);
    if(add_post_meta( $pid, 'poll_value', $poll_value))
    {
      add_post_meta( $_POST['poll_video_id'], 'poll_id', $pid, true );
      add_post_meta( $pid, 'level', $_POST['level']);
      add_post_meta( $pid, 'silver', $_POST['silver']);
      
       $msg= '<div class="wpcf7-response-output wpcf7-display-none wpcf7-validation-errors" style="display: block;">Your Poll is now successfully added. </div>';
    }

}
            ?>
            <script>
jQuery(document).ready(function(){
   jQuery("#levelval").keypress(function (e) {
     //if the letter is not digit then display error and don't type anything
     if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
        //display error message
        jQuery("#errmsg2").html("Digits Only").show().fadeOut("slow");

               return false;
    }
    

   });
     jQuery("#levelval").keyup(function (event) {
    
        var number = parseFloat(jQuery(this).val());
        if(number > 99){
           jQuery(this).val("");
        }
        if(number==0){
           jQuery(this).val("1");

        }
    });
       jQuery("#stock").keyup(function (event) {
    
        var number = parseFloat(jQuery(this).val());
        if(number > 9999){
           jQuery(this).val("");
        }
        if(number==0){
           jQuery(this).val("1");

        }
    });
   jQuery("#stock").keypress(function (e) {
     //if the letter is not digit then display error and don't type anything
     if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {

        //display error message
        jQuery("#errmsg3").html("Digits Only").show().fadeOut("slow");

               return false;
    }
   });
     jQuery("#gold_val").keypress(function (e) {
     //if the letter is not digit then display error and don't type anything
     if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
        //display error message
        jQuery("#errmsg4").html("Digits Only").show().fadeOut("slow");
               return false;
    }
   });
     jQuery("#silver_val").keypress(function (e) {
     //if the letter is not digit then display error and don't type anything
     if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
        //display error message
        jQuery("#errmsg5").html("Digits Only").show().fadeOut("slow");
               return false;
    }
   });
  
  jQuery('ul.tabs li').click(function(){
    var tab_id = jQuery(this).attr('data-tab');
    jQuery('ul.tabs li').removeClass('current');
    jQuery('.tab-content').removeClass('current');
    jQuery(this).addClass('current');
    jQuery("#"+tab_id).addClass('current');
  })
    jQuery("#quantity").keypress(function (e) {
     //if the letter is not digit then display error and don't type anything
     if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
        //display error message
        jQuery("#errmsg").html("Digits Only").show().css("color","red").fadeOut("slow");
               return false;
    }

});
    jQuery("#user_profile_pic").change(function() {

    var val = jQuery(this).val();
    switch(val.substring(val.lastIndexOf('.') + 1).toLowerCase()){
         case 'jpg': case 'png':case 'jpeg':
          jQuery("#error").hide();
           jQuery("#add_post").removeAttr('disabled');
            break;
        default:
            jQuery(this).val('');
            // error message here
            jQuery("#error").html("Please Enter an Image").show().css("color","red");
            jQuery("#add_post").attr('disabled', 'disabled');
            break;
    }
});
     jQuery('#date').datepicker({ dateFormat: 'dd-mm-yy' });
       jQuery('input:radio[name=Pledgetype]').change(function() {
        if (this.value == '167') {
            jQuery('#cutofdate').show();
            jQuery('#level').hide();
            jQuery('#levelval').val('');
            jQuery('#manage').hide();
            jQuery('#gold').hide();
            jQuery('#silver').hide();
            jQuery('#gold_val').val('');
            jQuery('#silver_val').val('');
            jQuery('#add_pledge').show();
           jQuery('#cutofdate').show();
           jQuery('#level2').show();
            jQuery('#add_poll').hide();
            jQuery('#poll_fild').hide();
            jQuery('#pledge_label').show();
            jQuery('#poll_label').hide();
            jQuery('#poll_video_list').hide();
            jQuery('#video_pledge').show();
        }
        else if (this.value == '166') {
          jQuery('#level').show();
            jQuery('#cutofdate').hide();  //manage
            jQuery('#date').val('');
            jQuery('#manage').show();
            jQuery('#gold').show();
            jQuery('#silver').show();
             jQuery('#add_pledge').show();
           
           jQuery('#level2').show();
            jQuery('#add_poll').hide();
            jQuery('#poll_fild').hide();
            jQuery('#pledge_label').show();
            jQuery('#poll_label').hide();
            jQuery('#poll_video_list').hide();
            jQuery('#video_pledge').show();


        }
        else if(this.value=='poll'){
           jQuery('#add_pledge').hide();
           jQuery('#cutofdate').hide();
          // jQuery('#level2').hide();
            jQuery('#add_poll').show();
            jQuery('.crowdfund_cls').hide();
            jQuery('#manage').hide();
            jQuery('#gold').hide();
            jQuery('#silver').show();
            jQuery('#poll_fild').show();
            jQuery('#pledge_label').hide();
            jQuery('#poll_label').show();
            jQuery('#video_pledge').hide();
            jQuery('#poll_video_list').show();
        }
    });
      jQuery('#datepicker').datepicker({ dateFormat: 'yy/mm/dd' });
      jQuery('#datepicked').datepicker({ dateFormat: 'yy/mm/dd' });
      jQuery('#datepickedk').datepicker({ dateFormat: 'yy/mm/dd' });
     jQuery('#datepickerk').datepicker({ dateFormat: 'yy/mm/dd' });
      
              var check;
              jQuery("#crowdfund_chk").on("click", function(){
              check = jQuery("#crowdfund_chk").prop("checked");
              if(check) {
                  jQuery(".crowdfunding").show();
              } else {
                 jQuery(".crowdfunding").hide();
              }
          });
             var checkk;
              jQuery("#crowdfund_chkk").on("click", function(){
              checkk = jQuery("#crowdfund_chkk").prop("checked");
              if(checkk) {
                    
                  jQuery(".crowdfundingk").css('display','block');
              } else {
                jQuery(".crowdfundingk").css('display','none');
              }
              
          });
              jQuery('.wpcf7-email').val('premisetv@gmail.com');
              var max_fields      = 5; //maximum input boxes allowed
              var wrapper         = jQuery(".input_fields_wrap"); //Fields wrapper
              var add_button      = jQuery(".add_field_button"); //Add button ID
              var x = 1; //initlal text box count
              jQuery(add_button).click(function(e){ //on add input button click
               e.preventDefault();
              if(x < max_fields){ //max input box allowed
                x++; //text box increment
               jQuery(wrapper).append('<div><input type="text" placeholder="Poll Option" name="poll_value[]"/><a href="#" class="remove_field">Remove</a></div>'); //add input box
               }
                });
   
              jQuery(wrapper).on("click",".remove_field", function(e){ //user click on remove text
                  e.preventDefault(); jQuery(this).parent('div').remove(); x--;
              });
    });
 </script>

            <style>
            #wpcf7-f855-o1 .wpcf7-form > p:nth-child(3) {
  display: none;
}

    ul.tabs{
      margin: 0px;
      padding: 0px;
      list-style: none;
    }
    ul.tabs li{
      background: none;
      color: #222;
      display: inline-block;
      padding: 10px 15px;
      cursor: pointer;
    }
    ul.tabs li.current{
      background: #ededed;
      color: #222;
    }
    .tab-content{
      display: none;
      background: #ededed;
      padding: 15px;
    }
    .tab-content.current{
      display: inherit;
    }

 .tab_video_merch li {
  text-align: center;
  width: 32%;
}
 .video_merch_contant {
  background: transparent none repeat scroll 0 0 !important;
  border: 1px solid #ededed;
  margin-bottom: 20px;
}
ul.tabs li.current {
  background: #ededed none repeat scroll 0 0;
  border-bottom: 2px solid #f9c73d;
  color: #222;
}
.tab_video_merch {
  margin-top: 20px !important;
 background: #f7f7f7 none repeat scroll 0 0;
}
#add_post, input[type="submit"] {
  background: #f9c73d none repeat scroll 0 0;
  border: 1px solid #f9c73d;
  margin-top: 20px;
}
#add_post:hover, input[type="submit"]:hover {
  background: #222 none repeat scroll 0 0;
  border-color: #222;
}
label {
  font-weight: normal;
}
.manage_btn {
  background: #f9c73d none repeat scroll 0 0;
  border: 1px solid #f9c73d;
  border-radius: 3px;
  color: #fff;
  display: inline-block;
  line-height: 1.5;
  margin: 26px 0;
  outline: medium none;
  padding: 7px 18px;
  text-align: center;
  transition: all 0.2s ease 0s;
}
.postTitle.hasDatepicker {
    box-sizing: border-box;
    width: 23%;
}

            </style>
  <div class="container">
    <div class="row">
      <?php echo $msg; ?>
      <div class="col-md-8 col-md-offset-2">

      <ul class="tabs tab_video_merch">
        <li class="tab-link current" data-tab="tab-1">Upload Video</li>
        <li class="tab-link" data-tab="tab-2">Upload Merchandise</li>
        <li class="tab-link" data-tab="tab-3">Upload Pledge</li>
      </ul>
     <div id="tab-1" class="tab-content current video_merch_contant">
        <div class="col-md-12 col-sm-12 ">  
          <div class="video-item">
            <?php echo do_shortcode( '[contact-form-7 id="855" title="User submit videos"]' );?>
          </div>
        </div>
      </div>
            <?php 
            /* $args = array(
                      'author'        =>  $userID, 
                      'orderby'       =>  'post_date',
                      'order'         =>  'ASC',
                      'posts_per_page' => -1, // no limit
                      'post_status'=> array('publish', 'pending', 'draft','private', 'inherit')
                    );
                    $current_user_posts = get_posts( $args );
                      $count = count($current_user_posts); 
                      //echo $count; 
              if($count>=3){?>*/
                 //<style>
                   /* .video-item form .video-url {
                      position: relative;
                    }
                    .video-item form .video-url::after {
                      background: rgba(0, 0, 0, 0.1) none repeat scroll 0 0;
                      content: "";
                      height: 35px;
                      left: 0;
                      position: absolute;
                      top: -9px;
                      width: 100%;
                    }*/
                //</style>
                //<script type="text/javascript">
                    /*jQuery(function()
                    {
                   jQuery('.video-url').parent('p').click(function()
                   {
                   alert('Sorry,You have upload only 3 videos');
                   });
                    });*/
                //</script>
               // <?php  } else{
                //echo '<div class="video-item"></div>';}?>
                <?php //echo do_shortcode( '[contact-form-7 id="855" title="User submit videos"]' );?>
    <div id="tab-2" class="tab-content video_merch_contant">
    <div class="col-md-12 col-sm-12 ">  
      <div class="woo-item">
        <p>Please fill in the information below to submit your merchandise.</p>
        <form method="post" enctype="multipart/form-data" name="mainForm" action="">
                <div id="postTitleOuter">
                    <label> Name of merchandise</label>
                    <input type="text" name="postTitle" class="postTitle" required/>

                    </div>
                    <div id="postTitleOuter">
                       <?php
                       global $post;

                       $args = array(
                      'author'        =>  $userID, 
                      "post_type"        => "post",
                      'orderby'       =>  'post_date',
                      'order'         =>  'DESC',
                      'posts_per_page' => -1 ,// no limit
                     "post_status"      => "publish,pending,draft"
                    );
                    $current_user_posts = get_posts( $args );
                   
                    echo '<select id="merchandise_video_list" name="merchandise_video_id">';
                    echo '<option value="" selected="selected">Please pick a video below...</option>';
                    

                    foreach($current_user_posts as $post) : setup_postdata($post);
                    $post_id=$post->ID;
                    $check = get_post_meta($post_id, 'merchandise_video_id', true);
                    if($check){
                      }else{ 

                    ?>
                        <option value="<?php echo $post->ID; ?>"><?php echo $post->post_title; ?></option>
                    <?php } endforeach;
                    wp_reset_postdata();
                    echo '</select>';
                    ?>
                      </div>
                       <div id="postTitleOuter" class="crowdfund_cls">
                    <label> Crowdfunding</label></br>
                    <input id="crowdfund_chk" type="checkbox" name="crowdfund" value="yes"/>
          </div>
          <div class="crowdfunding" style="display:none;">
              <div id="startofdate">
                      <label>Start Date</label>
                      <input type="text"  id="datepicker" name="start_date" class="postTitle" />
              </div>
               <div id="start_time">
                      <label>Start Time</label>
                      <select name="start_time">
                       <option value="00:00am">12.00 AM</option>
                       <option value="00:30am">12.30 AM</option>
                        <option value="01:00am">01.00 AM</option>
                        <option value="01:30am">01.30 AM</option>
                        <option value="02:00am">02.00 AM</option>
                        <option value="02:30am">02.30 AM</option>
                        <option value="03:00am">03.00 AM</option>
                        <option value="03:30am">03.30 AM</option>
                        <option value="04:00am">04.00 AM</option>
                        <option value="04:30am">04.30 AM</option>
                        <option value="05:00am">05.00 AM</option>
                        <option value="05:30am">05.30 AM</option>
                        <option value="06:00am">06.00 AM</option>
                        <option value="06:30am">06.30 AM</option>
                        <option value="07:00am">07.00 AM</option>
                        <option value="07:30am">07.30 AM</option>
                        <option value="08:00am">08.00 AM</option>
                        <option value="08:30am">08.30 AM</option>
                        <option value="09:00am">09.00 AM</option>
                        <option value="09:30am">09.30 AM</option>
                        <option value="10:00am">10.00 AM</option>
                        <option value="10:30am">10.30 AM</option>
                        <option value="11:00am">11.00 AM</option>
                        <option value="11:30am">11.30 AM</option>
                        <option value="12:00pm">12.00 PM</option>
                        <option value="12:30pm">12.30 PM</option>
                        <option value="01.00pm">01.00 PM</option>
                        <option value="01.30pm">01.30 PM</option>
                        <option value="02.00pm">02.00 PM</option>
                        <option value="02.30pm">02.30 PM</option>
                        <option value="03.00pm">03.00 PM</option>
                        <option value="03.30pm">03.30 PM</option>
                        <option value="04.00pm">04.00 PM</option>
                        <option value="04.30pm">04.30 PM</option>
                        <option value="05.00pm">05.00 PM</option>
                        <option value="05.30pm">05.30 PM</option>
                        <option value="06.00pm">06.00 PM</option>
                        <option value="06.30pm">06.30 PM</option>
                        <option value="07.00pm">07.00 PM</option>
                        <option value="07.30pm">07.30 PM</option>
                        <option value="08.00pm">08.00 PM</option>
                        <option value="08.30pm">08.30 PM</option>
                        <option value="09.00pm">09.00 PM</option>
                        <option value="09.30pm">09.30 PM</option>
                        <option value="10.00pm">10.00 PM</option>
                        <option value="10.30pm">10.30 PM</option>
                        <option value="11.00pm">11.00 PM</option>
                        <option value="11.30pm">11.30 PM</option>

                       </select>
               
              </div>
               <div id="endofdate">
                      <label>End Date</label>
                      <input type="text"  id="datepicked" name="end_date" class="postTitle" />
              </div>
               <div id="ended_time">
                      <label>End Time</label>
                      <select name="end_time">
                       <option value="00:00am">12.00 AM</option>
                       <option value="00:30am">12.30 AM</option>
                        <option value="01:00am">01.00 AM</option>
                        <option value="01:30am">01.30 AM</option>
                        <option value="02:00am">02.00 AM</option>
                        <option value="02:30am">02.30 AM</option>
                        <option value="03:00am">03.00 AM</option>
                        <option value="03:30am">03.30 AM</option>
                        <option value="04:00am">04.00 AM</option>
                        <option value="04:30am">04.30 AM</option>
                        <option value="05:00am">05.00 AM</option>
                        <option value="05:30am">05.30 AM</option>
                        <option value="06:00am">06.00 AM</option>
                        <option value="06:30am">06.30 AM</option>
                        <option value="07:00am">07.00 AM</option>
                        <option value="07:30am">07.30 AM</option>
                        <option value="08:00am">08.00 AM</option>
                        <option value="08:30am">08.30 AM</option>
                        <option value="09:00am">09.00 AM</option>
                        <option value="09:30am">09.30 AM</option>
                        <option value="10:00am">10.00 AM</option>
                        <option value="10:30am">10.30 AM</option>
                        <option value="11:00am">11.00 AM</option>
                        <option value="11:30am">11.30 AM</option>
                        <option value="12:00pm">12.00 PM</option>
                        <option value="12:30pm">12.30 PM</option>
                        <option value="01.00pm">01.00 PM</option>
                        <option value="01.30pm">01.30 PM</option>
                        <option value="02.00pm">02.00 PM</option>
                        <option value="02.30pm">02.30 PM</option>
                        <option value="03.00pm">03.00 PM</option>
                        <option value="03.30pm">03.30 PM</option>
                        <option value="04.00pm">04.00 PM</option>
                        <option value="04.30pm">04.30 PM</option>
                        <option value="05.00pm">05.00 PM</option>
                        <option value="05.30pm">05.30 PM</option>
                        <option value="06.00pm">06.00 PM</option>
                        <option value="06.30pm">06.30 PM</option>
                        <option value="07.00pm">07.00 PM</option>
                        <option value="07.30pm">07.30 PM</option>
                        <option value="08.00pm">08.00 PM</option>
                        <option value="08.30pm">08.30 PM</option>
                        <option value="09.00pm">09.00 PM</option>
                        <option value="09.30pm">09.30 PM</option>
                        <option value="10.00pm">10.00 PM</option>
                        <option value="10.30pm">10.30 PM</option>
                        <option value="11.00pm">11.00 PM</option>
                        <option value="11.30pm">11.30 PM</option>

                       </select>
              </div>
              <div id="max_price">
                      <label>Goal</label>
                      <input type="text"  id="max" name="goal_price" class="postTitle" />
              </div>
          </div>
                    <div id="postContentOuter">
                    <label>Short description</label>
                    <textarea rows="4" cols="20" class="postContent" name="postContent" required></textarea>
                </div>
               
                <div id="postTitleOuter">
                    <label>Price</label>
                    <input type="text" name="_regular_price" class="postTitle" id="quantity" required/>
                   <span id="eveneror" style="display:none; color:red;"> Enter Only Odd Value</span>
                    </div> 
                    <div id="postImg"> <label>Merchandise Image</label>
                    <input type="file" name="featured" id="user_profile_pic" class="size"/>
                    <span id="error"></span>
                </div>
                    <input type="submit" name="add_post" id="add_post" value="Add Merchandise">
            </form>
    </div>
  </div>
  </div>
    <div id="tab-3" class="tab-content video_merch_contant">
    <div class="col-md-12 col-sm-12 ">  
      <div class="woo-item">
        <p>Please fill in the information below to submit your Pledge.</p>
        <form method="post" enctype="multipart/form-data" name="mainForm" action="">
          <div id="postTitleOuter">
                    <p id="alreay_crow" style="color:#F9C73D; display:none;">This video already select for Crowdfunding</p>
                    <label> Type of Pledge</label></br>
                    <input type="radio" name="Pledgetype" value="166"/>Ballot
                    <input id="bidd" type="radio" name="Pledgetype" value="167"/>Bid
                    <input id="poll" type="radio" name="Pledgetype" value="poll"/>Poll
          </div>
          <?php
          $args2 = array(
                      'author'        =>  $userID, 
                      'orderby'       =>  'post_date',
                      'order'         =>  'DESC',
                      'posts_per_page' => -1 ,// no limit
                      'post_status'=> array('publish', 'pending', 'draft','private', 'inherit')
                    );
             $current_user_posts = get_posts( $args2 );
                    echo '<select id="video_pledge" name="video_id">';
                    echo '<option value="" selected="selected">Please pick a video below...</option>';
                    foreach($current_user_posts as $post) : setup_postdata($post);
                    ?>
                        <option value="<?php echo $post->ID; ?>"><?php echo $post->post_title; ?></option>
                    <?php endforeach;
                    echo '</select>';
                    ?>
                    <?php
                    echo '<select id="poll_video_list" name="poll_video_id" style="display:none;">';
                    echo '<option value="" selected="selected">Please pick a video below...</option>';
                    foreach($current_user_posts as $post) : setup_postdata($post);
                    $post_id=$post->ID;
                    $check = get_post_meta($post_id, 'poll_id', true);
                    if($check){
                      }else{ 

                    ?>
                        <option value="<?php echo $post->ID; ?>"><?php echo $post->post_title; ?></option>
                    <?php } endforeach;
                    echo '</select>';
                    ?>
          
          <div id="postTitleOuter" class="crowdfund_cls" style="display:none;">
                    <label> Crowdfunding</label></br>
                    <input id="crowdfund_chkk" type="checkbox" name="crowdfund" value="yes"/>
          </div>
          <div class="crowdfundingk" style="display:none;">
              <div id="startofdate">
                      <label>Start Date</label>
                      <input type="text"  id="datepickerk" name="start_date" class="postTitle" />
              </div>
               <div id="start_time">
                      <label>Start Time</label>
                      <select name="start_time">
                       <option value="00:00am">12.00 AM</option>
                       <option value="00:30am">12.30 AM</option>
                        <option value="01:00am">01.00 AM</option>
                        <option value="01:30am">01.30 AM</option>
                        <option value="02:00am">02.00 AM</option>
                        <option value="02:30am">02.30 AM</option>
                        <option value="03:00am">03.00 AM</option>
                        <option value="03:30am">03.30 AM</option>
                        <option value="04:00am">04.00 AM</option>
                        <option value="04:30am">04.30 AM</option>
                        <option value="05:00am">05.00 AM</option>
                        <option value="05:30am">05.30 AM</option>
                        <option value="06:00am">06.00 AM</option>
                        <option value="06:30am">06.30 AM</option>
                        <option value="07:00am">07.00 AM</option>
                        <option value="07:30am">07.30 AM</option>
                        <option value="08:00am">08.00 AM</option>
                        <option value="08:30am">08.30 AM</option>
                        <option value="09:00am">09.00 AM</option>
                        <option value="09:30am">09.30 AM</option>
                        <option value="10:00am">10.00 AM</option>
                        <option value="10:30am">10.30 AM</option>
                        <option value="11:00am">11.00 AM</option>
                        <option value="11:30am">11.30 AM</option>
                        <option value="12:00pm">12.00 PM</option>
                        <option value="12:30pm">12.30 PM</option>
                        <option value="01.00pm">01.00 PM</option>
                        <option value="01.30pm">01.30 PM</option>
                        <option value="02.00pm">02.00 PM</option>
                        <option value="02.30pm">02.30 PM</option>
                        <option value="03.00pm">03.00 PM</option>
                        <option value="03.30pm">03.30 PM</option>
                        <option value="04.00pm">04.00 PM</option>
                        <option value="04.30pm">04.30 PM</option>
                        <option value="05.00pm">05.00 PM</option>
                        <option value="05.30pm">05.30 PM</option>
                        <option value="06.00pm">06.00 PM</option>
                        <option value="06.30pm">06.30 PM</option>
                        <option value="07.00pm">07.00 PM</option>
                        <option value="07.30pm">07.30 PM</option>
                        <option value="08.00pm">08.00 PM</option>
                        <option value="08.30pm">08.30 PM</option>
                        <option value="09.00pm">09.00 PM</option>
                        <option value="09.30pm">09.30 PM</option>
                        <option value="10.00pm">10.00 PM</option>
                        <option value="10.30pm">10.30 PM</option>
                        <option value="11.00pm">11.00 PM</option>
                        <option value="11.30pm">11.30 PM</option>

                       </select>
               
              </div>
               <div id="endofdate">
                      <label>End Date</label>
                      <input type="text"  id="datepickedk" name="end_date" class="postTitle" />
              </div>
               <div id="ended_time">
                      <label>End Time</label>
                      <select name="end_time">
                       <option value="00:00am">12.00 AM</option>
                       <option value="00:30am">12.30 AM</option>
                        <option value="01:00am">01.00 AM</option>
                        <option value="01:30am">01.30 AM</option>
                        <option value="02:00am">02.00 AM</option>
                        <option value="02:30am">02.30 AM</option>
                        <option value="03:00am">03.00 AM</option>
                        <option value="03:30am">03.30 AM</option>
                        <option value="04:00am">04.00 AM</option>
                        <option value="04:30am">04.30 AM</option>
                        <option value="05:00am">05.00 AM</option>
                        <option value="05:30am">05.30 AM</option>
                        <option value="06:00am">06.00 AM</option>
                        <option value="06:30am">06.30 AM</option>
                        <option value="07:00am">07.00 AM</option>
                        <option value="07:30am">07.30 AM</option>
                        <option value="08:00am">08.00 AM</option>
                        <option value="08:30am">08.30 AM</option>
                        <option value="09:00am">09.00 AM</option>
                        <option value="09:30am">09.30 AM</option>
                        <option value="10:00am">10.00 AM</option>
                        <option value="10:30am">10.30 AM</option>
                        <option value="11:00am">11.00 AM</option>
                        <option value="11:30am">11.30 AM</option>
                        <option value="12:00pm">12.00 PM</option>
                        <option value="12:30pm">12.30 PM</option>
                        <option value="01.00pm">01.00 PM</option>
                        <option value="01.30pm">01.30 PM</option>
                        <option value="02.00pm">02.00 PM</option>
                        <option value="02.30pm">02.30 PM</option>
                        <option value="03.00pm">03.00 PM</option>
                        <option value="03.30pm">03.30 PM</option>
                        <option value="04.00pm">04.00 PM</option>
                        <option value="04.30pm">04.30 PM</option>
                        <option value="05.00pm">05.00 PM</option>
                        <option value="05.30pm">05.30 PM</option>
                        <option value="06.00pm">06.00 PM</option>
                        <option value="06.30pm">06.30 PM</option>
                        <option value="07.00pm">07.00 PM</option>
                        <option value="07.30pm">07.30 PM</option>
                        <option value="08.00pm">08.00 PM</option>
                        <option value="08.30pm">08.30 PM</option>
                        <option value="09.00pm">09.00 PM</option>
                        <option value="09.30pm">09.30 PM</option>
                        <option value="10.00pm">10.00 PM</option>
                        <option value="10.30pm">10.30 PM</option>
                        <option value="11.00pm">11.00 PM</option>
                        <option value="11.30pm">11.30 PM</option>

                       </select>
              </div>
              <div id="max_price">
                      <label>Goal</label>
                      <input type="text"  id="max" name="goal_price" class="postTitle" />
              </div>
          </div>
                <div id="postTitleOuter">
                    <label id="pledge_label"> Name of Pledge</label>
                    <label id="poll_label" style="display:none;"> Name of Poll</label>
                    <input type="text" name="PledgeTitle" class="postTitle" required/>

                    </div>
                    <div id="postContentOuter">
                    <label>Short description</label>
                   
                     <?php wp_editor('','newtestfield',array('textarea_name'=> 'PledgeContent','textarea_rows'=>7,'wpautop'=>false));?>
                 </div>
                 <div id="postTitleOuter" style="display:none;">
                    <label>Price</label>
                    <input type="text" name="_regular_price" value="0" class="postTitle" id="quantity"required/>
                    <span id="errmsg"></span>
                    </div> 
                      <div >
                
                      
                </div>

                     <div id="cutofdate" style="display:none;">
                    <label>Bidding Cut-Off Date</label>
                    <input type="text"  id="date" name="cut" class="postTitle" />
                </div>
                
                  <div id="level2">
                    <label> Level Requirement</label>
                    <input type="text"  id="levelval" name="level"   class="postTitle" />
                    <span style="color:red" id="errmsg2"></span>
                </div>
                    <div id="manage" style="display:none;">
                    <label> Quantity</label>
                    <input type="text"  id="stock" name="stock" value="1" class="postTitle" />
                     <span style="color:red" id="errmsg3"></span>
                </div>
                 <div id="gold" style="display:none;">
                    <label> Gold Points Required For Purchase</label>
                    <input type="text"  id="gold_val" name="gold" value="0" class="postTitle" />
                     <span style="color:red" id="errmsg4"></span>
                     <span id="evengold" style="display:none; color:red;"> Enter Only Odd Value</span>
                </div>
                 <div id="silver" style="display:none;">
                    <label> Silver Points Required For Purchase</label>
                    <input type="text"  id="silver_val" name="silver" value="0" class="postTitle" />
                    <span style="color:red" id="errmsg5"></span>
                    <span id="evensilver" style="display:none; color:red;"> Enter Only Odd Value</span>
                </div>
              
                  <div id="poll_fild" style="display:none;">
                    <div class="input_fields_wrap">
                    <button class="add_field_button">Add More Poll Options</button>
                    <div><input type="text" placeholder="Poll Option" name="poll_value[]"></div>
                    
                </div>
          </div>
                    <input type="submit" name="add_pledge" id="add_pledge" value="Add Pledge">
                     <input type="submit" name="add_poll" id="add_poll" value="Add Poll" style="display:none;">
            </form>
    </div>
  </div>
  </div>
  </div>
  <div class="manage"><a href="<?php echo site_url();?>/edit-content/" class="manage_btn">Manage Your Content</a></div>
</div>
</div>
<?php get_footer(); ?>