<?php
/* Template Name: pledge.php */ 
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
   jQuery('#date').datepicker({ dateFormat: 'dd-mm-yy' });
     jQuery('#datepicker').datepicker({ dateFormat: 'yy/mm/dd' });
      jQuery('#datepicked').datepicker({ dateFormat: 'yy/mm/dd' });
});
      </script>
<div class="container">
  <div class="row">
    <?php
$edit_id= $_GET['edit'];
$data = $wpdb->get_results( "SELECT * FROM 413_posts where ID=$edit_id");
$cut = get_post_meta( $edit_id, '_end_date', true );
global $wpdb;
                   $tdata = $wpdb->get_row( "SELECT * FROM `413_postmeta` WHERE `meta_key`='pledge_id' AND`meta_value` LIKE '%$edit_id%' " );
                   $post_id=$tdata->post_id;
//echo'<pre>';
//print_r($data);
//die();
$term_list = wp_get_post_terms($edit_id,'product_cat',array('fields'=>'ids'));
$cat_id = (int)$term_list[0];
  $gold= get_post_meta( $edit_id, 'gold_points',true );
  $silver= get_post_meta( $edit_id, 'silver_points',true);
  $level= get_post_meta( $edit_id, 'level_pledge',true ); 
  $stock= get_post_meta( $edit_id, '_stock',true ); //level_pledge //_stock
  $crowd= get_post_meta( $edit_id, '_alg_crowdfunding_enabled',true );
  $startdate= get_post_meta( $edit_id, '_alg_crowdfunding_startdate',true );
  $starttime= get_post_meta( $edit_id, '_alg_crowdfunding_starttime',true );
  $end_date= get_post_meta( $edit_id, '_alg_crowdfunding_deadline',true );
  $end_time= get_post_meta( $edit_id, '_alg_crowdfunding_deadline_time',true );
  $goal_sum= get_post_meta( $edit_id, '_alg_crowdfunding_goal_sum',true );

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
         $post = array(
              'ID'           => $edit_id,
              'post_title'   => $_POST['post_title'],
              'post_content' => $_POST['post_content'],
              'post_status'    => 'publish',
              'post_type'      => 'product'
          );
    $sql= wp_update_post( $post ); 
      $gold_points= intval(update_post_meta( $edit_id, 'gold_points',$_POST['gold'] ));
      $silver_points= intval(update_post_meta( $edit_id, 'silver_points',$_POST['silver'] ));
      $level_pledge= intval(update_post_meta( $edit_id, 'level_pledge',$_POST['level'] ));
      $_stock= intval(update_post_meta( $edit_id, '_stock',$_POST['stock'] ));
      $start= intval(update_post_meta( $edit_id, '_alg_crowdfunding_startdate',$_POST['start_date'] ));
      $enddate= intval(update_post_meta( $edit_id, '_alg_crowdfunding_deadline',$_POST['end_date'] ));
      $goal= intval(update_post_meta( $edit_id, '_alg_crowdfunding_goal_sum',$_POST['goal_price'] ));
      $startime= intval(update_post_meta( $edit_id, '_alg_crowdfunding_starttime',$_POST['start_time'] ));
      $endintime= intval(update_post_meta( $edit_id, '_alg_crowdfunding_deadline_time',$_POST['end_time']));
       update_post_meta($edit_id, '_end_date', $_POST['cut'] );

       $havemeta = get_post_meta($_POST['video_id'], 'pledge_id', true);

     if(metadata_exists( 'post',$_POST['video_id'], 'pledge_id' )  ){
      $havemeta=$havemeta.','.$edit_id;
      
      if(update_post_meta( $_POST['video_id'], 'pledge_id', $havemeta))
      {
        
        $remove=get_post_meta($post_id, 'pledge_id', true); 
          $pledgearr=explode(",",$remove);
        //  print_r($pledgearr);
          $detils = array_diff($pledgearr, array($edit_id));
          //print_r($detils);
         // $detils = array_delete($edit_id, $pledgearr);
         $updatearr=implode(",",$detils);
         update_post_meta( $post_id, 'pledge_id', $updatearr);

      }

    } else{
        if(add_post_meta( $_POST['video_id'], 'pledge_id', $edit_id, true )){
          $remove=get_post_meta($post_id, 'pledge_id', true); 
          $pledgearr=explode(",",$remove);
          //print_r($pledgearr);
          $detils = array_diff($pledgearr, array($edit_id));
         // $detils = array_delete($edit_id, $pledgearr);
         $updatearr=implode(",",$detils);
         update_post_meta( $post_id, 'pledge_id', $updatearr);
        }
    } 

     $price= update_post_meta($edit_id, '_regular_price', $_POST['_regular_price'] );
     if( $sql||$price){
   echo '<div class="wpcf7-response-output wpcf7-display-none wpcf7-validation-errors" style="display: block;">update sucessfully</div>';
    } 
  }

 ?> 
          <!--pledge code start here-->
    <div class="col-md-8 col-md-offset-2">
            <div class="woo-item update_merch">
              <h3 class="text-center update_title">Update pledge</h3>
               
                <p>Please fill in the information below to submit your pledge.</p>
                <form method="post" enctype="multipart/form-data" name="mainForm" action="">
                     <?php $args = array(
                      'author'        =>  $userID, 
                      'orderby'       =>  'post_date',
                      'order'         =>  'ASC',
                      'posts_per_page' => -1, // no limit
                      'post_status'=> array('publish', 'pending', 'draft','private', 'inherit')
                    );
                    

                    $current_user_posts = get_posts( $args );
                    ?>
                    <label>Name of Video</label>
                    <?php
                    echo '<select name="video_id">';
                   
                    foreach($current_user_posts as $post) : setup_postdata($post);
                    ?>
                        <option  value="<?php echo $post->ID; ?>" <?php if($post_id==$post->ID){echo 'selected';}?>><?php echo $post->post_title; ?></option>
                    <?php endforeach;
                    echo '</select>';
                    ?>
                      
                <div id="postTitleOuter">
                    <label> Name of Pledge</label>
                    <input type="text" name="post_title" value="<?php echo $postTitle; ?>" class="postTitle" />

                    </div>
                    <div id="postContentOuter">
                    <label>Short Description</label>
                       <?php wp_editor($postContent,'newtestfield',array('textarea_name'=> 'post_content','textarea_rows'=>7,'wpautop'=>false));?>
                   <!--  <textarea rows="4" cols="20" name="post_content" class="postContent" name="postContent" ><?php //echo $postContent; ?></textarea> -->
                </div>
                <div id="postTitleOuter" style="display:none;">
                    <label>Price</label>
                    <input type="text" name="_regular_price" value="<?php echo $price; ?>"  class="postTitle" id="quantity" />
                    <span id="errmsg"></span>
                </div>
                <div id="level" >
                    <label> Level Requirement</label>
                    <input type="text" value="<?php echo $level;?>"  id="levelval" name="level" class="postTitle" />
                </div>
                <?php if( $crowd==yes){ ?>
                <div id="startofdate">
                      <label>Start Date</label>
                      <input type="text"  id="datepicker" value="<?php echo $startdate;?>" name="start_date" class="postTitle" />
                </div>
                <div id="start_time">
                      <label>Start Time</label>
                      <select name="start_time" >   
                        <option value="12:00am"<?php if ($starttime == '12:00am') echo ' selected="selected"'; ?>>12:00am</option>
                        <option value="12:30am"<?php if ($starttime == '12:30am') echo ' selected="selected"'; ?>>12:30am</option>
                        <option value="01:00am"<?php if ($starttime == '01:00am') echo ' selected="selected"'; ?>>01:00am</option>
                        <option value="01:30am"<?php if ($starttime == '01:30am') echo ' selected="selected"'; ?>>01:30am</option>
                        <option value="02:00am"<?php if ($starttime == '02:00am') echo ' selected="selected"'; ?>>02:00am</option>
                        <option value="02:30am"<?php if ($starttime == '02:30am') echo ' selected="selected"'; ?>>02:30am</option>
                        <option value="03:00am"<?php if ($starttime == '03:00am') echo ' selected="selected"'; ?>>03:00am</option>
                        <option value="03:30am"<?php if ($starttime == '03:30am') echo ' selected="selected"'; ?>>03:30am</option>
                        <option value="04:00am"<?php if ($starttime == '04:00am') echo ' selected="selected"'; ?>>04:00am</option>
                        <option value="04:30am"<?php if ($starttime == '04:30am') echo ' selected="selected"'; ?>>04:30am</option>
                        <option value="05:00am"<?php if ($starttime == '05:00am') echo ' selected="selected"'; ?>>05:00am</option>
                        <option value="05:30am"<?php if ($starttime == '05:30am') echo ' selected="selected"'; ?>>05:30am</option>
                        <option value="06:00am"<?php if ($starttime == '06:00am') echo ' selected="selected"'; ?>>06:00am</option>
                        <option value="06:30am"<?php if ($starttime == '06:30am') echo ' selected="selected"'; ?>>06:30am</option>
                        <option value="07:00am"<?php if ($starttime == '07:00am') echo ' selected="selected"'; ?>>07:00am</option>
                        <option value="07:30am"<?php if ($starttime == '07:30am') echo ' selected="selected"'; ?>>07:30am</option>
                        <option value="08:00am"<?php if ($starttime == '08:00am') echo ' selected="selected"'; ?>>08:00am</option>
                        <option value="08:30am"<?php if ($starttime == '08:30am') echo ' selected="selected"'; ?>>08:30am</option>
                        <option value="09:00am"<?php if ($starttime == '09:00am') echo ' selected="selected"'; ?>>09:00am</option>
                        <option value="09:30am"<?php if ($starttime == '09:30am') echo ' selected="selected"'; ?>>09:30am</option>
                        <option value="10:00am"<?php if ($starttime == '10:00am') echo ' selected="selected"'; ?>>10:00am</option>
                        <option value="10:30am"<?php if ($starttime == '10:30am') echo ' selected="selected"'; ?>>10:30am</option>
                        <option value="11:00am"<?php if ($starttime == '11:00am') echo ' selected="selected"'; ?>>11:00am</option>
                        <option value="11:30am"<?php if ($starttime == '11:30am') echo ' selected="selected"'; ?>>11:30am</option>
                        <option value="12:00pm"<?php if ($starttime == '12:00pm') echo ' selected="selected"'; ?>>12:00pm</option>
                        <option value="12:30pm"<?php if ($starttime == '12:30pm') echo ' selected="selected"'; ?>>12:30pm</option>
                        <option value="01.00pm"<?php if ($starttime == '01.00pm') echo ' selected="selected"'; ?>>01.00pm</option>
                        <option value="01.30pm"<?php if ($starttime == '01.30pm') echo ' selected="selected"'; ?>>01.30pm</option>
                        <option value="02.00pm"<?php if ($starttime == '02.00pm') echo ' selected="selected"'; ?>>02.00pm</option>
                        <option value="02.30pm"<?php if ($starttime == '02.30pm') echo ' selected="selected"'; ?>>02.30pm</option>
                        <option value="03.00pm"<?php if ($starttime == '03.00pm') echo ' selected="selected"'; ?>>03.00pm</option>
                        <option value="03.30pm"<?php if ($starttime == '03.30pm') echo ' selected="selected"'; ?>>03.30pm</option>
                        <option value="04.00pm"<?php if ($starttime == '04.00pm') echo ' selected="selected"'; ?>>04.00pm</option>
                        <option value="04.30pm"<?php if ($starttime == '04.30pm') echo ' selected="selected"'; ?>>04.30pm</option>
                        <option value="05.00pm"<?php if ($starttime == '05.00pm') echo ' selected="selected"'; ?>>05.00pm</option>
                        <option value="05.30pm"<?php if ($starttime == '05.30pm') echo ' selected="selected"'; ?>>05.30pm</option>
                        <option value="06.00pm"<?php if ($starttime == '06.00pm') echo ' selected="selected"'; ?>>06.00pm</option>
                        <option value="06.30pm"<?php if ($starttime == '06.30pm') echo ' selected="selected"'; ?>>06.30pm</option>
                        <option value="07.00pm"<?php if ($starttime == '07.00pm') echo ' selected="selected"'; ?>>07.00pm</option>
                        <option value="07.30pm"<?php if ($starttime == '07.30pm') echo ' selected="selected"'; ?>>07.30pm</option>
                        <option value="08.00pm"<?php if ($starttime == '08.00pm') echo ' selected="selected"'; ?>>08.00pm</option>
                        <option value="08.30pm"<?php if ($starttime == '08.30pm') echo ' selected="selected"'; ?>>08.30pm</option>
                        <option value="09.00pm"<?php if ($starttime == '09.00pm') echo ' selected="selected"'; ?>>09.00pm</option>
                        <option value="09.30pm"<?php if ($starttime == '09.30pm') echo ' selected="selected"'; ?>>09.30pm</option>
                        <option value="10.00pm"<?php if ($starttime == '10.00pm') echo ' selected="selected"'; ?>>10.00pm</option>
                        <option value="10.30pm"<?php if ($starttime == '10.30pm') echo ' selected="selected"'; ?>>10.30pm</option>
                        <option value="11.00pm"<?php if ($starttime == '11.00pm') echo ' selected="selected"'; ?>>11.00pm</option>
                        <option value="11.30pm"<?php if ($starttime == '11.30pm') echo ' selected="selected"'; ?>>11.30pm</option>

                       </select>
                <div id="endofdate">
                      <label>End Date</label>
                      <input type="text"  id="datepicked" value="<?php echo $end_date;?>" name="end_date" class="postTitle" />
                </div>
                <div id="ended_time">
                      <label>End Time</label>
                      <select name="end_time">
                        <option value="12:00am"<?php if ($end_time == '12:00am') echo ' selected="selected"'; ?>>12:00am</option>
                        <option value="12:30am"<?php if ($end_time == '12:30am') echo ' selected="selected"'; ?>>12:30am</option>
                        <option value="01:00am"<?php if ($end_time == '01:00am') echo ' selected="selected"'; ?>>01:00am</option>
                        <option value="01:30am"<?php if ($end_time == '01:30am') echo ' selected="selected"'; ?>>01:30am</option>
                        <option value="02:00am"<?php if ($end_time == '02:00am') echo ' selected="selected"'; ?>>02:00am</option>
                        <option value="02:30am"<?php if ($end_time == '02:30am') echo ' selected="selected"'; ?>>02:30am</option>
                        <option value="03:00am"<?php if ($end_time == '03:00am') echo ' selected="selected"'; ?>>03:00am</option>
                        <option value="03:30am"<?php if ($end_time == '03:30am') echo ' selected="selected"'; ?>>03:30am</option>
                        <option value="04:00am"<?php if ($end_time == '04:00am') echo ' selected="selected"'; ?>>04:00am</option>
                        <option value="04:30am"<?php if ($end_time == '04:30am') echo ' selected="selected"'; ?>>04:30am</option>
                        <option value="05:00am"<?php if ($end_time == '05:00am') echo ' selected="selected"'; ?>>05:00am</option>
                        <option value="05:30am"<?php if ($end_time == '05:30am') echo ' selected="selected"'; ?>>05:30am</option>
                        <option value="06:00am"<?php if ($end_time == '06:00am') echo ' selected="selected"'; ?>>06:00am</option>
                        <option value="06:30am"<?php if ($end_time == '06:30am') echo ' selected="selected"'; ?>>06:30am</option>
                        <option value="07:00am"<?php if ($end_time == '07:00am') echo ' selected="selected"'; ?>>07:00am</option>
                        <option value="07:30am"<?php if ($end_time == '07:30am') echo ' selected="selected"'; ?>>07:30am</option>
                        <option value="08:00am"<?php if ($end_time == '08:00am') echo ' selected="selected"'; ?>>08:00am</option>
                        <option value="08:30am"<?php if ($end_time == '08:30am') echo ' selected="selected"'; ?>>08:30am</option>
                        <option value="09:00am"<?php if ($end_time == '09:00am') echo ' selected="selected"'; ?>>09:00am</option>
                        <option value="09:30am"<?php if ($end_time == '09:30am') echo ' selected="selected"'; ?>>09:30am</option>
                        <option value="10:00am"<?php if ($end_time == '10:00am') echo ' selected="selected"'; ?>>10:00am</option>
                        <option value="10:30am"<?php if ($end_time == '10:30am') echo ' selected="selected"'; ?>>10:30am</option>
                        <option value="11:00am"<?php if ($end_time == '11:00am') echo ' selected="selected"'; ?>>11:00am</option>
                        <option value="11:30am"<?php if ($end_time == '11:30am') echo ' selected="selected"'; ?>>11:30am</option>
                        <option value="12:00pm"<?php if ($end_time == '12:00pm') echo ' selected="selected"'; ?>>12:00pm</option>
                        <option value="12:30pm"<?php if ($end_time == '12:30pm') echo ' selected="selected"'; ?>>12:30pm</option>
                        <option value="01.00pm"<?php if ($end_time == '01.00pm') echo ' selected="selected"'; ?>>01.00pm</option>
                        <option value="01.30pm"<?php if ($end_time == '01.30pm') echo ' selected="selected"'; ?>>01.30pm</option>
                        <option value="02.00pm"<?php if ($end_time == '02.00pm') echo ' selected="selected"'; ?>>02.00pm</option>
                        <option value="02.30pm"<?php if ($end_time == '02.30pm') echo ' selected="selected"'; ?>>02.30pm</option>
                        <option value="03.00pm"<?php if ($end_time == '03.00pm') echo ' selected="selected"'; ?>>03.00pm</option>
                        <option value="03.30pm"<?php if ($end_time == '03.30pm') echo ' selected="selected"'; ?>>03.30pm</option>
                        <option value="04.00pm"<?php if ($end_time == '04.00pm') echo ' selected="selected"'; ?>>04.00pm</option>
                        <option value="04.30pm"<?php if ($end_time == '04.30pm') echo ' selected="selected"'; ?>>04.30pm</option>
                        <option value="05.00pm"<?php if ($end_time == '05.00pm') echo ' selected="selected"'; ?>>05.00pm</option>
                        <option value="05.30pm"<?php if ($end_time == '05.30pm') echo ' selected="selected"'; ?>>05.30pm</option>
                        <option value="06.00pm"<?php if ($end_time == '06.00pm') echo ' selected="selected"'; ?>>06.00pm</option>
                        <option value="06.30pm"<?php if ($end_time == '06.30pm') echo ' selected="selected"'; ?>>06.30pm</option>
                        <option value="07.00pm"<?php if ($end_time == '07.00pm') echo ' selected="selected"'; ?>>07.00pm</option>
                        <option value="07.30pm"<?php if ($end_time == '07.30pm') echo ' selected="selected"'; ?>>07.30pm</option>
                        <option value="08.00pm"<?php if ($end_time == '08.00pm') echo ' selected="selected"'; ?>>08.00pm</option>
                        <option value="08.30pm"<?php if ($end_time == '08.30pm') echo ' selected="selected"'; ?>>08.30pm</option>
                        <option value="09.00pm"<?php if ($end_time == '09.00pm') echo ' selected="selected"'; ?>>09.00pm</option>
                        <option value="09.30pm"<?php if ($end_time == '09.30pm') echo ' selected="selected"'; ?>>09.30pm</option>
                        <option value="10.00pm"<?php if ($end_time == '10.00pm') echo ' selected="selected"'; ?>>10.00pm</option>
                        <option value="10.30pm"<?php if ($end_time == '10.30pm') echo ' selected="selected"'; ?>>10.30pm</option>
                        <option value="11.00pm"<?php if ($end_time == '11.00pm') echo ' selected="selected"'; ?>>11.00pm</option>
                        <option value="11.30pm"<?php if ($end_time == '11.30pm') echo ' selected="selected"'; ?>>11.30pm</option>

                       </select>
              </div>
                 <div id="max_price">
                      <label>Goal</label>
                      <input type="text"  id="max" value="<?php echo $goal_sum;?>" name="goal_price" class="postTitle" />
                </div>
                 <?php }?> 
                     <div>
                </div>
                  <!-- <div id="level" >
                    <label> Level Requirement</label>
                    <input type="text" value="<?php //echo $level;?>"  id="levelval" name="level" class="postTitle" />
                </div> -->
                    <?php if($cat_id==167){ ?>
                 
                    <div id="cutofdate" > 
                    <label> Bidding Cut-Off Date</label>
                    <input type="text"  id="date" value="<?php echo $cut;?>" name="cut" class="postTitle" />
                   </div>
                   <?php }?>
                     <?php if($cat_id==166){ ?>
                
                    <div id="manage" >
                    <label> Quantity</label>
                    <input type="text"  id="stock" name="stock" value="<?php echo $stock;?>" class="postTitle" />
                </div>
                 <div id="gold">
                    <label> Gold Points Required For Purchase</label>
                    <input type="text"  id="gold_val" name="gold" value="<?php echo $gold;?>" class="postTitle" />
                     <span id="evengold" style="display:none; color:red;"> Enter Only Odd Value</span>
                </div>
                 <div id="silver" >
                    <label> Silver Points Required For Purchase</label>
                    <input type="text"  id="silver_val" name="silver" value="<?php echo $silver;?>" class="postTitle" />
                     <span id="evensilver" style="display:none; color:red;"> Enter Only Odd Value</span>
                </div>
                  <?php }?>
                    <input type="submit" name="add_post" id="add_post" value="Update">
                   
            </form>
        </div>
    </div>
  </div>
</div>
<?php get_footer(); ?>