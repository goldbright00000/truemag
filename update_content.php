
<?php
/* Template Name: update content Creator Page */ 
?>
<?php get_header();?>
<?php
 if (!is_user_logged_in() ) {
  
 wp_redirect(home_url());

}
// $current_user   = wp_get_current_user();
  //  $role_name      = $current_user->roles[0];
    // $people = array("content_creator", "administrator");
    // if (!in_array($role_name, $people)){
     //  echo'<script>
     //jQuery(".page-template").css("display","none");
     //window.location.href = "http://biggboss.info/premise";</script>';
    // }
      ?>
      <script>
jQuery(document).ready(function(){
  jQuery("#quantity").keypress(function (e) {
       //if the letter is not digit then display error and don't type anything
       if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
          //display error message
          jQuery("#errmsg").html("Digits Only").show().css("color","red").fadeOut("slow");
                 return false;
      }

  });
   jQuery('#date').datepicker({ dateFormat: 'dd-mm-yy' });
});
      </script>
<div class="container">
  <div class="row">
<?php

$edit_id= $_GET['edit'];
$tag =get_the_tags($edit_id);
 $tags= $tag[0]->slug;
$retrieve_data = $wpdb->get_results( "SELECT * FROM 413_posts where ID=$edit_id");
//echo"<pre>";
//print_r($retrieve_data);
//die();
  foreach ($retrieve_data as $query) {
        $status = $query->post_status;
    $postTitle = $query->post_title;
    $postContent = $query->post_content;
    $url = get_post_meta( $edit_id, 'tm_video_url', true );
    $email = get_post_meta( $edit_id, 'tm_user_submit', true );
    $Director= get_post_meta( $edit_id, 'director', true );
    $writer = get_post_meta( $edit_id, 'writer', true );
    $stars = get_post_meta( $edit_id, 'stars', true );
    $age = get_post_meta( $edit_id, 'age', true );
    $cut = get_post_meta( $edit_id, '_end_date', true );
    $post_type= $query->post_type;
    $post_project = get_post_meta( $edit_id, 'post_project', true );
    $post_updates = get_post_meta( $edit_id, 'post_updates', true );
    $post_faq = get_post_meta( $edit_id, 'post_faq', true );
    
  }
    if(isset($_POST['submit'])){
       $my_post = array(
              'ID'           => $edit_id,
              'post_title'   => $_POST['post_title'],
              'post_content' => $_POST['post_content'],
              'post_status'    => 'pending',
              'post_type'      => 'post'
          );
       $cat_list=$_POST['cat'];
       wp_set_post_terms( $edit_id,$cat_list, 'category' );
     

    wp_set_post_tags( $edit_id, $_POST['tag'], false );
// Update the post into the database
     wp_update_post( $my_post );
     update_post_meta($edit_id, 'post_project', $_POST['post_project'] );
     update_post_meta($edit_id, 'post_updates', $_POST['post_updates'] );
     update_post_meta($edit_id, 'post_faq', $_POST['post_faq'] );

     update_post_meta($edit_id, 'age', $_POST['age'] );
     update_post_meta($edit_id, '_end_date', $_POST['cut'] );
    $dir= update_post_meta($edit_id, 'director', $_POST['director'] );
    $wri= update_post_meta($edit_id, 'writer', $_POST['writer'] );
    $star= update_post_meta($edit_id, 'stars',$_POST['stars'] );
    $url= update_post_meta($edit_id, 'tm_video_url', $_POST['tm_video_url'] );
    $mail= update_post_meta($edit_id, 'tm_user_submit', $_POST['tm_user_submit'] );
     if($dir || $wri ||$star|| $url || $mail){
      echo '<div class="wpcf7-response-output wpcf7-display-none wpcf7-validation-errors" style="display: block;">update sucessfully.</div>';
     }
    }


?>

<?php if($post_type == 'post'){?>
      
      <div class="col-md-8 col-md-offset-2">
        <div class="woo-item update_merch">
        <h3 class="text-center update_title">Update Video</h3>
        <p>Please fill in the information below to submit your merchandise.</p>
        <form method="post" enctype="multipart/form-data" name="mainForm" action="">
                <!-- <div id="postTitleOuter">
                    <label> Your Email</label>
                    <input type="text" name="tm_user_submit" class="postTitle" value="<?php //echo $email; ?>" />
                </div> -->
                    <div id="postContentOuter">
                    <label>Video URL</label>
                     <input type="text" name="tm_video_url" class="postTitle" value="<?php echo $url; ?>" />
                </div>
                <div id="postTitleOuter">
                    <label>Video Title</label>
                    <input type="text" name="post_title" class="postTitle" value="<?php echo $postTitle; ?>" />
                    </div> 
                    <div id="postContentOuter">
                    <label>Video Description</label>

                      <?php wp_editor($postContent,'newtestfield',array('textarea_name'=> 'post_content','textarea_rows'=>7,'wpautop'=>false));?>
                    <!-- <textarea rows="4" cols="20" class="post_content" name="post_content"  ><?php echo $postContent; ?></textarea> -->
                </div>
                <div id="postContentOuter">
              <label style="padding-top: 6px;">Project</label>
              <?php wp_editor($post_project,'post_project',array('textarea_name'=> 'post_project','textarea_rows'=>7,'wpautop'=>false));?>
              </div> 
              <div id="postContentOuter">

              <label  style="padding-top: 6px;">Updates</label>
              <?php wp_editor($post_updates,'post_updates',array('textarea_name'=> 'post_updates','textarea_rows'=>7,'wpautop'=>false));?>
            </div>
           <div id="postContentOuter">

              <label  style="padding-top: 6px;">FAQ</label>
              <?php wp_editor($post_faq,'post_faq',array('textarea_name'=> 'post_faq','textarea_rows'=>7,'wpautop'=>false));?>
            </div>     
            
                    <div id="postContentOuter">
                    <label>Tags</label>
                    <input type="text" name="tag" class="postTitle" value="<?php echo $tags; ?>" />
                </div>
                 <div id="postContentOuter">
                    <label>Age Limit</label>
                    <input type="text" name="age" class="postTitle" value="<?php echo $age; ?>" />
                </div>
                 <div id="postContentOuter">
                    <label>Cut-off date</label>
                    <input type="text"  id="date" name="cut" class="postTitle" value="<?php echo $cut; ?>" />
                </div>
                <div id="postContentOuter">
                    <label>Director Name</label>
                     <input type="text" name="director" class="postTitle" value="<?php echo $Director; ?>" />
                </div>
                <div id="postTitleOuter">
                    <label>Writer Name</label>
                    <input type="text" name="writer" class="postTitle" value="<?php echo $writer; ?>" />
                    </div> 
                    <div id="postTitleOuter">
                    <label>Stars Name</label>
                    <input type="text" name="stars" class="postTitle" value="<?php echo $stars; ?>" />
                    </div> 
                     <div id="postTitleOuter">
                    <?php 
                 $categories = get_the_terms($edit_id, 'category' );
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
                    echo '<input type="checkbox" name="cat[]" checked  value="'.$cat->term_id.'" >'. $cat->name ."</br>"; 
                  }else{
                    echo '<input type="checkbox" name="cat[]"   value="'.$cat->term_id.'" >'. $cat->name ."</br>"; 
                  }
              }

?>
                      </div> 
                    <input type="submit" name="submit" id="add_post" value="Update">
                   
            </form>
    </div>
    </div>
  <?php } else if($post_type == 'product'){?>
<?php 

$data = $wpdb->get_results( "SELECT * FROM 413_posts where ID=$edit_id");
//echo'<pre>';
//print_r($data);
//die();
$_product = wc_get_product( $edit_id );
$price=$_product->get_regular_price();
  foreach ($data as $querys) {
    $postTitle = $querys->post_title;
    $postContent = $querys->post_content;   
   
  }
  if(isset($_POST['add_post'])){
    /*echo"<pre>";
    print_r($_FILES);
    die();*/
     $file_check=$_FILES['featured']['name'];
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
       $post = array(
              'ID'           => $edit_id,
              'post_title'   => $_POST['post_title'],
              'post_content' => $_POST['post_content'],
              'post_status'    => 'draft',
              'post_type'      => 'product'
          );
    $sql= wp_update_post( $post ); 

     if(!empty($file_check)){
    $image = update_post_meta($edit_id,'_thumbnail_id',$attach_id);
      set_post_thumbnail( $pid, $thumbnail_id );
    set_post_thumbnail_size( 300,300, true );
       }
    $price= update_post_meta($edit_id, '_regular_price', $_POST['_regular_price'] );
     if( $sql||$price||$image){
   echo '<div class="wpcf7-response-output wpcf7-display-none wpcf7-validation-errors" style="display: block;">update sucessfully</div>';
    } 
  }

 ?> 
          <div class="col-md-8 col-md-offset-2">
            <div class="woo-item update_merch">
              <h3 class="text-center update_title">Update merchandise</h3>
               
                <p>Please fill in the information below to submit your merchandise.</p>
                <form method="post" enctype="multipart/form-data" name="mainForm" action="">
                <div id="postTitleOuter">
                    <label> Name of Merchandise</label>
                    <input type="text" name="post_title" value="<?php echo $postTitle; ?>" class="postTitle" />

                    </div>
                    <div id="postContentOuter">
                    <label>Short Description</label>
                    <textarea rows="4" cols="20" name="post_content" class="postContent" name="postContent" ><?php echo $postContent; ?></textarea>
                </div>
                <div id="postTitleOuter">
                    <label>Price</label>
                    <input type="text" name="_regular_price" value="<?php echo $price; ?>"  class="postTitle" id="quantity" />
                    <span id="errmsg"></span>
                    <span id="eveneror" style="display:none; color:red;"> Enter Only Odd Value</span>
                    </div> 
                    <div id="postImg"> <label>Merchandises Image</label>
                    <input type="file" name="featured" class="size"/>
 <?php $image = wp_get_attachment_image_src( get_post_thumbnail_id( $edit_id ), 'single-post-thumbnail' );?>

    <img src="<?php  echo $image[0]; ?>" height="200" width="200">
                  </div>
                    <input type="submit" name="add_post" id="add_post" value="Update">
                   
            </form>
        </div>
    </div>

<?php } ?>
  </div>
</div>
<?php get_footer(); ?>