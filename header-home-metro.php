<?php
global $header_query;
$header_height = ot_get_option('header_home_height');
?>
<div id="head-carousel">
    <div class="is-carousel" id="metro-carousel" <?php echo !ot_get_option('header_home_auto')?'data-notauto=1':'';
        echo ot_get_option('header_home_auto_timeout')?' data-auto_timeout='.ot_get_option('header_home_auto_timeout'):'';
        echo ot_get_option('header_home_auto_duration')?' data-auto_duration='.ot_get_option('header_home_auto_duration'):'';?>>
        <div class="carousel-content">
            <?php if($header_query->have_posts()){
            global $post;
            $args = array( 'tax_query' => array(
                    array(
                        'taxonomy' => 'topics',
                        'field' => 'slug',
                        'terms' => array ('slider')
                    ),
                ),'posts_per_page' => 15 );
                $myposts = get_posts( $args );
            //print_r($myposts);
            foreach( $myposts as $post ) : setup_postdata($post);
            $ids[]= $post->ID;
            endforeach; 
            //print_r($ids);
           // die();

           $colors = $ids;
           global $wpdb;
       

         $retrieve_data = $wpdb->get_results( "SELECT * FROM 413_Rank_slider " );
        foreach ($retrieve_data as $retrieved_data){
           $colors_wo_yellow[]= $retrieved_data->post_id;

        } 
        //print_r($colors_wo_yellow);
        
           
           // $color_to_move = ["$a"];
          //  $colors_wo_yellow = array_diff($colors, $color_to_move);// This will give an array without "yellow"
            //Now add "yellow" as 1st element to $
          //  array_unshift($colors_wo_yellow,$color_to_move[0]);
            $item_count=0;
            $open_double = 0;
               $args = array(
                    'posts_per_page' => 11,
                     'post__in' => $colors_wo_yellow,
                    'orderby' => 'post__in',
                    
               
                );

                $header_query = new WP_Query( $args );
            while($header_query->have_posts()): $header_query->the_post();
            $item_count++;
            if($item_count%7==2){ echo '<div class="video-item">'; $open_double = 1;}
            if($item_count%7==4||$item_count%7==6){ echo '</div><!--/two-video-item--><div class="video-item">'; $open_double = 1;}
            if($item_count%7==1&&$item_count>7){ echo '</div><!--/two-video-item-->'; $open_double = 0;}
            $format = get_post_format(get_the_ID());
           /* $item_count=0;
            $open_double = 0;                    
            query_posts( array('orderby' => 'post__in','post__in' => $colors_wo_yellow ,'posts_per_page' => 12));
            while(have_posts()): the_post();
            $item_count++;
            if($item_count%7==2){ echo '<div class="video-item">'; $open_double = 1;}
            if($item_count%7==4||$item_count%7==6){ echo '</div><!--/two-video-item--><div class="video-item">'; $open_double = 1;}
            if($item_count%7==1&&$item_count>7){ echo '</div><!--/two-video-item-->'; $open_double = 0;}
            $format = get_post_format(get_the_ID());*/
        ?>
            <div class="video-item">
                <?php 
                  $quick_if = ot_get_option('quick_view_for_slider');
                  if($quick_if=='1'){
                    echo '
                        <div class="qv_tooltip"  title="
                            <h4 class=\'gv-title\'>'.esc_attr(get_the_title()).'</h4>
                            <div class=\'gv-ex\' >'.esc_attr(get_the_excerpt()).'</div>
                            <div class= \'gv-button\'>';
                            if($format=='video'){
                                echo  '<div class=\'quick-view\'><a href='.get_permalink().' title=\''.esc_attr(get_the_title()).'\'>'.__('Watch Now','cactusthemes').'</a></div>';
                            }else{
                                echo  '<div class=\'quick-view\'><a href='.get_permalink().' title=\''.esc_attr(get_the_title()).'\'>'.__('Read more','cactusthemes').'</a></div>';
                            };
                        echo '<div class= \'gv-link\'>'.quick_view_tm().'</div>
                            </div>
                            </div>
                    ">';
                  }
                  ?>
                <div  class="item-thumbnail">
                    <a href="<?php the_permalink() ?>" title="<?php the_title_attribute(); ?>" >
                    <?php
                        if(has_post_thumbnail()){
                            global $_device_;
                            global $_is_retina_;
                            if($_device_== 'mobile' && !$_is_retina_){
                                $thumb = $open_double ? 'thumb_260x146' : 'thumb_520x293';
                            }else{
                                if($header_height>350){
                                    $thumb=$open_double ? 'thumb_356x200':'thumb_748x421';
                                }else{
                                    $thumb = $open_double ? 'thumb_260x146':'thumb_520x293';
                                }
                            }
                            
                            $responsive_mode = ot_get_option('responsive_image', 'on');
                            if($responsive_mode == 'on'){
                               // echo cactus_thumbnail($thumb);
                            } else {
                                $thumbnail = wp_get_attachment_image_src(get_post_thumbnail_id(),$thumb, true);
                                ?>
                                <img src="<?php echo $thumbnail[0] ?>" width="<?php echo $thumbnail[1] ?>" height="<?php echo $thumbnail[2] ?>" alt="<?php the_title_attribute(); ?>" title="<?php the_title_attribute(); ?>">
                                <?php
                            }
                        }else{
                            $thumbnail[0]=function_exists('tm_get_default_image')?tm_get_default_image():'';
                            $thumbnail[1]=520;
                            $thumbnail[2]=293;
                            ?>
                            <img src="<?php echo $thumbnail[0] ?>" width="<?php echo $thumbnail[1] ?>" height="<?php echo $thumbnail[2] ?>" alt="<?php the_title_attribute(); ?>" title="<?php the_title_attribute(); ?>">
                            <?php
                        }
                        
                        if($format=='' || $format =='standard'  || $format =='gallery'){ ?>
                          <div class="link-overlay fa fa-search"></div>
                          <?php }else {?>
                          <div class="link-overlay fa fa-play"></div>
                          <?php }  ?>

                    </a>
                    <div class="item-head">
                        <h3><a href="<?php the_permalink() ?>"  title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></h3>
                        <?php if(!$open_double && !ot_get_option('header_home_hidecat')){?>
                        <span><?php the_category(', '); ?></span>
                        <?php }?>
                    </div>
                </div>
                <?php if($quick_if=='1'){
                        echo '</div>';
                }?>
            </div><!--/video-item-->
        <?php

            if($item_count==$header_query->post_count && $open_double){ echo '</div><!--/two-video-item-last-->';}
            endwhile;

            wp_reset_postdata();
        }?>
        </div><!--/carousel-content-->
        <div class="carousel-button">
            <a href="#" class="prev maincolor1 bordercolor1 bgcolor1hover"><i class="fa fa-chevron-left"></i></a>
            <a href="#" class="next maincolor1 bordercolor1 bgcolor1hover"><i class="fa fa-chevron-right"></i></a>
        </div><!--/carousel-button-->
    </div><!--/is-carousel-->
</div><!--head-carousel-->