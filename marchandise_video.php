<?php 
/* Template Name: marchandise video */ 
get_header();
$login_id=$current_user->ID;
?>
<style type="text/css">
.submit_videos p:nth-child(3) {
 display: block !important;
}
.manage_btn {
  float: right;
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
.manage_contant {
  float: left;
  padding: 0 15px;
  width: 100%;
}
.manage_contant .manage_btn:hover {
  color: #fff;
}
.submit_videos {
  border: 1px solid #edd48a;
  padding: 10px;
}
.submit_another_pledge{
  border: 1px solid #edd48a;
  padding: 10px;
}
</style>

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
     add_post_meta($pid, '_alg_crowdfunding_enabled','yes');
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
?>
<?php
$msg_poll ="";
$msg_pledge ="";
$sucessmsg="";
   if(isset($_POST['addvideo'])){
       $my_post = array(
              'post_author' => $login_id,
              'post_title'   => $_POST['post_title'],
              'post_content' => $_POST['post_content'],
               
              'post_type'      => 'post',
               'post_status'    => 'draft'
          );
        $postid = wp_insert_post( $my_post );
       $cat_list=$_POST['cat'];
       set_post_format( $postid, 'video' );

      wp_set_post_terms( $postid,$cat_list, 'category' );
      wp_set_post_tags( $postid, $_POST['tag'], false );
// Update the post into the database
    $post_project= add_post_meta($postid, 'post_project', $_POST['post_project'] );
    $post_updates= add_post_meta($postid, 'post_updates', $_POST['post_updates'] );
    $faq= add_post_meta($postid, 'post_faq', $_POST['post_faq'] );
    $dir= add_post_meta($postid, 'director', $_POST['director'] );
    $wri= add_post_meta($postid, 'writer', $_POST['writer'] );
    $star= add_post_meta($postid, 'stars',$_POST['stars'] );
    $url= add_post_meta($postid, 'tm_video_url', $_POST['tm_video_url'] );
     if($dir || $wri ||$star|| $url){
       $email =get_bloginfo('admin_email');
      $post_title   = $_POST['post_title'];
      $post_content = $_POST['post_content'];
      $header .= "MIME-Version: 1.0\n";
      $header .= "Content-Type: text/html; charset=utf-8\n";
      $message = "Post Title: $post_title\n";
      $message .= "Post content: $post_content\n";
      $subject = "Product content";
      $subject = "=?utf-8?B?" . base64_encode($subject) . "?=";
      $to = $email;
      // send the email using wp_mail()
      wp_mail($to, $subject, $message, $header);
     // $msg= '<div class="wpcf7-response-output wpcf7-display-none wpcf7-validation-errors" style="display: block;">Your merchandise is now successfully added. It will be included in the shop after it is approved by admin.</div>';
      $sucessmsg= '<div class="wpcf7-response-output wpcf7-display-none wpcf7-validation-errors" style="display: block;">Video url has been submitted. It will be broadcasted on the site once it is approved by admin.</div>';
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
   
 $msg_pledge= '<div class="wpcf7-response-output wpcf7-display-none wpcf7-validation-errors" style="display: block;">Your Pledge is now successfully added. It will be included in the video Pledge list after it is approved by admin.</div>';

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
      
       $msg_poll= '<div class="wpcf7-response-output wpcf7-display-none wpcf7-validation-errors" style="display: block;">Your Poll is now successfully added. </div>';
    }

}
?>
<div class="container">
  <div class="row">
      <div class="col-md-12">
          <div class="merchandic_title">
              <h2>merchandise</h2>
                <h3><i class="fa fa-arrows" aria-hidden="true"></i> Costomize Placement</h3>
            </div>
            <div class="manage_contant">
              <a class="manage_btn" href="http://www.premise.tv/edit-content">Manage Your Content</a>
            </div>
        </div>
    </div>
</div>

<?php
$args = array(
        'posts_per_page'   => -1,
        'author' => $login_id,
        'post_type' => 'product',
        'post_status' => array('publish', 'pending', 'draft' ),
        'product_cat' => 'merchandise',
    );
    $author_posts = get_posts( $args );
    if(empty($author_posts)){
      echo '<style>#itemslider .carousel-control {display: none;}</style>';
    }
/*echo "<pre>";
print_r($author_posts); */
?>
<!-- Item slider-->
<div class="container">
  <div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12">
     
      <div class="carousel product-2 carousel-showmanymoveone slide" id="itemslider">
        <div class="carousel-inner">

  <?php foreach ($author_posts as $author_post){ 
  $attachment_ids[0] = get_post_thumbnail_id( $author_post->ID );
                         $attachment = wp_get_attachment_image_src($attachment_ids[0], 'full' );?>
          <div class="item">
            <div class="col-xs-12 col-sm-6 col-md-3">

              <div class="img_video">
             <?php if($attachment !="") {?>
              <a href="<?php echo get_permalink($author_post->ID ); ?>"><img src="<?php echo $attachment[0]; ?>" class="img-responsive center-block"></a>
              <?php } else{?>
    <a href="javascript:void(0)"><img src="<?php echo bloginfo('template_directory');?>/images/image.png" class="img-responsive center-block"></a>

            <?php  }?>
          </div>
              <div class="slider_processing"><?php echo $author_post->post_status; ?></div>
                  <div class="slider_dis">
                    <p class="slider_text"><?php echo $author_post->post_title; ?></p>
                    <p class="slider_price"><?php echo get_post_meta( $author_post->ID, '_event_name',true );?></p>
                  </div>
            </div>
          </div>
<?php  } ?>

        </div>
        <div class="container">
          <?php echo  $msg; ?>

            <div class="row">
                <div class="col-md-12">
                     <div  id="upload_another_item" class="another_item" >
                        <a href="javascript:void(0)"><i class="fa fa-plus" aria-hidden="true"></i>upload another item</a>
                      </div>
                </div>
            </div>
        </div>
       
        
        <div id="slider-control">
        <span class="left carousel-control" href="#itemslider" data-slide="prev"><img src="https://s12.postimg.org/uj3ffq90d/arrow_left.png" alt="Left" class="img-responsive"></span>
        <span class="right carousel-control" href="#itemslider" data-slide="next"><img src="https://s12.postimg.org/djuh0gxst/arrow_right.png" alt="Right" class="img-responsive"></span>
      </div>
      <div class="col-md-12">
      <div class="submit_another_video" style="display:none;">
        <form method="post" enctype="multipart/form-data" name="mainForm" id="MyForm" action="">
        <div id="postTitleOuter" class="merchandise_name">
            <label> Name of merchandise</label>
            <input type="text" name="postTitle" class="postTitle" id="postTitle" required/>
        </div>
        <div id="postTitleOuter" class="pick_video">
            <label> Name of video</label>
            <?php
            $userID=$current_user->ID;
            echo '<select id="merchandise_video_list" name="merchandise_video_id">';
            echo '<option value="" selected="selected">Please pick a video below...</option>';
            $args = array(
              'author'        =>  $userID, 
              'orderby'       =>  'post_date',
              'order'         =>  'DESC',
              'posts_per_page' => -1 ,// no limit
              'post_status'=> array('publish', 'pending', 'draft','private', 'inherit')
            );
            $current_user_posts = get_posts( $args );
            foreach($current_user_posts as $post) : setup_postdata($post);
            $post_id=$post->ID;
            $check = get_post_meta($post_id, 'merchandise_video_id', true);
            if($check){
              }else{ 

            ?>
            <option value="<?php echo $post->ID; ?>"><?php echo $post->post_title; ?></option>
            <?php } endforeach;
            echo '</select>';
            ?>
        </div>
        <div id="postTitleOuter" class="crowdfund_cls">
            <label> Crowdfunding</label>
            <input id="crowdfund_chk" type="checkbox" name="crowdfund" value="yes"/>
     </div>
      <div class="crowdfunding" class="" style="display:none;">
          <div id="startofdate">
              <label>Start Date</label>
              <input type="text"  id="datepicker" name="start_date" class="postTitle" />
          </div>
          <div id="start_time">
              <label>Start Time</label>
              <select name="start_time" id="starttime">
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
              <select name="end_time" id="end_time" >
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
                <input type="text"  id="max" name="goal_price" class="postTitle numeric" />
           <span class="error" style="color: Red; display: none"> Input digits (0 - 9)</span>
        </div>
    </div>
            <div id="postContentOuter" class="shrt_description">
              <label>Short description</label>
              <textarea rows="4" cols="20" class="postContent" id="postContent" name="postContent" required></textarea>
          </div>
       
        <div id="postTitleOuter" class="shrt_price">
            <label>Price</label>
            <input type="text" name="_regular_price" class="postTitle numerics" id="quantity" required/>
            <span id="eveneror" style="display:none; color:red;"> Enter Only Odd Value</span>
 <span class="errors" style="color: Red; display: none"> Input digits (0 - 9)</span>
        </div> 
        <div id="postImg" class="merchandise_image"> <label>Merchandise Image</label>
            <input type="file" name="featured" id="user_profile_pic" class="size"/>
            <span id="error"></span>
        </div>
            <input type="submit" name="add_post" id="add_post" value="Add Merchandise">
    </form>
    </div>
      </div>
      </div>
      
      
      
      <div class="container">
  <div class="row">
      <div class="col-md-12">
          <div class="videos_title">
              <h2>Videos</h2>
                <h3><i class="fa fa-arrows" aria-hidden="true"></i> Costomize Placement</h3>
            </div>
        </div>
    </div>
</div>
      
<?php  
  $arg = array(
      'posts_per_page'   => -1,
        'author' => $login_id,
        'post_type' => 'post',
        'post_status' => array('publish', 'pending', 'draft' )
      );
  $retrieve_data = get_posts( $arg );
   if(empty($retrieve_data)){
      echo '<style>#itemslider1 .carousel-control {display: none;}</style>';
    }
        /* echo "<pre>";
  print_r($retrieve_data);*/
?>
        <div class="carousel product-3 carousel-showmanymoveone slide" id="itemslider1">
        <div class="carousel-inner">
        <?php foreach($retrieve_data as $data){ ?>
          <div class="item">
            <div class="col-xs-12 col-sm-6 col-md-4">
              <div class="img_slide">
              <?php 
                $img = get_post_meta( $data->ID, '_video_thumbnail', true );

              if($img !=""){ ?>
               <a href="<?php echo get_permalink($data->ID ); ?>">
                <img src="<?php echo get_post_meta( $data->ID, '_video_thumbnail', true ); ?>" class="img-responsive center-block">
              </a>
               <?php } else { ?>
               <a href="javascript:void(0)"><img src="<?php echo bloginfo('template_directory');?>/images/image.png" class="img-responsive center-block"></a>
               <?php } ?>
             </div>
              <div class="slider_processing"><?php echo $data->post_status; ?></div>
                  <div class="slider_dis">
                    <p class="slider_text"><?php echo $data->post_title;?></p>
                    <p class="slider_price"></p>
                  </div>
            </div>
          </div>
          <?php } ?>

        </div>
        
         <div class="container">
          <?php echo  $sucessmsg; ?>
            <div class="row">
                <div class="col-md-12">
                     <div class="another_item" id="upload_another_video">
                        <a href="javascript:void(0)"><i class="fa fa-plus" aria-hidden="true"></i>upload another video</a>
                      </div>
                </div>
            </div>
        </div>
      <div id="slider-control">
        <span class="left carousel-control" href="#itemslider1" data-slide="prev"><img src="https://s12.postimg.org/uj3ffq90d/arrow_left.png" alt="Left" class="img-responsive"></span>
        <span class="right carousel-control" href="#itemslider1" data-slide="next"><img src="https://s12.postimg.org/djuh0gxst/arrow_right.png" alt="Right" class="img-responsive"></span>
      </div> 
      <div class="col-md-12">
       <div class="submit_videos" style="display:none;">


 <form method="post">
            <p>
            <label>Video URL</label>    
            <input type="text" name="tm_video_url" id="url" value="" required>
            <span class="err"><span>  
            </p>  
            <p>
              <label>Video Title</label>
              <input type="text" name="post_title" value="" required>
            </p> 
             <p>
              <label>Video Description</label>
              <?php wp_editor('','post_content',array('textarea_name'=> 'post_content','textarea_rows'=>7,'wpautop'=>false));?>
            </p> 
              <p style="padding-top: 14px;">
              <label>Project</label>
              <?php wp_editor('','post_project',array('textarea_name'=> 'post_project','textarea_rows'=>7,'wpautop'=>false));?>
            </p> 
              <p style="padding-top: 14px;">

              <label>Updates</label>
              <?php wp_editor('','post_updates',array('textarea_name'=> 'post_updates','textarea_rows'=>7,'wpautop'=>false));?>
            </p>
            <p style="padding-top: 14px;">

              <label>FAQ</label>
              <?php wp_editor('','post_faq',array('textarea_name'=> 'post_faq','textarea_rows'=>7,'wpautop'=>false));?>
            </p>     
            <p>

              <label>Tags</label>
              <input type="text" name="tag" value="">
            </p>     
             <p>
              <label>Director Name</label>
              <input type="text" name="director" value="">
            </p>  
             <p>
              <label>Writer Name</label>
              <input type="text" name="writer" value="">
            </p> 
             <p>
              <label>Stars Name</label>
              <input type="text" name="stars" value="">
            </p> 
            <p>
              <label>Categories</label>
              <div class="cat_out">
              <?php 
                 $categories = get_the_terms('category' );
                  foreach( $categories as $category ) {

                      $m[]=$category->term_id;
                    } 
                  $taxonomy     = 'category';
                  $orderby      = 'name';  
                  $show_count   = 0;      // 1 for yes, 0 for no
                  $pad_counts   = 0;      // 1 for yes, 0 for no
                  $hierarchical = 1;      // 1 for yes, 0 for no  
                  $title        = '';  
                  $empty        = 0;
                  $args = array(
                         'taxonomy'     => $taxonomy,
                         'orderby'      => $orderby,
                         'show_count'   => $show_count,
                         'pad_counts'   => $pad_counts,
                         'hierarchical' => $hierarchical,
                         'title_li'     => $title,
                         'hide_empty'   => $empty
                  );
            
               $all_categories = get_categories( $args );
              foreach ($all_categories as $cat) {
                $category_id = $cat->term_id; 
                  if (in_array($category_id, $m)){
                    echo '<div class="cat_list"><input type="checkbox" name="cat[]" checked  value="'.$cat->term_id.'" >'. $cat->name."</div>"; 
                  }else{
                    echo '<div class="cat_list"><input type="checkbox" name="cat[]"   value="'.$cat->term_id.'" >'. $cat->name."</div>";
                  }
              }
              ?>
              </div>
            </p>    
            <p>
              <input type="submit" name="addvideo" value="submit">
            </p>  
            </form>   

    <?php //echo do_shortcode( '[contact-form-7 id="855" title="User submit videos"]' );?>
    </div>
    </div>
      </div>
      <div class="container">
  <div class="row">
      <div class="col-md-12">
          <div class="videos_title">
              <h2>Pledge</h2>
                <h3><i class="fa fa-arrows" aria-hidden="true"></i> Costomize Placement</h3>
            </div>
        </div>
    </div>
</div>
 <div class="carousel product-4 carousel-showmanymoveone slide" id="itemslider">
   
        <div class="container">
          <?php echo  $msg_pledge; echo $msg_poll;?>

            <div class="row">
                <div class="col-md-12">
                     <div  id="upload_another_pledge" class="another_item" >
                        <a href="javascript:void(0)"><i class="fa fa-plus" aria-hidden="true"></i>upload another Pledge</a>
                      </div>
                </div>
            </div>
        </div>
       
        
       <!--  <div id="slider-control">
        <span class="left carousel-control" href="#itemslider" data-slide="prev"><img src="https://s12.postimg.org/uj3ffq90d/arrow_left.png" alt="Left" class="img-responsive"></span>
        <span class="right carousel-control" href="#itemslider" data-slide="next"><img src="https://s12.postimg.org/djuh0gxst/arrow_right.png" alt="Right" class="img-responsive"></span>
      </div>
 -->      <div class="col-md-12">
        <div class="submit_another_pledges" style="display:none;">
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
  </div>
</div>


 
<style>
    span.carousel-control{cursor: pointer}
</style>
<script>
jQuery(document).ready(function(){
    jQuery("#slider-control a").click(function(e){e.preventDefault()});
  jQuery(".wpcf7-email").val();
  jQuery("#itemslider .carousel-inner .item:first-child").addClass("active");
   jQuery("#itemslider1 .carousel-inner .item:first-child").addClass("active");
 
 jQuery("#upload_another_item").click(function(){
   jQuery(".submit_another_video").toggle();
 }); 
jQuery("#upload_another_video").click(function(){
   jQuery(".submit_videos").toggle();
 });
jQuery("#upload_another_pledge").click(function(){
   jQuery(".submit_another_pledge").toggle();
 });
     jQuery('#date').datepicker({ dateFormat: 'dd-mm-yy' });
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
});

</script>
 <script type="text/javascript">
        var specialKeys = new Array();
        specialKeys.push(8); //Backspace
        jQuery(function () {
            jQuery(".numeric").bind("keypress", function (e) {
                var keyCode = e.which ? e.which : e.keyCode
                var ret = ((keyCode >= 48 && keyCode <= 57) || specialKeys.indexOf(keyCode) != -1);
                jQuery(".error").css("display", ret ? "none" : "inline");
                return ret;
            });
            jQuery(".numeric").bind("paste", function (e) {
                return false;
            });
            jQuery(".numeric").bind("drop", function (e) {
                return false;
            });
        });
    </script>
 <script type="text/javascript">
        var specialKeys = new Array();
        specialKeys.push(8); //Backspace
        jQuery(function () {
            jQuery(".numerics").bind("keypress", function (e) {
                var keyCode = e.which ? e.which : e.keyCode
                var ret = ((keyCode >= 48 && keyCode <= 57) || specialKeys.indexOf(keyCode) != -1);
                jQuery(".errors").css("display", ret ? "none" : "inline");
                return ret;
            });
            jQuery(".numerics").bind("paste", function (e) {
                return false;
            });
            jQuery(".numerics").bind("drop", function (e) {
                return false;
            });
        });
    </script>
    <script>
jQuery(document).ready(function(){

 jQuery('#itemslider,#itemslider1').carousel({ interval: 2000 });
});

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
  jQuery('.product-2.carousel-showmanymoveone .item').each(function(){
    var itemToClone = jQuery(this);

    for (var i=1;i<4;i++) {
      itemToClone = itemToClone.next();


      if (!itemToClone.length) {
        itemToClone = jQuery(this).siblings(':first');
      }


      itemToClone.children(':first-child').clone()
        .addClass("cloneditem-"+(i))
        .appendTo(jQuery(this));
    }
  });
     jQuery('.product-3.carousel-showmanymoveone .item').each(function(){
    var itemToClone = jQuery(this);

    for (var i=1;i<3;i++) {
      itemToClone = itemToClone.next();


      if (!itemToClone.length) {
        itemToClone = jQuery(this).siblings(':first');
      }


      itemToClone.children(':first-child').clone()
        .addClass("cloneditem-"+(i))
        .appendTo(jQuery(this));
    }
  });
       jQuery('.product-4.carousel-showmanymoveone .item').each(function(){
    var itemToClone = jQuery(this);

    for (var i=1;i<3;i++) {
      itemToClone = itemToClone.next();


      if (!itemToClone.length) {
        itemToClone = jQuery(this).siblings(':first');
      }


      itemToClone.children(':first-child').clone()
        .addClass("cloneditem-"+(i))
        .appendTo(jQuery(this));
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
});

    </script>
<?php get_footer();?>