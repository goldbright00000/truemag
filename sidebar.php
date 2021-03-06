<?php
/**
 * The sidebar containing the main widget area.
 */
global $sidebar_width;
?>
<div id="sidebar" class="<?php echo $sidebar_width?'col-md-3':'col-md-4' ?>">
<?php 
if(is_front_page() && is_active_sidebar('home_sidebar')){
  dynamic_sidebar( 'home_sidebar' );
} else if(is_category()||is_home()&&!is_front_page()){
  $cat_id = get_query_var('cat');
  $style = get_option("cat_layout_$cat_id")?get_option("cat_layout_$cat_id"):ot_get_option('blog_style','video');
  if($style=='video'&&is_active_sidebar('video_listing_sidebar')){
    dynamic_sidebar( 'video_listing_sidebar' );
  }elseif($style=='blog'&&is_active_sidebar('blog_sidebar')){
    dynamic_sidebar( 'blog_sidebar' );
  }elseif(is_active_sidebar('main_sidebar')){
    dynamic_sidebar( 'main_sidebar' );
  }
}elseif(is_plugin_active('buddypress/bp-loader.php') && bp_current_component()){ //buddypress
  if(bp_is_user() && is_active_sidebar('bp_single_member_sidebar')){ //single member
    dynamic_sidebar( 'bp_single_member_sidebar' );
  }elseif(bp_is_group() && is_active_sidebar('bp_single_group_sidebar')){ //single group
    //dynamic_sidebar( 'bp_single_group_sidebar' );
  }elseif(bp_is_register_page() && is_active_sidebar('bp_register_sidebar')){ //register
    dynamic_sidebar( 'bp_register_sidebar' );
  }elseif(bp_is_directory()){ //sitewide
    if(bp_is_activity_component() && is_active_sidebar('bp_activity_sidebar')){
      dynamic_sidebar( 'bp_activity_sidebar' ); //activity
    }elseif(bp_is_groups_component() && is_active_sidebar('bp_group_sidebar')){
      dynamic_sidebar( 'bp_group_sidebar' ); //groups
    }elseif(bp_current_component('members') && is_active_sidebar('bp_member_sidebar')){
      dynamic_sidebar( 'bp_member_sidebar' ); //members
    }elseif(is_active_sidebar('bp_sidebar')){
      dynamic_sidebar( 'bp_sidebar' );
    }else{
      //dynamic_sidebar( 'main_sidebar' );
    }
  }elseif(is_active_sidebar('bp_sidebar')){
    dynamic_sidebar( 'bp_sidebar' );
  }else{
    //dynamic_sidebar( 'main_sidebar' );
  }
}elseif(is_single()&&tm_is_post_video()&&is_active_sidebar('single_video_sidebar')){
  ?>
     <div class="side_slider side2">
        <div class="shop_body outer latest_products_inner inner">
  <?php

   $author_id=$post->post_author;
   $current_post=$post->ID;
   $isPoll = get_post_meta($post->ID, 'poll_id', true);
   $havemeta = get_post_meta($post->ID, 'pledge_id', true);
     
     $pledgearr=explode(",",$havemeta);
   
$args = array(
   'post__in' => $pledgearr,
    'post_type'   =>  'product',
    
    'order'       =>  'DESC',
             'tax_query'     => array(
            array(
            'taxonomy'  => 'product_cat',
            'field'     => 'id',
            'terms'     => array(166,167)
        ),
    ),
    );
    
    $loop = new WP_Query( $args );
  
    while ( $loop->have_posts() ) : $loop->the_post(); global $product; ?>
             <div class="bar_div_white">
              <h2 class="plugename" style="display:none;">Pledge <?php echo $product->get_price_html();?> or more </h2>
              <h4><?php 
              //print_r($loop);
              the_title(); ?></h4>
             <p><?php the_content();?></p>
             <?php  $id= get_the_ID(); 
             $category = get_the_terms( $id, 'product_cat' );
            // print_r($category);  //[term_id //name
             $cat=$category[0]->term_id;
             $stock = get_post_meta(  $id, '_stock', true );
             /*if(empty($stock)){
              echo '<p class="pledge_txt"><strong>Quantity</strong><span> 0</span></p>';
             } else{
              echo '<p class="pledge_txt"><strong>Quantity</strong><span>('.$stock.' Remaining)</span></p>';
             }*/
             $cat_name=$category[0]->name;
             if($cat!=157){
             echo '<h2 class="pledge_txt" style="padding-top: 0px;">
						<p class="side_title">Type of Pledge</p>
						<span>'.$cat_name.'</span>
					</h2>';
             $pluglevel = get_post_meta($id, 'level_pledge', true ); 
             if(empty($pluglevel)){$pluglevel=0;}
             echo '<h2 class="pledge_txt" style="padding-top: 0px;"><p class="side_title">Level Requirement</p><span>'. $pluglevel.'</span></h2>';       }
             $user_id = get_current_user_id();
              $pluglevel = get_post_meta($id, 'level_pledge', true );
              $requried_gold_points = get_post_meta($id, 'gold_points', true );
              $requried_silver_points = get_post_meta($id, 'silver_points', true );
             if ($user_id) {
               if($cat==167)
                { 
                  global $wpdb;
                   $tdata = $wpdb->get_row( "SELECT * FROM  bid_amount where pledge_id='$id' and user_id='$user_id'" );
                    $amount=$tdata->amount;
                    if($amount){
                      $product->get_price_html();
                      woocommerce_template_loop_add_to_cart( $loop->post, $product );
                    } else{
                echo '<div class="btmText" style="padding-top: 0px;">';
                $cut_date = get_post_meta($id, '_end_date', true );  
                $check_bidd_price = $wpdb->get_var("select max(amount) FROM bidding where pledge_id = $id");
                echo '<h2 class="pledge_txt"><p class="side_title">Bidding Cut-off Date</p>'.$cut_date;
                echo '<h2 class="pledge_txt"><p class="side_title">Highest Bid</p><span id="amount-'.$id.'">'.$check_bidd_price.'</span></h2>';

                echo '<div><h4>Amount <img src="http://www.premise.tv/wp-content/uploads/2017/06/gold.png"/></h4>
                <input type="text" name="amount" id="'.$id.'-bidamount" class="amt_gld" />
                
                <a href="javascript:void(0)" id="'.$id.'" class="submit_bid" >Support</a>
<span id="'.$id.'-error" style="display:none; color:red;">Please fill pledge amount</span>
                <i class="fa fa-spinner fa-spin" id="'.$id.'-response" style="font-size:24px; display:none;"></i>
                <div id="'.$id.'-bid_ok"></div>


                </div>';
                  //echo $product->get_price_html(); 
                 // woocommerce_template_loop_add_to_cart( $loop->post, $product );
                  echo '</div>';
              }
              }
            if($cat==166){
                          
                         
               if(empty($stock)){
                    echo '<h2 class="pledge_txt"><p class="side_title">Quantity</p><span> Sold out</span></h2>';
                   } else{
                    echo '<h2 class="pledge_txt" style="padding-top:0px;"><p class="side_title">Quantity</p><span>('.$stock.' Remaining)</span></h2>';
                   }
             global $wpdb;
             $upgarde ='interactive_system';
             $row = $wpdb->get_row( "SELECT * FROM  interactive_system WHERE user_id ='$user_id'" );
                   $free=$row->points; 
                   $transaction_points=$row->transaction_points; 
                   $gold=$row->gold_points;
                   $cal=$free+$gold*2;
                   $retrieve_data = $wpdb->get_results( "SELECT * FROM  points WHERE type='send' AND user_id ='$user_id' AND status='1'");
                   $send_points=0;
                   foreach ($retrieve_data as $retrieved_data){
                    
                       $send_points+=$retrieved_data->amount; 
                   }
                   $send_points;
                   $retrieve_data2 = $wpdb->get_results( "SELECT * FROM  points WHERE recevier ='$user_id' AND status='1'");
                   $receve_points=0;
                   foreach ($retrieve_data2 as $retrieved_data2){
                    
                      $receve_points+=$retrieved_data2->amount; 
                   }
                   $receve_points;
                   $Totel=$cal+$transaction_points+$receve_points-$send_points;
                   $level=floor ($Totel/100);

                   if($level>=$pluglevel){
                         if($gold>=$requried_gold_points || $free>=$requried_silver_points)
                      {
                            echo '<div class="btmText gold silver">';
                            if($gold>=$requried_gold_points){
                           echo '<label class="radio-inline"><input type="radio" name="points" id="'.$id.'-gold" value="'.$requried_gold_points.'"/>'.$requried_gold_points.' <img src="http://www.premise.tv/wp-content/uploads/2017/06/gold.png"/></label>'; }
                           else{echo '<label class="radio-inline"><input  class="gold_not" type="radio"  value="gold_not"/>'.$requried_gold_points.' <img src="http://www.premise.tv/wp-content/uploads/2017/06/gold.png"/></label> ';}
                           if($free>=$requried_silver_points){
                               echo '<label class="radio-inline"><input id="'.$id.'-silver" type="radio" name="points" value="'.$requried_silver_points.'"/>'.$requried_silver_points.' <img src="http://www.premise.tv/wp-content/uploads/2017/06/silver.png"/> </label>';}
                               else{ echo '<label class="radio-inline"><input class="silver_not" type="radio" value="silver_not"/>'.$requried_silver_points.' <img src="http://www.premise.tv/wp-content/uploads/2017/06/silver.png"/> </label>';}
                          echo '<div class="'.$id.' goldsilver">';
                          echo '<i class="fa fa-circle-o-notch fa-spin" id="'.$id.'-type"  style="font-size:14px; display:none;"></i>';
                     $product->get_price_html(); 
                    woocommerce_template_loop_add_to_cart( $loop->post, $product );
                  echo '</div>';
                        
                  echo '<i class="fa fa-spinner fa-spin" id="'.$id.'-response" style="font-size:24px;display:none;"></i></div>';
                    } else{
                                       echo '<label class="radio-inline"><input type="radio" name="points" value="'.$requried_gold_points.'"/>'.$requried_gold_points.' <img src="http://www.premise.tv/wp-content/uploads/2017/06/gold.png"/></label> 
                                <label class="radio-inline"><input type="radio" name="points" value="'.$requried_silver_points.'"/>'.$requried_silver_points.' <img src="http://www.premise.tv/wp-content/uploads/2017/06/silver.png"/> </label>';
                      echo '<div class="btmText pledge_cart"><a class="not_point button  add_to_cart_button ajax_add_to_cart"  href="javascript:void(0)" >Add to cart</a></div>';
                    }
                }
                    else{
                        echo '<label class="radio-inline"><input type="radio" name="points" value="'.$requried_gold_points.'"/>'.$requried_gold_points.' <img src="http://www.premise.tv/wp-content/uploads/2017/06/gold.png"/></label> 
                                <label class="radio-inline"><input type="radio" name="points" value="'.$requried_silver_points.'"/>'.$requried_silver_points.' <img src="http://www.premise.tv/wp-content/uploads/2017/06/silver.png"/> </label>';
                      echo '<div class="btmText pledge_cart"><a class="not_level button  add_to_cart_button ajax_add_to_cart"  href="javascript:void(0)" >Add to cart</a></div>';
           
                    }
            }
           } else{    if($cat==167){
                  $check_bidd_price = $wpdb->get_var("select max(amount) FROM bidding where pledge_id = $id");
                  $cut_date = get_post_meta($id, '_end_date', true );  
                echo '<h2 class="pledge_txt"><p class="side_title">Bidding Cut-off Date</p><span>'.$cut_date.'</span></h2>';
                echo '<h2 class="pledge_txt"><p class="side_title">Highest Bid</strong></p><span id="amount-'.$id.'">'.$check_bidd_price.'</span></h2>';

           }    if($cat==166){
               if(empty($stock)){
              echo '<h2 class="pledge_txt" style="padding-top:0px;"><p class="side_title">Quantity</p><span> Sold out</span></h2>';
             } else{
              echo '<h2 class="pledge_txt" style="padding-top:0px;"><p class="side_title">Quantity</p><span>('.$stock.' Remaining)</span></h2>';
             }

              echo '<label class="radio-inline"><input type="radio" name="points1" value="'.$requried_gold_points.'"/>'.$requried_gold_points.' <img src="http://www.premise.tv/wp-content/uploads/2017/06/gold.png"/></label> 
                                <label class="radio-inline"><input type="radio" name="points1" value="'.$requried_silver_points.'"/>'.$requried_silver_points.' <img src="http://www.premise.tv/wp-content/uploads/2017/06/silver.png"/></label>';}
              echo '<div class="btmText pledge_cart"><a class="not_login button  add_to_cart_button ajax_add_to_cart"  href="javascript:void(0)" >Add to cart</a></div>';
            }
              
               $backers= get_post_meta( $id, 'total_sales', true );
               if( $backers){
               echo '<h4 class="backers" style="font-size: 12px;">'.$backers.' Backers'.'</h4>';} else{ echo '<h4  class="backers" style="font-size: 12px;">'.'0 Backers'.'</h4>'; }
           
               ?>
                 
          </div>

<?php 
    endwhile;
   $check_next_episide=get_post_meta( $current_post, 'episodelink', true );
   $poll_options = get_post_meta($isPoll, 'poll_value', true );
             $poll_options_arr=explode(',', $poll_options);
             /*print_r($poll_options_arr);*/
             $totle_option= count($poll_options_arr);
    
            if($check_next_episide=='' && $isPoll>0 ){
               echo '<div class="bar_div_white">';
             echo '<h4>'.get_the_title($isPoll).'</h4>';
            // wp_reset_query();
             echo '<p>'.get_post_field('post_content', $isPoll).'</p>';
             $user_id = get_current_user_id();
             $polllevel = get_post_meta($isPoll, 'level', true );
             $silver = get_post_meta($isPoll, 'silver', true );
              global $wpdb;
             $upgarde ='interactive_system';
             $row = $wpdb->get_row( "SELECT * FROM  interactive_system WHERE user_id ='$user_id'" );
                   $free=$row->points; 
                   $transaction_points=$row->transaction_points; 
                   $gold=$row->gold_points;
                   $cal=$free+$gold*2;
                   $retrieve_data = $wpdb->get_results( "SELECT * FROM  points WHERE type='send' AND user_id ='$user_id' AND status='1'");
                   $send_points=0;
                   foreach ($retrieve_data as $retrieved_data){
                    
                       $send_points+=$retrieved_data->amount; 
                   }
                   $send_points;
                   $retrieve_data2 = $wpdb->get_results( "SELECT * FROM  points WHERE recevier ='$user_id' AND status='1'");
                   $receve_points=0;
                   foreach ($retrieve_data2 as $retrieved_data2){
                    
                      $receve_points+=$retrieved_data2->amount; 
                   }
                   $receve_points;
                   $Totel=$cal+$transaction_points+$receve_points-$send_points;
                   $level=floor ($Totel/100);
              echo '<h2 class="pledge_txt " style="padding-top: 0px;"><p class="side_title">Level Requirement</p><span>'. $polllevel.'</span></h2>';
              echo '<h2 class="pledge_txt " style="padding-top: 0px;"><p class="side_title"><span>'. $silver.'</span><lable class="radio-inline_1"><img src="http://www.premise.tv/wp-content/uploads/2017/06/silver.png"/> </lable></p></h2>';
             for($i=0;$i<$totle_option;$i++){
              echo '<div class="checkbox"><label   class="checkbox-inline"><input type="radio" name="poll" value="'.$i.'" checked>'.$poll_options_arr[$i].'</label></div>';
              
             }
             echo '<input type="hidden" id="poll_id" value="'.$isPoll.'"/><div>';
             if($level>=$polllevel){
             echo '<a  id="poll_insert" class="button"  href="javascript:void(0)" >Vote</a></div>';
              }else{
                echo '<a class="not_level_poll"  href="javascript:void(0)" >Vote</a></div>';
              }
             echo '<i class="fa fa-spinner fa-spin" id="poll_response" style="font-size:24px; display:none;"></i>
             <h4 id="yes_vote" style="color:green; display:none;">Your Vote successfully added!</h4>
             <h4 id="no_vote" style="color:red; display:none;">You dont have sufficient silver point to give vote!</h4>';
                echo '</div>';
          }  else{
             
            $totel_votes = $wpdb->get_var("select count(*) FROM poll_system where poll_id = $isPoll");
                if($totel_votes>0){
                  echo '<div class="bar_div_white">';
             echo '<h3>Poll Result</h3>';
              
              for($j=0;$j<$totle_option;$j++){
              $c='v_'.$j;
               $single_votes = $wpdb->get_var("select count(*) FROM poll_system where $c=1 AND  poll_id = $isPoll");
                $claculations=$single_votes/$totel_votes*100;
                 $claculation=number_format($claculations,1);
               echo '<p>'.$poll_options_arr[$j].'</p><div class="main_graph" style="background: #eee none repeat scroll 0 0;  margin-bottom: 7px;">
         <div class="sub_graph '.$c.'" style="padding: 4px 5px;width:'. $claculation.'%;"><p>'.$claculation.'%</p></div>
        </div>';
              }}}
              echo '</div>';
             
             /*  $havemeta2 = get_post_meta($current_post, 'merchandise_video_id', true);
                // print_r($havemeta2 );
                if(!empty($havemeta2)){
              echo '<div class="bar_div_white">';
              $pledgearr2=explode(",",$havemeta2);
              $args2 = array(
               'post__in' => $pledgearr2,
                'post_type'   =>  'product',
                
                'order'       =>  'DESC',
                         'tax_query'     => array(
                        array(
                        'taxonomy'  => 'product_cat',
                        'field'     => 'id',
                        'terms'     => array(158)
                    ),
                ),
                );
    
             $loop2 = new WP_Query( $args2 );
             while ( $loop2->have_posts() ) : $loop2->the_post(); global $product;
              echo the_title();
               echo the_content();
                 echo '<strong>Type:Merchandise</strong></br>';
               echo $product->get_price_html(); 
                echo '<div class="merchandise_type">';
                 woocommerce_template_loop_add_to_cart( $loop2->post, $product );
                 echo '</div>';
                    endwhile;
                echo '</div>';}*/
    wp_reset_query(); 

?>
             
<!--  <div class="bar_div_white">
    <h4>Pledged 1$ or more</h4><p>Virtual high-five! </p>
      <p>Do you want to help us create the next generation of shirts.but can't pledge the full amount?We appreciate all the support!</p>
        <div class="btmText">
       <span>Estimated Delievery :</span>  <h6>FEBRUARY</h6>
                </div><h5>23 BRACKERS</h5></div></div> -->
  <?php
  //dynamic_sidebar( 'single_video_sidebar' );
}elseif(is_single()&&is_active_sidebar('single_blog_sidebar')){
  dynamic_sidebar( 'single_blog_sidebar' );
}elseif(is_page()&&is_active_sidebar('single_page_sidebar')&&!is_front_page()){
  dynamic_sidebar( 'single_page_sidebar' );
  
}elseif(function_exists('is_woocommerce')&& is_woocommerce()&&!is_shop()){
  dynamic_sidebar( 'single_woo_sidebar' );
  
}elseif(function_exists('is_shop')&&is_shop()){
  dynamic_sidebar( 'shop_sidebar' );
  
}elseif(is_active_sidebar('main_sidebar')){
  dynamic_sidebar( 'main_sidebar' );
}elseif( is_active_sidebar( 'custom-side-bar' ) ){
	dynamic_sidebar( 'custom-side-bar' );
}
?>
</div><!--#sidebar-->
