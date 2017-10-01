<?php 
/**
 * Template Name: Demo Shortcode
 */
$layout = 'left';
global $sidebar_width;
global $post;
get_header();
$topnav_style = ot_get_option('topnav_style','dark');
if(!is_front_page()&&!is_page_template('page-templates/front-page.php')){
?>
	<div class="blog-heading <?php echo $topnav_style=='light'?'heading-light':'' ?>">
    	<div class="container">
            <h1><?php echo $post->post_title ?></h1>
        </div>
    </div><!--blog-heading-->
<?php } ?>
    <div id="body">
        <div class="container">
            <div class="row">
  				<div id="content" class="<?php echo $layout!='full'?($sidebar_width?'col-md-9':'col-md-8'):'col-md-12' ?><?php echo ($layout == 'left') ? " revert-layout":"";?>" role="main">
                	<?php
					//content
					if (have_posts()) :
						while (have_posts()) : the_post();
							get_template_part('content','single');
						endwhile;
					endif;
					//share
					$social_post= get_post_meta($post->ID,'showhide_social',true);
					if($social_post=='show'){ //check if show social share
						gp_social_share(get_the_ID());
					}
					if($social_post=='def'){
						if(ot_get_option( 'page_show_socialsharing', 1)){ //check if show social share
							gp_social_share(get_the_ID());
						}
					}
					//author
					if(ot_get_option('page_show_authorbio',0) != 0){?>
						<div class="about-author">
							<div class="author-avatar">
								<?php 
								global $_is_retina_;
								if($_is_retina_){
									echo get_avatar( get_the_author_meta('email'), 60, get_template_directory_uri() . '/images/avatar-2x.png' );
								} else {
									echo get_avatar( get_the_author_meta('email'), 60, get_template_directory_uri() . '/images/avatar.png' );
								} ?>
							</div>
							<div class="author-info">
								<h5><?php echo __('About The Author','cactusthemes'); ?></h5>
								<?php the_author(); ?> - 
								<?php the_author_meta('description'); ?>
							</div>
							<div class="clearfix"></div>
						</div><!--/about-author-->
					<?php }
					comments_template( '', true );
					?>
                </div><!--#content-->
                <?php if($layout != 'full'){
					global $sidebar_width; ?>
					<div id="sidebar" class="<?php echo $sidebar_width?'col-md-3':'col-md-4' ?>">
                    <?php dynamic_sidebar( 'demo-shortcode' );?>
					</div><!--#sidebar-->
				<?php }?>
            </div><!--/row-->
        </div><!--/container-->
    </div><!--/body-->
<?php get_footer(); ?>