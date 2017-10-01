<?php 
/*
 * Template Name: Demo Header 3
 */
 
global $global_page_layout;
$layout = $global_page_layout?$global_page_layout:ot_get_option('page_layout','right');
global $sidebar_width;
global $post;
?>
<!DOCTYPE html>
<!--[if IE 7]>
<html class="ie ie7" <?php language_attributes(); ?>>
<![endif]-->
<!--[if IE 8]>
<html class="ie ie8" <?php language_attributes(); ?>>
<![endif]-->
<!--[if !(IE 7) | !(IE 8)  ]><!-->
<html <?php language_attributes(); ?>>
<!--<![endif]-->
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<meta name="viewport" content="width=device-width, minimum-scale=1.0, initial-scale=1.0">

<title><?php wp_title( '|', true, 'right' ); ?></title>
<link rel="profile" href="http://gmpg.org/xfn/11" />
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />

<?php // Loads HTML5 JavaScript file to add support for HTML5 elements in older IE versions. ?>
<!--[if lt IE 9]>
<script src="<?php echo get_template_directory_uri(); ?>/js/html5.js" type="text/javascript"></script>
<![endif]-->
<!--[if lte IE 9]>
<link rel="stylesheet" type="text/css" href="<?php echo get_template_directory_uri(); ?>/css/ie.css" />
<![endif]-->

<?php if ( !isset( $_COOKIE['retina'] ) ) { 
	// this is used to set cookie to detect if screen is retina
	?>
<script type="text/javascript">
var retina = 'retina='+ window.devicePixelRatio +';'+ retina;
document.cookie = retina;
//document.location.reload(true);
</script>
<?php } ?> 
<style type="text/css" >
	@media only screen and (-webkit-min-device-pixel-ratio: 2),(min-resolution: 192dpi) {
		/* Retina Logo */
		.logo{background:url(<?php echo get_template_directory_uri() ?>/images/Logo-White-2X.png) no-repeat center; display:inline-block !important; background-size:contain;}
		.logo img{ opacity:0; visibility:hidden}
		.logo *{display:inline-block}
	}
</style>
<?php if(ot_get_option('echo_meta_tags')) ct_meta_tags();?>

<?php wp_head(); ?>
</head>

<body <?php body_class() ?>>
<a name="top" style="height:0; position:absolute; top:0;" id="top-anchor"></a>
<?php if(ot_get_option('loading_effect',2)==1||(ot_get_option('loading_effect',2)==2&&(is_front_page()||is_page_template('page-templates/front-page.php')))){ ?>
<div id="pageloader">   
    <div class="loader-item">
    	<i class="fa fa-refresh fa-spin"></i>
    </div>
</div>
<?php }?>
<div id="body-wrap">
<div id="wrap">
    <header class="dark-div">
    	<?php
		global $global_title;
		if(is_category()){
			$global_title = single_cat_title('',false);
		}elseif(is_tag()){
			$global_title = single_tag_title('',false);
		}elseif(is_tax()){
			$global_title = single_term_title('',false);
		}elseif(is_author()){
			$global_title = "Author: " . get_the_author();
		}elseif(is_day()){
			$global_title = "Archives for " . date_i18n(get_option('date_format') ,get_the_time('U'));
		}elseif(is_month()){
			$global_title = "Archives for " . get_the_date('F, Y');
		}elseif(is_year()){
			$global_title = "Archives for " . get_the_date('Y');
		}elseif(is_home()){
			if(get_option('page_for_posts')){ $global_title = get_the_title(get_option('page_for_posts'));
			}else{
				$global_title = get_bloginfo('name');
			}
		}elseif(is_404()){
			$global_title = '404!';
		}else{
			global $post;
			if($post)
				$global_title = $post->post_title;
		}
		
	// Navigation part of template
	$topnav_style = 'dark';
	$topnav_layout = 'layout-3';
	$fixed = ot_get_option('topnav_fixed');
?>
	<?php tm_display_ads('ad_top_1');?>
        <?php if(ot_get_option('disable_mainmenu') != "1"){ 
		ob_start(); //get top nav html
		?>
        <div id="top-nav" class="<?php echo $topnav_style=='light'?'topnav-light light-div':'topnav-dark'; echo $fixed?' fixed-nav':''; echo ' '.$topnav_layout; ?>">
			<nav class="navbar <?php echo $topnav_style=='dark'?'navbar-inverse':'' ?> navbar-static-top" role="navigation">
				<div class="container">
					<!-- Brand and toggle get grouped for better mobile display -->
					<div class="navbar-header">
						<button type="button" class="navbar-toggle<?php if(ot_get_option('mobile_nav',1)){ echo ' off-canvas-toggle"';}else{ ?>" data-toggle="collapse" data-target=".navbar-collapse"<?php } ?>>
						  <span class="sr-only"><?php _e('Toggle navigation','cactusthemes') ?></span>
						  <i class="fa fa-reorder fa-bars fa-lg"></i>
						</button>
                        <?php if(ot_get_option('logo_image') == ''):?>
						<a class="logo" href="<?php echo home_url(); ?>"><img src="<?php echo get_template_directory_uri() ?>/images/logo.png" alt="logo"></a>
                        <?php else:?>
                        <a class="logo" href="<?php echo get_home_url(); ?>" title="<?php wp_title( '|', true, 'right' ); ?>"><img src="<?php echo ot_get_option('logo_image'); ?>" alt="<?php wp_title( '|', true, 'right' ); ?>"/></a>
						<?php endif;?>
					</div>
					<!-- Collect the nav links, forms, and other content for toggling -->
					<div class="main-menu collapse navbar-collapse">
						<!--<form class="navbar-form navbar-right search-form" role="search">
							<label class="" for="s">Search for:</label>
							<input type="text" placeholder="SEARCH" name="s" id="s" class="form-control">
							<input type="submit" id="searchsubmit" value="Search">
						</form>-->
                        <?php
							$user_show_info = ot_get_option('user_show_info');
						    if ( is_user_logged_in() && $user_show_info=='1') {
							$current_user = wp_get_current_user();
							$link = get_edit_user_link( $current_user->ID );
							?>
                            <div class="user_curent navbar-right">
                            	<ul class="nav navbar-nav navbar-right hidden-xs">
                                    <li class="main-menu-item dropdown">
                                    <?php  
                                    echo '<a class="account_cr" href="#">'.$current_user->user_login; 
                                    echo get_avatar( $current_user->ID, '25' ).'</a>';
                                    ?>
                                    <ul class="dropdown-menu">
                                    	<li><a href="<?php echo $link; ?>"><?php _e('Edit Profile','cactusthemes') ?></a></li>
                                        <li><a href="<?php echo wp_logout_url( get_permalink() ); ?>"><?php _e('Logout','cactusthemes') ?></a></li>
                                    </ul>
                                    </li>
                            	</ul>
                            </div>
                        <?php } ?>
                        <?php
						    if(ot_get_option('user_submit')==1) {
								$text_bt_submit = ot_get_option('text_bt_submit');
								if($text_bt_submit==''){ $text_bt_submit = 'Submit Video';}
								if(ot_get_option('only_user_submit',1)){
									if(is_user_logged_in()){?>
                                    <ul class="nav navbar-nav navbar-right hidden-xs user_submit">
                                        <li class="main-menu-item">
                                            <a class="submit-video" href="#" data-toggle="modal" data-target="#submitModal"><span class="btn btn-xs bgcolor1"><?php _e($text_bt_submit,'cactusthemes'); ?></span></a>
                                        </li>
                                    </ul>
                       			<?php }
								} else{
								?>
                                    <ul class="nav navbar-nav navbar-right hidden-xs user_submit">
                                        <li class="main-menu-item">
                                            <a class="submit-video" href="#" data-toggle="modal" data-target="#submitModal"><span class="btn btn-xs bgcolor1"><?php _e($text_bt_submit,'cactusthemes'); ?></span></a>
                                        </li>
                                    </ul>
                       			<?php
								}
								if($limit_tags = ot_get_option('user_submit_limit_tag')){ ?>
                                <script>
                                jQuery(document).ready(function(e) {
                                    jQuery("form.wpcf7-form").submit(function (e) {
										var submit_tags = jQuery('input[name=tag].wpcf7-form-control').val().split(",");
										if(submit_tags.length > <?php echo $limit_tags ?>){
											if(jQuery('.limit-tag-alert').length==0){
												jQuery('.wpcf7-form-control-wrap.tag').append('<span role="alert" class="wpcf7-not-valid-tip limit-tag-alert"><?php _e('Please enter less than or equal to '.$limit_tags.' tags','cactusthemes') ?>.</span>');
											}
											return false;
										}else{
											return true;
										}
									});
                                });
                                </script>
                                <?php
								}
						} ?>
                        <?php if($topnav_layout){ ?>
						<ul class="nav navbar-nav hidden-xs nav-search-box">
                            <li class="main-menu-item">
                                    <form class="<?php echo $topnav_style=='light'?'light-form':''; ?> dark-form" action="<?php echo home_url() ?>">
                                        <div class="input-group">
                                            <input type="text" name="s" class="form-control" placeholder="<?php echo __('Search...','cactusthemes');?>">
                                            <span class="input-group-btn">
                                                <button class="btn btn-default maincolor1 maincolor1hover" type="submit"><i class="fa fa-search"></i></button>
                                            </span>
                                        </div>
                                    </form>
                            </li>
                        </ul>
                        <?php } ?>
						<ul class="nav navbar-nav <?php echo $topnav_layout?'':'navbar-right'; ?> hidden-xs">
						<?php
							if(has_nav_menu( 'main-navigation' )){
								wp_nav_menu(array(
									'theme_location'  => 'main-navigation',
									'container' => false,
									'items_wrap' => '%3$s',
									'walker'=> new custom_walker_nav_menu()
								));	
							}else{?>
								<li><a href="<?php echo home_url(); ?>/"><?php _e('Home','cactusthemes') ?></a></li>
								<?php wp_list_pages('depth=1&number=5&title_li=' ); ?>
						<?php } ?>
						</ul>
                        <?php if(!ot_get_option('mobile_nav',1)){ //is classic dropdown ?>
                            <!--mobile-->
                            <ul class="nav navbar-nav navbar-right visible-xs classic-dropdown">
                            <?php
                                if(has_nav_menu( 'main-navigation' )){
                                    wp_nav_menu(array(
                                        'theme_location'  => 'main-navigation',
                                        'container' => false,
                                        'items_wrap' => '%3$s'
                                    ));	
                                }else{?>
                                    <li><a href="<?php echo home_url(); ?>/"><?php _e('Home','cactusthemes') ?></a></li>
                                    <?php wp_list_pages('depth=1&number=5&title_li=' ); ?>
                            <?php }
							//user menu
								$user_show_info = ot_get_option('user_show_info');
								if ( is_user_logged_in() && $user_show_info =='1') {
								$current_user = wp_get_current_user();
								$link = get_edit_user_link( $current_user->ID );
								?>
									<li class="menu-item current_us">
									<?php  
									echo '<a class="account_cr" href="#">'.$current_user->user_login; 
									echo get_avatar( $current_user->ID, '25' ).'</a>';
									?>
									<ul class="sub-menu">
										<li class="menu-item"><a href="<?php echo $link; ?>"><?php _e('Edit Profile','cactusthemes') ?></a></li>
										<li class="menu-item"><a href="<?php echo wp_logout_url( get_permalink() ); ?>"><?php _e('Logout','cactusthemes') ?></a></li>
									</ul>
									</li>
							<?php }?>
                            <?php //submit menu
						    if(ot_get_option('user_submit',1)) {
								$text_bt_submit = ot_get_option('text_bt_submit');
								if($text_bt_submit==''){ $text_bt_submit = 'Submit Video';}
								if(ot_get_option('only_user_submit',1)){
									if(is_user_logged_in()){?>
                                    <li class="menu-item"><a class="" href="#" data-toggle="modal" data-target="#submitModal"><?php _e($text_bt_submit,'cactusthemes'); ?></a></li>
                       			<?php }
								} else{
								?>
                                    <li class="menu-item"><a class="" href="#" data-toggle="modal" data-target="#submitModal"><?php _e($text_bt_submit,'cactusthemes'); ?></a></li>
                       			<?php
								}
							} ?>
                            </ul>
                        <?php } ?>
					</div><!-- /.navbar-collapse -->
				</div>
			</nav>
		</div><!-- #top-nav -->
		<?php
			$top_nav_html=ob_get_contents();
			ob_end_clean();
			echo $topnav_layout=='layout-3'?'':$top_nav_html;
		}
		$show_top_headline = ot_get_option('show_top_headline');
		$social_account = array(
			'facebook',
			'instagram',
			'envelope',
			'twitter',
			'linkedin',
			'tumblr',
			'google-plus',
			'pinterest',
			'youtube',
			'flickr',
		);
		?>
        <div id="headline" class="<?php echo $topnav_style=='light'?'topnav-light light-div':'topnav-dark'; echo $fixed?' is-fixed-nav':''; echo ' '.$topnav_layout; ?>">
            <div class="container">
                <div class="row">
                	<?php if(1){
						$count = 0;
						foreach($social_account as $social){
							if($link = ot_get_option('acc_'.$social,false)){
								$count++;
							}
						}
						if($search_icon==1 && $show_top_headline == 0 && $count == 0 ){
							 echo '<style>
							 	#headline{ display:none}
							 </style>';
						}
					?>
                    <div class="headline-content col-md-6 col-sm-6 hidden-xs">
                    	<?php if ( is_active_sidebar( 'headline_sidebar' ) ) : ?>
							<?php dynamic_sidebar( 'headline_sidebar' ); ?>
                        <?php else:
								
								$number_item = ot_get_option('number_item_head_show');
								$icon_headline = ot_get_option('icon_headline');
								$title_headline = ot_get_option('title_headline');
								$cat= ot_get_option('cat_head_video');
								if($show_top_headline!=0){
                             		echo do_shortcode('[headline link="yes" icon="'.$icon_headline.'" sortby="rand" cat="'.$cat.'" posttypes="post" number="'.$number_item.'" title="'.$title_headline.'" ]');
								}?>
                        <?php endif; ?>
                    </div>
                    <?php }elseif(is_active_sidebar('pathway_sidebar')){
							echo '<div class="pathway pathway-sidebar col-md-6 hidden-xs">';
							dynamic_sidebar('pathway_sidebar');
							echo '</div>';
						}else{?>
                    <div class="pathway col-md-6 col-sm-6 hidden-xs">
                    	<?php if(function_exists('tm_breadcrumbs')){ tm_breadcrumbs(); } ?>
                    </div>
                    <?php } ?>
                    <div class="social-links col-md-6 col-sm-6">
                    	<div class="pull-right">
                        <?php 
						$file = get_post_meta($post->ID, 'tm_video_file', true);
						$url = trim(get_post_meta($post->ID, 'tm_video_url', true));
						$user_turnoff = ot_get_option('user_turnoff_load_next');
						$auto_load= ot_get_option('auto_load_next_video');
						if((strpos($file, 'youtube.com') !== false)&&($using_yt_param !=1) ||(strpos($url, 'youtube.com') !== false )&&($using_yt_param !=1) || (strpos($file, 'vimeo.com') !== false)||(strpos($url, 'vimeo.com') !== false )) { 
						if(is_single()){
						?>
                            <div class="tm-autonext" id="tm-autonext">
                            <script>
                            jQuery(document).ready(function(e) {
                                jQuery('#tm-autonext').toggle(function(){
									  jQuery('#tm-autonext span#autonext').removeClass('autonext');
									  jQuery('#tm-autonext .tooltip-inner').html('<?php _e('Auto Next OFF','cactusthemes') ?>');
									  jQuery('#tm-autonext .gptooltip.turnoffauto').attr('data-original-title', '<?php _e('Auto Next OFF','cactusthemes') ?>');
                                },
                                function(){
									  jQuery('#tm-autonext span#autonext').addClass('autonext');
									  jQuery('#tm-autonext .tooltip-inner').html('<?php _e('Auto Next ON','cactusthemes') ?>');
									  jQuery('#tm-autonext .gptooltip.turnoffauto').attr('data-original-title', '<?php _e('Auto Next ON','cactusthemes') ?>');
                                });	
                            });
                            </script>
                            <?php if($user_turnoff==1){?>
                            <span class="autonext" id="autonext" >
                                <a href="#" data-toggle="tooltip" title="<?php _e('Auto Next ON','cactusthemes') ?>" class="gptooltip turnoffauto" data-animation="true">
                                    <i class="fa fa-play"></i>
                                </a>
                            </span>
                            <?php }?>
                            </div>
                        <?php 
						}
						} ?>
                        <?php 
						$social_link_open = ot_get_option('social_link_open');
						foreach($social_account as $social){
							if($link = ot_get_option('acc_'.$social,false)){
								if($social=='envelope'){ ?>
                                	<a class="social-icon<?php echo $topnav_style=='dark'?' maincolor1 bordercolor1hover bgcolor1hover':'' ?>" href="mailto:<?php echo $link ?>" <?php if($social_link_open==1){?>target="_blank" <?php }?>><i class="fa fa-<?php echo $social ?>"></i></a>
                                <?php } else{?>
                        			<a class="social-icon<?php echo $topnav_style=='dark'?' maincolor1 bordercolor1hover bgcolor1hover':'' ?>" href="<?php echo $link ?>" <?php if($social_link_open==1){?>target="_blank" <?php }?>><i class="fa fa-<?php echo $social ?>"></i></a>
                        <?php } } } ?>
                        <?php 
						if($search_icon!=1 && !$topnav_layout){ ?>
                        <a class="search-toggle social-icon<?php echo $topnav_style=='dark'?' maincolor1 bordercolor1hover bgcolor1hover':'' ?>" href="#"><i class="fa fa-search"></i></a>
                        <div class="headline-search">
							<?php if ( is_active_sidebar( 'search_sidebar' ) ) : ?>
                                <?php dynamic_sidebar( 'search_sidebar' ); ?>
                            <?php else: ?>
                                <form class="dark-form" action="<?php echo home_url() ?>">
                                    <div class="input-group">
                                        <input type="text" name="s" class="form-control" placeholder="<?php echo __('Search for videos','cactusthemes');?>">
                                        <span class="input-group-btn">
                                            <button class="btn btn-default maincolor1 maincolor1hover" type="submit"><i class="fa fa-search"></i></button>
                                        </span>
                                    </div>
                                </form>
                            <?php endif; ?>
                        </div><!--/heading-search-->
                        <?php }?>
                        </div>
                    </div>
                </div><!--/row-->
				
				<?php tm_display_ads('ad_top_2');?>
            </div><!--/container-->			
        </div><!--/headline-->
        <?php echo $topnav_layout=='layout-3'?$top_nav_html:''; ?>
<?php		
		get_template_part( 'header', 'frontpage' );
		
		global $sidebar_width;
		$sidebar_width = ot_get_option('sidebar_width');
		?>
    </header>

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
					get_sidebar();
				}?>
            </div><!--/row-->
        </div><!--/container-->
    </div><!--/body-->
<?php get_footer(); ?>