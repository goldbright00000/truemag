<?php
/* Template Name: New Content Editor */

global $wpdb;
session_start();



//echo "<pre>";
// print_r($_SESSION);
//echo "<pre>";

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
//if($_POST['add_post']){
//    //inserts new post into 'uploads' database
//    require_once(ABSPATH . "wp-admin" . '/includes/image.php');
//    require_once(ABSPATH . "wp-admin" . '/includes/file.php');
//    require_once(ABSPATH . "wp-admin" . '/includes/media.php');
//    $uploaddir = wp_upload_dir();
//    $file = $_FILES['featured' ];
//    $uploadfile = $uploaddir['path'] . '/' . basename( $file['name'] );
//    move_uploaded_file( $file['tmp_name'] , $uploadfile );
//    $filename = basename( $uploadfile );
//    $wp_filetype = wp_check_filetype(basename($filename), null );
//    $attachment = array(
//        'post_mime_type' => $wp_filetype['type'],
//        'post_title' => preg_replace('/\.[^.]+$/', '', $filename),
//        'post_content' => '',
//        'post_status' => 'inherit',
//        'menu_order' => $_i + 1000
//    );
//    $attach_id = wp_insert_attachment( $attachment, $uploadfile );
//    $post_info = array(
//        'post_title' => wp_strip_all_tags( $_POST['postTitle'] ),
//        'post_content' => $_POST['postContent'],
//        'post_type' => 'product',
//        'post_status'=>'draft'
//    );
//    $pid = wp_insert_post( $post_info );
//    update_post_meta($pid,'_thumbnail_id',$attach_id);
//    set_post_thumbnail( $pid, $thumbnail_id );
//    set_post_thumbnail_size( 300,300, true );
//    add_post_meta( $_POST['merchandise_video_id'], 'merchandise_video_id', $pid, true );
//    wp_set_post_terms( $pid, array(157,158), 'product_cat' );
//    if(isset($_POST['crowdfund'])){
//        add_post_meta( $pid, '_alg_crowdfunding_enabled','yes');
//
//
//        add_post_meta($pid, '_alg_crowdfunding_startdate', $_POST['start_date'] );
//        add_post_meta($pid, '_alg_crowdfunding_starttime', $_POST['start_time']);
//        add_post_meta($pid, '_alg_crowdfunding_deadline',  $_POST['end_date']);
//        add_post_meta($pid, '_alg_crowdfunding_deadline_time',$_POST['end_time']);
//        add_post_meta($pid, '_alg_crowdfunding_goal_sum',$_POST['goal_price']);
//        // add_post_meta($pid, '_alg_crowdfunding_product_open_price_enabled','yes');
//        //add_post_meta($pid, '_alg_crowdfunding_product_open_price_default_price','100');
//
//    }
//    $price= intval(update_post_meta( $pid, '_regular_price', $_POST['_regular_price'] ));
//    if($price){
//        $email =get_bloginfo('admin_email');
//        $post_title   = $_POST['postTitle'];
//        $post_content = $_POST['postContent'];
//        $header .= "MIME-Version: 1.0\n";
//        $header .= "Content-Type: text/html; charset=utf-8\n";
//        $message = "Post Title: $post_title\n";
//        $message .= "Post content: $post_content\n";
//        $subject = "Product content";
//        $subject = "=?utf-8?B?" . base64_encode($subject) . "?=";
//        $to = $email;
//        // send the email using wp_mail()
//        wp_mail($to, $subject, $message, $header);
//        $msg= '<div class="wpcf7-response-output wpcf7-display-none wpcf7-validation-errors" style="display: block;">Your merchandise is now successfully added. It will be included in the shop after it is approved by admin.</div>';
//    }
//}

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

        function scrollTOControlBar(){
            jQuery('html, body').animate({
                scrollTop: jQuery(c).offset().top
            }, 1000);
        }

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


            jQuery(document).ajaxComplete(function(event, xhr, options)
            {






                if(xhr.responseJSON){
//
//
                     if(xhr.responseJSON.status == 'mail_sent'){

                         var slug = "<?= get_site_url() ?>/?p="+xhr.responseJSON.vd_id+"&preview=true";
                         var videoTitle = xhr.responseJSON.vd_title;
                        jQuery("#st2").trigger("click");
                        jQuery('#prv_btn').attr('href',slug);
                        jQuery("#preview_button").show();
                        var optionHtml = "<option value="+xhr.responseJSON.vd_id+">"+videoTitle+"</option>";
                        jQuery("#video_pledge").append(optionHtml);
                        jQuery("#merchandise_video_list").append(optionHtml);
                        jQuery("#poll_video_list").append(optionHtml);
                        jQuery('html, body').animate({
                            scrollTop: jQuery("#sc").offset().top - 50
                        }, 1000);



                    }

                }

            });




        });
    </script>

    <style>
        #wpcf7-f855-o1 .wpcf7-form > p:nth-child(3) {
            display: none;
        }

        #dummy li {
            cursor: pointer;
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
    <div class="container-fluid">
         <div class="outer-container">
            <div class="row">

                <?php echo $msg; ?>
                    <div class="left-sidebar open-nav">
                        <div class="manage"><a href="<?php echo site_url();?>/edit-content/" class="manage_btn">Manage Your Content</a></div>

                        <div class="diff-tab">
                            <ul class="tabs tab_video_merch">
                                <li class="tab-link current" data-tab="tab-1">Content</li>
                                <li class="tab-link remove-active clearfix"data-toggle="collapse" data-target="#sub-part" data-tab="tab-3" id="st2">Pledges
                                    <i class="fa fa-angle-down" aria-hidden="true"></i>
                                    <div id="sub-part">
                                        <ul class="menu-sub" id="dm">
                                            <li data-tab="tab-3" id="blt" data-id="bollot">1.Ballot</li>
                                            <li data-tab="tab-3" data-id="bidd">2.Bid</li>
                                            <li data-tab="tab-3" data-id="poll">3.poll</li>
                                        </ul>
                                    </div>
                                </li>
                                <li class="tab-link" data-tab="tab-2" id="st3">Merchandise</li>
                            </ul>



                        </div>

                    </div>

               <div class="main-block clearfix">
                  <div class="controlBar">
					  <div class="controlBar-content">
						<h1>Campaign / <span class="controlBar-context">Content</span></h1>
					  </div>

                          <div class="controlBar-controls" style="display: none;" id="preview_button">
                              <a class="manage_btn" id='prv_btn'  href="<?= get_site_url() ?>/?p=<?= $_SESSION['vd_slug'] ?>&preview=true" target="_blank" >Preview</a>
                          </div>
                      <div class="scr"></div>

				  </div>


                    <!--<a href="javascript:void(0)">Preview</a>-->
                    <div id="tab-1" class="tab-content current video_merch_contant">
                        <div class="col-md-6 col-sm-12 ">
                            <div class="video-item">
                                <?php echo do_shortcode( '[contact-form-7 id="855" title="User submit videos"]' );?>
                            </div>
                        </div>
						<div class="clearfix"></div>
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
                        <div class="col-md-6 col-sm-12 ">
                            <div class="woo-item">
                                <h3 id="mc">Please fill in the information below to submit your merchandise.</h3>
                                <form method="post" enctype="multipart/form-data" id="MerchandiseForm" name="mainForm">
                                    <div id="postTitleOuter">
                                       <div class="group-block">
                                        <label class="label-control"> Name of merchandise</label>
                                        <input type="text" name="postTitle" class="postTitle" required/></div>

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

                                        echo '<div class="group-block"><select id="merchandise_video_list" name="merchandise_video_id">';
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
                                        echo '</select></div>';
                                        ?>
                                    </div>
                                    <input id="crowdfund_2" type="hidden" name="crowdfund" />
                                    <!--<div id="postTitleOuter" class="crowdfund_cls">
                                       <div class="group-block">
                                        <label class="label-control"> Crowdfunding</label></br>

									</div>-->
                                    </div>
                                    <div>


                                        <div>
                                           <div class="group-block">
                                            <label class="label-control">Goal</label>
                                            <input type="text"  id="max" name="goal_price" class="postTitle" />
											</div>
                                        </div>
                                    </div>
                                    <div id="postContentOuter">
                                       <div class="group-block">
                                        <label class="label-control">Short description</label>
                                        <textarea rows="4" cols="20" class="postContent" name="postContent" required></textarea>
										</div>
                                    </div>

                                    <div id="postTitleOuter">
                                       <div class="group-block">
                                        <label class="label-control">Price</label>
                                        <input type="text" name="_regular_price" class="postTitle" id="quantity" required/>
                                        <span id="eveneror" style="display:none; color:red;"> Enter Only Odd Value</span>
										</div>
                                    </div>
                                    <div id="postImg">
                                       <div class="group-block">
                                        <label class="label-control">Merchandise Image</label>
                                        <div class="entreImage-placeholder">
                                            <input name="featured" type="file">
                                            <div class="tertiaryAction">
                                              <span class="tertiaryAction-icon tertiaryAction-icon--entre">
                                                <i class="fa fa-upload" aria-hidden="true"></i>
                                                </span>
                                              <span class="tertiaryAction-text tertiaryAction-text--entre ng-binding">Upload image</span>
                                            </div>
                                    </div>
                                        <span id="error"></span>
										</div>
                                    </div>
                                    <div class="group-block">
                                    <input type="submit" name="add_post" id="add_post" value="Add Merchandise" class="ld">
                                    <div class="clearfix"></div>
                                    </div>
                                </form>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                    <div id="tab-3" class="tab-content video_merch_contant">
                        <div class="col-md-6 col-sm-12 ">
                            <div class="woo-item">
                                <h3 id="sc">Please fill in the information below to submit your Pledge.</h3>
                                <form method="post" enctype="multipart/form-data" name="mainForm" id="PledgeForm">
                                    <div id="postTitleOuter" style="display: none">
                                        <p id="alreay_crow" style="color:#F9C73D; display:none;">This video already select for Crowdfunding</p>
                                        <label class="label-control"> Type of Pledge</label></br>
                                        <input type="radio" name="Pledgetype" value="166" id="bollot"/>Ballot
                                        <input id="bidd" type="radio" name="Pledgetype" value="167"/>Bid
                                        <input id="poll" type="radio" name="Pledgetype" value="poll"/>Poll
                                    </div>
                                    <input type="hidden" name="crowdfund" id="crowdfund_1" />
                                    <?php
                                    $args2 = array(
                                        'author'        =>  $userID,
                                        'orderby'       =>  'post_date',
                                        'order'         =>  'DESC',
                                        'posts_per_page' => -1 ,// no limit
                                        'post_status'=> array('publish', 'pending', 'draft','private', 'inherit')
                                    );
                                    $current_user_posts = get_posts( $args2 );
                                    echo '<div class="group-block"><select id="video_pledge" name="video_id">';
                                    echo '<option value="" selected="selected">Please pick a video below...</option>';
                                    foreach($current_user_posts as $post) : setup_postdata($post);
                                        ?>
                                        <option value="<?php echo $post->ID; ?>"><?php echo $post->post_title; ?></option>
                                    <?php endforeach;
                                    echo '</select></div>';
                                    ?>
                                    <?php
                                    echo '<div class="group-block"><select id="poll_video_list" name="poll_video_id" style="display:none;">';
                                    echo '<option value="" selected="selected">Please pick a video below...</option>';
                                    foreach($current_user_posts as $post) : setup_postdata($post);
                                        $post_id=$post->ID;
                                        $check = get_post_meta($post_id, 'poll_id', true);
                                        if($check){
                                        }else{

                                            ?>
                                            <option value="<?php echo $post->ID; ?>"><?php echo $post->post_title; ?></option>
                                        <?php } endforeach;
                                    echo '</select></div>';
                                    ?>

<!--                                    <div id="postTitleOuter" class="crowdfund_cls" style="display:none;">-->
<!--                                       <div class="group-block">-->
<!--                                        <label class="label-control"> Crowdfunding</label></br>-->
<!--                                        <input id="crowdfund_chkk" type="checkbox" name="crowdfund" value="yes"/></div>-->
<!--                                    </div>-->
                                    <div class="crowdfundingk">

                                        <div>
                                           <div class="group-block">
                                            <label class="label-control">Goal</label>
                                            <input type="text"  id="max" name="goal_price" class="postTitle" /></div>
                                        </div>
                                    </div>
                                    <div id="postTitleOuter">
                                       <div class="group-block">
                                        <label class="label-control" id="pledge_label"> Name of Pledge</label>
                                        <label class="label-control" id="poll_label" style="display:none;"> Name of Poll</label>
                                        <input type="text" name="PledgeTitle" class="postTitle" required/></div>

                                    </div>
                                    <div id="postContentOuter"><div class="group-block">
                                        <label class="label-control">Short description</label>

                                        <?php wp_editor('','newtestfield',array('textarea_name'=> 'PledgeContent','textarea_rows'=>7,'wpautop'=>false));?>
										</div>
                                    </div>
                                    <div id="postTitleOuter" style="display:none;">
                                       <div class="group-block">
                                        <label class="label-control">Price</label>
                                        <input type="text" name="_regular_price" value="0" class="postTitle" id="quantity"required/>
                                        <span id="errmsg"></span>
										</div>
                                    </div>
                                    <div >


                                    </div>

                                    <div id="cutofdate" style="display:none;">
                                       <div class="group-block">
                                        <label class="label-control">Bidding Cut-Off Date</label>
                                        <input type="text"  id="date" name="cut" class="postTitle" />
										</div>
                                    </div>

                                    <div id="level2">
                                       <div class="group-block">
                                        <label class="label-control"> Level Requirement</label>
                                        <input type="text"  id="levelval" name="level"   class="postTitle" />
                                        <span style="color:red" id="errmsg2"></span>
										</div>
                                    </div>
                                    <div id="manage" style="display:none;">
                                       <div class="group-block">
                                        <label class="label-control"> Quantity</label>
                                        <input type="text"  id="stock" name="stock" value="1" class="postTitle" />
                                        <span style="color:red" id="errmsg3"></span>
										</div>
                                    </div>
                                    <div id="gold" style="display:none;">
                                       <div class="group-block">
                                        <label class="label-control"> Gold Points Required For Purchase</label>
                                        <input type="text"  id="gold_val" name="gold" value="0" class="postTitle" />
                                        <span style="color:red" id="errmsg4"></span>
                                        <span id="evengold" style="display:none; color:red;"> Enter Only Odd Value</span>
										</div>
                                    </div>
                                    <div id="silver" style="display:none;">
                                       <div class="group-block">
                                        <label class="label-control"> Silver Points Required For Purchase</label>
                                        <input type="text"  id="silver_val" name="silver" value="0" class="postTitle" />
                                        <span style="color:red" id="errmsg5"></span>
                                        <span id="evensilver" style="display:none; color:red;"> Enter Only Odd Value</span>
										</div>
                                    </div>

                                    <div id="poll_fild" style="display:none;">
                                       <div class="group-block">
                                        <div class="input_fields_wrap">
                                            <button class="add_field_button manage_btn">Add More Poll Options</button>
                                            <div><input type="text" placeholder="Poll Option" name="poll_value[]"></div>
										   </div>
                                        </div>
                                    </div>
                                    <div class="group-block"><span class="ld"><input type="submit" name="add_pledge" id="add_pledge" value="Add Pledge">
                                        <input type="submit" name="add_poll" id="add_poll" value="Add Poll" style="display:none;"></div></span>
                                    <div class="clearfix"></div>
                                    </div>
                                </form>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                    <div class="clearfix"></div>

			  </div>
            </div>
         </div>

    </div>

<script>
    jQuery(function(){

        jQuery(function(){

            //jQuery("Input[name=text-271]").tagsInput();

            jQuery("#MerchandiseForm").submit(function(e){



                var data = new FormData(this);
                e.preventDefault();
                showLoader();


                jQuery.ajax({

                    url: "<?= admin_url('admin-ajax.php?action=save_merchandise') ?>",
                    type: "POST",
                    contentType:false,
                    processData:false,
                    data: data,
                    success:function(o){


                        jQuery('html, body').animate({
                            scrollTop: jQuery("#mc").offset().top - 50
                        }, 1000);
                        hideLoader();
                       // scrollTOControlBar();

                    }

                });
//
//              return false;



            });

            jQuery("#PledgeForm").submit(function(e){
                tinymce.get('newtestfield').save();
                var data =  new FormData(this);

                e.preventDefault();
                showLoader();



                jQuery.ajax({

                    url: "<?= admin_url('admin-ajax.php?action=save_pledge') ?>",
                    type: "POST",
                    contentType:false,
                    processData:false,
                    data: data,
                    success:function(o){

                        if(o == true){
                            jQuery("#st3").trigger("click");
                            jQuery('html, body').animate({
                                scrollTop: jQuery("#mc").offset().top - 50
                            }, 1000);
                        }

                        hideLoader();

                    }

                });



            });

            jQuery("#video_pledge,#poll_video_list,#merchandise_video_list").change(function(){

               var videoId = jQuery(this).val();
               jQuery.ajax({
                   type: "GET",
                   url:"<?= admin_url('admin-ajax.php') ?>",
                   data:{action:"getVideoCrowdfundingDuration",videoId:videoId},
                   success:function(d){

                      // alert(d);

                       jQuery("#crowdfund_1").val(d);
                       jQuery("#crowdfund_2").val(d);

                   }
               });

            });



        });

        function showLoader(){

           // jQuery('.ld').after("<img class='loader' src='<?= get_site_url() ?>/wp-content/themes/truemag/images/loader/30.gif'>");

        }

        function hideLoader(){

            //jQuery(".loader").hide();

        }









        jQuery(".custom-btn").show();

        if(jQuery(".left-sidebar").hasClass('open-nav')){
            jQuery('body').css("margin-left",'250px');
        }
         jQuery("#bollot").trigger('click');

        jQuery("#dm li").click(function(){
          var rid = jQuery(this).data('id');
          jQuery("#"+rid).trigger("click");
            jQuery("#dm li").removeClass("current");
            jQuery(this).addClass("current");
        });

        jQuery("#st2").click(function(){
           jQuery("#dm li").removeClass("current");
           jQuery("#blt").addClass("current");

        });


         jQuery('.menu-sub li').click(function(e) {
                    e.stopPropagation(); // prevents event e from going to parent
        });

                jQuery(".custom-btn").click(function(){

                    jQuery(".left-sidebar").toggleClass('open-nav');
                    if(jQuery(".left-sidebar").hasClass('open-nav')){
                        jQuery('body').css('margin-left', '250px');
                    }else{

                        jQuery('body').css('margin-left', '0px');
                    }

                });

                var stickySidebar = jQuery('.controlBar').offset().top;

                jQuery(window).scroll(function() {
                    if (jQuery(window).scrollTop() > stickySidebar) {

                       if(jQuery(".left-sidebar").hasClass('open-nav')){

                        jQuery('.controlBar').addClass('fixed');
                       }else{
                          jQuery('.controlBar').removeClass('fixed');
                       }

                    }
                    else {

                        jQuery('.controlBar').removeClass('fixed');
                    }


                });

                jQuery(".wpcf7-form").submit(function(){
                    tinyMCE.triggerSave();

                });

              //  jQuery("Input[name=stars]").tagsInput();
    });


</script>
<div class="clearfix"></div>
    <script src="http://maps.googleapis.com/maps/api/js?sensor=false&amp;libraries=places&key=AIzaSyD20xAeZIFYdz946srRKjrYPSarOezSp34"></script>
    <script src="<?= get_template_directory_uri().'/js/jquery.geocomplete.min.js' ?>"></script>

    <script>
        jQuery(document).ready(function(){
            jQuery('#location').geocomplete();
        });
    </script>
    <script type="javascript">
        function initialize() {
            autocomplete = new google.maps.places.Autocomplete(
                document.getElementById('location'),
                { types: ['geocode'] }
            );
        }
        google.maps.event.addDomListener(window, 'load', initialize);
    </script>
<?php get_footer(); ?>