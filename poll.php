<?php
/* Template Name: poll.php */ 
get_header();
if (!is_user_logged_in() ) {
  wp_redirect(home_url());
}
$current_user   = wp_get_current_user();
 $userID=$current_user->ID;
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
  });
</script>
<div class="container">
  <div class="row">
      <?php
        $edit_id= $_GET['edit'];
        $data = $wpdb->get_results( "SELECT * FROM 413_posts where ID=$edit_id");
        global $wpdb;
        $silver= get_post_meta( $edit_id, 'silver',true);
        $level= get_post_meta( $edit_id, 'level',true ); 
          foreach ($data as $querys) {
            $postTitle = $querys->post_title;
            $postContent = $querys->post_content;   
           
          }
          if(isset($_POST['add_poll'])){
                 $post = array(
                      'ID'           => $edit_id,
                      'post_title'   => $_POST['post_title'],
                      'post_content' => $_POST['post_content'],
                      'post_status'    => 'publish',
                      'post_type'      => 'poll'
                  );
            $sql= wp_update_post( $post ); 
              $silver_points= intval(update_post_meta( $edit_id, 'silver',$_POST['silver'] ));
              $level_pledge= intval(update_post_meta( $edit_id, 'level',$_POST['level'] ));
          }
         if( $sql||$price){
   echo '<div class="wpcf7-response-output wpcf7-display-none wpcf7-validation-errors" style="display: block;">update sucessfully</div>';
    } 
        ?> 
          <!--pledge code start here-->
      <div class="col-md-8 col-md-offset-2">
            <div class="woo-item update_merch">
              <h3 class="text-center update_title">Update poll</h3>
                <p>Please fill in the information below to submit your pledge.</p>
                <form method="post" enctype="multipart/form-data" name="mainForm" action="">
                    <div id="postTitleOuter">
                      <label> Name of Poll</label>
                      <input type="text" name="post_title" value="<?php echo $postTitle; ?>" class="postTitle" />
                    </div>
                      <div id="postContentOuter">
                          <label>Short Description</label>
                          <?php wp_editor($postContent,'newtestfield',array('textarea_name'=> 'post_content','textarea_rows'=>7,'wpautop'=>false));?>
                      </div>
                    <div id="level">
                      <label> Level Requirement</label>
                      <input type="text" value="<?php echo $level;?>"  id="levelval" name="level" class="postTitle"/>
                    </div>  
                 <div id="silver">
                    <label> Silver Points Required For Purchase</label>
                    <input type="text"  id="silver_val" name="silver" value="<?php echo $silver;?>" class="postTitle" />
                     <span id="evensilver" style="display:none; color:red;"> Enter Only Odd Value</span>
                </div>
                    <input type="submit" name="add_poll" id="add_poll" value="Update Poll">
                </form>
            </div>
      </div>
  </div>
</div>
<?php get_footer(); ?>