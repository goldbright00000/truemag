<?php
/**
 * The template for displaying the footer.
 *
 * Contains footer content and the closing of the
 * #main and #page div elements.
 *
 */
?>

    <footer class="dark-div">

        <?php if ( is_404() && is_active_sidebar( 'footer_404_sidebar' ) ) { ?>
        <div id="bottom">
            <div class="container">
                <div class="row">
                    <?php dynamic_sidebar( 'footer_404_sidebar' ); ?>                    
                </div><!--/row-->
            </div><!--/container-->
        </div><!--/bottom-->
        <?php } elseif ( is_active_sidebar( 'footer_sidebar' ) ) { ?>
        <div id="bottom">
            <div class="container">
                <div class="row">
                    <?php dynamic_sidebar( 'footer_sidebar' ); ?>                    
                </div><!--/row-->
            </div><!--/container-->
        </div><!--/bottom-->
        <?php } ?>
        <?php tm_display_ads('ad_foot');?>
                    
        <div id="bottom-nav">
            <div class="container">
                <div class="row">
                    <div class="copyright col-md-6"><?php echo ot_get_option('copyright',get_bloginfo('name').' - '.get_bloginfo('description')); ?></div>
                    <nav class="col-md-6">
                        <ul class="bottom-menu list-inline pull-right">
                            <?php
                                if(has_nav_menu( 'footer-navigation' )){
                                    wp_nav_menu(array(
                                        'theme_location'  => 'footer-navigation',
                                        'container' => false,
                                        'items_wrap' => '%3$s'
                                    )); 
                                }?>
                        </ul>
                    </nav>
                </div><!--/row-->
            </div><!--/container-->
        </div>
        <?php
        if ( is_shop() ){
        global $wpdb;
        $chked=$wpdb->query("UPDATE check_gold SET chk = 'gold' WHERE id = '1'");

        }
         $row = $wpdb->get_row( "SELECT * FROM  check_gold WHERE id ='1'");?>
         <input type="hidden" id="money_type" value="<?php echo $row->chk;?>" />
         <input type="hidden" id="login_user" value="<?php echo $login_user = get_current_user_id();?>" />
         <input type="hidden" id="login_user_email" value="<?php $user_info = get_userdata($login_user); echo $user_info->user_email;?>" />
         <input type="hidden" id="admin_email_ad" value="<?php echo bloginfo('admin_email');?>" />
         <input type="hidden" id="site_url" value="<?php echo site_url();?>" />
         <?php 

            global $wpdb;  
            $table = 'interactive_system';
            $today=date("Y-m-d");
            $today_entry = date("Y-m-d");
            if ( is_user_logged_in() ) {
            $userID=$login_user;
           /* $chk=bp_core_fetch_avatar(array('item_id' => bp_get_member_user_id(), 'type' => 'full', 'width' => 100, 'height' => 100, 'html' => false));
             
            if(!empty($chk)){
                 $current_amount = get_user_meta($userID, 'wallet-amount', true);
                 $totalAmount= $current_amount+100;
                 update_user_meta($userID, 'wallet-amount', $totalAmount );
            }
            */
            $usermail=$user_info->user_email;
            $data_array = array( 'user_id'=>$userID,'date'=>$today,'user_email'=>$usermail,'next_day'=>$today_entry,'points'=>'10');
            $check_vote = $wpdb->get_var("select count(*) FROM interactive_system where user_id = $userID");
             if($check_vote ==0){
            $point_insert= $wpdb->insert( $table, $data_array);
            $data_array2 = array('user_id'=>$userID,'type'=>'Daily Login','points'=>'10','date'=>$today);
            $point_insert2= $wpdb->insert( 'free_points_details', $data_array2);

              //$current_amount = get_user_meta($userID, 'wallet-amount', true);
             // $totalAmount= $current_amount+10;
              //update_user_meta($userID, 'wallet-amount', $totalAmount );
            }
            else{
            $row = $wpdb->get_row( "SELECT * FROM  interactive_system WHERE user_id ='$userID'");
            $row2 = $wpdb->get_row( "SELECT * FROM  point_editable WHERE id ='1'");
            $login_time=$row->date;
            $daily_point=$row2->points;
            $nxt=$row->next_day; $currentPoint=$row->points; $updatePoints=$currentPoint+$daily_point;
            $counter=$row->counter;
      $inc=$counter+1;
            if(strtotime($today)!=strtotime($nxt))
                   {

                 $upgarde ='interactive_system';
                 $data_array = array('next_day'=>$today_entry,'points' =>$updatePoints,'counter'=>$inc);
                 $where = array('user_id' => $userID);
                 $r= $wpdb->update( $upgarde, $data_array, $where );
                 $data_array2 = array('user_id'=>$userID,'type'=>'Daily Login','points'=>$daily_point,'date'=>$today);
                 $point_insert2= $wpdb->insert( 'free_points_details', $data_array2);
                 // $current_amount = get_user_meta($userID, 'wallet-amount', true);
                 // $totalAmount= $current_amount+10;
                 // update_user_meta($userID, 'wallet-amount', $totalAmount );
                 }
           if($inc==30)
           {    $row4 = $wpdb->get_row( "SELECT * FROM  point_editable WHERE id ='4'");
           // $current_amount = get_user_meta($userID, 'wallet-amount', true);
                //  $totalAmount= $current_amount+100;
                 // update_user_meta($userID, 'wallet-amount', $totalAmount );
                   $month_bons=$row4->points;
                   $upgarde2 ='interactive_system';
                   $bons=$updatePoints+$month_bons;
                   $data_array = array('points' =>$bons);
                   $where = array('user_id' => $userID);
                   $r= $wpdb->update( $upgarde2, $data_array, $where );
                   $data_array2 = array('user_id'=>$userID,'type'=>'30 Days Login Bouns','points'=>$month_bons,'date'=>$today);
                   $point_insert2= $wpdb->insert( 'free_points_details', $data_array2);
           }
            }



    }
            
             $retrieve_data = $wpdb->get_results( "SELECT * FROM  $table where mail='0'" );
            foreach ($retrieve_data as $retrieved_data){
                     $login_time=$retrieved_data->date;
                $next_time=$retrieved_data->next_day;
                
             //  echo $today;
                     $user_email=$retrieved_data->user_email;
                     $diff=strtotime($today)-strtotime($next_time);
                     if($diff >=86400)
                     {
                        $mailset ='interactive_system';
                        $data_array = array('next_day'=>$today,'mail' =>1);
                        $where = array('user_email' => $user_email);
                        $r= $wpdb->update( $mailset, $data_array, $where );
                      $mailarry.= ','.$retrieved_data->user_email;
                     }

         } 
            $to      = $mailarry;
            $subject = 'Login your Account For Get Points';
            $headers.= "MIME-version: 1.0\n";
            $headers.= "Content-type: text/html; charset= iso-8859-1\n";
            $message .= "Please Login your account". "\r\n";
          //    $message .= $mycode;
            $headers = 'X-Mailer: PHP/' . phpversion();
            //print_r($message);
            mail($to, $subject, $message, $headers);
             if ( is_user_logged_in() ) {
                 $userID=$login_user;
                 $dis = xprofile_get_field_data('Short Description ', $userID);
                 $fb_info = xprofile_get_field_data('facebook', $userID);
                 $youtube = xprofile_get_field_data('youtube', $userID);
                 $twitter = xprofile_get_field_data('Twitter', $userID);
                 $insta = xprofile_get_field_data('Instagram', $userID);
                 $contact = xprofile_get_field_data('Contact', $userID);

           global $wpdb;
   
            $wpdb->get_results("SELECT * FROM 413_rt_rtm_media WHERE media_author = $userID");
            $photos= $wpdb->num_rows;
             if ($fb_info !== ''&& $twitter !== '')
             {
                if($photos>1){
                       $profile_status ='interactive_system';
                        $data_array = array('profile_status' =>1);
                        $where = array('user_id' => $userID,'profile_status'=>0);
                        if($wpdb->update( $profile_status, $data_array, $where )){
                        $get_points = $wpdb->get_row( "SELECT * FROM  interactive_system where user_id='$userID'" );
                        $currentPoints=$get_points->points;
                        $row_2 = $wpdb->get_row( "SELECT * FROM  point_editable WHERE id ='2'");
                        $complete_profile_points=$row_2->points;
                        $totalPoints= $currentPoints+$complete_profile_points;
                        $allowed = $wpdb->query("UPDATE interactive_system SET points = '$totalPoints' WHERE user_id = '$userID'");
                        $data_array2 = array('user_id'=>$userID,'type'=>'profile complete','points'=>$complete_profile_points,'date'=>$today);
                        $point_insert2= $wpdb->insert( 'free_points_details', $data_array2);


                      //   if()//{
                     //    $current_amount = get_user_meta($userID, 'wallet-amount', true);
                        //$totalAmount= $current_amount+100;
                        //update_user_meta($userID, 'wallet-amount', $totalAmount );
                    //}
                    }

                }
             }

             $gold_points = get_user_meta($userID, 'wallet-amount', true);
             $upgarde ='interactive_system';
             $data_array2 = array('gold_points'=>$gold_points);
             $where = array('user_id' => $userID);
             $r2= $wpdb->update( $upgarde, $data_array2, $where );
            
         }





?>
    </footer>
    </div>
    <div class="wrap-overlay"></div>
</div><!--wrap-->
<?php if(ot_get_option('mobile_nav',1)){ ?>
<div id="off-canvas">
    <div class="off-canvas-inner">
        <nav class="off-menu">
            <ul>
            <li class="canvas-close"><a href="#"><i class="fa fa-times"></i> <?php _e('Close','cactusthemes'); ?></a></li>
            <?php
                $megamenu = ot_get_option('megamenu', 'off');
                if($megamenu == 'on' && function_exists('mashmenu_load')){
                    global $in_mobile_menu;
                    $in_mobile_menu = true;
                    mashmenu_load();
                    $in_mobile_menu = false;
                }elseif(has_nav_menu( 'main-navigation' )){
                    wp_nav_menu(array(
                        'theme_location'  => 'main-navigation',
                        'container' => false,
                        'items_wrap' => '%3$s'
                    )); 
                }else{?>
                    <li><a href="<?php echo home_url(); ?>/"><?php _e('Home','cactusthemes'); ?></a></li>
                    <?php wp_list_pages('title_li=' ); ?>
            <?php } ?>
            <?php
                $user_show_info = ot_get_option('user_show_info');
                if ( is_user_logged_in() && $user_show_info =='1') {
                $current_user = wp_get_current_user();
                $link = get_edit_user_link( $current_user->ID );
                ?>
                    <li class="menu-item current_us">
                    <?php  
                    echo '<a class="account_cr" href="#">'.$current_user->user_login; 
                    echo get_avatar( $current_user->ID, '25' ).'</a>';
                    ?>
                    <ul class="sub-menu">
                        <li class="menu-item"><a href="<?php echo $link; ?>"><?php _e('Edit Profile','cactusthemes') ?></a></li>
                        <li class="menu-item"><a href="<?php echo wp_logout_url( get_permalink() ); ?>"><?php _e('Logout','cactusthemes') ?></a></li>
                    </ul>
                    </li>
                <?php }?>
                <?php //submit menu
                if(ot_get_option('user_submit',1)) {
                    $text_bt_submit = ot_get_option('text_bt_submit');
                    if($text_bt_submit==''){ $text_bt_submit = 'Submit Video';}
                    if(ot_get_option('only_user_submit',1)){
                        if(is_user_logged_in()){?>
                        <li class="menu-item"><a class="submit-video" href="#" data-toggle="modal" data-target="#submitModal"><?php _e($text_bt_submit,'cactusthemes'); ?></a></li>
                    <?php }
                    } else{
                    ?>
                        <li class="menu-item"><a class="submit-video" href="#" data-toggle="modal" data-target="#submitModal"><?php _e($text_bt_submit,'cactusthemes'); ?></a></li>
                    <?php 
                        
                    }
                } ?>
            </ul>
        </nav>
    </div>
</div><!--/off-canvas-->
<script>off_canvas_enable=1;</script>
<?php }?>
<?php if(ot_get_option('theme_layout',false)){ ?>
</div><!--/boxed-container-->
<?php }?>
<div class="bg-ad">
    <div class="container">
        <div class="bg-ad-left">
            <?php tm_display_ads('ad_bg_left');?>
        </div>
        <div class="bg-ad-right">
            <?php tm_display_ads('ad_bg_right');?>
        </div>
    </div>
</div>
</div><!--/body-wrap-->
<?php
    if(ot_get_option('user_submit',1)) {?>
    <div class="modal fade" id="submitModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title" id="myModalLabel"><?php _e('Submit Video','cactusthemes'); ?></h4>
          </div>
          <div class="modal-body">
            <?php dynamic_sidebar( 'user_submit_sidebar' ); ?>
          </div>
        </div>
      </div>
    </div>
<?php } ?>
<?php
    if( is_single() && ot_get_option('video_report','on')!='off' ) {?>
    <div class="modal fade" id="reportModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title" id="myModalLabel"><?php _e('Report Video','cactusthemes'); ?></h4>
          </div>
          <div class="modal-body">
            <?php echo do_shortcode('[contact-form-7 id="325" title="Suggest video"]'); ?>
          </div>
        </div>
      </div>
    </div> 
<?php } ?>
<?php if(!ot_get_option('theme_layout') && (ot_get_option('adsense_slot_ad_bg_left')||ot_get_option('ad_bg_left')||ot_get_option('adsense_slot_ad_bg_right')||ot_get_option('ad_bg_right')) ){ //fullwidth layout ?>
<script>
    enable_side_ads = true;
</script>
    
<?php } ?>


    <script type="text/javascript">
        //var woocommerce_cart_hash = 'woocommerce_cart_hash='+ window.devicePixelRatio +';'+ woocommerce_cart_hash;
        //document.cookie = woocommerce_cart_hash;
        //if(document.cookie){
        //   alert(woocommerce_cart_hash)
        //   document.location.reload(true);
        //}
    </script>

<script type="text/javascript">
        jQuery(document).ready(function() {
            jQuery('#password').blur(function() {
checkStrength(jQuery('#password').val());
});   
            jQuery( "#cmt" ).on( "click", function() {
                jQuery( "#comment" ).trigger( "click" );
 
            });
                        jQuery(".share_icn").click(function(){
              //  jQuery(".s_share").slideToggle( "slow" );
               jQuery('.s_share').animate({height: 'toggle'});
            }); 
                        var post_url=jQuery('#post_url').val();
            jQuery('#f_video_name').val(post_url);
            jQuery('.video_gallery_wrap').show();
                jQuery("#description").keypress(function (e) {
     //if the letter is not digit then display error and don't type anything
     if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
        
              // return false;
    }

});        jQuery('.wpmui-label-website').text('Date of Birth');
               var login_user1= jQuery('#login_user').val();
               jQuery('#user_id').val(login_user1);
                jQuery('.wpmui-label-description').text('Contact Number');
            var logineID=jQuery('#login_user_email').val();
            jQuery('.wpcf7-email').val(logineID);
            jQuery('.rtMedia-upload-button').hide();
              jQuery('#requst_cc').click(function() {
               var vote=this.id;
        var login_user= jQuery('#login_user').val();
        var login_user_email= jQuery('#login_user_email').val();
        var admin_email_ad= jQuery('#admin_email_ad').val();
        var site_url= jQuery('#site_url').val();
        var ajex_url=site_url+'/wp-admin/admin-ajax.php';
        // alert(email);
        // var myFunction;
        jQuery.ajax({
                  type:'POST',
                  data:{action:'requst_cc',login_user:login_user,login_user_email:login_user_email,admin_email_ad:admin_email_ad},
                  url: ajex_url,
                  success: function(value) {
                 if(value=='insert')
                 {
                    alert('Successfully Request for Cc submitted. ');
                    jQuery('#requst').hide();

                 }
              
                  }
               }); 
           });
           //for set gold 
           function gold(chk){
            var chk;
              var site_url= jQuery('#site_url').val();

              var ajex_url=site_url+'/wp-admin/admin-ajax.php';
              jQuery.ajax({
                  type:'POST',
                  data:{action:'set_gold',chk:chk},
                  url: ajex_url,
                  success: function(value) {
                    if(value=='gold'){
                        var url = site_url+"/cart/";
                        window.location.href=url;

                    }
                     if(value=='checkout'){
                        var url = site_url+"/checkout/";
                        window.location.href=url;

                    }
                 
              
                  }
               }); 
            
           }
        

           jQuery('.checkout-button').click(function(event) { 
            var price1 =jQuery('#totel').text();
            jQuery('#check_money').show();
             var price= price1.replace(",", "")
           
            
             event.preventDefault();
             var money_type= jQuery('#money_type').val();
             
            var site_url= jQuery('#site_url').val();
             if(money_type=='gold'){
             var login_user= jQuery('#login_user').val();
             
             var ajex_url=site_url+'/wp-admin/admin-ajax.php';
             jQuery.ajax({
                  type:'POST',
                  data:{action:'check_wallet',login_user:login_user,price:price},
                  url: ajex_url,
                  success: function(value) {
                  if(value=='notsufficient')
                 {
                     jQuery('#check_money').hide();
                    jQuery('#no_credit').show();
                    
                   
                     jQuery( "#no_credit" ).dialog({
                        resizable: false,
                        height: "auto",
                        closeText : '',
                        width: 400,
                        modal: true,
                      });
                   
                   jQuery('.added_to_cart').css( "display","none !important");
                    jQuery('.added_to_cart.wc-forward').attr("style", "display: none !important");

                 }
                 if(value=='no'){
                   // alert('hii');
                           
                            var url = site_url+"/checkout/";
                            window.location.href=url;
                     //jQuery('.added_to_cart').attr("style", "display: inline !important");
                 }
              
                  }
               }); 
              } else{
                var url = site_url+"/checkout/";
                window.location.href=url;
                
                 
              }

              });
jQuery("#imgg").click(function(){
    
    jQuery('.rtMedia-upload-button').show();
      var numItems = jQuery('.rtmedia-gallery-item-actions').length;
     if(numItems==3){
        
        alert('Limit number of media you can upload - Maximum 3.');
       jQuery("#rtmedia-upload-container").hide();

     } else{
       // 
        jQuery("#imgg").hide();
       jQuery('#rtMedia-upload-button').trigger( "click" );
        jQuery(".rtm-media-gallery-uploader").show();
         //jQuery('#rtMedia-upload-button').prop("disabled",false);
     }
   
});  
jQuery("#rtMedia-upload-button").click(function(){
      var numItems = jQuery('.plupload_queue_li').length;
      var numItems2 = jQuery('.rtmedia-gallery-item-actions').length;

      var sum =parseInt(numItems)+parseInt(numItems2);
     // alert(sum);
     if(sum>2){
         jQuery('#rtMedia-upload-button').prop("disabled",true);
       // jQuery("").attr('disabled','disabled');
      //  alert('Limit number of pictures you can upload - Maximum 3 photos.');
       // jQuery("#rtmedia-upload-container").hide();

     } else{
         jQuery('#rtMedia-upload-button').prop("disabled",false);
     }
   
});
/*jQuery("#rtMedia-upload-button").click(function(){
      var numItems = jQuery('.plupload_queue_li').length;
     if(numItems==1){
        alert('Limit number of pictures you can upload - Maximum 3 photos.');
        jQuery("#rtmedia-upload-container").hide();

     } else{
        jQuery("#rtmedia-upload-container").show();
     }
   
});*/
 jQuery('.not_level').click(function() {
   jQuery( "#not_level" ).dialog({
                                    resizable: false,
                                    height: "auto",
                                    width: 400,
                                     closeText : '',
                                    modal: true
                                  });
  });
  jQuery('.not_level_poll').click(function() {
   jQuery( "#not_level_poll" ).dialog({
                                    resizable: false,
                                    height: "auto",
                                    width: 400,
                                     closeText : '',
                                    modal: true
                                  });
  });
  jQuery('.not_point').click(function() {
   jQuery( "#not_point" ).dialog({
                                    resizable: false,
                                    height: "auto",
                                    width: 400,
                                     closeText : '',
                                    modal: true
                                  });
  });
    jQuery('input:radio[name=points]').change(function() {
        jQuery(".goldsilver").css({"cursor": "default", "pointer-events": "none"});
        jQuery(".goldsilver a").css({"background": "#a8a8a8", "color": "#ccc"});
        
    var id=this.id;
    var price=jQuery('#'+id).val();

    var arr = id.split('-');
    var clsname=arr[0];
    jQuery('#'+clsname+'-type').show();
    var ballot_type=arr[1];
     var site_url= jQuery('#site_url').val();
                    var ajex_url=site_url+'/wp-admin/admin-ajax.php';
                    jQuery.ajax({
                     type:'POST',
                    data:{action:'ballot_fun',ballot_id:clsname,price:price,ballot_type:ballot_type},
                   url: ajex_url,
                  success: function(value) {
                    if(value=='ballot_fun')
                       jQuery('#'+clsname+'-type').hide();
                      jQuery("."+clsname).css({"cursor": "pointer", "pointer-events": "inherit"});
                      jQuery("."+clsname+" a").css({"background": "#f9c63e", "color": "#000"});
                  }
               });

   
/*
    if(clsname){
         jQuery(".goldsilver").css({"cursor": "default", "pointer-events": "none"});
     jQuery(".goldsilver a").css({"background": "#a8a8a8", "color": "#ccc"});
    jQuery("."+clsname).css({"cursor": "pointer", "pointer-events": "inherit"});
     jQuery("."+clsname+" a").css({"background": "#f9c63e", "color": "#000"});
  
      } */
  });
    jQuery('.submit_bid').click(function() {
      var bid_id=this.id;
      var amount=jQuery('#'+bid_id+'-bidamount').val();
         if(amount>0){
                    jQuery('#'+bid_id+'-response').show();  //class="result_response"
                     jQuery('.result_response').hide();
                    jQuery('#'+bid_id+'-error').hide();

                    var login_user= jQuery('#login_user').val();
                     var totel_sum_amount= jQuery('#totel_sum_amount').val();
                     var Remaining_amount= jQuery('#Remaining_amount').val();
                     var goal= jQuery('#goal').val();
                    var site_url= jQuery('#site_url').val();
                    var ajex_url=site_url+'/wp-admin/admin-ajax.php';
                    jQuery.ajax({
                     type:'POST',
                    data:{action:'add_bid',login_user:login_user,bid_id:bid_id,amount:amount,totel_sum_amount:totel_sum_amount,Remaining_amount:Remaining_amount,goal:goal},
                   url: ajex_url,
                  success: function(value) {
                    if(value=='<h4 class="result_response" style="color:green;">Successfully submit your bid.</h4>')
                    {
                       jQuery('#amount-'+bid_id).text(amount); 
                       window.location.reload(true);
                    }
                    
                    jQuery('#'+bid_id+'-response').hide();
                    jQuery('#'+bid_id+'-bid_ok').html(value);
                  }
               });

         } else{
            jQuery('#'+bid_id+'-error').show();
         }
        });
                jQuery("#quantity").on( "click keyup", function(){
                var value = jQuery(this).val();
                if(value % 2 == 0)
                         {
                            jQuery('#eveneror').show();
                            jQuery('#add_post').prop('disabled', true);
                        } else{jQuery('#eveneror').hide();
              jQuery('#add_post').prop('disabled', false);
                        }
                        
                /*value = value.replace(/[^0-9]+/g, '');
                jQuery(this).val(value);*/
            });
                jQuery("#gold_val").on( "click keyup", function(){
                var value = jQuery(this).val();
                if(value % 2 == 0)
                        {
                            // var shortenedString = value.substr(1,(value.length -1));
                            // jQuery('#gold_val').val(shortenedString);
                            jQuery('#add_pledge').prop('disabled', true);
                            jQuery('#evengold').show();
                              } else{ jQuery('#evengold').hide();jQuery('#add_pledge').prop('disabled', false);}
            });
                jQuery("#silver_val").on( "click keyup", function(){
                var value = jQuery(this).val();
                if(value % 2 == 0)
                        { 
                              jQuery('#add_pledge').prop('disabled', true);
                            // var shortenedString = value.substr(0,(value.length -1));
                            // jQuery('#silver_val').val(shortenedString);
                             jQuery('#evensilver').show();
                              } else{  jQuery('#evensilver').hide();

                              jQuery('#add_pledge').prop('disabled', false);
                          }
            });
                var addressValue = jQuery('.next_post').attr("href");
                jQuery("#next_episode").attr("href", addressValue);
                jQuery('#video_pledge').on('change', function() {
                    var post_id=this.value;
                     var site_url= jQuery('#site_url').val();
                     var ajex_url=site_url+'/wp-admin/admin-ajax.php';
                     jQuery.ajax({
                     type:'POST',
                     data:{action:'check_crowdfund',post_id:post_id},
                     url: ajex_url,
                  success: function(value) {
                      if(value=='yes'){
                         jQuery('#alreay_crow').show();
                         jQuery('.crowdfund_cls').hide();
                         jQuery('.crowdfunding').hide();  
                        jQuery("#crowdfund_chk").removeAttr("checked");

                      }
                   else{
                    jQuery('#alreay_crow').hide();
                     jQuery('.crowdfund_cls').show();


                  }
               }
                })
      }); 

             jQuery( ".gold_not" ).click(function() {
             jQuery(this).attr('disabled', 'disabled');
             jQuery( "#not_gold" ).dialog({
                                    resizable: false,
                                    height: "auto",
                                     closeText : '',
                                    width: 400,
                                    modal: true
                                  });
             });
             jQuery( ".silver_not" ).click(function() {
             
             jQuery(this).attr('disabled', 'disabled');
             jQuery( "#not_silver" ).dialog({
                                    resizable: false,
                                    height: "auto",
                                     closeText : '',
                                    width: 400,
                                    modal: true
                                  });
             });
              jQuery('.wpcf7-file').bind('change', function() {
               var size=this.files[0].size/1024/1024;
               if(size>2){
                  jQuery('.wpcf7-file').val('');
                jQuery('#file_size').show();
               } else{
                jQuery('#file_size').hide();
               }
        });
                jQuery('#poll_insert').click(function(){

                 jQuery("#poll_response").show();
                 var poll_val=jQuery('input[name=poll]:checked').val();
                 var poll_id=jQuery('#poll_id').val();
                 var login_user= jQuery('#login_user').val();
                 var site_url= jQuery('#site_url').val();
                 var ajex_url=site_url+'/wp-admin/admin-ajax.php';
                 jQuery.ajax({
                          type:'POST',
                          data:{action:'poll_insert',login_user:login_user,poll_val:poll_val,poll_id:poll_id},
                          url: ajex_url,
                          success: function(value) {
                            jQuery("#poll_response").hide();
                            if(value=='done'){
                                jQuery('#yes_vote').show();
                                jQuery('#no_vote').hide();

                            } else{
                                 jQuery('#yes_vote').hide();
                                 jQuery('#no_vote').show();
                          }
                        
                      
                          }
                       }); 

                
                  
                });
jQuery('#add_edit').click(function(){

                    jQuery('.video_gallery_wrap').show();
                      });
     jQuery( ".vote_good" ).click(function() {
              jQuery( "#like" ).click();
            });//vote_evil
            jQuery( ".vote_evil" ).click(function() {
              jQuery( "#dislike" ).click();
            });
               jQuery( ".like_up" ).click(function() {
              jQuery( "#like" ).click();
            });//vote_evil
            jQuery( ".vote_dislike" ).click(function() {
              jQuery( "#dislike" ).click();
            });
                jQuery('.merchandise_type .add_to_cart_button').text('Merchandise');
            });
function submit_video(){
                 var login_user= jQuery('#login_user').val();
      
        var site_url= jQuery('#site_url').val();
        var ajex_url=site_url+'/wp-admin/admin-ajax.php';
        // alert(email);
        // var myFunction;
        jQuery.ajax({
                  type:'POST',
                  data:{action:'submit_video_request',login_user:login_user},
                  url: ajex_url,
                  success: function(value) {
                
              
                  }
               }); 

    
                 }

                 function checkStrength(password){
                    var pswd = password;
                    jQuery('#password-error').hide();
                   //validate the length
              
               

                //validate capital letter
                if ( pswd.match(/[A-Z]/) ) {  jQuery(':input[type="submit"]').prop('disabled', false);
                   jQuery( "#capital" ).hide();
                } else { 
                    jQuery(':submit').prop('disabled', true);
                    if(jQuery('#capital').length == 0) {
                    jQuery( "#password" ).after( '<label id="capital" class="ms-validation-error">Please enter atleast 1 capital letter.</label>' );}
                }

                //validate number
                if ( pswd.match(/\d/) ) {  jQuery(':input[type="submit"]').prop('disabled', false);
                      jQuery( "#Number" ).hide();
                   
                } else {  jQuery(':submit').prop('disabled', true);
                    if(jQuery('#Number').length == 0) {
                    jQuery( "#password" ).after( '<label id="Number" class="ms-validation-error">Please enter atleast 1 alphanumeric.</label>' );}
                }
                  if ( pswd.length > 7 ) {
                    jQuery(':submit').prop('disabled', false);
                    jQuery( "#letter" ).hide();
                } else {
                     jQuery(':submit').prop('disabled', true);
                     if(jQuery('#letter').length == 0) {
                   jQuery( "#password" ).after( '<label id="letter" class="ms-validation-error">Please enter minimum 8 characters.</label>');}
                }                              
                }

                 

    </script>
    <style type="text/css">
.goldsilver .fa-spin {
  left: -22px;
  position: absolute;
  top: 6px;
}
.goldsilver {
  position: relative;
}
#u_0_1 svg {
  display: none !important;
}
.pluginButtonImage > button {
  background: rgba(0, 0, 0, 0) url("http://premise.tv/wp-content/plugins/woocommerce-wallet-system/includes/front/fb_img.png") no-repeat scroll 5px center;
  width: 38px  !important;
}
 .pluginButtonImage > button {
  display: none  !important;
}
.pledge_txt > span {
  font-weight: normal;
  padding-left: 0;
}
#credit_card_store {
  font-size: 18px;
  font-weight: 300;
  margin: 0;
  min-height: auto !important;
  text-align: center;
}
.latest_products_inner .checkbox-inline > input { margin-right: 6px; margin-top: -2px; vertical-align: middle;}
.latest_products_inner .checkbox-inline ,.latest_products_inner .checkbox { padding: 0;}
.sub_graph p {
  margin: 0;
  text-align: center;
  font-size: 12px;
}
.sub_graph.v_0{
    background: #69B8D6 none repeat scroll 0 0;
}
.sub_graph.v_1{
    background: #6EC9C0 none repeat scroll 0 0;
}
.sub_graph.v_2{
    background: #A397B6 none repeat scroll 0 0;
}
.sub_graph.v_3{
    background: #80bfff none repeat scroll 0 0;
}
.sub_graph.v_4{
    background: #e6ccff none repeat scroll 0 0;
}

.single_video_im {
  float: left;
  width: 33%;
}
.video_list .single_video_im:nth-child(3) {
  text-align: center;
}
.video_list .single_video_im:last-child {
  text-align: right;
}
#item-body h4 {
  font-size: 24px;
  text-align: center;
  text-transform: uppercase;
}
#item-body .rtmedia-list-media.rtm-gallery-list {
  margin-bottom: 30px;
}
#my_vote{display: none;}

.vote_good, .vote_evil {
    z-index: 9999;
}
.vote_good:hover, .vote_evil:hover {
  cursor: pointer;
}
 .logo > img {
  margin: 10px 0;
  width: 62%;
}
.forum-template-default .breadcrumbs, .topic-template-default .breadcrumbs {
  display: none;
}
.forum-template-default #sidebar.col-md-4, .topic #sidebar.col-md-4{
  display: none;
}
.forum-template-default #content.col-md-8, .topic #content.col-md-8 {
  width: 100%;
}
#mark_name_anonymous > label {
  display: none;
}
.radio-inline img {
  height: 15px;
}
.btmText img {
  height: 15px;
  margin-left: 5px;
}
.user_name {
    text-transform: capitalize;
}
#wpcf7-f325-o2 .video_name {
  display: none;
}
.products li a {
  text-align: left;
}
.products li a h3 {
  text-align: center;
}
.price .woocommerce-Price-amount.amount {
  margin-left: 50%;
  text-align: right;
}
._2tga._49ve {
  display: none !important;
}
.video_col_ul .bawpvc-ajax-counter {
    display: inline;
}
.fa.fa-thumbs-up, .fa.fa-thumbs-down {
    cursor: pointer;
}
</style>
    <div id="no_credit" style="display:none;">
        <h1>INSUFFICIENT BALANCE!</h1>
        <p>You do not have enought Premier TV Coins to buy this item,to recharge your Premier TV Coins balance, click on the Recharge button.</p>
        <a href="<?php echo site_url();?>/my-account/wallet/">RECHARGE!</a>
    </div>
    <p id="credit_card_store" style="color:green; display:none;">Website do not hold or store users credit card details.</p>
    <p id="not_level" style="color:red;display:none;">You don't have sufficient Rank to purchase this Pledge.</p>
     <p id="not_level_poll" style=" color:red; display:none;">You don't have sufficient level to vote this poll.</p>
     <p id="not_point" style="color:red; display:none;">You don't have sufficient Points to purchase this Pledge.</p>
     <p id="not_gold" style="color:red; display:none;">You don't have sufficient Gold to purchase this Pledge.</p>
      <p id="not_silver" style="color:red; display:none;">You don't have sufficient Silver to purchase this Pledge.</p>
<a href="#top" id="gototop" class="notshow" title="Go to top"><i class="fa fa-angle-up"></i></a>
<?php echo ot_get_option('google_analytics_code', ''); ?>

<script src="<?php echo get_template_directory_uri(); ?>/js/jquery-tags-input.js"></script>
<?php  wp_footer(); ?>
</body>
</html>
