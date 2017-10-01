<article <?php post_class(is_single()&&get_post_format()=='video'?'video-item single-video-view':'');
if($review_point = get_post_meta(get_the_ID(),'taq_review_score',true)){
?> itemscope itemtype="http://data-vocabulary.org/Review" >
<div class="hidden">
	<span itemprop="itemreviewed"><?php the_title() ?></span>
    <span itemprop="reviewer"><?php echo get_bloginfo('name') ?></span>
    <span itemprop="rating" itemscope itemtype="http://data-vocabulary.org/Rating">      
         Rating: <span itemprop="value"><?php echo round($review_point/10,1) ?></span> / <meta itemprop="best" content="10"/>10
    </span>
</div>
<?php }else{ echo '>';} ?>
	

    <?php 
	if(is_front_page()||is_page_template('page-templates/front-page.php')){
		$show_hide_title = ot_get_option('show_hide_title','1');
		if($show_hide_title=='1'){?>
    		<!--<h1 class="light-title entry-title"><?php the_title(); ?></h1>-->
	<?php }
	}else if(is_single() && get_post_format()=='video'){ ?>
    	<?php tm_display_ads('ad_single_content');?>
		<!--<h1 class="light-title entry-title"><?php the_title(); ?></h1>-->
           <?php
        $current_post=$post->ID;
         
        $havemeta = get_post_meta($post->ID, 'pledge_id', true);
        if(empty($havemeta )){
          $havemeta=get_post_meta( $post->ID, 'merchandise_video_id', true );
        }
        $pledgearr=explode(",",$havemeta);
       $args2 = array(
       'post__in' => $pledgearr,
       'post_type'   =>  'product',
       'order'       =>  'DESC',
             'tax_query'     => array(
            array(
            'taxonomy'  => 'product_cat',
            'field'     => 'id',
            'terms'     => array(166,167,157)
        ),
    ),
    );
    
    $loop2 = new WP_Query( $args2 );
    while ( $loop2->have_posts() ) : $loop2->the_post(); global $product;
    $Post_id= get_the_ID();
    global $wpdb; 
    $Table ='free_credit_trasactions';
    $retrieve_data = $wpdb->get_results( "SELECT * FROM  $Table Where product_id=$Post_id" );
    $sum=0;
    foreach ($retrieve_data as $retrieved_data){
     $free_credit+= $retrieved_data->amount;

    }
   $free_credit;
   $is_crowd= get_post_meta( $Post_id, '_alg_crowdfunding_enabled',true );
    if($is_crowd=='yes'){
   $backers= get_post_meta( $Post_id, '_alg_crowdfunding_total_orders',true );
   $goal= get_post_meta( $Post_id, '_alg_crowdfunding_goal_sum',true );
   $deadline= get_post_meta($Post_id, '_alg_crowdfunding_deadline',true );

   $totel_sum= get_post_meta($Post_id, '_alg_crowdfunding_orders_sum',true );
   if($free_credit>0){
    $totel_sum=$totel_sum-$free_credit;
    if($totel_sum<0)
    {
      $totel_sum=0;
    }
   }
   $totel_sum_amount=$totel_sum;
   $Remaining=$goal-$totel_sum;
   $Remaining_amount=$Remaining;
   $today=date("Y/m/d");
   $start = strtotime($today);
   $end = strtotime($deadline);
   $cut_date = get_post_meta($id, '_end_date', true );  
   $cut_date = strtotime($cut_date);
   $today_date=date("d-m-Y");
   $today_date = strtotime($today_date);
   $days_between = ceil(abs($end - $start) / 86400);
   $check_bidd_price = $wpdb->get_var("select max(amount) FROM bidding where pledge_id = $Post_id");
   if( $cut_date>$today_date)
    { 
        $totel_sum=$totel_sum+$check_bidd_price; 
        $Remaining=$goal-$totel_sum;
    }

   $persant=$totel_sum*100/$goal;
   if($persant>100)
   {
    $persantEnd=100;
    $persantage=$persantEnd.'%';
   } else{
     $persantage=$persant.'%';
   }

   
   ?>
    <div class="pledged_ac">
        <div class="pledged_bar"><input type="hidden" id="totel_sum_amount" value="<?php echo $totel_sum_amount; ?>"/>
            <input type="hidden" id="Remaining_amount" value="<?php echo $Remaining_amount; ?>"/>
             <input type="hidden" id="goal" value="<?php echo $goal; ?>"/>
            <div class="current_pledge" style="width:<?php echo $persantage; ?>">
                <span><p><b id="totel_current">$<?php echo $totel_sum;?></b><br>pledged</p></span>
            </div>
            <div class="goal_value">
                <span><p><b>$<?php echo $goal;?></b><br>goal</p></span>
            </div>
        </div>
        <ul class="away_backer">
            <?php if($goal >$totel_sum){?>
            <li><div id="away" class="away_text">$<?php echo $Remaining;?></div>away from goal</li>
            <?php } else{?>
             <li><div class="away_text"><?php echo $totel_sum.'/'.$goal;?></div>Complete goal</li>
            <?php }?>
            <li><div class="away_text"><?php echo $backers; ?></div>Backers</li>
            <li><div class="away_text"><?php echo $days_between;?></div>days to go</li>
        </ul>
    </div>
    <?php }
  
    endwhile;
      $page_data = get_page($current_post); 
            $title = $page_data->post_title; 
            $content = $page_data->post_content;
?>

          <div class="col-md-12">
           <ul class="tabs tab_video_merch">
            <li class="tab-link current" data-tab="tab-1">Project</li>
            <li class="tab-link" data-tab="tab-2">Updates</li>
            <li class="tab-link" data-tab="tab-3">FAQ</li>
            <li class="tab-link" id="comment" data-tab="tab-4" >Comment</li>
            </ul>
      <div id="tab-1" class="tab-content current video_merch_contant"> <?php echo $post_project = get_post_meta( $current_post, 'post_project', true );?></div>
        <div id="tab-2" class="tab-content video_merch_contant">
            <?php
            echo $post_updates = get_post_meta( $current_post, 'post_updates', true );
          /*
            echo "<div class='direct'><h2>Title in Series:</h2>".$title."</div>";

            $category_detail=get_the_category($current_post);//$post->ID
            echo"<h2>Category:</h2>";
                foreach($category_detail as $cd){
                  echo "<div class='direct'>". $cd->cat_name."</div>";
                }*/
            ?>
        </div>
           <div id="tab-3" class="tab-content">
            <?php  
            echo $post_faq = get_post_meta( $current_post, 'post_faq', true );

/*

                    echo "<div class='direct'><h2>Director:</h2>";
                     $director=get_post_meta($current_post, 'director', true);
                     $dir_arr=explode(',',$director);
                    $dir_result = count($dir_arr);
                    $comma=$dir_result-1;

                     for($i=0;$i<$dir_result ;$i++)
                     {
                        if ( username_exists( $dir_arr[$i] ) ){
                     ?>
                      <a href="<?php echo site_url();?>/members/<?php echo $dir_arr[$i];?>/media/"><?php echo $dir_arr[$i];?></a>
                     <?php
                    } else{
                       echo $dir_arr[$i];
                    }
                    if($i<$comma){
                    echo ',';
                     }
                   }

                    echo"</div>";
                    echo "<div class='direct'><h2>Writer:</h2>";
                    $writer=get_post_meta($current_post, 'writer', true);
                    
                     $writer_arr=explode(',',$writer);
                    $writer_arr_result = count($writer_arr);
                    $comma2=$writer_arr_result-1;

                     for($j=0;$j<$writer_arr_result ;$j++)
                     {
                        if ( username_exists( $writer_arr[$j] ) ){
                     ?>
                      <a href="<?php echo site_url();?>/members/<?php echo $writer_arr[$j];?>/media/"><?php echo $writer_arr[$j];?></a>
                     <?php
                    } else{
                       echo $writer_arr[$j];
                    }
                    if($j<$comma2){
                    echo ',';
                     }
                   }
                    echo "</div>";
                    echo "<div class='direct'><h2>Stars:</h2>";
                    $stars=get_post_meta($current_post, 'stars', true);

                    
                     $stars_arr=explode(',',$stars);
                    $stars_arr_result = count($stars_arr);
                    $comma3=$stars_arr_result-1;

                     for($k=0;$k<$stars_arr_result ;$k++)
                     {
                        if ( username_exists( $stars_arr[$k] ) ){
                     ?>
                      <a href="<?php echo site_url();?>/members/<?php echo $stars_arr[$k];?>/media/"><?php echo $stars_arr[$k];?></a>
                     <?php
                    } else{
                       echo $stars_arr[$k];
                    }
                    if($k<$comma3){
                    echo ',';
                     }
                   }

                    echo "</div>";
*/
            ?>
             </div>
             <div id="tab-4" class="tab-content">
             <?php  wp_reset_postdata();
                comments_template( '', true ); ?>
             </div>
    </div>
        <div class="item-info">
            <?php if(ot_get_option('single_show_meta_author',1)){?><span class="vcard author"><span class="fn"><?php the_author_posts_link();?></span></span> <?php } ?>
            <?php if(ot_get_option('single_show_meta_date',1)){ ?>
            <span class="item-date"><span class="post-date updated"><?php the_time(get_option('date_format')); ?> <?php the_time(get_option('time_format')); ?></span></span>
            <?php }?>
        </div>
	<?php } ?>       
    <div class="<?php echo is_single()?'item-content':'content-single'; echo ot_get_option('showmore_content',1)?'':' toggled' ?>">
        <?php the_content(); ?>
        <?php
		$pagiarg = array(
			'before'           => '<div class="single-post-pagi">'.__( 'Pages:','cactusthemes'),
			'after'            => '</div>',
			'link_before'      => '<span type="button" class="btn btn-default btn-sm">',
			'link_after'       => '</span>',
			'next_or_number'   => 'number',
			'separator'        => ' ',
			'nextpagelink'     => __( 'Next page','cactusthemes'),
			'previouspagelink' => __( 'Previous page','cactusthemes'),
			'pagelink'         => '%',
			'echo'             => 1
		);
		wp_link_pages($pagiarg);
		edit_post_link(esc_html__( 'Edit','cactusthemes'),'<i class="fa fa-pencil"></i>  ');
		?>
        <div class="clearfix"></div>
        <?php if(is_single()&&get_post_format()=='video'){ ?>
        <div class="item-tax-list">
        	<?php 
			  $onoff_tag = ot_get_option('onoff_tag');
			  $onoff_cat = ot_get_option('onoff_cat');
			  if($onoff_cat !='0'){
			?>
            <div><strong><?php _e('Category:', 'cactusthemes'); ?> </strong><?php the_category(', '); ?></div>
            <?php }
			if($onoff_tag !='0'){
			?>
            <div><?php the_tags('<strong>'.__('Tags:', 'cactusthemes').' </strong>', ', ', ''); ?></div>
            <?php } ?>
        </div>
        <?php
			global $video_toolbar_html;
			if(ot_get_option('video_toolbar_position','top')=='bottom'){
				echo $video_toolbar_html;
			}
		}elseif(is_single() && !(function_exists('is_bbpress') && is_bbpress())){ ?>
        <h1 class="light-title entry-title hidden"><?php the_title(); ?></h1>
        <div class="blog-meta">
        	<?php 
			 $onoff_tag = ot_get_option('onoff_tag');
			 $onoff_cat = ot_get_option('onoff_cat');
			 $single_show_meta_author = ot_get_option('single_show_meta_author');
			 $single_show_meta_comment = ot_get_option('single_show_meta_comment');
			if($onoff_tag !='0'){ ?>
            <div class="blog-tags"><?php the_tags('<i class="fa fa-tags"></i> ', ', ', ''); ?></div>
			<?php } ?>
            <div class="blog-meta-cat"><?php 
				if($single_show_meta_author !='0'){  ?><span class="vcard author"><span class="fn"><?php the_author_posts_link();?></span></span> | <?php } 
				if($onoff_cat !='0'){ the_category(', ');?> | <?php }?>
                <?php if(ot_get_option('single_show_meta_date',1)){ ?>
                    <span class="item-date"><span class="post-date updated"><?php the_time(get_option('date_format')); ?> <?php the_time(get_option('time_format')); ?></span></span>
                <?php }?>
            <?php
			 if($single_show_meta_comment !='0'){
					 if(comments_open()){ ?><a href="#comment"><?php comments_number(__('0 Comments','cactusthemes'),__('1 Comment','cactusthemes')); ?></a></span><?php } 
			}//check comment open?>
			<?php if(ot_get_option('show_hide_sharethis')){?>
                <div class="pull-right share-this">
                    <span class='st_sharethis' displayText=''></span>
                    <span class='st_facebook' displayText=''></span>
                    <span class='st_twitter' displayText=''></span>
                    <span class='st_googleplus' displayText=''></span>
                    <span class='st_pinterest' displayText=''></span>
                    <script type="text/javascript">var switchTo5x=false;</script>
                    <script type="text/javascript" src="http://w.sharethis.com/button/buttons.js"></script>
                    <script type="text/javascript">stLight.options({publisher: "37243fc6-d06b-449d-bdd3-a60613856c42", doNotHash: false, doNotCopy: false, hashAddressBar: false});</script>
                </div>
            <?php }else{
				echo '<div class="pull-right social-links">';
				tm_social_share();
				echo '</div>';
			}?>
            </div>
        </div>
        <?php }?>
    </div><!--/item-content-->
    <?php if(is_single() && get_post_format()=='video' && ot_get_option('showmore_content',1)){ ?>
    <div class="item-content-toggle">
    	<div class="item-content-gradient"></div>
    	<i class="fa fa-angle-down maincolor2hover"></i>
    </div>
    <?php } ?>
</article>