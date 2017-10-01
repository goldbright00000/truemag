
<!-- <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/LoadGo/2.2/loadgo.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/LoadGo/2.2/loadgo-nojquery.min.js"></script> -->
<?php
/**
 * The Template for displaying all single video posts with standard layout.
 *
 */

get_header();
$layout = ot_get_option('single_layout_ct_video','right');
$layout_ct_video = get_post_meta($post->ID,'single_ly_ct_video',true);
$current_post=$post->ID;
if($layout_ct_video != 'def') $layout = $layout_ct_video;
global $sidebar_width;
?>
 <script>
jQuery(document).ready(function(){

  jQuery('ul.tabs li').click(function(){
    var tab_id = jQuery(this).attr('data-tab');
    jQuery('ul.tabs li').removeClass('current');
    jQuery('.tab-content').removeClass('current');
    jQuery(this).addClass('current');
    jQuery("#"+tab_id).addClass('current');
  })

})
            </script>
            <style>

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

 .tab-video-merch li {
  text-align: center;
  width: 49%;
}
 .video-merch-contant {
  background: transparent none repeat scroll 0 0 !important;
  border: 1px solid #ededed;
  margin-bottom: 20px;
}
ul.tabs li.current {
  background: #ededed none repeat scroll 0 0;
  border-bottom: 2px solid #f9c73d;
  color: #222;
}
.tab-video-merch {
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
#dialog-notallow .no_thanks {
  background: #000 none repeat scroll 0 0;
  color: #f9c73d;
  font-weight: 600;
  margin-left: 20px;
  padding: 9px 28px;
}
#dialog-notallow.ui-dialog-content {
  background: #181818 none repeat scroll 0 0;
  padding: 10px 20px 40px;
  position: relative;
  text-align: center;
}
#dialog-notallow > h1 {
  color: #f9c73d;
  font-size: 25px;
  margin: 20px 0;
  text-align: center;
  font-weight: 600;
}
#dialog-notallow > p {
  color: #fff;
  font-size: 16px;
}
#dialog-notallow > a {
  background: #f9c73d none repeat scroll 0 0;
  color: #000;
  display: inline-block;
  font-size: 15px;
  margin-top: 15px;
  padding: 7px 20px;
}


#voted .no_thanks {
  background: #000 none repeat scroll 0 0;
  color: #f9c73d;
  font-weight: 600;
  margin-left: 20px;
  padding: 9px 28px;
}
#voted.ui-dialog-content {
  background: #181818 none repeat scroll 0 0;
  padding: 10px 20px 40px;
  position: relative;
  text-align: center;
}
#voted > h1 {
  color: #f9c73d;
  font-size: 25px;
  margin: 20px 0;
  text-align: center;
  font-weight: 600;
}
#voted > p {
  color: #fff;
  font-size: 16px;
}
#voted > a {
  background: #f9c73d none repeat scroll 0 0;
  color: #000;
  display: inline-block;
  font-size: 15px;
  margin-top: 15px;
  padding: 7px 20px;
}




#dialog-login .no_thanks {
  background: #000 none repeat scroll 0 0;
  color: #f9c73d;
  font-weight: 600;
  margin-left: 20px;
  padding: 9px 28px;
}
#dialog-login.ui-dialog-content {
  background: #181818 none repeat scroll 0 0;
  padding: 10px 20px 40px;
  position: relative;
  text-align: center;
}
#dialog-login > h1 {
  color: #f9c73d;
  font-size: 25px;
  margin: 20px 0;
  text-align: center;
  font-weight: 600;
}
#dialog-login > p {
  color: #fff;
  font-size: 16px;
}
#dialog-login > a {
  background: #f9c73d none repeat scroll 0 0;
  color: #000;
  display: inline-block;
  font-size: 15px;
  margin-top: 15px;
  padding: 7px 20px;
}
.ui-dialog.ui-corner-all.ui-widget.ui-widget-content {

  width: 670px!important;
}
            </style>
<div id="body">
        <div class="container">
            <div class="row">

          <div id="content" class="<?php echo $layout!='full'?($sidebar_width?'col-md-9':'col-md-8'):'col-md-12' ?><?php echo ($layout == 'left') ? " revert-layout":"";?>" role="main">
                  <?php
          //content
          if (have_posts()) :
            $get_layout = get_post_meta($post->ID,'page_layout',true);
            if($get_layout=='def' || $get_layout==''){$get_layout = ot_get_option('single_layout_video');}
            if($get_layout=='inbox'){
              get_template_part( 'single', 'inbox' );
            }else{
            while (have_posts()) : the_post();
              $multi_link = get_post_meta(get_the_ID(), 'tm_multi_link', true);
              if(!empty($multi_link)){
                tm_build_multi_link($multi_link, true);
              }
              get_template_part('content','single');
            endwhile;
            }
          endif;
          //author
            $onoff_author = ot_get_option( 'onoff_author');
            if($onoff_author!='0'){
            ?>

              <div class="about-author">
                <div class="author-avatar">
                  <?php echo tm_author_avatar(); ?>
                </div>
                <div class="author-info">
                  <h5><?php echo __('About The Author','cactusthemes'); ?></h5>
                  <?php the_author_posts_link(); ?> -
                  <?php the_author_meta('description'); ?>
                </div>
                <div class="clearfix"></div>
              </div><!--/about-author-->

          <?php }
           ?>
           <?php  global $wpdb;
         $current_post;

           ?>
                    <div class="simple-navigation ">
                      <?php
                    $end_daye = get_post_meta($current_post, '_end_date', true );
                       $today=date("d-m-Y");
                       if(empty($end_daye))
                       {
                    $end_daye='23-03-2031';
                       }
                       if(strtotime($end_daye)>=strtotime($today))
                       {

                      ?>

                      <div id="my_vote">
                        <?php

                               $user_id=get_current_user_id();
                              $total_row1 = $wpdb->get_var("select count(*) FROM vote_system where post_id = $current_post and user_id=$user_id");
                         if($total_row1>0)
                         {
                          ?>
                           <a  class="already_vote" href="javascript:void(0)" style="color:green;"  id="like"><i class="fa fa-thumbs-o-up" aria-hidden="true"></i></a> <?php echo $total_row = $wpdb->get_var("select count(*) FROM vote_system where post_id = $current_post and vote='like'");?> |
                           <a  class="already_vote" href="javascript:void(0)" style="color:red;" id="dislike" ><i class="fa fa-thumbs-o-down" aria-hidden="true"></i></a> <?php echo $total_row = $wpdb->get_var("select count(*) FROM vote_system where post_id = $current_post and vote='dislike'");?>

                                <?php

                         }
                         else{


                         ?>
                         <a <?php  if ( is_user_logged_in() ) { echo 'class="vote"';}else{echo 'class="not_login"';}?> href="javascript:void(0)" style="color:green;"  id="like"><i class="fa fa-thumbs-o-up" aria-hidden="true"></i></a> <?php echo $total_row = $wpdb->get_var("select count(*) FROM vote_system where post_id = $current_post and vote='like'");?>|
                           <a <?php  if ( is_user_logged_in() ) { echo 'class="vote"';}else{echo 'class="not_login"';}?> href="javascript:void(0)" style="color:red;" id="dislike" ><i class="fa fa-thumbs-o-down" aria-hidden="true"></i></a> <?php echo $total_row = $wpdb->get_var("select count(*) FROM vote_system where post_id = $current_post and vote='dislike'");?>
                      <div id="dialog-login" style="display:none; color:red"><p><h1>Please Login</h1></p></div>
                       <?php }?>

</div>
<?php }
   else{

   ?>
<div id="my_vote">
<a  class="out_date" href="javascript:void(0)" style="color:green;" ><i class="fa fa-thumbs-o-up" aria-hidden="true"></i></a> <?php echo $total_row = $wpdb->get_var("select count(*) FROM vote_system where post_id = $current_post and vote='like'");?> |
<a  class="out_date" href="javascript:void(0)" style="color:red;"  ><i class="fa fa-thumbs-o-down" aria-hidden="true"></i></a> <?php echo $total_row = $wpdb->get_var("select count(*) FROM vote_system where post_id = $current_post and vote='dislike'");?>
 <div id="dialog-confirm" style="display:none;">
  <p>Voting period for this video has ended</p>
</div>
  </div> <?php  }

?>

                      <div id="voted" style="display:none;"><p><h2>You have already voted this video</h2></p></div>
                      
<?php  if ( !is_user_logged_in() ) {  ?>
                <div id="SubmitConfirm" style="display:none;"><p><h2>You have already voted this video</h2></p></div>
      <?php } ?>
           <?php
        $onoff_postnavi = ot_get_option( 'onoff_postnavi');
        if($onoff_postnavi!='0'){
        $auto_load_same_cat= ot_get_option('auto_load_same_cat');
        if($auto_load_same_cat=='1'){
        ?>
                <div class="simple-navigation">
                    <div class="row">
                        <div class="simple-navigation-item col-md-6 col-sm-6 col-xs-6">
                        <?php
                            $p = get_adjacent_post(true, '', true);
                            if(!empty($p)){ echo '<a href="' . get_permalink($p->ID) . '" title="' . $p->post_title . '" class="maincolor2hover pull-left">
              <i class="fa fa-angle-left pull-left"></i>
              <div class="simple-navigation-item-content">
                <span>'.__('Previous','cactusthemes').'</span>
                <h4>' . apply_filters('the_title', $p->post_title) . '</h4>
              </div>
              </a>';}
                        ?>
                        </div>
                        <div class="simple-navigation-item col-md-6 col-sm-6 col-xs-6">
                        <?php
                            $n = get_adjacent_post(true, '', false);
                            if(!empty($n)) echo '<a href="' . get_permalink($n->ID) . '" title="' . $n->post_title . '" class="maincolor2hover next_post">
              <i class="fa fa-angle-right pull-right"></i>
              <div class="simple-navigation-item-content">
                <span>'.__('Next','cactusthemes').'</span>
                <h4>' . apply_filters('the_title', $n->post_title) . '</h4>
              </div>
                            </a>';
                            ?>

                        </div>
                    </div>
                </div><!--/simple-nav-->

                <?php
        }elseif($auto_load_same_cat=='0' || $auto_load_same_cat=='' ){?>
                     <?php
            $idp= get_the_ID();
                        $tags = "";
            $n_tags = "";
                        $post_tags = get_the_tags();
                        if ($post_tags) {
                         foreach($post_tags as $tag) {
                            $n_tags .= ',' . $tag->term_id;
                        }
                        }
                        $n_tags = substr($n_tags, 1);
                     $args = array(
                        'post_type' => 'post',
                        'post_status' => 'publish',
                        'tag__in' => array($n_tags),
                     );
                     $current_key = $next = $previous= '';
                     $tm_query = get_posts($args);
                     //print_r($tm_query);
                     foreach ( $tm_query as $key => $post ) : setup_postdata( $post );
                        if($post->ID == get_the_ID()){$current_key = $key;}
                     endforeach;
           $current_key = $current_key-1;;
           $id_pre = ($tm_query[$current_key+1]->ID);
           $id_nex = ($tm_query[$current_key-1]->ID);
                     if($id_pre!= ''){ $next = get_permalink($tm_query[$current_key+1]->ID); }
                     if($id_nex!= ''){$previous = get_permalink($tm_query[$current_key-1]->ID);}
                     ?>
                    <div class="simple-navigation">
                        <div class="row">
                          <div class="simple-navigation-item col-md-6 col-sm-6 col-xs-6">
                        <?php if($tm_query[$current_key-1]->ID!='' && $tm_query[$current_key-1]->ID!=$idp){?>
                          <a href="<?php echo get_permalink($tm_query[$current_key-1]->ID);?>" class="maincolor2hover" >
                              <i class="fa fa-angle-left pull-left"></i>
                                <div class="simple-navigation-item-content">
                                  <span><?php echo __('Next','cactusthemes'); ?></span>
                                    <h4><?php echo get_the_title($tm_query[$current_key-1]->ID)?></h4>
                                </div>
                            </a><?php }?>

                        </div>
                          <div class="simple-navigation-item col-md-6 col-sm-6 col-xs-6">
                        <?php if($tm_query[$current_key+1]->ID!='' && $tm_query[$current_key+1]->ID!=$idp){?>
                        <a href="<?php echo get_permalink($tm_query[$current_key+1]->ID);?>" class="maincolor2hover pull-right" >
                              <i class="fa fa-angle-right pull-right"></i>
                                <div class="simple-navigation-item-content">
                                  <span><?php echo __('Previous','cactusthemes'); ?></span>
                                  <h4><?php echo get_the_title($tm_query[$current_key+1]->ID)?></h4>
                                </div>
                            </a><?php }?>

                            </div>
                        </div>
                    </div><!--/simple-nav-->
      <?php }
          wp_reset_postdata();
        }?>
         <div id="comments" style="display:none;">
          <?php comments_template( '', true ); ?>
                    </div>
          <?php
            $count='';
            global $post;

            if($layout_ct_video=='full'){$count=6;}else if($layout_ct_video=='right'){$count=4;}
            $tags = '';
            $posttags = get_the_tags();
            if ($posttags) {
              foreach($posttags as $tag) {
                $tags .= ',' . $tag->slug;
              }
              $tags = substr($tags, 1);
            }
            if(ot_get_option('related_video_by')){ //by cat
              $tags = '';
              $categories = get_the_category();
              if ($categories) {
                foreach($categories as $tag) {
                  $tags .= ',' . $tag->term_id;
                }
                $tags = substr($tags, 1);
              }
            }
            ?>

                    <div class="related-single" ><a name="related"></a>
          <?php
          $onoff_related_video = ot_get_option('onoff_related_video');
          if($onoff_related_video !='0'){
                        echo do_shortcode('[tm_related_post title="'.__('Related Videos','cactusthemes').'" count="'.$count.'" postformat="video" orderby="rand"  tag="'.$tags.'"]');
          }
                    ?>
                    </div>

                </div><!--#content--> </div>
                <?php if($layout != 'full'){
          get_sidebar();
        }?>
            </div><!--/row-->
            <input type="hidden" id="userid" value="<?php echo $user_id = get_current_user_id();?>" />
             <input type="hidden" id="site_url" value="<?php echo site_url();?>" />
             <input type="hidden" id="post_id" value="<?php echo $post->ID;?>" />
              <input type="hidden" id="admin_email" value="<?php echo bloginfo('admin_email');?>" />
        </div><!--/container-->
        <script type="text/javascript">
        jQuery(document).ready(function() {
            jQuery('.vote').click(function() {
               var vote=this.id;
         var post_id= jQuery('#post_id').val();
        var userid= jQuery('#userid').val();
        var site_url= jQuery('#site_url').val();
        var ajex_url=site_url+'/wp-admin/admin-ajax.php';
        // alert(email);
        // var myFunction;
        jQuery.ajax({
                  type:'POST',
                  data:{action:'myFunction',vote:vote,userid:userid,post_id:post_id},
                  url: ajex_url,
                  success: function(value) {
                 //jQuery('#my_vote').html(value);
                 jQuery( "#vote_insert" ).dialog({
                                    resizable: false,
                                    height: "auto",
                                    closeText : '',
                                    width: 400,
                                    modal: true
                                  });

                  }
               });
           });
                   jQuery('.not_login').click(function() {
                     jQuery( "#dialog-login" ).dialog({
                        resizable: false,
                        height: "auto",
                        closeText : '',
                        width: 400,
                        modal: true,
                      });
                   });
                     jQuery('.already_vote').click(function() {
                         jQuery( "#voted" ).dialog({
                        resizable: false,
                        closeText : '',
                        height: "auto",
                        width: 400,
                        modal: true,
                      });
                     var post_id= jQuery('#post_id').val();
                     var userid= jQuery('#userid').val();
                     var site_url= jQuery('#site_url').val();
                     var ajex_url=site_url+'/wp-admin/admin-ajax.php';
              // alert(email);
              // var myFunction;
        jQuery.ajax({
                  type:'POST',
                  data:{action:'voted',userid:userid,post_id:post_id},
                  url: ajex_url,
                  success: function(value) {
                  jQuery('#voted').html(value);
                  }
               });
                     });
                     jQuery('.out_date').click(function() {
                      jQuery( "#dialog-confirm" ).dialog({
                        resizable: false,
                        height: "auto",
                        closeText : '',
                        width: 400,
                        modal: true,
                      });
                     });

            jQuery('.submit-video').click(function() {
                      jQuery( "#SubmitConfirm" ).dialog({
                        resizable: false,
                        height: "auto",
                        closeText : '',
                        width: 400,
                        modal: true,
                      });
                     });

                     jQuery('#flag').click(function() {
                       jQuery( "#dialog-flag" ).dialog({
                                    resizable: false,
                                    height: "auto",
                                    closeText : '',
                                    width: 400,
                                    modal: true,
                                    buttons : {
                                  "Confirm" : function() {
                                     jQuery(this).dialog("close");
                                    var post_id= jQuery('#post_id').val();
                                    var userid= jQuery('#userid').val();
                                    var site_url= jQuery('#site_url').val();
                                    var ajex_url=site_url+'/wp-admin/admin-ajax.php';
                                    var admin_email=jQuery('#admin_email').val();
                                    jQuery.ajax({
                                        type:'POST',
                                        data:{action:'flag',userid:userid,post_id:post_id,admin_email:admin_email},
                                        url: ajex_url,
                                        success: function(value) {
                                       if(value=='done'){
                                        jQuery("#flag").css('color', '#4141a0');
                                       }
                                        }
                                     });

                                  },
                                  "Cancel" : function() {
                                    jQuery(this).dialog("close");
                                  }
                                }
                                  });
                     });
 jQuery('.not_allow').click(function() {
   jQuery( "#dialog-notallow" ).dialog({
                                    resizable: false,
                                    height: "auto",
                                    width: 400,
                                     closeText : '',
                                    modal: true
                                  });
  });

});</script>
<div id="dialog-notallow" style="display:none;">
 <h1>YOU MUST BE 21 OR OLDER TO WATCH! </h1>
<P>This is Photoshop's version of Lorem lpsum.Proin gravida nibh vel velit auctor aliquet.Aenean sollicitudin, loream quis bibendum auctor,nisi elit consequat ipsum,nec sadittis sem nibh id elit.Duis sed odio sit amet nibh vulputate cursus a sit amet mauris. Morbi accumsan ipsum velit. Nam nec tellus a odio tincidunt auctor a ornare odio.
  Sed non mauris vitae erat consequat auctor eu in elit.</P>

 <a href="javascript:void(0)">Verify</a> <a  class="no_thanks" href="<?php echo site_url();?>">NO THANKS </a>
</div>
<div id="vote_insert" style="display:none; color:green">
<p>Thanks for your vote !</p>
</div>
   </div> </div><!--/body-->
<?php get_footer(); ?>
