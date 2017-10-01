<?php
/*
Template name:reward_get.php
*/
get_header();
?>
<div class="reward_bg">
<h2 class="rewares_title">Rewards</h2>
 <div class="container">
  <div class="row">
    <?php
$args = array( 'post_type' => 'create_rewards', 'posts_per_page' => 10,'order' => 'DESC' );
$loop = new WP_Query( $args );


while ( $loop->have_posts() ) : $loop->the_post();?>

 
      <div class="col-md-6">
        <div class="reward_post">
          <ul>
            <li class="title_reward"><span class="reward_digit"><?php echo the_title();?></li>
            <li class="img_reward"> 
                <?php
                echo '<div class="entry-content">';
                  if ( has_post_thumbnail() ) {
                      the_post_thumbnail();
                  } 
                  else {
                          echo '<img src="'.site_url().'/wp-content/uploads/2017/06/silver.png" />';
                       }   echo '</div>';?>
            </li>
          <li class="dash"><i class="fa fa-minus" aria-hidden="true"></i></li>
            <li class="discrt_reward"><span class="reward_dis"><?php the_content();?></span></li>
          </ul>
        </div>
      </div>
     <!-- <div class="col-md-6">
        <div class="reward_post">
          <ul>
            <li class="title_reward"><span class="reward_digit"><?php echo the_title();?></li>
            <li class="img_reward"> 
                <?php
           /*echo '<div class="entry-content">';
                  if ( has_post_thumbnail() ) {
                      the_post_thumbnail();
                  } 
                  else {
                          echo '<img src="'.site_url().'/wp-content/uploads/2017/06/silver.png" />';
                       }   echo '</div>';*/?>
            </li>
          <li class="dash"><i class="fa fa-minus" aria-hidden="true"></i></li>
            <li class="discrt_reward"><span class="reward_dis"><?php the_content();?></span></li>
          </ul>
        </div>
      </div>  -->
    
  <?php
endwhile;?>
</div>
  </div>
</div>
<?php get_footer();?>
<style>
.reward_bg {
  background: rgba(0, 0, 0, 0) url("http://www.premise.tv/wp-content/uploads/2016/10/bg2.jpg") no-repeat scroll 0 0 / cover ;
  padding-bottom: 20px;
  position: relative;
}
.reward_post li {
  display: inline-block;
  vertical-align: middle;
  padding: 0 !important;
}
.reward_post li::before {
  content: none !important;
}
.reward_post .reward_digit {
  color: #fff;
  font-size: 30px;
  font-weight: 600;
  display: block;
}
.reward_post .reward_dis {
  color: #fff;
  display: block;
  font-size: 15px;
  font-weight: 600;
  margin-top: 10px !important;
}
.reward_post .entry-content > img {
  height: 40px;
  overflow: hidden;
  width: 40px;
}
.reward_post .title_reward {
  width: 10%;
  text-align: right;
}
.img_reward {
  width: 9%;
}
.reward_post .discrt_reward {
  width: 75%;
  vertical-align: top !important;
}
.rewares_title {
  color: #fff;
  font-size: 32px;
  font-weight: 600;
  margin: 0;
  padding: 30px 0;
  text-align: center;
}
.reward_post > ul {
  padding-left: 0;
  margin-bottom: 0;
}
.reward_post .dash {
  color: #fff;
  width: 15px;
}
.reward_dis > p {
  line-height: 17px;
  margin: 0;
}
.reward_bg .col-md-6 {
    min-height: 63px;
}
</style>