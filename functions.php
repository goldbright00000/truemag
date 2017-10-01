<?php

if(!defined('PARENT_THEME')){
	define('PARENT_THEME','truemag');
}
if ( ! isset( $content_width ) ) $content_width = 900;
global $_theme_required_plugins;

/* Define list of recommended and required plugins */
$_theme_required_plugins = array(
        array(
            'name'      => 'WP Pagenavi',
            'slug'      => 'wp-pagenavi',
            'required'  => false
        ),
        array(
            'name'      => 'BAW Post Views Count',
            'slug'      => 'baw-post-views-count',
            'required'  => false
        ),
        array(
            'name'      => 'Truemag - Member',
            'slug'      => 'ct-member',
            'required'  => false
        ),
        array(
            'name'      => 'TrueMAG - Movie',
            'slug'      => 'truemag-movie',
            'required'  => false
        ),
        array(
            'name'      => 'TrueMAG Rating',
            'slug'      => 'truemag-rating',
            'required'  => false
        ),
        array(
            'name'      => 'TrueMAG - Shortcodes',
            'slug'      => 'truemag-shortcodes',
            'required'  => false
        ),
		array(
            'name'      => 'Video Thumbnails',
            'slug'      => 'video-thumbnails',
            'required'  => false
        ),
		array(
            'name'      => 'WTI Like Post',
            'slug'      => 'wti-like-post',
            'required'  => false
        ),
		array(
            'name'      => 'Categories Images',
            'slug'      => 'categories-images',
            'required'  => false
        ),
		array(
            'name'      => 'Black Studio TinyMCE Widget',
            'slug'      => 'black-studio-tinymce-widget',
            'required'  => false
        ),
		array(
            'name'      => 'Contact Form 7',
            'slug'      => 'contact-form-7',
            'required'  => false
        ),
		array(
            'name'      => 'Simple Twitter Tweets',
            'slug'      => 'simple-twitter-tweets',
            'required'  => false
        ),
    );
	
include_once( ABSPATH . 'wp-admin/includes/plugin.php' ); //for check plugin status
/**
 * Load core framework
 */
require_once 'inc/core/skeleton-core.php';
require_once 'inc/videos-functions.php';
/**
 * Load Theme Options settings
 */ 
add_filter('option_tree_settings_args','filter_option_tree_args');
function filter_option_tree_args($custom_settings){
	include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
    if(!is_plugin_active('video-ads/video-ads-management.php')){
		for($i = 0; $i < count($custom_settings['sections']); $i++){
			$section = $custom_settings['sections'][$i];
			if($section['id'] == 'video-ads'){
				unset($custom_settings['sections'][$i]);
				break;
			}
		}		
	}
	
	return $custom_settings;
}

require_once 'inc/theme-options.php';

/**
 * Load Theme Core Functions, Hooks & Filter
 */
require_once 'inc/core/theme-core.php';

require_once 'inc/videos-functions.php';

require_once 'sample-data/tm_importer.php';

add_action( 'after_setup_theme', 'tm_megamenu_require' );
function tm_megamenu_require(){
	if(!class_exists('MashMenuWalkerCore')){
		require_once 'inc/megamenu/megamenu.php';
	}
}

/*//////////////////////////////////////////////True-Mag////////////////////////////////////////////////*/

/*Remove filter*/
function remove_like_view_widget() {
	unregister_widget('MostLikedPostsWidget');
	unregister_widget('WP_Widget_Most_Viewed_Posts');
}
add_action( 'widgets_init', 'remove_like_view_widget' );

remove_filter('the_content', 'PutWtiLikePost');

/* Add filter to modify markup */
add_filter( 'video_thumbnail_markup', 'tm_video_thumbnail_markup', 10, 2 );

add_filter('widget_text', 'do_shortcode');
if(!function_exists('tm_get_default_image')){
	function tm_get_default_image(){
		return get_template_directory_uri().'/images/nothumb.jpg';
	}
}
//add prev and next link rel on head
add_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0 );

//add author social link meta
add_action( 'show_user_profile', 'tm_show_extra_profile_fields' );
add_action( 'edit_user_profile', 'tm_show_extra_profile_fields' );
function tm_show_extra_profile_fields( $user ) { ?>
	<h3><?php _e('Social informations','cactusthemes') ?></h3>
	<table class="form-table">
		<tr>
			<th><label for="twitter">Twitter</label></th>
			<td>
				<input type="text" name="twitter" id="twitter" value="<?php echo esc_attr( get_the_author_meta( 'twitter', $user->ID ) ); ?>" class="regular-text" /><br />
				<span class="description"><?php _e('Enter your Twitter profile url.','cactusthemes')?></span>
			</td>
		</tr>
        <tr>
			<th><label for="facebook">Facebook</label></th>
			<td>
				<input type="text" name="facebook" id="facebook" value="<?php echo esc_attr( get_the_author_meta( 'facebook', $user->ID ) ); ?>" class="regular-text" /><br />
				<span class="description"><?php _e('Enter your Facebook profile url.','cactusthemes')?></span>
			</td>
		</tr>
        <tr>
			<th><label for="flickr">Flickr</label></th>
			<td>
				<input type="text" name="flickr" id="flickr" value="<?php echo esc_attr( get_the_author_meta( 'flickr', $user->ID ) ); ?>" class="regular-text" /><br />
				<span class="description"><?php _e('Enter your Flickr profile url.','cactusthemes')?></span>
			</td>
		</tr>
        <tr>
			<th><label for="google-plus">Google+</label></th>
			<td>
				<input type="text" name="google" id="google" value="<?php echo esc_attr( get_the_author_meta( 'google', $user->ID ) ); ?>" class="regular-text" /><br />
				<span class="description"><?php _e('Enter your Google+ profile url.','cactusthemes')?></span>
			</td>
		</tr>
	</table>
<?php }
add_action( 'personal_options_update', 'tm_save_extra_profile_fields' );
add_action( 'edit_user_profile_update', 'tm_save_extra_profile_fields' );
function tm_save_extra_profile_fields( $user_id ) {
	if ( !current_user_can( 'edit_user', $user_id ) )
		return false;
	/* Copy and paste this line for additional fields. Make sure to change 'twitter' to the field ID. */
	update_user_meta( $user_id, 'twitter', $_POST['twitter'] );
	update_user_meta( $user_id, 'facebook', $_POST['facebook'] );
	update_user_meta( $user_id, 'flickr', $_POST['flickr'] );
	update_user_meta( $user_id, 'google', $_POST['google'] );
}
//get video meta count
if(!function_exists('tm_html_video_meta')){
	function tm_html_video_meta($single = false, $label = false, $break = false, $listing_page = false, $post_id = false){
		global $post;
		$post_id = $post_id?$post_id:get_the_ID();
		ob_start();
        
        if(function_exists('bawpvc_main')){
            $view_count = get_post_meta($post_id, '_count-views_all', true);
        } elseif(function_exists('get_tptn_post_count_only')) {
            $view_count = get_tptn_post_count_only($post_id);
        }
        
		if($single == 'view'){
			echo '<span class="pp-icon"><i class="fa fa-eye"></i> '.($view_count?$view_count:0).'</span>';
		}elseif($single == 'like'){
			if(function_exists('GetWtiLikeCount')){
			echo '<span class="pp-icon iclike"><i class="fa fa-thumbs-up"></i> '.str_replace('+','',GetWtiLikeCount($post_id)).'</span>';
			}
		}elseif($single == 'comment'){
			echo '<span class="pp-icon"><i class="fa fa-comment"></i> '.get_comments_number($post_id).'</span>';			
		}elseif($listing_page){
			if(ot_get_option('blog_show_meta_view',1)){?>
        	<span><i class="fa fa-eye"></i> <?php echo ($view_count?$view_count:0).($label?__('  Views'):'') ?></span><?php echo $break?'<br>':'' ?>
            <?php }
			if(ot_get_option('blog_show_meta_comment',1)){?>
            <span><i class="fa fa-comment"></i> <?php echo get_comments_number($post_id).($label?__('  Comments'):''); ?></span><?php echo $break?'<br>':'' ?>
            <?php }
			if(ot_get_option('blog_show_meta_like',1)&&function_exists('GetWtiLikeCount')){?>
            <span><i class="fa fa-thumbs-up"></i> <?php echo str_replace('+','',GetWtiLikeCount($post_id)).($label?__('  Likes'):''); ?></span>
		<?php
			}
		}else{?>
            <span><i class="fa fa-eye"></i> <?php echo ($view_count?$view_count:0).($label?__('  Views'):'') ?></span>
            <?php echo $break?'<br>':'' ?>
            <span><i class="fa fa-comment"></i> <?php echo get_comments_number($post_id).($label?__('  Comments'):''); ?></span>
            <?php echo $break?'<br>':'' ?>
            <?php if(function_exists('GetWtiLikeCount')){?>
            <span><i class="fa fa-thumbs-up"></i> <?php echo str_replace('+','',GetWtiLikeCount($post_id)).($label?__('  Likes'):''); ?></span>
            <?php }
		}
		$html = ob_get_clean();
		return $html;
	}
}
//quick view
if(!function_exists('quick_view_tm')){
	function quick_view_tm(){
		  $html = '';
		  $title = get_the_title();
		  $title = strip_tags($title);
		  $link_q = get_post_meta(get_the_id(),'tm_video_url',true);
		  $link_q = str_replace('http://vimeo.com/','http://player.vimeo.com/video/',$link_q);
		  if((strpos($link_q, 'wistia.com')) !== false){$link_q ='';}
		  if($link_q==''){
			  $file = get_post_meta(get_the_id(), 'tm_video_file', true);
			  $files = !empty($file) ? explode("\n", $file) : array();
			  $link_q = isset($files[0])?$files[0]:'';
		  }
		  
		  if($link_q!='' ){
		  if((strpos($link_q, 'youtube.com')) !== false){
			  $id_vd = Video_Fetcher::extractIDFromURL($link_q);
			  $link_q ='//www.youtube.com/embed/'.$id_vd.'?rel=0&amp;wmode=transparent';
		  }
		  $html .='<div><a href='.esc_url($link_q).' class=\'youtube\'  title=\''.esc_attr($title).'\' data-url=\''.esc_url(get_permalink()).'\' id=\'light_box\'>
				'.__('Quick View','cactusthemes').'
			</a></div>';
		  }
		  return $html;
	}
}
if(!function_exists('tm_post_rating')){
	function tm_post_rating($post_id,$get=false){
		$rating = round(get_post_meta($post_id, 'taq_review_score', true)/10,1);
		if($rating){
			$rating = number_format($rating,1,'.','');
		}
		if($get){
			return $rating;
		}elseif($rating){
			$html='<span class="rating-bar bgcolor2">'.$rating.'</span>';
			return $html;
		}
	}
}

/**
 * Sets up theme defaults and registers the various WordPress features that
 * theme supports.
 *
 * @uses load_theme_textdomain() For translation/localization support.
 * @uses add_editor_style() To add a Visual Editor stylesheet.
 * @uses add_theme_support() To add support for post thumbnails, automatic feed links,
 * 	custom background, and post formats.
 * @uses register_nav_menu() To add support for navigation menus.
 * @uses set_post_thumbnail_size() To set a custom post thumbnail size.
 */
function cactusthemes_setup() {
	/*
	 * Makes theme available for translation.
	 *
	 * Translations can be added to the /languages/ directory.
	 */
	load_theme_textdomain( 'cactusthemes', get_template_directory() . '/languages' );

	// This theme styles the visual editor with editor-style.css to match the theme style.
	add_editor_style();

	// Adds RSS feed links to <head> for posts and comments.
	add_theme_support( 'automatic-feed-links' );
	// This theme supports a variety of post formats.
	
	add_theme_support( 'post-formats', array( 'gallery', 'video', 'audio' ) );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menu( 'main-navigation', __( 'Main Navigation', 'cactusthemes' ) );
	register_nav_menu( 'footer-navigation', __( 'Footer Navigation', 'cactusthemes' ) );

	// This theme uses a custom image size for featured images, displayed on "standard" posts.
	add_theme_support( 'post-thumbnails' );
	set_post_thumbnail_size( 624, 9999 ); // Unlimited height, soft crop
	
	add_theme_support( 'woocommerce' );
}
add_action( 'after_setup_theme', 'cactusthemes_setup', 10 );

/**
 * Enqueues scripts and styles for front-end.
 */
function cactusthemes_scripts_styles() {
	global $wp_styles;
	
	/*
	 * Loads our main javascript.
	 */	
	
	wp_enqueue_script( 'jquery');
	wp_enqueue_script( 'bootstrap', get_template_directory_uri() . '/js/bootstrap.min.js', array(), '', true );
	wp_enqueue_script( 'caroufredsel', get_template_directory_uri() . '/js/jquery.caroufredsel-6.2.1.min.js', array('jquery'), '', true );
	if(ot_get_option( 'nice-scroll', 'off')=='on'){
		wp_enqueue_script( 'smooth-scroll', get_template_directory_uri() . '/js/SmoothScroll.js', array('jquery'), '', true);
	}
	wp_enqueue_script( 'touchswipe', get_template_directory_uri() . '/js/helper-plugins/jquery.touchSwipe.min.js', array('caroufredsel'), '', true );
	wp_enqueue_script( 'hammer', get_template_directory_uri() . '/js/jquery.hammer.js', array('jquery'), '', true );		
	wp_enqueue_script( 'template', get_template_directory_uri() . '/js/template.js', array('jquery'), '', true );
	
	wp_enqueue_script( 'colorbox', get_template_directory_uri() . '/js/colorbox/jquery.colorbox-min.js', array('jquery'), '', true );		
	wp_register_script( 'js-scrollbox', get_template_directory_uri() . '/js/jquery.scrollbox.js', array(), '', true );
	
	wp_enqueue_script( 'tooltipster', get_template_directory_uri() . '/js/jquery.tooltipster.js', array(), '', true );
	wp_enqueue_script( 'malihu-scroll', get_template_directory_uri() . '/js/malihu-scroll/jquery.mCustomScrollbar.concat.min.js', array(), '', true );
	//wp_enqueue_script( 'waypoints' );
	/*
	 * videojs.
	 */
	 wp_enqueue_script( 'my_script', get_template_directory_uri() . '/js/delete.js', array( 'jquery' ), '1.0.0', true );
	 wp_enqueue_script( 'ui-js', get_template_directory_uri() . '/js/jquery-ui.js', array( 'jquery' ), '1.12.1', true );
	wp_register_script( 'videojs-cactus', get_template_directory_uri() . '/js/videojs/video.js' , array('jquery'), '', false );
	wp_enqueue_script( 'videojs-cactus' );
	wp_register_style( 'videojs-cactus', get_template_directory_uri() . '/js/videojs/video-js.min.css');
	wp_enqueue_style( 'videojs-cactus' );
	/*
	 * Loads our main stylesheet.
	 */
	$tm_all_font = array();
	$rm_sp = ot_get_option('text_font', 'Open Sans');
	if(ctype_space($rm_sp) == false){
		if($rm_sp != 'Custom Font'){
			$tm_all_font[] = $rm_sp;
		}
		$all_font = implode('|',$tm_all_font);
		wp_enqueue_style( 'google-font', '//fonts.googleapis.com/css?family='.$all_font );
	}

	wp_enqueue_style( 'colorbox', get_template_directory_uri() . '/js/colorbox/colorbox.css');
        wp_enqueue_style( 'bootstrap', get_template_directory_uri() . '/css/bootstrap.min.css');
	wp_enqueue_style( 'tooltipster', get_template_directory_uri() . '/css/tooltipster.css');
	
	wp_enqueue_style( 'fontastic-entypo', get_template_directory_uri().'/fonts/fontastic-entypo.css' );
	wp_enqueue_style( 'google-font-Oswald', '//fonts.googleapis.com/css?family=Oswald:300' );
	wp_enqueue_style( 'style', get_stylesheet_directory_uri() . '/style.css');
	if (class_exists('Woocommerce')) {
		wp_enqueue_style( 'tmwoo-style', get_template_directory_uri() . '/css/tm-woo.css');
	}
	if(ot_get_option( 'flat-style')){
		wp_enqueue_style( 'flat-style', get_template_directory_uri() . '/css/flat-style.css');
	}
	wp_deregister_style( 'font-awesome' );
	wp_enqueue_style( 'font-awesome', get_template_directory_uri() .'/fonts/css/font-awesome.min.css');
	wp_enqueue_style( 'custom-css', get_template_directory_uri() . '/css/custom.css.php');
	if(ot_get_option( 'right_to_left', 0)){
		wp_enqueue_style( 'rtl', get_template_directory_uri() . '/rtl.css');
	}
	if(ot_get_option( 'responsive', 1)!=1){
		wp_enqueue_style( 'no-responsive', get_template_directory_uri() . '/css/no-responsive.css');
	}
	if(is_singular() ) wp_enqueue_script( 'comment-reply' );
	if(is_plugin_active( 'buddypress/bp-loader.php' )){
		wp_enqueue_style( 'truemag-bp', get_template_directory_uri() . '/css/tm-buddypress.css');
	}
	if(is_plugin_active( 'bbpress/bbpress.php' )){
		wp_enqueue_style( 'truemag-bb', get_template_directory_uri() . '/css/tm-bbpress.css');
	}
	wp_enqueue_style( 'ui-css', get_template_directory_uri() . '/css/jquery-ui.css');
	wp_enqueue_style( 'truemag-icon-blg', get_template_directory_uri() . '/css/justVectorFont/stylesheets/justVector.css');
	wp_enqueue_style( 'malihu-scroll-css', get_template_directory_uri() . '/js/malihu-scroll/jquery.mCustomScrollbar.min.css');


}
add_action( 'wp_enqueue_scripts', 'cactusthemes_scripts_styles' );

add_action('wp_head','cactus_wp_head',100);
if(!function_exists('cactus_wp_head')){
	function cactus_wp_head(){
		echo '<!-- custom css -->
				<style type="text/css">';
		
		require get_template_directory() . '/css/custom.css.php';
		
		echo '</style>
			<!-- end custom css -->';
	}
}

/**
 * Registers our main widget area and the front page widget areas.
 *
 * @since Twenty Twelve 1.0
 */
function cactusthemes_widgets_init() {
	$rtl = ot_get_option( 'righttoleft', 0);
	
	register_sidebar( array(
		'name' => __( 'Main Sidebar', 'cactusthemes' ),
		'id' => 'main_sidebar',
		'description' => __( 'Appears on posts and pages except the optional Front Page template, which has its own widgets', 'cactusthemes' ),
		'before_widget' => '<div id="%1$s" class="widget widget-border %2$s">',
		'after_widget' => '</div>',
		'before_title' => $rtl ? '<h2 class="widget-title maincolor2">' : '<h2 class="widget-title maincolor2">',
		'after_title' => $rtl ? '</h2>' : '</h2>',
	));
	
	register_sidebar( array(
		'name' => __( 'Home Sidebar', 'cactusthemes' ),
		'id' => 'home_sidebar',
		'description' => __('Sidebar in home page. If empty, main sidebar will be used', 'cactusthemes' ),
		'before_widget' => '<div id="%1$s" class="widget widget-border %2$s">',
		'after_widget' => '</div>',
		'before_title' => $rtl ? '<h2 class="widget-title maincolor2">' : '<h2 class="widget-title maincolor2">',
		'after_title' => $rtl ? '</h2>' : '</h2>',
	));
	
	register_sidebar( array(
		'name' => __( 'Main Top Sidebar', 'cactusthemes' ),
		'id' => 'maintop_sidebar',
		'description' => __( 'Sidebar in top of site, be used if there are no slider ', 'cactusthemes' ),
		'before_widget' => '<div id="%1$s" class="%2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h2 class="widget-title maincolor1">',
		'after_title' => '</h2>',
	));
	register_sidebar( array(
		'name' => __( 'Headline Sidebar', 'cactusthemes' ),
		'id' => 'headline_sidebar',
		'description' => __( '', 'cactusthemes' ),
		'before_widget' => '<div id="%1$s" class="headline-widget %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h3>',
		'after_title' => '</h3>',
	));
	register_sidebar( array(
		'name' => __( 'Pathway Sidebar', 'cactusthemes' ),
		'id' => 'pathway_sidebar',
		'description' => __( 'Replace Pathway (Breadcrumbs) with your widgets', 'cactusthemes' ),
		'before_widget' => '<div id="%1$s" class="pathway-widget %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h3>',
		'after_title' => '</h3>',
	));
	register_sidebar( array(
		'name' => __( 'Search Box Sidebar', 'cactusthemes' ),
		'id' => 'search_sidebar',
		'description' => __( '', 'cactusthemes' ),
		'before_widget' => '<div id="%1$s" class="heading-search-widget %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h3>',
		'after_title' => '</h3>',
	));
	register_sidebar( array(
		'name' => __( 'User Submit Video Sidebar', 'cactusthemes' ),
		'id' => 'user_submit_sidebar',
		'description' => __( 'Sidebar in popup User submit video', 'cactusthemes' ),
		'before_widget' => '<div id="%1$s" class="%2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h2 class="widget-title maincolor2">',
		'after_title' => '</h2>',
	));
	register_sidebar( array(
		'name' => __( 'Footer Sidebar', 'cactusthemes' ),
		'id' => 'footer_sidebar',
		'description' => __( '', 'cactusthemes' ),
		'before_widget' => '<div id="%1$s" class="widget col-md-3 col-sm-6 %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h2 class="widget-title maincolor1">',
		'after_title' => '</h2>',
	));
	register_sidebar( array(
		'name' => __( 'Footer 404 page Sidebar', 'cactusthemes' ),
		'id' => 'footer_404_sidebar',
		'description' => __( '', 'cactusthemes' ),
		'before_widget' => '<div id="%1$s" class="widget col-md-3 col-sm-6 %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h2 class="widget-title maincolor1">',
		'after_title' => '</h2>',
	));
	register_sidebar( array(
		'name' => __( 'Blog Sidebar', 'cactusthemes' ),
		'id' => 'blog_sidebar',
		'description' => __( 'Sidebar in blog, category (blog) page. If there is no widgets, Main Sidebar will be used ', 'cactusthemes' ),
		'before_widget' => '<div id="%1$s" class="widget widget-border %2$s">',
		'after_widget' => '</div>',
		'before_title' => $rtl ? '<h2 class="widget-title maincolor2">' : '<h2 class="widget-title maincolor2">',
		'after_title' => $rtl ? '</h2>' : '</h2>',
	));
	register_sidebar( array(
		'name' => __( 'Video listing Sidebar', 'cactusthemes' ),
		'id' => 'video_listing_sidebar',
		'description' => __( 'Sidebar in blog, category (video) page. If there is no widgets, Main Sidebar will be used ', 'cactusthemes' ),
		'before_widget' => '<div id="%1$s" class="widget widget-border %2$s">',
		'after_widget' => '</div>',
		'before_title' => $rtl ? '<h2 class="widget-title maincolor2">' : '<h2 class="widget-title maincolor2">',
		'after_title' => $rtl ? '</h2>' : '</h2>',
	));
	register_sidebar( array(
		'name' => __( 'Single Blog Sidebar', 'cactusthemes' ),
		'id' => 'single_blog_sidebar',
		'description' => __( 'Sidebar in single post page. If there is no widgets, Main Sidebar will be used ', 'cactusthemes' ),
		'before_widget' => '<div id="%1$s" class="widget widget-border %2$s">',
		'after_widget' => '</div>',
		'before_title' => $rtl ? '<h2 class="widget-title maincolor2">' : '<h2 class="widget-title maincolor2">',
		'after_title' => $rtl ? '</h2>' : '</h2>',
	));
	register_sidebar( array(
		'name' => __( 'Single Video Sidebar', 'cactusthemes' ),
		'id' => 'single_video_sidebar',
		'description' => __( 'Sidebar in single Video post page. If there is no widgets, Main Sidebar will be used ', 'cactusthemes' ),
		'before_widget' => '<div id="%1$s" class="widget widget-border %2$s">',
		'after_widget' => '</div>',
		'before_title' => $rtl ? '<h2 class="widget-title maincolor2">' : '<h2 class="widget-title maincolor2">',
		'after_title' => $rtl ? '</h2>' : '</h2>',
	));
	register_sidebar( array(
		'name' => __( 'Search page Sidebar', 'cactusthemes' ),
		'id' => 'search_page_sidebar',
		'description' => __( 'Appears on Search result page', 'cactusthemes' ),
		'before_widget' => '<div id="%1$s" class="widget widget-border %2$s">',
		'after_widget' => '</div>',
		'before_title' => $rtl ? '<h2 class="widget-title maincolor2">' : '<h2 class="widget-title maincolor2">',
		'after_title' => $rtl ? '</h2>' : '</h2>',
	));
	register_sidebar( array(
		'name' => __( 'Single Page Sidebar', 'cactusthemes' ),
		'id' => 'single_page_sidebar',
		'description' => __( 'Sidebar in single page. If there is no widgets, Main Sidebar will be used ', 'cactusthemes' ),
		'before_widget' => '<div id="%1$s" class="widget widget-border %2$s">',
		'after_widget' => '</div>',
		'before_title' => $rtl ? '<h2 class="widget-title maincolor2">' : '<h2 class="widget-title maincolor2">',
		'after_title' => $rtl ? '</h2>' : '</h2>',
	));
	register_sidebar( array(
		'name' => __( 'Pledges_content', 'cactusthemes' ),
		'id' => 'Pledges_content',
		'description' => __( 'Sidebar in single page. If there is no widgets, Main Sidebar will be used ', 'cactusthemes' ),
		'before_widget' => '<div id="%1$s" class="widget widget-border %2$s">',
		'after_widget' => '</div>',
		'before_title' => $rtl ? '<h2 class="widget-title maincolor2">' : '<h2 class="widget-title maincolor2">',
		'after_title' => $rtl ? '</h2>' : '</h2>',
	));
	register_sidebar( array(
		'name' => __( 'Pledges_submit', 'cactusthemes' ),
		'id' => 'Pledges_submit',
		'description' => __( 'Sidebar in single page. If there is no widgets, Main Sidebar will be used ', 'cactusthemes' ),
		'before_widget' => '<div id="%1$s" class="widget widget-border %2$s">',
		'after_widget' => '</div>',
		'before_title' => $rtl ? '<h2 class="widget-title maincolor2">' : '<h2 class="widget-title maincolor2">',
		'after_title' => $rtl ? '</h2>' : '</h2>',
	));
	if(function_exists('is_woocommerce')){
		register_sidebar( array(
			'name' => __( 'Woocommerce Single Product Sidebar', 'cactusthemes' ),
			'id' => 'single_woo_sidebar',
			'description' => __( 'Sidebar in single product. If there is no widgets, Main Sidebar will be used ', 'cactusthemes' ),
			'before_widget' => '<div id="%1$s" class="widget widget-border %2$s">',
			'after_widget' => '</div>',
			'before_title' => $rtl ? '<h2 class="widget-title maincolor2">' : '<h2 class="widget-title maincolor2">',
			'after_title' => $rtl ? '</h2>' : '</h2>',
		));
		register_sidebar( array(
			'name' => __( 'Woocommerce Shop Page Sidebar', 'cactusthemes' ),
			'id' => 'shop_sidebar',
			'description' => __( 'Sidebar in shop page. If there is no widgets, Main Sidebar will be used ', 'cactusthemes' ),
			'before_widget' => '<div id="%1$s" class="widget widget-border %2$s">',
			'after_widget' => '</div>',
			'before_title' => $rtl ? '<h2 class="widget-title maincolor2">' : '<h2 class="widget-title maincolor2">',
			'after_title' => $rtl ? '</h2>' : '</h2>',
		));
	}
if ( is_plugin_active( 'buddypress/bp-loader.php' ) ) {
	//buddyPress
	register_sidebar( array(
		'name' => __( 'BuddyPress Sidebar', 'cactusthemes' ),
		'id' => 'bp_sidebar',
		'description' => __( 'Sidebar in BuddyPress Page.', 'cactusthemes' ),
		'before_widget' => '<div id="%1$s" class="widget widget-border %2$s">',
		'after_widget' => '</div>',
		'before_title' => $rtl ? '<h2 class="widget-title maincolor2">' : '<h2 class="widget-title maincolor2">',
		'after_title' => $rtl ? '</h2>' : '</h2>',
	));
	register_sidebar( array(
		'name' => __( 'BuddyPress Sitewide Activity Sidebar', 'cactusthemes' ),
		'id' => 'bp_activity_sidebar',
		'description' => __( 'Sidebar in BuddyPress Sitewide Activity Page. If there is no widgets, BuddyPress Sidebar will be used', 'cactusthemes' ),
		'before_widget' => '<div id="%1$s" class="widget widget-border %2$s">',
		'after_widget' => '</div>',
		'before_title' => $rtl ? '<h2 class="widget-title maincolor2">' : '<h2 class="widget-title maincolor2">',
		'after_title' => $rtl ? '</h2>' : '</h2>',
	));
	register_sidebar( array(
		'name' => __( 'BuddyPress Sitewide Members Sidebar', 'cactusthemes' ),
		'id' => 'bp_member_sidebar',
		'description' => __( 'Sidebar in BuddyPress Sitewide Member Page. If there is no widgets, BuddyPress Sidebar will be used', 'cactusthemes' ),
		'before_widget' => '<div id="%1$s" class="widget widget-border %2$s">',
		'after_widget' => '</div>',
		'before_title' => $rtl ? '<h2 class="widget-title maincolor2">' : '<h2 class="widget-title maincolor2">',
		'after_title' => $rtl ? '</h2>' : '</h2>',
	));
	register_sidebar( array(
		'name' => __( 'BuddyPress Sitewide Groups Sidebar', 'cactusthemes' ),
		'id' => 'bp_group_sidebar',
		'description' => __( 'Sidebar in BuddyPress Sitewide Groups Page. If there is no widgets, BuddyPress Sidebar will be used', 'cactusthemes' ),
		'before_widget' => '<div id="%1$s" class="widget widget-border %2$s">',
		'after_widget' => '</div>',
		'before_title' => $rtl ? '<h2 class="widget-title maincolor2">' : '<h2 class="widget-title maincolor2">',
		'after_title' => $rtl ? '</h2>' : '</h2>',
	));
	register_sidebar( array(
		'name' => __( 'BuddyPress Single Members Sidebar', 'cactusthemes' ),
		'id' => 'bp_single_member_sidebar',
		'description' => __( 'Sidebar in BuddyPress Single Member Page. If there is no widgets, BuddyPress Sidebar will be used', 'cactusthemes' ),
		'before_widget' => '<div id="%1$s" class="widget widget-border %2$s">',
		'after_widget' => '</div>',
		'before_title' => $rtl ? '<h2 class="widget-title maincolor2">' : '<h2 class="widget-title maincolor2">',
		'after_title' => $rtl ? '</h2>' : '</h2>',
	));
	register_sidebar( array(
		'name' => __( 'BuddyPress Single Groups Sidebar', 'cactusthemes' ),
		'id' => 'bp_single_group_sidebar',
		'description' => __( 'Sidebar in BuddyPress Single Groups Page. If there is no widgets, BuddyPress Sidebar will be used', 'cactusthemes' ),
		'before_widget' => '<div id="%1$s" class="widget widget-border %2$s">',
		'after_widget' => '</div>',
		'before_title' => $rtl ? '<h2 class="widget-title maincolor2">' : '<h2 class="widget-title maincolor2">',
		'after_title' => $rtl ? '</h2>' : '</h2>',
	));
	register_sidebar( array(
		'name' => __( 'BuddyPress Register Sidebar', 'cactusthemes' ),
		'id' => 'bp_register_sidebar',
		'description' => __( 'Sidebar in BuddyPress Register Page. If there is no widgets, BuddyPress Sidebar will be used', 'cactusthemes' ),
		'before_widget' => '<div id="%1$s" class="widget widget-border %2$s">',
		'after_widget' => '</div>',
		'before_title' => $rtl ? '<h2 class="widget-title maincolor2">' : '<h2 class="widget-title maincolor2">',
		'after_title' => $rtl ? '</h2>' : '</h2>',
	));
}
}
add_action( 'widgets_init', 'cactusthemes_widgets_init' );

add_image_size('thumb_139x89',139,89, true); //widget
add_image_size('thumb_365x235',365,235, true); //blog
add_image_size('thumb_196x126',196,126, true); //cat carousel, related
add_image_size('thumb_520x293',520,293, true); //big carousel 16:9
add_image_size('thumb_260x146',260,146, true); //metro carousel 16:9
add_image_size('thumb_356x200',356,200, true); //metro carousel 16:9 bigger
add_image_size('thumb_370x208',370,208, true); //scb grid 16:9
add_image_size('thumb_180x101',180,101, true); //scb small
add_image_size('thumb_130x73',130,73, true); //mobile
add_image_size('thumb_748x421',748,421, true); //classy big
add_image_size('thumb_72x72',72,72, true); //classy thumb

add_image_size('thumb_358x242',358,242, true); //shop

// Hook widget 'SEARCH'
add_filter('get_search_form', 'cactus_search_form'); 
function cactus_search_form($text) {
	$text = str_replace('value=""', 'placeholder="'.__("SEARCH",'cactusthemes').'"', $text);
    return $text;
}

/* Display Facebook and Google Plus button */
function gp_social_share($post_ID){
/*if(ot_get_option('social_like',1)){	
?>
<div id="social-share">
    &nbsp;
    <iframe src="//www.facebook.com/plugins/like.php?href=<?php echo urlencode(get_permalink($post_ID)) ?>&amp;width=450&amp;height=21&amp;colorscheme=light&amp;layout=button_count&amp;action=like&amp;show_faces=false&amp;send=false&amp;appId=498927376861973" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:85px; height:21px;" allowTransparency="true"></iframe>
    &nbsp;
    <div class="g-plusone" data-size="medium"></div>
    <script type="text/javascript">
      window.___gcfg = {lang: 'en-GB'};
      (function() {
        var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
        po.src = 'https://apis.google.com/js/plusone.js';
        var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
      })();
    </script>
</div>
<?php }*/
}

/* Display Icon Links to some social networks */
function tm_social_share(){ ?>
<div class="tm-social-share">
	<?php if(ot_get_option('share_facebook')){ ?>
	<a class="social-icon s-fb" title="<?php _e('Share on Facebook','cactusthemes'); ?>" href="#" target="_blank" rel="nofollow" onclick="window.open('https://www.facebook.com/sharer/sharer.php?u='+encodeURIComponent(location.href),'facebook-share-dialog','width=626,height=436');return false;"><i class="fa fa-facebook"></i></a>
    <?php } ?>
    <?php if(ot_get_option('share_twitter')){ ?>
    <a class="social-icon s-tw" href="#" title="<?php _e('Share on Twitter','cactusthemes'); ?>" rel="nofollow" target="_blank" onclick="window.open('http://twitter.com/share?text=<?php echo urlencode(html_entity_decode(get_the_title(get_the_ID()), ENT_COMPAT, 'UTF-8')); ?>&amp;url=<?php echo urlencode(get_permalink(get_the_ID())); ?>','twitter-share-dialog','width=626,height=436');return false;"><i class="fa fa-twitter"></i></a>
    <?php } ?>
    <?php if(ot_get_option('share_linkedin')){ ?>
    <a class="social-icon s-lk" href="#" title="<?php _e('Share on LinkedIn','cactusthemes'); ?>" rel="nofollow" target="_blank" onclick="window.open('http://www.linkedin.com/shareArticle?mini=true&amp;url=<?php echo urlencode(get_permalink(get_the_ID())); ?>&amp;title=<?php echo urlencode(html_entity_decode(get_the_title(get_the_ID()), ENT_COMPAT, 'UTF-8')); ?>&amp;source=<?php echo urlencode(get_bloginfo('name')); ?>','linkedin-share-dialog','width=626,height=436');return false;"><i class="fa fa-linkedin"></i></a>
    <?php } ?>
    <?php if(ot_get_option('share_tumblr')){ ?>
    <a class="social-icon s-tb" href="#" title="<?php _e('Share on Tumblr','cactusthemes'); ?>" rel="nofollow" target="_blank" onclick="window.open('http://www.tumblr.com/share/link?url=<?php echo urlencode(get_permalink(get_the_ID())); ?>&amp;name=<?php echo urlencode(html_entity_decode(get_the_title(get_the_ID()), ENT_COMPAT, 'UTF-8')); ?>','tumblr-share-dialog','width=626,height=436');return false;"><i class="fa fa-tumblr"></i></a>
    <?php } ?>
    <?php if(ot_get_option('share_google_plus')){ ?>
    <a class="social-icon s-gg" href="#" title="<?php _e('Share on Google Plus','cactusthemes'); ?>" rel="nofollow" target="_blank" onclick="window.open('https://plus.google.com/share?url=<?php echo urlencode(get_permalink(get_the_ID())); ?>','googleplus-share-dialog','width=626,height=436');return false;"><i class="fa fa-google-plus"></i></a>
    <?php } ?>
    
    <?php if(ot_get_option('share_blogger')){ ?>
    <a class="social-icon s-bl" href="#" title="<?php _e('Share on Blogger','cactusthemes'); ?>" rel="nofollow" target="_blank" onclick="window.open('https://www.blogger.com/blog-this.g?u=<?php echo urlencode(get_permalink(get_the_ID())); ?>&amp;n=<?php echo urlencode(html_entity_decode(get_the_title(get_the_ID()), ENT_COMPAT, 'UTF-8')); ?>&amp;t=<?php echo urlencode(get_the_excerpt()); ?>','blogger-share-dialog','width=626,height=436');return false;"><i id="jv-blogger" class="jv-blogger"></i></a>
    <?php } ?>
    <?php if(ot_get_option('share_reddit')){ ?>
    <a class="social-icon s-rd" href="#" title="<?php _e('Share on Reddit','cactusthemes'); ?>" rel="nofollow" target="_blank" onclick="window.open('//www.reddit.com/submit?url=<?php echo urlencode(get_permalink(get_the_ID())); ?>','reddit-share-dialog','width=626,height=436');return false;"><i class="fa fa-reddit"></i></a>
    <?php } ?>
    
    <?php if(ot_get_option('share_vk')){ ?>
    <a class="social-icon s-vk" href="#" title="<?php _e('Share on Vk','cactusthemes'); ?>" rel="nofollow" target="_blank" onclick="window.open('http://vkontakte.ru/share.php?url=<?php echo urlencode(get_permalink(get_the_ID())); ?>','vk-share-dialog','width=626,height=436');return false;"><i class="fa fa-vk"></i></a>
    <?php } ?>
    
    
    <?php if(ot_get_option('share_pinterest')){ ?>
    <a class="social-icon s-pin" href="#" title="<?php _e('Pin this','cactusthemes'); ?>" rel="nofollow" target="_blank" onclick="window.open('//pinterest.com/pin/create/button/?url=<?php echo urlencode(get_permalink(get_the_ID())) ?>&amp;media=<?php echo urlencode(wp_get_attachment_url( get_post_thumbnail_id(get_the_ID()))); ?>&amp;description=<?php echo urlencode(html_entity_decode(get_the_title(get_the_ID()), ENT_COMPAT, 'UTF-8')); ?>','pin-share-dialog','width=626,height=436');return false;"><i class="fa fa-pinterest"></i></a>
    <?php } ?>
    <?php if(ot_get_option('share_email')){ ?>
    <a class="social-icon s-em" href="mailto:?subject=<?php echo urlencode(html_entity_decode(get_the_title(get_the_ID()), ENT_COMPAT, 'UTF-8')); ?>&amp;body=<?php echo urlencode(get_permalink(get_the_ID())) ?>" title="<?php _e('Email this','cactusthemes'); ?>"><i class="fa fa-envelope"></i></a>
    <?php } ?>
</div>
<?php }

require_once 'inc/category-metadata.php';
require_once 'inc/google-adsense-responsive.php';

/*facebook comment*/
if(!function_exists('tm_update_fb_comment')){
	function tm_update_fb_comment(){
		if(is_plugin_active('facebook/facebook.php')&&get_option('facebook_comments_enabled')&&is_single()){
			global $post;
			//$actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
			if(class_exists('Facebook_Comments')){
				//$comment_count = Facebook_Comments::get_comments_number_filter(0,$post->ID);
				$comment_count = get_comments_number($post->ID);
			}else{
				$actual_link = get_permalink($post->ID);
				$fql  = "SELECT url, normalized_url, like_count, comment_count, ";
				$fql .= "total_count, commentsbox_count, comments_fbid FROM ";
				$fql .= "link_stat WHERE url = '".$actual_link."'";
				$apifql = "https://api.facebook.com/method/fql.query?format=json&query=".urlencode($fql);
				$json = file_get_contents($apifql);
				//print_r( json_decode($json));
				$link_fb_stat = json_decode($json);
				$comment_count = $link_fb_stat[0]->commentsbox_count?$link_fb_stat[0]->commentsbox_count:0;
			}
			update_post_meta($post->ID, 'custom_comment_count', $comment_count);
		}elseif(is_plugin_active('disqus-comment-system/disqus.php')&&is_single()){
			global $post;
			echo '<a href="'.get_permalink($post->ID).'#disqus_thread" id="disqus_count" class="hidden">comment_count</a>';
			?>
            <script type="text/javascript">
			/* * * CONFIGURATION VARIABLES: EDIT BEFORE PASTING INTO YOUR WEBPAGE * * */
			var disqus_shortname = '<?php echo get_option('disqus_forum_url','testtruemag') ?>'; // required: replace example with your forum shortname
			/* * * DON'T EDIT BELOW THIS LINE * * */
			(function () {
			var s = document.createElement('script'); s.async = true;
			s.type = 'text/javascript';
			s.src = 'http://' + disqus_shortname + '.disqus.com/count.js';
			(document.getElementsByTagName('HEAD')[0] || document.getElementsByTagName('BODY')[0]).appendChild(s);
			}());
			//get comments number
			jQuery(window).load(function(e) {
                var str = jQuery('#disqus_count').html();
				var pattern = /[0-9]+/;
				var matches = str.match(pattern);
				matches = (matches)?matches[0]:0;
				if(!isNaN(parseFloat(matches)) && isFinite(matches)){ //is numberic
					var param = {
						action: 'tm_disqus_update',
						post_id:<?php echo $post->ID ?>,
						count:matches,
					};
					jQuery.ajax({
						type: "GET",
						url: "<?php echo home_url('/'); ?>wp-admin/admin-ajax.php",
						dataType: 'html',
						data: (param),
						success: function(data){
							//
						}
					});
				}//if numberic
			});
			</script>
            <?php
		}
	}
}

add_action('wp_footer', 'tm_update_fb_comment', 100);
//ajax update disqus count
if(!function_exists('tm_disqus_update')){
	function tm_disqus_update(){
		if(isset($_GET['post_id'])){
			update_post_meta($_GET['post_id'], 'custom_comment_count', $_GET['count']?$_GET['count']:0);
		}
	}
}
add_action("wp_ajax_tm_disqus_update", "tm_disqus_update");
add_action("wp_ajax_nopriv_tm_disqus_update", "tm_disqus_update");

//hook for get disqus count
if(!function_exists('tm_get_disqus_count')){
	function tm_get_disqus_count($count, $post_id){
		if(is_plugin_active('disqus-comment-system/disqus.php')){
			$return = get_post_meta($post_id,'custom_comment_count',true);
			return $return?$return:0;
		}else{
			return $count;
		}
	}
}
add_filter( 'get_comments_number', 'tm_get_disqus_count', 10, 2 );

if(!function_exists('tm_breadcrumbs')){
	function tm_breadcrumbs(){
		/* === OPTIONS === */
		$text['home']     = __('Home','cactusthemes'); // text for the 'Home' link
		$text['category'] = '%s'; // text for a category page
		$text['search']   = __('Search Results for','cactusthemes').' "%s"'; // text for a search results page
		$text['tag']      = __('Tag','cactusthemes').' "%s"'; // text for a tag page
		$text['author']   = __('Author','cactusthemes').' %s'; // text for an author page
		$text['404']      = __('404','cactusthemes'); // text for the 404 page

		$show_current   = 1; // 1 - show current post/page/category title in breadcrumbs, 0 - don't show
		$show_on_home   = 1; // 1 - show breadcrumbs on the homepage, 0 - don't show
		$show_home_link = 1; // 1 - show the 'Home' link, 0 - don't show
		$show_title     = 1; // 1 - show the title for the links, 0 - don't show
		$delimiter      = ' \\ '; // delimiter between crumbs
		$before         = '<span class="current">'; // tag before the current crumb
		$after          = '</span>'; // tag after the current crumb
		/* === END OF OPTIONS === */

		global $post;
		$home_link    = home_url('/');
		$link_before  = '<span typeof="v:Breadcrumb">';
		$link_after   = '</span>';
		$link_attr    = ' rel="v:url" property="v:title"';
		$link         = $link_before . '<a' . $link_attr . ' href="%1$s">%2$s</a>' . $link_after;
		$parent_id    = $parent_id_2 = ($post) ? $post->post_parent : 0;
		$frontpage_id = get_option('page_on_front');

		if (is_front_page()) {

			if ($show_on_home == 1) echo '<div class="breadcrumbs"><a href="' . $home_link . '">' . $text['home'] . '</a></div>';

		}elseif(is_home()){
			$title = get_option('page_for_posts')?get_the_title(get_option('page_for_posts')):__('Blog','cactusthemes');
			echo '<div class="breadcrumbs"><a href="' . $home_link . '">' . $text['home'] . '</a> \ '.$title.'</div>';
		} else {

			echo '<div class="breadcrumbs" xmlns:v="http://rdf.data-vocabulary.org/#">';
			if ($show_home_link == 1) {
				echo '<a href="' . $home_link . '" rel="v:url" property="v:title">' . $text['home'] . '</a>';
				if ($frontpage_id == 0 || $parent_id != $frontpage_id) echo $delimiter;
			}
			if(is_tax()){
				single_term_title('',true);
			}else if ( is_category() ) {
				$this_cat = get_category(get_query_var('cat'), false);
				if ($this_cat->parent != 0) {
					$cats = get_category_parents($this_cat->parent, TRUE, $delimiter);
					if ($show_current == 0) $cats = preg_replace("#^(.+)$delimiter$#", "$1", $cats);
					$cats = str_replace('<a', $link_before . '<a' . $link_attr, $cats);
					$cats = str_replace('</a>', '</a>' . $link_after, $cats);
					if ($show_title == 0) $cats = preg_replace('/ title="(.*?)"/', '', $cats);
					echo $cats;
				}
				if ($show_current == 1) echo $before . sprintf($text['category'], single_cat_title('', false)) . $after;

			} elseif ( is_search() ) {
				echo $before . sprintf($text['search'], get_search_query()) . $after;

			} elseif ( is_day() ) {
				echo sprintf($link, get_year_link(get_the_time('Y')), get_the_time('Y')) . $delimiter;
				echo sprintf($link, get_month_link(get_the_time('Y'),get_the_time('m')), get_the_time('F')) . $delimiter;
				echo $before . get_the_time('d') . $after;

			} elseif ( is_month() ) {
				echo sprintf($link, get_year_link(get_the_time('Y')), get_the_time('Y')) . $delimiter;
				echo $before . get_the_time('F') . $after;

			} elseif ( is_year() ) {
				echo $before . get_the_time('Y') . $after;

			} elseif ( is_single() && !is_attachment() ) {
				if ( get_post_type() != 'post' ) {
					$post_type = get_post_type_object(get_post_type());
					$slug = $post_type->rewrite;
					printf($link, $home_link . '/' . $slug['slug'] . '/', $post_type->labels->singular_name);
					if ($show_current == 1) echo $delimiter . $before . get_the_title() . $after;
				} else {
					$cat = get_the_category();
                    if(count($cat) > 0){
                        $cat = $cat[0];
                    } else {
                        $cat = 1; // uncategorized
                    }
                        $cats = get_category_parents($cat, TRUE, $delimiter);
                        if ($show_current == 0) $cats = preg_replace("#^(.+)$delimiter$#", "$1", $cats);
                    
                        $cats = str_replace('<a', $link_before . '<a' . $link_attr, $cats);
                        $cats = str_replace('</a>', '</a>' . $link_after, $cats);
                        if ($show_title == 0) $cats = preg_replace('/ title="(.*?)"/', '', $cats);
                        echo $cats;
                    
                    if ($show_current == 1) echo $before . get_the_title() . $after;
				}

			} elseif ( !is_single() && !is_page() && get_post_type() != 'post' && !is_404() ) {
				$post_type = get_post_type_object(get_post_type());
				echo $before . $post_type->labels->singular_name . $after;

			} elseif ( is_attachment() ) {
				$parent = get_post($parent_id);
				printf($link, get_permalink($parent), $parent->post_title);
				if ($show_current == 1) echo $delimiter . $before . get_the_title() . $after;

			} elseif ( is_page() && !$parent_id ) {
				if ($show_current == 1) echo $before . get_the_title() . $after;

			} elseif ( is_page() && $parent_id ) {
				if ($parent_id != $frontpage_id) {
					$breadcrumbs = array();
					while ($parent_id) {
						$page = get_page($parent_id);
						if ($parent_id != $frontpage_id) {
							$breadcrumbs[] = sprintf($link, get_permalink($page->ID), get_the_title($page->ID));
						}
						$parent_id = $page->post_parent;
					}
					$breadcrumbs = array_reverse($breadcrumbs);
					for ($i = 0; $i < count($breadcrumbs); $i++) {
						echo $breadcrumbs[$i];
						if ($i != count($breadcrumbs)-1) echo $delimiter;
					}
				}
				if ($show_current == 1) {
					if ($show_home_link == 1 || ($parent_id_2 != 0 && $parent_id_2 != $frontpage_id)) echo $delimiter;
					echo $before . get_the_title() . $after;
				}

			} elseif ( is_tag() ) {
				echo $before . sprintf($text['tag'], single_tag_title('', false)) . $after;

			} elseif ( is_author() ) {
				global $author;
				$userdata = get_userdata($author);
				echo $before . sprintf($text['author'], $userdata->display_name) . $after;

			} elseif ( is_404() ) {
				echo $before . $text['404'] . $after;
			}

			if ( get_query_var('paged') ) {
				if ( is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author() || is_home() || is_page_template()) echo ' (';
				echo __('Page','cactusthemes') . ' ' . get_query_var('paged');
				if ( is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author() || is_home() || is_page_template()) echo ')';
			}

			echo '</div><!-- .breadcrumbs -->';

		}
	} // end tm_breadcrumbs()
}

//custom login fail
add_action( 'wp_login_failed', 'tm_login_fail' );  // hook failed login
function tm_login_fail( $username ) {
	if($login_page = ot_get_option('login_page',false)){
		$referrer = $_SERVER['HTTP_REFERER'];  // where did the post submission come from?
		// if there's a valid referrer, and it's not the default log-in screen
		if ( !empty($referrer) && !strstr($referrer,'wp-login') && !strstr($referrer,'wp-admin') ) {
			wp_redirect(get_permalink($login_page).'?login=failed');  // let's append some information (login=failed) to the URL for the theme to use
			exit;
		}
	}
}
//redirect default login
add_action('init','tm_login_redirect');
function tm_login_redirect(){
	if($login_page = ot_get_option('login_page',false)){
	 global $pagenow;
	  if( 'wp-login.php' == $pagenow ) {
		if ( (isset($_POST['wp-submit']) && $_POST['log']!='' && $_POST['pwd']!='') ||   // in case of LOGIN
		  ( isset($_GET['action']) && $_GET['action']=='logout') ||   // in case of LOGOUT
		  ( isset($_GET['checkemail']) && $_GET['checkemail']=='confirm') ||   // in case of LOST PASSWORD
		  ( isset($_GET['action']) && $_GET['action']=='lostpassword') ||
		  ( isset($_GET['action']) && $_GET['action']=='rp') ||
		  ( isset($_GET['checkemail']) && $_GET['checkemail']=='registered') || // in case of REGISTER
		  isset($_GET['loginFacebook']) || isset($_GET['imadmin'])) return true;
		elseif(isset($_POST['wp-submit'])&&($_POST['log']=='' || $_POST['pwd']=='')){ wp_redirect(get_permalink($login_page) . '?login=failed' ); }
		else wp_redirect( get_permalink($login_page) ); // or wp_redirect(home_url('/login'));
		exit();
	  }
	}
}
//replace login page template
add_filter( 'page_template', 'tm_login_page_template' );
function tm_login_page_template( $page_template )
{
	if($login_page = ot_get_option('login_page',false)){
		if ( is_page( $login_page ) ) {
			$page_template = dirname( __FILE__ ) . '/page-templates/tpl-login.php';
		}
	}
    return $page_template;
}
function tm_author_avatar($ID = false, $size = 60){
	$user_avatar = false;
	$email='';
	if($ID == false){
		global $post;
		$ID = get_the_author_meta('ID');
		$email = get_the_author_meta('email');
	}
	if($user_avatar==false){
		global $_is_retina_;
		if($_is_retina_ && $size>120){
			$user_avatar = get_avatar( $email, $size, get_template_directory_uri() . '/images/avatar-3x.png' );
		}elseif($_is_retina_ || $size>120){ 
			$user_avatar = get_avatar( $email, $size, get_template_directory_uri() . '/images/avatar-2x.png' );
		}else{
			$user_avatar = get_avatar( $email, $size, get_template_directory_uri() . '/images/avatar.png' );
		}
	}
	return $user_avatar;
}

//add report post type
add_action( 'init', 'reg_report_post_type' );
function reg_report_post_type() {
	$args = array(
		'labels' => array(
			'name' => __( 'Reports' ),
			'singular_name' => __( 'Report' )
		),
		'menu_icon' 		=> 'dashicons-flag',
		'public'             => true,
		'publicly_queryable' => true,
		'exclude_from_search'=> true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'query_var'          => true,
		'capability_type'    => 'post',
		'has_archive'        => false,
		'hierarchical'       => false,
		'supports'           => array( 'title', 'editor', 'custom-fields' )
	);
	if(ot_get_option('video_report','on')!='off'){
		register_post_type( 'tm_report', $args );
	}
}
//redirect report post type
add_action( 'template_redirect', 'redirect_report_post_type' );
function redirect_report_post_type() {
	global $post;
	if(is_singular('tm_report')){
		if($url = get_post_meta(get_the_ID(),'tm_report_url',true)){
			wp_redirect($url);
		}
	}
}

//contact form 7 hook

//echo "<pre>";
// print_r($_SESSION);
//echo "</pre>";

function tm_contactform7_hook($WPCF7_ContactForm) {
	if(ot_get_option('user_submit',1)){
		$submission = WPCF7_Submission::get_instance();
		if($submission) {
			$posted_data = $submission->get_posted_data();
			
			if(isset($posted_data['video-url'])){
				$video_url = $posted_data['video-url'];
				$user_id = isset($posted_data['user_id'])?$posted_data['user_id']:'';
				$video_title = isset($posted_data['video-title'])?$posted_data['video-title']:'';
				$video_description = isset($posted_data['video-description'])?$posted_data['video-description']:'';
				$update_description = isset($posted_data['update-description'])?$posted_data['update-description']:'';
				$faq_description = isset($posted_data['faq-description'])?$posted_data['faq-description']:'';
				$video_excerpt = isset($posted_data['video-excerpt'])?$posted_data['video-excerpt']:'';
				$video_user = isset($posted_data['your-email'])?$posted_data['your-email']:'';
				$video_cat = isset($posted_data['cat'])?$posted_data['cat']:'';
				$video_tag = isset($posted_data['text-53'])?$posted_data['text-53']:'';
				$director = isset($posted_data['director'])?$posted_data['director']:'';
				$writer = isset($posted_data['writer'])?$posted_data['writer']:'';
				$stars = isset($posted_data['stars'])?$posted_data['stars']:'';
                $location = isset($posted_data['location'])?$posted_data['location']:'';
				$video_status = ot_get_option('user_submit_status','pending');
				$video_format = ot_get_option('user_submit_format','video');
                $crowdfunding_duration = isset($posted_data['crowdfunding_duration'])?$posted_data['crowdfunding_duration']:'';

				$htmlForProjetTab = $video_description;
				$htmlForProjetTab .= "<br/><h3>Location</h3>".$location."<br/><h3>Director</h3>".$director;
				$video_post = array(
				  'post_author' => $user_id,
				  'post_content'   => $video_description,
				  'post_excerpt'   => $video_excerpt,
				  'post_name' 	   => sanitize_title($video_title), //slug
				  'post_title'     => $video_title,
				  'post_status'    => 'draft',
				  'post_category'  => $video_cat,
				  'tags_input'	   => $video_tag,
				  'post_type'      => 'post'
				);
				if($new_ID = wp_insert_post( $video_post, $wp_error )){

                    $post = get_post($new_ID);
                    $slug = $post->post_name;
                    $_SESSION['vd_slug'] = $new_ID;
                    $_SESSION['vd_title'] = $post->post_title;
                    add_post_meta($new_ID, 'post_project',$htmlForProjetTab);
                    add_post_meta($new_ID, 'post_updates',$update_description);
                    add_post_meta($new_ID, 'post_faq',$faq_description);
					add_post_meta( $new_ID, 'tm_video_url', $video_url );
					add_post_meta( $new_ID, 'tm_user_submit', $video_user );
					add_post_meta( $new_ID, 'director', $director );
					add_post_meta( $new_ID, 'writer', $writer );
					add_post_meta( $new_ID, 'stars', $stars );
					add_post_meta( $new_ID, 'crowdfunding_duration_video', $crowdfunding_duration );
					if(!ot_get_option('user_submit_fetch',0)){
						add_post_meta( $new_ID, 'fetch_info', 1);
					}
					set_post_format( $new_ID, $video_format );
					$video_post['ID'] = $new_ID;
					wp_update_post( $video_post );
				}
			}//if video_url
		}//if submission
	}
	
	//catch report form
	$submission = WPCF7_Submission::get_instance();
	if($submission) {
		$posted_data = $submission->get_posted_data();
		//error_log(print_r($posted_data, true));
		if(isset($posted_data['report-url'])){
			$post_url = $posted_data['report-url'];
			$post_user = isset($posted_data['your-email'])?$posted_data['your-email']:'';
			$post_message = isset($posted_data['your-message'])?$posted_data['your-message']:'';
			
			$post_title = sprintf(__("%s reported a video",'cactusthemes'), $post_user);
			$post_content = sprintf(__("%s reported a video has inappropriate content with message:<blockquote>%s</blockquote><br><br>You could review it here <a href='%s'>%s</a>",'cactusthemes'), $post_user, $post_message, $post_url, $post_url);
			
			$report_post = array(
			  'post_content'   => $post_content,
			  'post_name' 	   => sanitize_title($video_title), //slug
			  'post_title'     => $post_title,
			  'post_status'    => 'publish',
			  'post_type'      => 'tm_report'
			);
			if($new_report = wp_insert_post( $report_post, $wp_error )){
				add_post_meta( $new_report, 'tm_report_url', $post_url );
				add_post_meta( $new_report, 'tm_user_submit', $post_user );
			}
		}//if report_url
	}//if submission



}
add_action("wpcf7_before_send_mail", "tm_contactform7_hook");

function tm_wpcf7_add_shortcode(){
	if(function_exists('wpcf7_add_shortcode')){
		wpcf7_add_shortcode(array('category','category*'), 'tm_catdropdown', true);
		wpcf7_add_shortcode(array('report_url','report_url*'), 'tm_report_input', true);
		//wpcf7_add_shortcode(array('video_description','video_description*'), 'video_description_fun', true);

	}
}
function video_description_fun( ) {
    $output = '<div class="wpcf7-form-control-wrap"><div class="row wpcf7-form-control">';


    ob_start(); // Start output buffer

    wp_editor( '', 'video-description' );

    $editor = ob_get_clean();
    $output .= $editor;

    $output .= '</div></div>';
    return $output;

}

function update_description_fun( ) {
    $output = '<div class="wpcf7-form-control-wrap"><div class="row wpcf7-form-control">';

    ob_start(); // Start output buffer

    wp_editor( '', 'update-description' );

    $editor = ob_get_clean();
    $output .= $editor;

    $output .= '</div></div>';
    return $output;

}

function faq_description_fun( ) {
    $output = '<div class="wpcf7-form-control-wrap"><div class="row wpcf7-form-control">';

    ob_start(); // Start output buffer

    wp_editor( '', 'faq-description' );

    $editor = ob_get_clean();
    $output .= $editor;

    $output .= '</div></div>';
    return $output;

}

add_shortcode("video_description",'video_description_fun');
add_shortcode("update_description",'update_description_fun');
add_shortcode("faq_description",'faq_description_fun');

add_filter( 'wpcf7_form_elements', 'mycustom_wpcf7_form_elements' );

function mycustom_wpcf7_form_elements( $form ) {
    $form = do_shortcode( $form );

    return $form;
}
add_shortcode( 'video_description', 'video_description_fun' );
function tm_catdropdown($tag){
	$class = '';
	$is_required = 0;
	if(class_exists('WPCF7_Shortcode')){
		$tag = new WPCF7_Shortcode( $tag );
		if ( $tag->is_required() ){
			$is_required = 1;
			$class .= ' required-cat';
		}
	}
	$cargs = array(
		'hide_empty'    => false, 
		'exclude'       => explode(",",ot_get_option('user_submit_cat_exclude',''))
	); 
	$cats = get_terms( 'category', $cargs );
	if($cats){
		$output = '<div class="wpcf7-form-control-wrap cat"><div class="row wpcf7-form-control wpcf7-checkbox wpcf7-validates-as-required'.$class.'">';
		if(ot_get_option('user_submit_cat_radio','off')=='on'){
			foreach ($cats as $acat){
				$output .= '<label class="col-md-4 wpcf7-list-item"><input type="radio" name="cat[]" value="'.$acat->term_id.'" /> '.$acat->name.'</label>';
			}
		}else{
			foreach ($cats as $acat){
				$output .= '<label class="col-md-4 wpcf7-list-item"><input type="checkbox" name="cat[]" value="'.$acat->term_id.'" /> '.$acat->name.'</label>';
			}
		}
		$output .= '</div></div>';
	}
	ob_start();
	if($is_required){
	?>
    <script>
	jQuery(document).ready(function(e) {
		jQuery("form.wpcf7-form").submit(function (e) {
			if(jQuery("input[name='cat[]']", this).length){
				var checked = 0;
				jQuery.each(jQuery("input[name='cat[]']:checked"), function() {
					checked = jQuery(this).val();
				});
				if(checked == 0){
					if(jQuery('.cat-alert').length==0){
						jQuery('.wpcf7-form-control-wrap.cat').append('<span role="alert" class="wpcf7-not-valid-tip cat-alert"><?php _e('Please choose a category','cactusthemes') ?>.</span>');
					}
					return false;
				}else{
					return true;
				}
			}
		});
	});
	</script>
	<?php
	}
	$js_string = ob_get_contents();
	ob_end_clean();
	return $output.$js_string;
}
function tm_report_input($tag){
	$class = '';
	$is_required = 0;
	if(class_exists('WPCF7_Shortcode')){
		$tag = new WPCF7_Shortcode( $tag );
		if ( $tag->is_required() ){
			$is_required = 1;
			$class .= ' required-cat';
		}
	}
	$output = '<div class="hidden wpcf7-form-control-wrap report_url"><div class="wpcf7-form-control wpcf7-validates-as-required'.$class.'">';
	$output .= '<input name="report-url" class="hidden wpcf7-form-control wpcf7-text wpcf7-validates-as-required" type="hidden" value="'.esc_attr(curPageURL()).'" />';
	$output .= '</div></div>';
	return $output;
}


add_action( 'init', 'tm_wpcf7_add_shortcode' );

//mail after publish
add_action( 'publish_post', 'notify_user_submit');
function notify_user_submit( $post_id ) {
	if ( wp_is_post_revision( $post_id ) || !ot_get_option('user_submit_notify',1) )
		return;
	
	$notified = get_post_meta($post_id,'notified',true);
	$email = get_post_meta($post_id,'tm_user_submit',true);
	if(!$notified && $email && get_post_status($post_id)=='publish'){
		$subject = __('Your video submission has been approved','cactusthemes');
		$messages = __('Your video ','cactusthemes').get_post_meta($post_id,'tm_video_url',true).' '.__('has been approved. You can see it here','cactusthemes').' '.get_permalink($post_id);
		wp_mail( $email, $subject, $messages );
			update_post_meta( $post_id, 'notified', 1);
		$post_author = get_post_field( 'post_author', $post_id );
        global $wpdb;
     	$sql = $wpdb->get_row("SELECT * FROM 413_posts WHERE post_author= $post_author  and post_status='publish' AND post_type ='post' ORDER BY ID DESC LIMIT 1 OFFSET 1 ");
       	$my_post_id			= 	$sql->ID;
       	$users 				= 	get_users();
  	 	$number_of_users 	= 	count($users);
     	$get_rows 			= 	$wpdb->get_results( "SELECT * FROM vote_system WHERE post_id=$my_post_id and vote='like'");
      	$vote 				= 	$wpdb->num_rows;
     	$per 				=	$vote*100;
      	$check 				=	round($per/ $number_of_users);
      	$post_title2		= 	get_the_title( $post_id ); 
    	$link 				= 	get_permalink($post_id );
    	//$mycode =  "Hi,  check this out <a href=\"$link\">Click Here</a>";
		
      	foreach ( $get_rows as $get_rows2 ){
           $userid 			= 	$get_rows2->user_id;
		   $user_info 		= 	get_userdata($userid);
		   $mail_list[]		= 	$user_info->user_email;
		 //  $email_list[]	=	implode(", ", $mail_list);
        }
        if($check >10){
        	//echo "<pre>";
        	 $string = implode(',', $mail_list);
        	//print_r($mail_lis);
        	//die();
			$to      = $string;
		    $subject = 'New secne';
		    $headers.= "MIME-version: 1.0\n";
			$headers.= "Content-type: text/html; charset= iso-8859-1\n";
		    $message .= "New scene:$post_title2 ". "\r\n";
		  //	$message .= $mycode;
		    $headers = 'X-Mailer: PHP/' . phpversion();
		    //print_r($message);
		    mail($to, $subject, $message, $headers);
		   // die();
     	}
      //  echo "users".$number_of_users;
     
	}
}

//social locker
function tm_get_social_locker($string){
	preg_match('~\[sociallocker\s+id\s*=\s*("|\')(?<id>.*?)\1\s*\]~i', $string, $match);
	$locker_id = isset($match['id']) ? $match['id']:''; //get id
	$id_text = $locker_id?'id="'.$locker_id.'"':''; //build id string
	return $id_text;
}

//YouTube WordPress plugin - video import - views
add_action( 'cbc_post_insert', 'cbc_tm_save_data', 10, 4 ); 
function cbc_tm_save_data( $post_id, $video, $theme_import, $post_type ){
	$data = get_post_meta($post_id, '__cbc_video_data', false);
	if( isset( $data['stats']['views'] ) ){
		update_post_meta( $post_id, '_count-views_all', $data['stats']['views']);
	}
}

add_theme_support( 'custom-header' );
add_theme_support( 'custom-background' );
function woo_related_tm() {
  global $product;
	
	$args['posts_per_page'] = 4;
	return $args;
}
add_filter( 'woocommerce_output_related_products_args', 'woo_related_tm' );
/* Functions, Hooks, Filters and Registers in Admin */
require_once 'inc/functions-admin.php';
if(!function_exists('cactus_get_datetime')){
	function cactus_get_datetime()
	{
		$post_datetime_setting  = 'off';
		if($post_datetime_setting == 'on')
			return '<a href="' . esc_url(get_the_permalink()) . '" class="cactus-info" rel="bookmark"><time datetime="' . get_the_date( 'c' ) . '" class="entry-date updated">' . date_i18n(get_option('date_format') ,get_the_time('U')) . '</time></a>';
		else
			return '<div class="cactus-info" rel="bookmark"><time datetime="' . get_the_date( 'c' ) . '" class="entry-date updated">' . date_i18n(get_option('date_format') ,get_the_time('U')) . '</time></div>';
	}
}

if(!function_exists('cactus_hook_get_meta')){
	function cactus_hook_get_meta($metadata, $object_id, $meta_key, $single) {
		$fieldtitle="_jwppp-video-url-1";
		if($meta_key==$fieldtitle&& isset($meta_key)) {
			//use $wpdb to get the value
			global $wpdb;
			$value = $wpdb->get_var( $wpdb->prepare( 
				"
					SELECT meta_value 
					FROM $wpdb->postmeta 
					WHERE post_id = %s 
					AND  meta_key = %s
				",
				$object_id,
				'tm_video_url'
			));
			if($value==''){
				$value = $wpdb->get_var( $wpdb->prepare( 
					"
						SELECT meta_value 
						FROM $wpdb->postmeta 
						WHERE post_id = %s
						AND  meta_key = %s
					",
					$object_id,
					'tm_video_file'
				));
			}
			//do whatever with $value
	
			return $value;
		}
	}
}
if(!is_admin()){
	add_filter('get_post_metadata', 'cactus_hook_get_meta', 10, 4);
}

function filter_excerpt_baw( $excerpt ) {
	global $post;
	if( function_exists('bawpvc_get_options') && ($bawpvc_options = bawpvc_get_options()) && 'on' == $bawpvc_options['in_content'] && in_array( $post->post_type, $bawpvc_options['post_types'] ) ) {
		$excerpt = preg_replace('/\([\s\S]+?\)/', '', $excerpt);
	}
	return $excerpt;
}
add_filter( 'get_the_excerpt', 'filter_excerpt_baw' );

/******************get buddpress social icon ****************************/
function member_social_extend(){
		$dmember_id = $bp->displayed_user->id;
		print_r(xprofile_get_field_data($dmember_id));
		$dis = xprofile_get_field_data('Short Description ', $dmember_id);
		$fb_info = xprofile_get_field_data('facebook', $dmember_id);
		$youtube = xprofile_get_field_data('youtube', $dmember_id);
		$twitter = xprofile_get_field_data('Twitter', $dmember_id);
		$contact = xprofile_get_field_data('Email Contact', $dmember_id);
		$instagram = xprofile_get_field_data('Instagram', $dmember_id);
		echo '<div class="member-social">';
if (function_exists('bp_displayed_user_id')) {
    echo '<span class="user-role">';
	$user = new WP_User( bp_displayed_user_id() );
	//echo"<pre>";
	//print_r($user);
	echo bp_core_get_username( $user->ID );
	echo '</span>';
   }
   ?>
   <div class="social-login">
   	<span class="profile_contact">Contact:</span>
<?php
	
		?>
	
		<span class="fb-info">
			<a href="<?php echo $fb_info; ?>" target="_blank">
				<!-- <img src="<?php //bloginfo('wpurl'); ?>/wp-content/themes/truemag/images/facebook.png" /> -->
				<i class="fa fa-facebook-official" aria-hidden="true"></i>
			</a>
		</span>
		<?php
	
		if ($instagram) {
		?>
		<span class="google-info">
			<a href="<?php echo $instagram; ?>" target="_blank">
				<!-- <img src="<?php // bloginfo('wpurl'); ?>/wp-content/themes/truemag/images/youtube.png" /> -->
				<i class="fa fa-instagram" aria-hidden="true"></i>
			</a>	
		</span>
	<?php
	}
	?>
		<!-- <span class="google-info">
			<a href="<?php //echo $twitter; ?>" target="_blank">
			<!-- 	<img src="<?php //bloginfo('wpurl'); ?>/wp-content/themes/truemag/images/twitter.png" /> -->
			<!--<i class="fa fa-twitter" aria-hidden="true"></i>
			</a>
		</span> -->
	<?php
	
		if ($youtube) {
		?>
		<span class="google-info">
			<a href="<?php echo $youtube; ?>" target="_blank">
				<!-- <img src="<?php // bloginfo('wpurl'); ?>/wp-content/themes/truemag/images/youtube.png" /> -->
				 <i class="fa fa-youtube-play" aria-hidden="true"></i>
			</a>	
		</span>
			

	
	<?php
	}
	?>
	
	<span class="whtats_info"><a href="#" target="_blank"><i class="fa fa-whatsapp" aria-hidden="true"></i></a></span>
	<span class="contact-info twitch-info">
			<a href="mailto:<?php echo $contact; ?>" target="_blank">
				<!-- <img src="<?php //bloginfo('wpurl'); ?>/wp-content/themes/truemag/images/mail.png" /> -->
				<i class="fa fa-envelope" aria-hidden="true"></i>
			</a>
		</span>
	<?php ?>
   </div>
   <?php
		if($dis){
			?>
			<span class="dis"><p class="content_member"><?php echo $dis; ?></p></span>
			<?php }  $current_login = get_current_user_id();
			          $member_id=$user->ID;
			          if($current_login== $member_id){
			echo '<span class="dis"><a href="'.site_url().'/members/'.bp_core_get_username( $user->ID ).'/profile/edit" ><i class="fa fa-pencil" aria-hidden="true"></i> Edit Your Profile</a></span>';
               } else{
               	echo '<style>#object-nav{display:none;}</style>';
               	if($current_login){
               		echo '<style>.bp-user .profile_bg { height: 397px;}</style>';
               	}
               }
		
	echo '</div>';
}
add_filter( 'bp_before_member_header_meta', 'member_social_extend' ); 
/*************************give height to buddypress cover image **********************************/
function your_theme_xprofile_cover_image( $settings = array() ) {
    $settings['width']  = 1170;
    $settings['height'] = 300;
 
    return $settings;
}
add_filter( 'bp_before_xprofile_cover_image_settings_parse_args', 'your_theme_xprofile_cover_image', 10, 1 );
function myFunction(){
  global $wpdb;
   $user_id= $_POST['userid'];
   $post_id= $_POST['post_id'];
    $vote= $_POST['vote'];
    $today=date("Y-m-d");
    $title=get_the_title($post_id);
    $tables = 'vote_system';
    $get_points = $wpdb->get_row( "SELECT * FROM  interactive_system where user_id='$user_id'" );
        $currentPoints=$get_points->points;
       // $current_amount = get_user_meta($TransferId, 'wallet-amount', true);
        $row5= $wpdb->get_row( "SELECT * FROM  point_editable WHERE id ='5'");
        $RatingofContent=$row5->points;

       $totalPoints= $currentPoints+$RatingofContent;
       
       // update_user_meta($TransferId, 'wallet-amount', $totalAmount );
        

   
    $data_array = array( 'post_id'=> $post_id ,'user_id'=>$user_id,'vote'=>$vote);
     $check_vote = $wpdb->get_var("select count(*) FROM vote_system where post_id = $post_id and user_id=$user_id");
     if($check_vote ==0){
   $vote_insert= $wpdb->insert( $tables, $data_array);
  if($vote_insert > 0){
  		$allowed = $wpdb->query("UPDATE interactive_system SET points = '$totalPoints' WHERE user_id = '$user_id'");
       	$data_array2 = array('user_id'=>$user_id,'type'=>'Rating of Content','femail'=>$title,'points'=>$RatingofContent,'date'=>$today);
        $point_insert= $wpdb->insert( 'free_points_details', $data_array2);

 ?>
    <a  class="already_vote" href="javascript:void(0)" style="color:green;"  id="like"><i class="fa fa-thumbs-o-up" aria-hidden="true"></i></a> <?php echo $total_row1 = $wpdb->get_var("select count(*) FROM vote_system where post_id =$post_id and vote='like'");?> |
    <a  class="already_vote" href="javascript:void(0)" style="color:red;" id="dislike" ><i class="fa fa-thumbs-o-down" aria-hidden="true"></i></a> <?php echo $total_row2 = $wpdb->get_var("select count(*) FROM vote_system where post_id =$post_id and vote='dislike'");?>
 <?php
    
    

   /* $total_row = $wpdb->get_var("select count(*) FROM vote_system where post_id = $post_id");

   echo '<div class="result"><i class="fa fa-heart" id="green"  style="color:green;  aria-hidden="true"></i><div class="count_vote">Total Votes:'.$total_row.'</div></div>';
   */                 	
  }else{
    echo"0";
  }
}
else
{
	?>
<a  class="already_vote" href="javascript:void(0)" style="color:green;"  id="like"><i class="fa fa-thumbs-o-up" aria-hidden="true"></i></a> <?php echo $total_row1 = $wpdb->get_var("select count(*) FROM vote_system where post_id =$post_id and vote='like'");?> |
    <a  class="already_vote" href="javascript:void(0)" style="color:red;" id="dislike" ><i class="fa fa-thumbs-o-down" aria-hidden="true"></i></a> <?php echo $total_row2 = $wpdb->get_var("select count(*) FROM vote_system where post_id =$post_id and vote='dislike'");?>
	<?php

}
  die();
}
add_action('wp_ajax_myFunction', 'myFunction');
add_action('wp_ajax_nopriv_myFunction', 'myFunction');

add_action('wp_ajax_voted', 'voted');
add_action('wp_ajax_nopriv_voted', 'voted');
function voted(){

  global $wpdb;
    $user_id= $_POST['userid'];
   $post_id= $_POST['post_id'];
   $my_vote = $wpdb->get_var("select vote FROM vote_system where post_id = $post_id and user_id=$user_id");
   echo '<p style="color:green;">You already '.$my_vote.' this video.</p>';


    die();
}
add_action('wp_ajax_flag', 'flag');
add_action('wp_ajax_nopriv_flag', 'flag');
function flag(){
     global $wpdb;
	$user_id= $_POST['userid'];
	$post_id= $_POST['post_id'];
 	$admin_email=$_POST['admin_email'];
 	$user_info = get_userdata($user_id); 
	$user_nicename = $user_info->user_nicename;
 	$Name   = 	get_the_title($post_id);
     $tables='falg_video';
     $data_array = array( 'post_id'=> $post_id ,'user_id'=>$user_id);
     $check_flag = $wpdb->get_var("select count(*) FROM falg_video where post_id = $post_id and user_id=$user_id");
     if($check_flag ==0){
     $flag_insert= $wpdb->insert( $tables, $data_array);
     if($flag_insert){
     	$header .= "MIME-Version: 1.0\n";
		$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
		$message = "Video Title: $Name " ."\r\n";
		$message .= "Username: $user_nicename"." \r\n";
		$message .= "flag on this video.";
		$subject = "Flag video";
		$to =  $admin_email;
		// send the email
		wp_mail($to, $subject, $message, $header);
		echo 'done';  	
     }
}
   die();

}

/** gurpiara singh
**
**/

/******** ad custom meta boxes in post**/


function call_meta_boxClass() {
    new custommetaClass();
}
 
if ( is_admin() ) {
    add_action( 'load-post.php',     'call_meta_boxClass' );
    add_action( 'load-post-new.php', 'call_meta_boxClass' );
}
 
/**
 * The Class.
 */

class custommetaClass {
 
    /**
     * Hook into the appropriate actions when the class is constructed.
     */
    public function __construct() {
        add_action( 'add_meta_boxes', array( $this, 'add_meta_box' ) );
        add_action( 'save_post',      array( $this, 'save'         ) );
    }
 
    /**
     * Adds the meta box container.
     */
    public function add_meta_box( $post_type ) {
        // Limit meta box to certain post types.
        $post_types = array( 'post', 'page','product' );
 
        if ( in_array( $post_type, $post_types ) ) {
            add_meta_box(
                'Date',
                __( 'Cut off date', 'textdomain' ),
                array( $this, 'render_meta_box_content' ),
                $post_type,
                'advanced',
                'high'
            );
        }
    }
 
    /**
     * Save the meta when the post is saved.
     *
     * @param int $post_id The ID of the post being saved.
     */
    public function save( $post_id ) {
 
        /*
         * We need to verify this came from the our screen and with proper authorization,
         * because save_post can be triggered at other times.
         */
 
        // Check if our nonce is set.
        if ( ! isset( $_POST['myplugin_inner_custom_box_nonce'] ) ) {
            return $post_id;
        }
 
        $nonce = $_POST['myplugin_inner_custom_box_nonce'];
 
        // Verify that the nonce is valid.
        if ( ! wp_verify_nonce( $nonce, 'myplugin_inner_custom_box' ) ) {
            return $post_id;
        }
 
        /*
         * If this is an autosave, our form has not been submitted,
         * so we don't want to do anything.
         */
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
            return $post_id;
        }
 
        // Check the user's permissions.
        if ( 'page' == $_POST['post_type'] ) {
            if ( ! current_user_can( 'edit_page', $post_id ) ) {
                return $post_id;
            }
        } else {
            if ( ! current_user_can( 'edit_post', $post_id ) ) {
                return $post_id;
            }
        }
 
        /* OK, it's safe for us to save the data now. */
 
        // Sanitize the user input.
       
        $mydata2 = sanitize_text_field( $_POST['myplugin_new_field_two'] );
 
      
        update_post_meta( $post_id, '_end_date', $mydata2 );
    }
 
 
    /**
     * Render Meta Box content.
     *
     * @param WP_Post $post The post object.
     */
    public function render_meta_box_content( $post ) {

       wp_enqueue_script('jquery-ui-datepicker');

 
        // Add an nonce field so we can check for it later.
        wp_nonce_field( 'myplugin_inner_custom_box', 'myplugin_inner_custom_box_nonce' );
 
        // Use get_post_meta to retrieve an existing value from the database.
        $value = get_post_meta( $post->ID, '_end_date', true );
 
        // Display the form, using the current value.
        ?>
        <script type="text/javascript">
     jQuery(document).ready(function(){
    jQuery('#date').datepicker({
        dateFormat: 'dd-mm-yy',
        option: 'disabled: true'
    });
});
</script>
     <style>
.date::-webkit-calendar-picker-indicator,
.date::-webkit-inner-spin-button{
    display: none;
}
     </style>
        <input type="date" id="date" name="myplugin_new_field_two" value="<?php echo esc_attr( $value ); ?>">

        <?php
    }
}

/***************change profile tab name*******************/
function mb_profile_menu_tabs(){
global $bp;
$bp->bp_nav['profile']['name'] = 'Edit Profile';
}
add_action('bp_setup_nav', 'mb_profile_menu_tabs', 201);
/***********************************************/

add_action( 'add_meta_boxes', 'cd_meta_box_add' );
function cd_meta_box_add(){
    add_meta_box( 'my-meta-box-id', 
		    	'video information',
		    	'cd_meta_box_cb', 
		    	'post', 
		    	'normal', 
		    	'high'
    	 		);
}
function cd_meta_box_cb(){
 	$post_id = get_the_ID();
	$director = esc_html( get_post_meta( $post_id, 'director', true ) );
	$writer = esc_html( get_post_meta( $post_id, 'writer', true ) );
	$stars = esc_html( get_post_meta( $post_id, 'stars', true ) );
	$age = intval( get_post_meta( $post_id, 'age', true ) );
	$post_project = get_post_meta( $post_id, 'post_project', true );
	$post_updates = get_post_meta( $post_id, 'post_updates', true );
	$post_faq = get_post_meta( $post_id, 'post_faq', true );
	 wp_nonce_field( 'my_meta_box_nonce', 'meta_box_nonce' );
	    ?>
	<div class="rwmb-field rwmb-text-wrapper">
	    <div class="rwmb-label">
	    	<label for="director">Director</label>
	    </div>
	    <div class="rwmb-input">
	    	<input type="text" name="director" id="director" size="64" value="<?php echo $director; ?>" />
	    </div>
	</div>
	<div class="rwmb-field rwmb-text-wrapper">
	    <div class="rwmb-label">
	    	<label for="writer">Writer</label>
	    </div>
	    <div class="rwmb-input">
	    	<input type="text" name="writer" id="writer" size="64" value="<?php echo $writer; ?>"/>
		</div>
	</div>
	<div class="rwmb-field rwmb-text-wrapper">
	    <div class="rwmb-label">
	    <label for="stars">Stars</label>
	     </div>
	     <div class="rwmb-input">
	    <textarea  name="stars" id="stars" cols="62" /><?php echo $stars; ?></textarea>
		 </div>
	</div>
	<div class="rwmb-field rwmb-text-wrapper">
	 <div class="rwmb-label">
    <label for="stars">Age Limit</label>
	</div>
	<div class="rwmb-input">
    <input type="text" name="age" id="age" size="64"  value="<?php echo $age; ?>"/>
	 </div>
	</div>
	<div class="rwmb-field rwmb-text-wrapper">
	 <div class="rwmb-label">
    <label for="stars">Project</label>
	</div>
	<div class="rwmb-input">
    <?php wp_editor($post_project,'post_project',array('textarea_name'=> 'post_project','textarea_rows'=>7,'wpautop'=>false));?>
	 </div>
	</div>
	<div class="rwmb-field rwmb-text-wrapper">
	 <div class="rwmb-label">
    <label for="stars">Updates</label>
	</div>
	<div class="rwmb-input">
    <?php wp_editor($post_updates,'post_updates',array('textarea_name'=> 'post_updates','textarea_rows'=>7,'wpautop'=>false));?>
	 </div>
	</div>
	<div class="rwmb-field rwmb-text-wrapper">
	 <div class="rwmb-label">
    <label for="stars">FAQ</label>
	</div>
	<div class="rwmb-input">
    <?php wp_editor($post_faq,'post_faq',array('textarea_name'=> 'post_faq','textarea_rows'=>7,'wpautop'=>false));?>
	 </div>
	</div>
    <?php    
}
add_action( 'save_post', 'cd_meta_box_save' );
function cd_meta_box_save( $post_id )
{
    // Bail if we're doing an auto save
    if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
     
    // if our nonce isn't there, or we can't verify it, bail
    if( !isset( $_POST['meta_box_nonce'] ) || !wp_verify_nonce( $_POST['meta_box_nonce'], 'my_meta_box_nonce' ) ) return;
     
    // if our current user can't edit this post, bail
    if( !current_user_can( 'edit_post' ) ) return;
     
    // now we can actually save the data
    $allowed = array( 
        'a' => array( // on allow a tags
            'href' => array() // and those anchors can only have href attribute
        )
    );
     
    // Make sure your data is set before trying to save it
    if( isset( $_POST['director'] ) )
        update_post_meta( $post_id, 'director', wp_kses( $_POST['director'], $allowed ) );
         
    if( isset( $_POST['writer'] ) )
      update_post_meta( $post_id, 'writer', wp_kses( $_POST['writer'], $allowed ) );
   if( isset( $_POST['stars'] ) )
      update_post_meta( $post_id, 'stars', wp_kses( $_POST['stars'], $allowed ) );
   if( isset( $_POST['age'] ) )
      update_post_meta( $post_id, 'age', wp_kses( $_POST['age'], $allowed ) );
  if( isset( $_POST['post_project'] ) )
      update_post_meta( $post_id, 'post_project', wp_kses( $_POST['post_project'], $allowed ) );
  if( isset( $_POST['post_updates'] ) )
      update_post_meta( $post_id, 'post_updates', wp_kses( $_POST['post_updates'], $allowed ) );
  if( isset( $_POST['post_faq'] ) )
      update_post_meta( $post_id, 'post_faq', wp_kses( $_POST['post_faq'], $allowed ) );
         
    // This is purely my personal preference for saving check-boxes
   
}

function authorNotification( $new_status, $old_status, $post ) {
     
    if ( $post->post_type=='product' && $new_status == 'publish' && $old_status != 'publish'  ) {
        $author = get_userdata($post->post_author);


        $message = "
            Hi ".$author->display_name.",
            New Merchandise, ".$post->post_title." has just been published at ".get_permalink( $post->ID ).".
        ";
        wp_mail($author->user_email, "New Merchandise Published", $message);
    }
}
add_action('transition_post_status', 'authorNotification', 10, 3 );


function display_user_ip() {
        $ip = $_SERVER['REMOTE_ADDR'];
        return $ip;
}
add_shortcode('user_ip', 'display_user_ip');

/***********Delete post Query*************************/
add_action( 'wp_ajax_my_delete_post', 'my_delete_post' );
function my_delete_post(){
 
    $permission = check_ajax_referer( 'my_delete_post_nonce', 'nonce', false );
    if( $permission == false ) {
        echo 'error';
    }
    else {
          $meta=$_REQUEST['id'];
          wp_trash_post( $_REQUEST['id'] );
          global $wpdb;
          $tdata = $wpdb->get_row( "SELECT * FROM 413_postmeta WHERE meta_key='poll_id' AND meta_value='$meta' " );
          $deleteID=$tdata->meta_id;
          $sql = $wpdb->query("DELETE FROM 413_postmeta WHERE meta_id =$deleteID");
          
        echo $_REQUEST['id'];
    }
 
    die();
 
}
/*************Delete product Query**************************/
 function remove_product() {
 	global $wpdb;
 	$id= $_REQUEST['id'];
 	$sql = $wpdb->query("DELETE FROM 413_posts WHERE post_type = 'product' AND ID= '$id'");
	 	if($sql){
	 		echo $_REQUEST['id'];
	 	}else{
	 		echo"not";
	 	}
	 	die();
    }

    add_action('wp_ajax_remove_product', 'remove_product');
    add_action('wp_ajax_nopriv_remove_product', 'remove_product');

add_action('admin_menu', 'my_plugin_menu');

function my_plugin_menu() {
		global $wpdb;
	$count_flag = $wpdb->get_var("select count(*) FROM falg_video where status =0");
	add_menu_page('Flag videos', 'Flag videos<span class="update-plugins count"><span class="plugin-count">'.$count_flag.'</span></span>', 'read', 'my-unique-identifier', 'flag_videos','dashicons-flag');
}
function flag_videos(){
	global $wpdb;
	$wpdb->query("UPDATE falg_video SET status='1'");
	?>
	<div class="wrap">
<h1 class="wp-heading-inline">Flag Video's</h1>
    <div style="width:100%">
      <table style="width:76%; text-align:left; " border="1" class="table" >
        <tr>
        <th style="" >Video Title</th>
        <th>User Name</th>
        <th style="width: 40px;">User Email</th>
        <th style="width: 40px;">Delete</th>
      </tr>
        <?php 
          global $wpdb; 
         $Table ='falg_video';
        $retrieve_data = $wpdb->get_results( "SELECT * FROM  $Table ORDER BY id ASC " );
        foreach ($retrieve_data as $retrieved_data){
         ?>
         <tr id="<?php echo $retrieved_data->id; ?>">
         <td> <?php echo get_the_title($retrieved_data->post_id); ?></td>
         <td> <?php $user= $retrieved_data->user_id;$user_info = get_userdata($user); echo $user_info->user_nicename;?></td>
         <td> <?php echo $user_info->user_email;?></td>
         <td> <a href="javascript:void(0)" class="del_falg" id="<?php echo $retrieved_data->id;?>" >Delete</a></td>

       
        </tr>
        <?php
        }?>
      </table>
       <script type="text/javascript">
        jQuery(document).ready(function() {
        	jQuery('.del_falg').click(function() {
        		  var id =this.id;
        		  //alert(id);
        		 // var ajex_url='http://premise.tv/wp-admin/admin-ajax.php';
        		  jQuery.ajax({
                  type:'POST',
                  url: ajaxurl,
                  data:{action:'delfalg',id:id},
                  success: function(value) {
                  if(value){
                  	 jQuery('#'+value).html('<div class="wpcf7-response-output wpcf7-display-none wpcf7-validation-errors" style="display: block;">Delete successfully....</div>').fadeOut(3000);
                  }
                  }
               });
        	});  
         });
        	</script>
    </div>
</div>
	<?php

}
	add_action('wp_ajax_delfalg', 'delfalg');
  add_action('wp_ajax_nopriv_delfalg', 'delfalg');
function delfalg(){
     global $wpdb;
     $id= $_REQUEST['id'];
    // $tables='falg_video';
    $flag = $wpdb->query("DELETE FROM falg_video WHERE id=$id");
    // $wpdb->delete( $tables, array( 'id' => $id ) );
  	 if($flag){
  	 	echo $id;
  	 }
      die();
     
}

function show_pending_number($menu) {    
    $types = array("post");
    $status = "pending";
    foreach($types as $type) {
        $num_posts = wp_count_posts($type, 'readable');
        $pending_count = 0;
        if (!empty($num_posts->$status)) $pending_count = $num_posts->$status;

        if ($type == 'post') {
            $menu_str = 'edit.php';
        } else {
            $menu_str = 'edit.php?post_type=' . $type;
        }

        foreach( $menu as $menu_key => $menu_data ) {
            if( $menu_str != $menu_data[2] )
                continue;
            $menu[$menu_key][0] .= " <span class='update-plugins count-$pending_count'><span class='plugin-count'>" . number_format_i18n($pending_count) . '</span></span>';
            }
        }
    return $menu;
}
add_filter('add_menu_classes', 'show_pending_number');
function show_pending_number2($menu) {    
    $types = array("product");
    $status = "draft";
    foreach($types as $type) {
        $num_posts = wp_count_posts($type, 'readable');
        $pending_count = 0;
        if (!empty($num_posts->$status)) $pending_count = $num_posts->$status;

        if ($type == 'product') {
          $menu_str = 'edit.php?post_type=' . $type;
        } else {
            $menu_str = 'edit.php?post_type=' . $type;
        }

        foreach( $menu as $menu_key => $menu_data ) {
            if( $menu_str != $menu_data[2] )
                continue;
            $menu[$menu_key][0] .= " <span class='update-plugins count-$pending_count'><span class='plugin-count'>" . number_format_i18n($pending_count) . '</span></span>';
            }
        }
    return $menu;
}
add_filter('add_menu_classes', 'show_pending_number2');



add_action('admin_menu', 'email_plugin');

function email_plugin() {
	add_menu_page('My Plugin Dashboard', 'late delivery Episode', 'read', 'email-user', 'send_email','dashicons-flag');
}
function send_email(){
	global $wpdb;
		echo '<h1>Late Delivery Template</h1>';
		$result = $wpdb->get_results("SELECT * FROM late_delivery_template WHERE id=1");
	 $template1=html_entity_decode($result[0]->template);
	
	echo'<div class="send-mail">

	<form method="post">
	<textarea cols="40" rows="12" name="template">'.strip_tags($template1).'</textarea>
	<input type="submit" name="submit" value="Send">
	</form>
	</div>';
	if(isset($_POST['submit'])){
	
	global $wpdb;
    $title=  nl2br($_POST['template']);
	
     // $results = $wpdb->insert('late_delivery_template', $title);
	$wpdb->query("UPDATE late_delivery_template SET template='$title' WHERE id='1'");
	$user = $wpdb->get_results("SELECT user_id FROM vote_system GROUP BY user_id");
		foreach ($user as $users) {
			$user_id = $users->user_id;
			$email = get_userdata($user_id);
			$send_email[]= $email->user_email;	
		}

			$header .= "MIME-Version: 1.0\n";
			$header .= "Content-Type: text/html; charset=utf-8\n";
			$message .= "$title\r\n";
			$subject = "Late Delivery Episode";
			$to = $send_email;
			// send the email
			if(wp_mail($to, $subject, $message, $header)){
				 echo "<script type='text/javascript'>alert('Late Delivery mail sended to user');</script>";
				  echo"<script type='text/javascript'>window.location.reload(true);</script>";
			}
	}	
}
$result = add_role( 'content_creator', __('Contact Creater' ),
array(

	'read' => true, // true allows this capability
	'edit_posts' => true, // Allows user to edit their own posts
	'edit_pages' => true, // Allows user to edit pages
	'edit_others_posts' => true, // Allows user to edit others posts not just their own
	'create_posts' => true, // Allows user to create new posts
	'manage_categories' => true, // Allows user to manage post categories
	'publish_posts' => true, // Allows the user to publish, otherwise posts stays in draft mode
));


function requst_cc(){
global $wpdb;

    $tables = '413_role_request_form';
    $data_array = array( 'user_id'=> $_POST['login_user'] ,'email'=>$_POST['login_user_email']);
    $check_vote = $wpdb->get_var("select count(*) FROM 413_role_request_form where email='".$_POST['login_user_email']."'");
   if($check_vote ==0){
   $vote_insert= $wpdb->insert( $tables, $data_array);
   if($vote_insert){
   	echo "insert";
   }
  
	}
	 die();
}
add_action('wp_ajax_requst_cc', 'requst_cc');
add_action('wp_ajax_nopriv_requst_cc', 'requst_cc');
add_action('user_register','my_function');

function my_function($user_id){
	 $user = new WP_User($user_id);
    	 $user_mail=$user->user_email;
    	global $wpdb;
    	$today=date("Y-m-d");
        $tdata = $wpdb->get_row( "SELECT * FROM  413_invite_friends where invite_email='$user_mail'" );
        $TransferId=$tdata->invitator_id;
        $points_with_reg=$tdata->points;
        $check_guest = $wpdb->get_var("select count(*) FROM 413_invite_friends where invitator_id = $TransferId");
        $get_points = $wpdb->get_row( "SELECT * FROM  interactive_system where user_id='$TransferId'" );
        $currentPoints=$get_points->points;
       // $current_amount = get_user_meta($TransferId, 'wallet-amount', true);
        $row3= $wpdb->get_row( "SELECT * FROM  point_editable WHERE id ='3'");
        $invited_guest=$row3->points;

       $totalPoints= $currentPoints+$invited_guest;
       if($check_guest<=100){
       // update_user_meta($TransferId, 'wallet-amount', $totalAmount );
       	$allowed = $wpdb->query("UPDATE interactive_system SET points = '$totalPoints' WHERE user_id = '$TransferId'");
       	$data_array = array('user_id'=>$TransferId,'type'=>'Invite Friend','femail'=>$user_mail,'points'=>$invited_guest,'date'=>$today);
        $point_insert= $wpdb->insert( 'free_points_details', $data_array); 

       

    }
    if($points_with_reg){
    		$data_array = array('user_id'=>$TransferId,'type'=>'send','recevier'=>$user_id,'status'=>'1','amount'=>$points_with_reg,'date'=>$today);
            $point_insert= $wpdb->insert( 'points', $data_array);

    }

    
}
add_action('admin_menu', 'my_credit_system');

function my_credit_system() {
	add_menu_page('Credit System ', 'Credit System Settings', 'read', 'my_credit_system', 'credit_system');
}
function credit_system(){
?>
	<style>
    .table_1{ border:0px solid #ccc; width:100%; margin:10px 0px 10px 0px;} 
    .table_1 tr:nth-child(2n+0) {
        background: #f1f1f1;
    }
    .table_1 td{ border:1px solid #ccc; padding:10px;} 
    .table_1 th{ border:1px solid #ccc; padding:10px; background:#4db59b; color:#fff; font-weight:bold;}
	</style>
	<h2 style="font-size: 30px;"><center>Credit system</center></h2>
   	<div id="select" style="width:100%">
      <table class="table_1">
	       <tr>
	       		<th>id</th>
		        <th>package</th>
		        <th>price(S$)</th>
		        <th>Gold</th>
		        <th>Silver</th>
		        <th>Points</th>
		        <th>status</th>
	   		</tr>	
	   	<!--</table>-->
		<?php 
		global $wpdb;
		$Table ='413_wallet_credit';
		  $retrieve_data = $wpdb->get_results( "SELECT * FROM  $Table" );
		     foreach ($retrieve_data as $retrieved_data){
		?>
 <form method="post" action="" enctype="multipart/form-data" >
 	     <!--<table class="table_1">-->
	<tr>
	     <td class="id"><?php echo $retrieved_data->id;?></td>
	     <td><?php echo $retrieved_data->package;?></td>
	   
	     <td>
			 <?php if((isset($_GET['edit']))&&($retrieved_data->id == $_GET['edit']))
			 {
			 ?>
				<input type="text" name="price" id="<?php echo $retrieved_data->id; ?>" value="<?php echo $retrieved_data->price;?>"/>
					<?php 
				} else { ?>
					<?php echo $retrieved_data->price;?>
			 		<?php  } ?>
	
	      </td>
	     <td>
	     	<?php if((isset($_GET['edit']))&&($retrieved_data->id == $_GET['edit']))
			 {
			 ?>
	      <input type="text" name="credit" id="<?php echo $retrieved_data->id; ?>" value="<?php echo $retrieved_data->credit;?>"/>
	        <?php 
				} else { ?>
					<?php echo $retrieved_data->credit;?>
			 		<?php  } ?>
				 </td>
				 <td>
	     	<?php if((isset($_GET['edit']))&&($retrieved_data->id == $_GET['edit']))
			 {
			 ?>
	      <input type="text" name="silver" id="<?php echo $retrieved_data->id; ?>" value="<?php echo $retrieved_data->silver;?>"/>
	        <?php 
				} else { ?>
					<?php echo $retrieved_data->silver;?>
			 		<?php  } ?>
				 </td>
				 <td>
	     	<?php if((isset($_GET['edit']))&&($retrieved_data->id == $_GET['edit']))
			 {
			 ?>
	      <input type="text" name="points" id="<?php echo $retrieved_data->id; ?>" value="<?php echo $retrieved_data->points;?>"/>
	        <?php 
				} else { ?>
					<?php echo $retrieved_data->points;?>
			 		<?php  } ?>
				 </td>
	        <td><a class="edit wc-update-now button-primary" href="<?php echo site_url();?>/wp-admin/admin.php?page=my_credit_system&edit=<?php echo $retrieved_data->id;?>">Edit</a>  
	     	<input class="button button-primary button-large" type="submit" name="submit" id="update" value="Update" /></td>
	     	
	</tr>
	</form>
	
		  

		     <?php
		     }?>
		    </table>
    
   
	</div>
	
	<?php
	if(isset($_POST['submit'])){
		global $wpdb;
		$id = $_GET['edit'];
		$price = $_POST['price'];
		$credit= $_POST['credit'];$silver= $_POST['silver'];$points= $_POST['points'];
		$allowed = $wpdb->query("UPDATE 413_wallet_credit SET price = '$price', credit = '$credit',silver='$silver',points='$points' WHERE id = '$id'"); 
		if($allowed)
		{
			wp_redirect(admin_url('/?page=my_credit_system', 'http'), 301);

			echo "successfully updated";
		}
		else
		{
			echo "not updated";
		}
	}
}
add_action('wp_ajax_check_wallet', 'check_wallet');
add_action('wp_ajax_nopriv_check_wallet', 'check_wallet');
function check_wallet()
{
	 $userid=$_REQUEST['login_user'];
	 $price=intval($_REQUEST['price']);
	 $current_amount = get_user_meta($userid, 'wallet-amount', true);
	
	 if($current_amount<$price)
	    {   
	 	echo 'notsufficient';
	 }
	 else{
	 	echo 'no';
	 }
	die();
}
function filter_gateways($gateways){
    $category_ID = array('157','158');  // <----------- add the ids for which u want to turn off "cash on delivery"

    global $woocommerce;

    foreach ($woocommerce->cart->cart_contents as $key => $values ) {
    	$product_id=$values['product_id'];
        if($product_id==2092) {
        	    echo '<script>
        	      jQuery("#credit_card_store").dialog({
                                    resizable: false,
                                    closeText : " ",
                                    height: "auto",
                                    width: 400,
                                    modal: true
                                  });
                 
        	    </script>';
                unset($gateways['cod']);
                unset($gateways['cheque']); 
               
                break;
            }
        $terms = get_the_terms( $values['product_id'], 'product_cat' );
       // print_r($terms);
             
        if($terms){
        foreach ($terms as $term) {
        	$cat=$term->term_id;
         $cat_sub=$terms[1]->term_id;
        	
         /*   if($cat==157) {
            	echo '<style>.wallet-checkout {
						  display: none;
						}
						.payment_method_wallet {
						  display: none;
						}</style>';
            //    unset($gateways['wallet']);
                break;
            }*/
             if($cat==158) {
                unset($gateways['cod']);
                unset($gateways['paypal']);
                unset($gateways['cheque']); 
                unset($gateways['bacs']); 
                unset($gateways['stripe']);  
                break;
            }
             if($cat==157) {
                unset($gateways['cod']);
                  
                break;
            }
            if($cat_sub==169) {
                unset($gateways['cod']);
                unset($gateways['paypal']);
                unset($gateways['cheque']); 
                unset($gateways['bacs']);   
                unset($gateways['stripe']); 
                break;
            }
             if($cat_sub==170) {
                unset($gateways['wallet']);
                unset($gateways['paypal']);
               unset($gateways['cheque']); 
                unset($gateways['bacs']);   
                unset($gateways['stripe']); 
                break;
            }
          
            break;
        }
    }}
    return $gateways;
}
add_filter('woocommerce_available_payment_gateways','filter_gateways');

/*add_action('admin_menu', 'free_credit_system');

function free_credit_system() {
	add_menu_page('Free Credit System', 'Free Credit System', 'read', 'free_point_system', 'free_point_system');
}*/
/*function free_point_system(){
?>
<style>
.table_1 {
  border: 0 solid #ccc;
  margin: 0 auto!important;
  width: 50%!important;
}
.table_1 td {
  text-align: center;
  text-transform: capitalize;
}
.wp-core-ui .button-primary {
  margin-right: 10px;
}
    .table_1{ border:0px solid #ccc; width:100%; margin:10px 0px 10px 0px;} 
    .table_1 tr:nth-child(2n+0) {
        background: #f1f1f1;
    }
    .table_1 td{ border:1px solid #ccc; padding:10px;} 
    .table_1 th{ border:1px solid #ccc; padding:10px; background:#4db59b; color:#fff; font-weight:bold;}
	</style>
	<h2 style="font-size: 30px;"><center>Free Credit system</center></h2>
   	<div id="select" style="width:100%;" >
      <table class="table_1">
	       <tr>
	       		<th>Name</th>
		        <th>Free Credit</th>
		        <th>Update</th>
	   		</tr>	
	   	<!--</table>-->
		<?php 
		global $wpdb;
		$Table ='interactive_system';
		  $retrieve_data = $wpdb->get_results( "SELECT * FROM  $Table" );
		     foreach ($retrieve_data as $retrieved_data){
		?>
 <form method="post" action="" enctype="multipart/form-data" >
 	     <!--<table class="table_1">-->
	<tr>
	     <td class="id"><?php
          $user_info = get_userdata($retrieved_data->id);


	       echo $user_info->user_login; ?></td>
	     
	   
	     <td>
			 <?php if((isset($_GET['edit']))&&($retrieved_data->id == $_GET['edit']))
			 {
			 ?>
				<input type="text" name="points" id="<?php echo $retrieved_data->id; ?>" value="<?php echo $retrieved_data->points;?>"/>
					<?php 
				} else { ?>
					<?php echo $retrieved_data->points;?>
			 		<?php  } ?>
	
	      </td>
	    
	        <td><a class="edit wc-update-now button-primary" href="<?php echo site_url();?>/wp-admin/admin.php?page=free_point_system&edit=<?php echo $retrieved_data->id;?>">Edit</a>  
	     	<input class="button button-primary button-large" type="submit" name="submit" id="update" value="Update" /></td>
	     	
	</tr>
	</form>
	
		  

		     <?php
		     }?>
		    </table>
    
   
	</div>
	<?php 
	if(isset($_POST['submit'])){
		global $wpdb;
		$id = $_GET['edit'];
		$points = $_POST['points'];
		
		$allowed = $wpdb->query("UPDATE interactive_system SET points = '$points' WHERE id = '$id'"); 
		if($allowed)
		{
			wp_redirect(admin_url('/?page=free_point_system', 'http'), 301);

			echo "successfully updated";
		}
		
	}
	}*/
	add_filter( 'upload_size_limit', 'PBP_increase_upload' );
function PBP_increase_upload( $bytes )
{
return 1048576; // 1 megabyte
}

//***Frame video code start here***//
//hook into the init action and call create_book_taxonomies when it fires
/*add_action( 'init', 'create_topics_hierarchical_taxonomy', 0 );

//create a custom taxonomy name it topics for your posts

function create_topics_hierarchical_taxonomy() {

// Add new taxonomy, make it hierarchical like categories
//first do the translations part for GUI

  $labels = array(
    'name' => _x( 'Frame video', 'taxonomy general name' ),
    'singular_name' => _x( 'Topic', 'taxonomy singular name' ),
    'search_items' =>  __( 'Search Topics' ),
    'all_items' => __( 'All Video' ),
    'parent_item' => __( 'Parent Topic' ),
    'parent_item_colon' => __( 'Parent Topic:' ),
    'edit_item' => __( 'Edit Topic' ), 
    'update_item' => __( 'Update Topic' ),
    'add_new_item' => __( 'Add New Frame video' ),
    'new_item_name' => __( 'New Topic Name' ),
    'menu_name' => __( 'Frame video' ),
  ); 	

// Now register the taxonomy

  register_taxonomy('topics',array('post'), array(
    'hierarchical' => true,
    'labels' => $labels,
    'show_ui' => true,
    'show_admin_column' => true,
    'query_var' => true,
    'rewrite' => array( 'slug' => 'topic' ),
  ));

}*/
/*// Checkbox Meta
add_action("admin_init", "checkbox_init");

function checkbox_init(){
  add_meta_box("checkbox", "Slider Frame", "checkbox", "post", "normal", "high");
}

function checkbox(){
  global $post;
  $custom = get_post_custom($post->ID);
  $field_id = $custom["field_id"][0];
 ?>

  <label>Slider Frame</label>
  <?php $field_id_value = get_post_meta($post->ID, 'field_id', true);
  if($field_id_value == "yes") $field_id_checked = 'checked="checked"'; ?>
    <input type="checkbox" name="field_id" value="yes" <?php echo $field_id_checked; ?> />
  <?php

}

// Save Meta Details
add_action('save_post', 'save_details');

function save_details(){
  global $post;

if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
    return $post->ID;
}

  update_post_meta($post->ID, "field_id", $_POST["field_id"]);
}

function check_values($post_ID, $post_after, $post_before){

   
    if($_REQUEST['field_id']=='yes')
    {
    $args = array(
        'posts_per_page'   => -1,
        'post_type'        => 'post',
        'suppress_filters' => true 
    );
    $posts_array = get_posts( $args );
    foreach($posts_array as $post_array)
    {
        update_post_meta($post_array->ID, 'field_id', ' ');
    }
   	global $wpdb;
    $table_name ='413_slider_frame';
    $wpdb->query("UPDATE $table_name SET frame ='$post_ID' WHERE id = '1'");
    }
   	//die;
}
add_action( 'post_updated', 'check_values', 10, 3 );*/
//***frame video code ends here***//

    add_action('woocommerce_product_options_general_product_data', 'woocommerce_product_custom_fields');
    add_action('woocommerce_process_product_meta', 'woocommerce_product_custom_fields_save');
    function woocommerce_product_custom_fields()
{
    global $woocommerce, $post;
    echo '<div class="product_custom_field">';
    // Custom Product Text Field
    woocommerce_wp_text_input(
        array(
            'id' => 'level_pledge',
            'placeholder' => '0',
            'label' => __('Level Requirement', 'woocommerce'),
            'desc_tip' => 'true'
        )
    );
    
    echo '</div>';
      echo '<div class="product_custom_field">';
    // Custom Product Text Field
    woocommerce_wp_text_input(
        array(
            'id' => 'gold_points',
            'placeholder' => '0',
            'label' => __('Gold points required', 'woocommerce'),
            'desc_tip' => 'true'
        )
    );
    
    echo '</div>';
      echo '<div class="product_custom_field">';
    // Custom Product Text Field
    woocommerce_wp_text_input(
        array(
            'id' => 'silver_points',
            'placeholder' => '0',
            'label' => __('Silver points required', 'woocommerce'),
            'desc_tip' => 'true'
        )
    );
    
    echo '</div>';

}
function woocommerce_product_custom_fields_save($post_id)
{
    // Custom Product Text Field
    $woocommerce_custom_product_text_field = $_POST['level_pledge'];
    if (!empty($woocommerce_custom_product_text_field))
        update_post_meta($post_id, 'level_pledge', esc_attr($woocommerce_custom_product_text_field));
     $woocommerce_custom_product_text_field = $_POST['gold_points'];
    if (!empty($woocommerce_custom_product_text_field))
        update_post_meta($post_id, 'gold_points', esc_attr($woocommerce_custom_product_text_field));
      $woocommerce_custom_product_text_field = $_POST['silver_points'];
    if (!empty($woocommerce_custom_product_text_field))
        update_post_meta($post_id, 'silver_points', esc_attr($woocommerce_custom_product_text_field));



}
function wc_remove_all_quantity_fields( $return, $product ) {
	$cat=$product->category_ids[0];
	if($cat==166 || $cat==167 ){
	
    return true;}
}
add_filter( 'woocommerce_is_sold_individually', 'wc_remove_all_quantity_fields', 10, 2 );
function shop_filter_cat($query) {
    if (!is_admin() && is_post_type_archive( 'product' ) && $query->is_main_query()) {
       $query->set('tax_query', array(
                    array ('taxonomy' => 'product_cat',
                                       'field' => 'slug',
                                        'terms' => array('poster','merchandise')
                           )
                    )
       );   
    }
 }
 add_action('pre_get_posts','shop_filter_cat');
function add_bid(){
global $wpdb;

     $tables = 'bidding';
     $login_user=$_POST['login_user'];
     $id=$_POST['bid_id'];
     $current_amount = get_user_meta($login_user, 'wallet-amount', true);
     $today = date("d-m-Y");
     $amount=$_POST['amount'];
     $cut_date = get_post_meta($id, '_end_date', true );
     $data_array = array( 'user_id'=> $_POST['login_user'] ,'pledge_id'=>$_POST['bid_id'],'amount'=>$_POST['amount'],'expire'=>$cut_date);
     $check_bidd_price = $wpdb->get_var("select max(amount) FROM bidding where pledge_id = $id");
     $get_previs_user = $wpdb->get_var("select user_id FROM bidding where pledge_id = $id AND amount=$check_bidd_price");
     $today_time = strtotime($today);
     $expire_time = strtotime($cut_date);
    if ($expire_time >=$today_time) {
           if($current_amount>=$amount){
    	if($amount>$check_bidd_price){



    	/*	$totel_sum_amount=$_POST['totel_sum_amount'];
    		$Remaining_amount=$_POST['Remaining_amount'];
    		$goal=$_POST['goal'];
    		$totel_sum=$totel_sum_amount+$amount; 
            $Remaining=$goal-$totel_sum-$amount;
            $persant=$totel_sum*100/$goal;
		   if($persant>100)
		   {
		    $persantEnd=100;
		    $persantage=$persantEnd.'%';
		   } else{
		     $persantage=$persant.'%';
		   }*/

       $bid_insert= $wpdb->insert( $tables, $data_array);
       if($bid_insert){
       	 $pre_user_current_amount = get_user_meta($get_previs_user, 'wallet-amount', true);
       	$refund=$pre_user_current_amount+$check_bidd_price;

       	update_user_meta($get_previs_user, 'wallet-amount', $refund );//refund
       	$new_current_amount = get_user_meta($login_user, 'wallet-amount', true);
       	$remaning=$new_current_amount-$amount;

       	update_user_meta($login_user, 'wallet-amount', $remaning );


       	/*echo '<script>jQuery(document).ready(function() {jQurey("#away").text('.$Remaining.');});</script>';*/

   	   echo '<h4 class="result_response" style="color:green;">Successfully submit your bid.</h4>';
      } } else{ echo '<h4 class="result_response" style="color:red;">Please enter amount higher than '.$check_bidd_price.' for this bid.</h4>';} 
     } else{echo '<h4 class="result_response" style="color:red;">You have not suffcient gold in your wallet please add gold in your wallet. Your current gold is '.$current_amount.'.</h4>';} 
     }
      else{ echo '<h4 class="result_response" style="color:red;">We’re sorry this pledge has expired!</h4>';}
	 die();
}
add_action('wp_ajax_add_bid', 'add_bid');
add_action('wp_ajax_nopriv_add_bid', 'add_bid');
add_action('admin_menu', 'bidd_system');

/*function bidd_system() {
	add_menu_page('Max Bidd On Pledge', 'Bidding System', 'read', 'max-bidd', 'max_bidd','dashicons-flag');
}*/
/*function max_bidd(){
	?>
 <style>
    .table_1{ border:0px solid #ccc; width:100%; margin:10px 0px 10px 0px;} 
    .table_1 tr:nth-child(2n+0) {
        background: #f1f1f1;
    }
    .table_1 td{ border:1px solid #ccc; padding:10px;} 
    .table_1 th{ border:1px solid #ccc; padding:10px; background:#4db59b; color:#fff; font-weight:bold;}
  </style>
  <h2 style="font-size: 30px;"><center>Bidding System</center></h2>
   <div style="width:100%">
      <table class="table_1">
        <tr>
        <th>User Name</th>
        <th>Pledge Name</th>
        <th>Amount</th>
         <th>Bidding Cut-Off Date</th>
        </tr>
        <?php 
          global $wpdb; 
          $table = $wpdb->prefix . "role_request_form";
            $retrieve_data = $wpdb->get_results( "SELECT t.user_id ,t.pledge_id, t.amount,t.expire FROM ( SELECT pledge_id , MAX(amount) AS max_amt FROM bidding
         GROUP BY pledge_id ) AS m INNER JOIN bidding AS t ON t.pledge_id = m.pledge_id AND t.amount = m.max_amt" );
          // print_r($retrieve_data);
           foreach ($retrieve_data as $retrieved_data){
         ?>
        <tr>
          <td>
            <?php
            $i=$retrieved_data->user_id;
                $user_info = get_userdata($i);   //user_email

                echo $user_info->user_login;
               ?></td>
            <td><?php $pledge_id=$retrieved_data->pledge_id; echo  get_the_title($pledge_id);?></td>
            <td><?php  echo  $amount=$retrieved_data->amount; ?></td>
            <td><?php  $cut_date= $retrieved_data->expire;
            $today = date("d-m-Y");
             $today_time = strtotime($today);
             $expire_time = strtotime($cut_date);
             if ($expire_time <$today_time) {
             	$check_vote = $wpdb->get_var("select count(*) FROM bid_amount where pledge_id = $pledge_id and user_id=$i");
             	if($check_vote){
             		echo '<i class="fa fa-check" style="color:green;" aria-hidden="true"></i>';

             	} else{
             	echo '<div id="m'.$pledge_id.'"><a class="button-primary add_amount"  id="'.$pledge_id.'" att="'.$amount.'" href="javascript:void(0)">Send Amount</a>
             	<input type="hidden" value="'.$i.'" id="u'.$pledge_id.'" /></div>';
             }
             }else{ echo $cut_date;}

            ?></td>

        </tr>
        <?php
        }?>
      </table>
       <script type="text/javascript">
        jQuery(document).ready(function() {
        	jQuery('.add_amount').click(function() {
        		  var pledge_id =this.id;
        		 
        		  var amount =jQuery('#'+pledge_id).attr('att');
        		  var usr =jQuery('#u'+pledge_id).val();
        		  
        		  jQuery.ajax({
                  type:'POST',
                  url: ajaxurl,
                  data:{action:'amount_send',amount:amount,pledge_id:pledge_id,usr:usr},
                  success: function(value) {
                  if(value=='ammount_added'){
                  	jQuery('#m'+pledge_id).html('<i class="fa fa-check" style="color:green;" aria-hidden="true"></i>');
                  	
                  	// jQuery('#'+value).html('<div class="wpcf7-response-output wpcf7-display-none wpcf7-validation-errors" style="display: block;">Delete successfully....</div>').fadeOut(3000);
                  }
                  }
               });
        	});  
         });
        	</script>

    </div>


	<?php
}*/
add_action('wp_ajax_amount_send', 'amount_send');
  add_action('wp_ajax_nopriv_amount_send', 'amount_send');
function amount_send(){
     global $wpdb;
     $amount=$_REQUEST['amount'];
     $pledge_id=$_REQUEST['pledge_id'];
     $usr=$_REQUEST['usr'];
     $Pledge=get_the_title( $pledge_id );
      $user_info = get_userdata($usr);
     $admin_email=$user_info->user_email;

     $data_array = array( 'user_id'=> $usr ,'pledge_id'=>$pledge_id,'amount'=>$amount);
     $amount_insert= $wpdb->insert( 'bid_amount', $data_array);
     if($amount_insert){
        $header .= "MIME-Version: 1.0\n";
		$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
		$message .= "Pledge name: $Pledge " ."\r\n";
		$message .= "Amount: $ $amount " ."\r\n";
		$subject = "Congratulation you can purchage Pledge";
		$to =  $admin_email;
		// send the email
		if(wp_mail($to, $subject, $message, $header)){
	 
     	echo 'ammount_added';
     }
     update_post_meta($pledge_id, '_regular_price', $amount );
     update_post_meta($pledge_id, '_price', $amount );  //_price

     }

   
         die();
     
}


//Ranking for slider video code start here
/**
 * Adds a box to the main column on the Post add/edit screens.
 */
function wdm_add_meta_box() {

        add_meta_box(
                'wdm_sectionid', 'Rank for slider video', 'wdm_meta_box_callback', 'post'
        ); //you can change the 4th paramter i.e. post to custom post type name, if you want it for something else

}

add_action( 'add_meta_boxes', 'wdm_add_meta_box' );

/**
 * Prints the box content.
 * 
 * @param WP_Post $post The object for the current post/page.
 */
function wdm_meta_box_callback( $post ) {

        // Add an nonce field so we can check for it later.
        wp_nonce_field( 'wdm_meta_box', 'wdm_meta_box_nonce' );

        /*
         * Use get_post_meta() to retrieve an existing value
         * from the database and use the value for the form.
         */
        $value = get_post_meta( $post->ID, 'rank_key', true ); //my_key is a meta_key. Change it to whatever you want

        ?>
        <label for="wdm_new_field"><?php _e( "Choose value:", 'choose_value' ); ?></label>
        <br/>  
      	<input type="radio" id="1" name="rank_key" value="1" <?php checked( $value, '1' ); ?> >Rank 1<br>
       	<input type="radio" id="2" name="rank_key" value="2" <?php checked( $value, '2' ); ?> >Rank 2<br>
       	<input type="radio" id="3" name="rank_key" value="3" <?php checked( $value, '3' ); ?> >Rank 3<br>
       	<input type="radio" id="4" name="rank_key" value="4" <?php checked( $value, '4' ); ?> >Rank 4<br>
       	<input type="radio" id="5" name="rank_key" value="5" <?php checked( $value, '5' ); ?> >Rank 5<br>
       	<input type="radio" id="6" name="rank_key" value="6" <?php checked( $value, '6' ); ?> >Rank 6<br>
       	<input type="radio" id="7" name="rank_key" value="7" <?php checked( $value, '7' ); ?> >Rank 7<br>
       	<input type="radio" id="8" name="rank_key" value="8" <?php checked( $value, '8' ); ?> >Rank 8<br>
       	<input type="radio" id="9" name="rank_key" value="9" <?php checked( $value, '9' ); ?> >Rank 9<br>
       	<input type="radio" id="10" name="rank_key" value="10" <?php checked( $value, '10' ); ?> >Rank 10<br>

        <?php

}

/**
 * When the post is saved, saves our custom data.
 *
 * @param int $post_id The ID of the post being saved.
 */
function wdm_save_meta_box_data( $post_id ) {

        /*
         * We need to verify this came from our screen and with proper authorization,
         * because the save_post action can be triggered at other times.
         */

        // Check if our nonce is set.
        if ( !isset( $_POST['wdm_meta_box_nonce'] ) ) {
                return;
        }

        // Verify that the nonce is valid.
        if ( !wp_verify_nonce( $_POST['wdm_meta_box_nonce'], 'wdm_meta_box' ) ) {
                return;
        }

        // If this is an autosave, our form has not been submitted, so we don't want to do anything.
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
                return;
        }

        // Check the user's permissions.
        if ( !current_user_can( 'edit_post', $post_id ) ) {
                return;
        }


        // Sanitize user input.
        $new_meta_value = ( isset( $_POST['rank_key'] ) ? sanitize_html_class( $_POST['rank_key'] ) : '' );

        // Update the meta field in the database.
        update_post_meta( $post_id, 'rank_key', $new_meta_value );

}

add_action( 'save_post', 'wdm_save_meta_box_data' );
//Ranking for slider video code end here
function check_values($post_ID, $post_after, $post_before){
	 $post_type=$_REQUEST['post_type'];
	 
	 if($post_type=='post')
	 {
	 	$rank=$_REQUEST['rank_key'];  
	    $post_ID=$_REQUEST['post_ID'];
	     $args = array(
        'posts_per_page'   => -1,
        'post_type'        => 'post',
        'suppress_filters' => true ,
        'meta_query' => array(
            array(
                'key' => 'rank_key',
                'value' => $rank
            )
        )
    );       global $wpdb;
	       $retrieve_data2 = $wpdb->get_results( "SELECT * FROM 413_Rank_slider " );
    foreach ($retrieve_data2 as $retrieved_data2){
    	$ids[]=$retrieved_data2->post_id;
    }
    $posts_array = get_posts( $args );
    foreach($posts_array as $post_array)
         {
    	update_post_meta($post_array->ID, 'rank_key', ' ');
       }
    
	    global $wpdb;

        $table_name ='413_Rank_slider';
        $tdata = $wpdb->get_row( "SELECT id FROM  413_Rank_slider where post_id='$post_ID'" );
        $already_rank=$tdata->id;
        if($already_rank){
        	$wpdb->query("UPDATE $table_name SET post_id ='0' WHERE id = '$already_rank'");
        	$wpdb->query("UPDATE $table_name SET post_id ='$post_ID' WHERE id = '$rank'");
        } else{
        	$wpdb->query("UPDATE $table_name SET post_id ='$post_ID' WHERE id = '$rank'");
        }
        /* global $wpdb; 
         $Table ='413_Rank_slider';
        $retrieve_data = $wpdb->get_results( "SELECT * FROM 413_Rank_slider WHERE post_id=0" );
        
        foreach ($retrieve_data as $retrieved_data){
        	echo ' Rank: '.$retrieved_data->id;

        }*/
      $args = array( 'numberposts' => '10','post_status' => 'publish' ,'orderby' => 'post_date','order' => 'DESC');
    $recent_posts = wp_get_recent_posts( $args );

    foreach( $recent_posts as $recent ){
        $recentpost[]=$recent["ID"];
    }

           //print_r($recentpost);
           
          // print_r($post_ID);
		//$recentpost1=deleteElement($rank, $recentpost);
  
         //    print_r($ids);

        //   echo '2nd';
		$recentpost1 =array_merge(array_diff($recentpost, array($post_ID)));
		//print_r($recentpost1);
		//echo '3rd';
		$recentpost3 =array_merge(array_diff($recentpost1, $ids));
		//print_r($recentpost3);
		//$recentpost2=array_unique( array_merge($recentpost1, $ids) );
		//print_r($recentpost2);
		



     $Table ='413_Rank_slider';
        $retrieve_data = $wpdb->get_results( "SELECT * FROM 413_Rank_slider WHERE post_id=0" );
          $i=0;
        foreach ($retrieve_data as $retrieved_data){
              $recent_arr=$recentpost3[$i];
              $ids=$retrieved_data->id;
              $wpdb->query("UPDATE 413_Rank_slider SET post_id='$recent_arr' WHERE id='$ids'");
              $i++;
        	

        }
    //$recentpost[0];
  //  die(); 

	 }

}
add_action( 'post_updated', 'check_values', 10, 3 );

//ballot Functionality
add_action('wp_ajax_ballot_fun', 'ballot_fun');
add_action('wp_ajax_nopriv_ballot_fun', 'ballot_fun');
function ballot_fun()
{
	  $ballot_id=$_REQUEST['ballot_id'];  //ballot_type
	  update_post_meta($ballot_id, '_price', 0 );
	  $ballot_type=$_REQUEST['ballot_type']; 
	  $price=$_REQUEST['price']; //price
	  if($ballot_type=='gold'){
	  	global $wpdb;
	  	global $woocommerce;
        $woocommerce->cart->empty_cart();
        $chked=$wpdb->query("UPDATE check_gold SET chk = 'gold' WHERE id = '1'");
	  	wp_set_post_terms( $ballot_id, array(166,169), 'product_cat' );
	  } else{
	  	global $wpdb;
	  	global $woocommerce;
        $woocommerce->cart->empty_cart();
        $chked=$wpdb->query("UPDATE check_gold SET chk = 'silver' WHERE id = '1'");

	  	wp_set_post_terms( $ballot_id, array(166,170), 'product_cat' );
	  }
	  update_post_meta($ballot_id, '_regular_price', $price );
      $done=update_post_meta($ballot_id, '_price', $price );  //_price
      if($done){
      	echo 'ballot_fun';
      }
	  
	die(); 
}
add_action( 'woocommerce_order_status_completed', 'status_completed' );
/*
 * Do something after WooCommerce set an order status as completed
 */
function status_completed($order_id) {
	
	// order object (optional but handy)
 	$order = new WC_Order( $order_id );
 	// $amount =  $order->get_total();
 	$items = $order->get_items();
   foreach ( $items as $item ) {
    $product_name = $item['name'];
    $product_id = $item['product_id'];
    $product_variation_id = $item['variation_id'];
}
     $product_id;
     $term_list = wp_get_post_terms($product_id,'product_cat',array('fields'=>'ids'));
     $check=$term_list[1];
     $payment_method = get_post_meta($order_id, '_payment_method', true);
      if( $payment_method=='wallet') {
           
		    $order_total = get_post_meta($order_id, '_order_total', true);
           }
    if($check==170 &&  $payment_method=='cod'){
    	    $_stock = get_post_meta($product_id, '_stock', true);
            $qty=$_stock+1;
            update_post_meta($product_id, '_stock', $qty );

    	    $today=date("Y-m-d");
            $user_id = (int)$order->user_id;
           

		    global $wpdb;
		    $data_array = array( 'user_id'=> $user_id ,'product_id'=>$product_id,'order_id'=>$order_id,'amount'=>$order_total,'date'=>$today);
            $free_credit= $wpdb->insert( 'free_credit_trasactions', $data_array);

            $data_array3 = array( 'order_date'=>$today,'order_id'=>$order_id,'type'=>'silver','user_id'=>$user_id,'product_id'=>$product_id,'amount'=>$order_total);
                $r2= $wpdb->insert( '413_all_trasactions', $data_array3);
                
			if($free_credit > 0){
				$tdata = $wpdb->get_row( "SELECT * FROM  interactive_system where user_id='$user_id'" );
                $free=$tdata->points;
                $left=$free-$order_total;
                $wpdb->query("UPDATE interactive_system SET points='$left' WHERE user_id='$user_id'");
           }
          

	// do some stuff here
	
}}
/*add_filter( 'woocommerce_add_to_cart_validation', 'bbloomer_only_one_in_cart' );
  
function bbloomer_only_one_in_cart( $cart_item_data ) {
global $woocommerce;
$woocommerce->cart->empty_cart();
return $cart_item_data;
}*/
/**
 * Register a custom Ballot pledge page.
 */
function wpdocs_register_my_custom_menu_pages4() {
    add_menu_page(
        __( 'Submit Film Requst', 'textdomain' ),
        'Submit Film Requst ',
        'manage_options',
        'edit.php?post_type=wpcf7s&wpcf7_contact_form=1381',  
        '',
        'dashicons-welcome-view-site',
        16
    );
}
add_action( 'admin_menu', 'wpdocs_register_my_custom_menu_pages4' );
function wpdocs_register_my_custom_menu_pages() {
    add_menu_page(
        __( 'Ballot Pledge', 'textdomain' ),
        'Ballot Pledge ',
        'manage_options',
        'edit.php?product_cat=ballot&post_type=product',
        '',
        'dashicons-welcome-view-site',
        6
    );
}
add_action( 'admin_menu', 'wpdocs_register_my_custom_menu_pages' );
/**
 * Register a custom Bid pledge page.
 */
function wpdocs_register_my_custom_menu_pages1() {
    add_menu_page(
        __( 'Bid Pledge', 'textdomain' ),
        'Bid Pledge ',
        'manage_options',
        'edit.php?product_cat=bid&post_type=product',
        '',
        'dashicons-welcome-view-site',
        6
    );
}
add_action( 'admin_menu', 'wpdocs_register_my_custom_menu_pages1' );
/**
 * Register a custom Merchandise page.
 */
function wpdocs_register_my_custom_menu_pages2() {
    add_menu_page(
        __( 'Merchandise', 'textdomain' ),
        'Merchandise',
        'manage_options',
        'edit.php?post_type=shop_order',
        '',
        'dashicons-cart',
        6
    );
}
add_action( 'admin_menu', 'wpdocs_register_my_custom_menu_pages2' );

/*function duplicate_titles_enqueue_scripts( $hook ) {

		if( !in_array( $hook, array( 'post.php', 'post-new.php' ) ) )
			return;

		wp_enqueue_script( 'duptitles',wp_enqueue_script( 'duptitles',get_template_directory_uri().'/js/rank_video.js',array( 'jquery' )), array( 'jquery' )  );
	}
	add_action( 'admin_enqueue_scripts', 'duplicate_titles_enqueue_scripts', 2000 );

	//add_action('wp_ajax_title_check', 'duplicate_title_check_callback');
	add_action('wp_ajax_rank_check', 'rank_check');
  add_action('wp_ajax_nopriv_rank_check', 'rank_check');
function rank_check(){
	$rank=$_REQUEST['rank'];
	 global $wpdb; 
         $Table ='413_Rank_slider';
        $retrieve_data = $wpdb->get_results( "SELECT * FROM 413_Rank_slider WHERE id<'$rank' AND post_id=0" );
        if($retrieve_data){
        foreach ($retrieve_data as $retrieved_data){
        	echo ' Rank: '.$retrieved_data->id;

        }} else{ echo 'no';};
	 
    

   
         die();
     
}
*/
add_action('init', 'buddyforms_allow_contributor_uploads');
			
function buddyforms_allow_contributor_uploads() {
    if ( current_user_can('content_creator') && !current_user_can('upload_files') ){
        $contributor = get_role('content_creator');
        $contributor->add_cap('upload_files');
    }
}
add_action( 'woocommerce_thankyou', 'custom_woocommerce_auto_complete_order');
function custom_woocommerce_auto_complete_order( $order_id ) {
    if ( ! $order_id ) {
        return;
    }
    $order = new WC_Order( $order_id );
    $items = $order->get_items();
    
      foreach ( $items as $item ) {
            $product_id = $item['product_id'];
        }
        $terms = get_the_terms ( $product_id, 'product_cat' );
       //print_r($terms);
        if($terms){
        foreach ( $terms as $term ) {

         $cat=$term->term_id;
         $cat_sub=$terms[1]->term_id;
         if($cat==166 || $cat==158){
         	 $order = wc_get_order( $order_id );
             $order->update_status( 'completed' );
         }
        

     }
}
}
add_action('wp_ajax_check_crowdfund', 'check_crowdfund');
add_action('wp_ajax_nopriv_check_crowdfund', 'check_crowdfund');
function check_crowdfund(){
	$post_id=$_REQUEST['post_id'];
	
        $havemeta = get_post_meta($post_id, 'pledge_id', true);
        $pledgearr=explode(",",$havemeta);
       $args2 = array(
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
    
    $loop2 = new WP_Query( $args2 );
    while ( $loop2->have_posts() ) : $loop2->the_post(); global $product;
     $Post_id= get_the_ID();
     $is_crowd= get_post_meta( $Post_id, '_alg_crowdfunding_enabled',true );
    if($is_crowd=='yes'){
    	echo 'yes';

    }
     endwhile;
     die();
}


add_action('admin_menu', 'point_edit_system');

function point_edit_system() {
	add_menu_page('Silver Point Edit System', 'Silver Point Edit System', 'read', 'silver_point_edit_system', 'silver_point_edit_system');
}
function silver_point_edit_system(){
?>
<style>
.table_1 {
  border: 0 solid #ccc;
  margin: 0 auto!important;
  width: 62%!important;
}
.table_1 td {
  
  text-transform: capitalize;
}
.wp-core-ui .button-primary {
  margin-right: 10px;
}
    .table_1{ border:0px solid #ccc; width:100%; margin:10px 0px 10px 0px;} 
    .table_1 tr:nth-child(2n+0) {
        background: #f1f1f1;
    }
    .table_1 td{ border:1px solid #ccc; padding:10px;} 
    .table_1 th{ border:1px solid #ccc; padding:10px; background:#4db59b; color:#fff; font-weight:bold;}
    .table_1 tr td:nth-child(3) {
  text-align: center;
}
	</style>
	<h2 style="font-size: 30px;"><center>Silver Point system</center></h2>
   	<div id="select" style="width:100%;" >
      <table class="table_1">
	       <tr>
	       		<th>Name</th>
		        <th>Points</th>
		        <th>Update</th>
	   		</tr>	
	   	<!--</table>-->
		<?php 
		global $wpdb;
		$Table ='point_editable';
		  $retrieve_data = $wpdb->get_results( "SELECT * FROM  $Table" );
		     foreach ($retrieve_data as $retrieved_data){
		?>
 <form method="post" action="" enctype="multipart/form-data" >
 	    <tr>
	     <td class="id"><?php
         echo $retrieved_data->point_type; ?></td>
	     
	   
	     <td>
			 <?php if((isset($_GET['edit']))&&($retrieved_data->id == $_GET['edit']))
			 {
			 ?>
				<input type="text" name="points" id="<?php echo $retrieved_data->id; ?>" value="<?php echo $retrieved_data->points;?>"/>
					<?php 
				} else { ?>
					<?php echo $retrieved_data->points;?>
			 		<?php  } ?>
	
	      </td>
	    
	        <td><a class="edit wc-update-now button-primary" href="<?php echo site_url();?>/wp-admin/admin.php?page=silver_point_edit_system&edit=<?php echo $retrieved_data->id;?>">Edit</a>  
	     	<input class="button button-primary button-large" type="submit" name="submit" id="update" value="Update" /></td>
	     	
	</tr>
	</form>
	
		  

		     <?php
		     }?>
		    </table>
    
   
	</div>
	<?php 
	if(isset($_POST['submit'])){
		global $wpdb;
		$id = $_GET['edit'];
		$points = $_POST['points'];
		
		$allowed = $wpdb->query("UPDATE point_editable SET points = '$points' WHERE id = '$id'"); 
		if($allowed)
		{
			wp_redirect(admin_url('/?page=silver_point_edit_system', 'http'), 301);

			echo "successfully updated";
		}
		
	}
	}
add_action('wp_ajax_socal_media_share', 'socal_media_share');
add_action('wp_ajax_nopriv_socal_media_share', 'socal_media_share');
function socal_media_share(){
	global $wpdb;
	$post_id = $_POST['post_id'];
	$userid = $_POST['userid'];
	$today=date("Y-m-d");
	$get_points = $wpdb->get_row( "SELECT * FROM  interactive_system where user_id='$userid'" );
        $currentPoints=$get_points->points;
         $title=get_the_title($post_id);
       // $current_amount = get_user_meta($TransferId, 'wallet-amount', true);
        $row6= $wpdb->get_row( "SELECT * FROM  point_editable WHERE id ='6'");
        $socialmedia=$row6->points;
        $totalPoints= $currentPoints+$socialmedia;
        $allowed = $wpdb->query("UPDATE interactive_system SET points = '$totalPoints' WHERE user_id = '$userid'");
       	$data_array2 = array('user_id'=>$userid,'type'=>'Social media platform','femail'=>$title,'points'=>$socialmedia,'date'=>$today);
        $point_insert= $wpdb->insert( 'free_points_details', $data_array2);
        if($point_insert){
        	echo 'Social media platform point recived';

        }
        
        die();
}
function my_project_updated_send_email( $post_id, $post, $update ) {

	// If this is a revision, don't send the email.
	if ( wp_is_post_revision( $post_id ) )
		return;
    $userid=$post->post_author;
    $post_name=$post->post_name;
    $post_type=$post->post_type;
    $today=date("Y-m-d");
	if($post_type=='topic'){
		global $wpdb;
		$get_points = $wpdb->get_row( "SELECT * FROM  interactive_system where user_id='$userid'" );
        $currentPoints=$get_points->points;
        $row8= $wpdb->get_row( "SELECT * FROM  point_editable WHERE id ='8'");
        $threadcreated=$row8->points;
        $totalPoints= $currentPoints+$threadcreated;
        $allowed = $wpdb->query("UPDATE interactive_system SET points = '$totalPoints' WHERE user_id = '$userid'");
       	$data_array2 = array('user_id'=>$userid,'type'=>'post thread created on the forum','femail'=>$post_name,'points'=>$threadcreated,'date'=>$today);
        $point_insert= $wpdb->insert( 'free_points_details', $data_array2);

	}


}
add_action( 'wp_insert_post', 'my_project_updated_send_email', 10, 3 );
add_action('wp_ajax_submit_video_request', 'submit_video_request');
add_action('wp_ajax_nopriv_submit_video_request', 'submit_video_request');
function submit_video_request(){
	global $wpdb;
	$login_user = $_POST['login_user'];
	$data_array2 = array('userid'=>$login_user);
    $entery_pop= $wpdb->insert( '413_submit_video', $data_array2);
        if($entery_pop){
        	echo 'Done';
 }
     die();
}
// Creates Movie Reviews Custom Post Type
function poll() {
    $args = array(
      'label' => 'Poll',
        'public' => true,
        'show_ui' => true,
        'capability_type' => 'post',
        'hierarchical' => false,
        'rewrite' => array('slug' => 'poll'),
        'query_var' => true,
        'menu_icon' => 'dashicons-universal-access',
        'supports' => array(
            'title',
            'editor',
            'excerpt',
            'trackbacks',
            'custom-fields',
            'comments',
            'revisions',
            'thumbnail',
            'author',
            'page-attributes',)
        );
    register_post_type( 'poll', $args );
}
add_action( 'init', 'poll' );
//shifali code start here---22-may-17
add_action( 'add_meta_boxes', 'video_meta_box_add' );
function video_meta_box_add(){
    add_meta_box( 'my-video-meta-box-id', 
		    	'Next video episode',
		    	'cd_meta_box_video', 
		    	'post', 
		    	'normal', 
		    	'high'
    	 		);
}
function cd_meta_box_video(){
 	$post_id = get_the_ID();
	$episode_title = esc_html( get_post_meta( $post_id, 'episodetitle', true ) );
	$episode_link = esc_html( get_post_meta( $post_id, 'episodelink', true ) );
	 wp_nonce_field( 'my_meta_box_nonce', 'meta_box_nonce' );
	    ?>
	<div class="rwmb-field rwmb-text-wrapper">
	    <div class="rwmb-label">
	    	<label for="Episode Title">Episode Title</label>
	    </div>
	    <div class="rwmb-input">
	    	<input type="text" name="episodetitle" id="episode" size="64" value="<?php echo $episode_title;?>" />
	    </div>
	</div>
	<div class="rwmb-field rwmb-text-wrapper">
	    <div class="rwmb-label">
	    	<label for="Episode link">Episode link</label>
	    </div>
	    <div class="rwmb-input">
	    	<input type="text" name="episodelink" id="link" size="64" value="<?php echo $episode_link;?>"/>
		</div>
	</div>
    <?php    
}
add_action( 'save_post', 'video_meta_box_save' );
function video_meta_box_save( $post_id )
{
    // Bail if we're doing an auto save
    if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
     
    // if our nonce isn't there, or we can't verify it, bail
    if( !isset( $_POST['meta_box_nonce'] ) || !wp_verify_nonce( $_POST['meta_box_nonce'], 'my_meta_box_nonce' ) ) return;
     
    // if our current user can't edit this post, bail
    if( !current_user_can( 'edit_post' ) ) return;
     
    // now we can actually save the data
    $allowed = array( 
        'a' => array( // on allow a tags
            'href' => array() // and those anchors can only have href attribute
        )
    );
     
    // Make sure your data is set before trying to save it
    if( isset( $_POST['episodetitle'] ) )
        update_post_meta( $post_id, 'episodetitle', wp_kses( $_POST['episodetitle'], $allowed ) );
         
    if( isset( $_POST['episodelink'] ) )
      update_post_meta( $post_id, 'episodelink', wp_kses( $_POST['episodelink'], $allowed ) );
         
    // This is purely my personal preference for saving check-boxes
   
}
add_action('wp_ajax_poll_insert', 'poll_insert');
add_action('wp_ajax_nopriv_poll_insert', 'poll_insert');
function poll_insert(){
	global $wpdb;
	$login_user = $_POST['login_user'];
	$poll_val = $_POST['poll_val'];
	$poll_id = $_POST['poll_id'];
	$get_points = $wpdb->get_row( "SELECT * FROM  interactive_system where user_id='$login_user'" );
    $currentPoints=$get_points->points;
    //$row9= $wpdb->get_row( "SELECT * FROM  point_editable WHERE id ='9'");
    $vote_price= get_post_meta($poll_id, 'silver', true );
   // $vote_price=$row9->points;
    $poll_val='v_'.$poll_val;
    
	$data_array2 = array('user_id'=>$login_user,'poll_id'=>$poll_id,$poll_val=>1);
	if($currentPoints>=$vote_price){
        $vote= $wpdb->insert( 'poll_system', $data_array2);
        if($vote){
        	$today=date("Y-m-d");
            $title=get_the_title($poll_id);
        	$remaning_points=$currentPoints-$vote_price;
        	$remaning = $wpdb->query("UPDATE interactive_system SET points = '$remaning_points' WHERE user_id = '$login_user'");
        	$data_array = array( 'user_id'=> $login_user ,'product_id'=>$poll_id,'order_id'=>$poll_id,'amount'=>$vote_price,'date'=>$today);
            $free_credit= $wpdb->insert( 'free_credit_trasactions', $data_array);
            
        	echo 'done';
          } 

     } else
              {
        	echo 'no_free_points';
        }
     die();
}

add_filter('ms_shortcode_account', 'wpmu_customize_account_shortcode_page', 10, 2);

function wpmu_customize_account_shortcode_page($html_output, $data){
		if ( is_user_logged_in() ) {
		    return $html_output;
		} else {
			$pre_html = "<div class='log_in_custom'>";
			$pre_html .= "<a href='http://www.premise.tv/wp-login.php?action=wordpress_social_authenticate&mode=login&provider=Facebook&redirect_to=http%3A%2F%2Fwww.premise.tv%2Fwp-admin%2F' ><img src='http://www.premise.tv/wp-content/uploads/2017/06/fb.png'></a><br />";
			$pre_html .= "<h3>or</h3>";
			$pre_html .= "<a href='http://www.premise.tv/wp-login.php?action=wordpress_social_authenticate&mode=login&provider=Google&redirect_to=http%3A%2F%2Fwww.premise.tv%2Fwp-admin%2F' ><img src='http://www.premise.tv/wp-content/uploads/2017/06/google.png'></a><br />";
			$pre_html .= "<h3>or</h3>";
			$pre_html .= $html_output;
			$pre_html .="</div>";
			return $pre_html;
		}
}
//Add custom post type for reward code written by shifali
add_action( 'init', 'create_rewards' );

function create_rewards() {
    register_post_type( 'create_rewards',
        array(
            'labels' => array(
                'name' => 'Rewards',
                'singular_name' => 'Rewards',
                'add_new' => 'Add New',
                'add_new_item' => 'Add New Rewards',
                'edit' => 'Edit',
                'edit_item' => 'Edit Rewards',
                'new_item' => 'New Rewards',
                'view' => 'View',
                'view_item' => 'View Rewards',
                'search_items' => 'Search Rewards',
                'not_found' => 'No Rewards found',
                'not_found_in_trash' => 'No Rewards found in Trash',
                'parent' => 'Parent Rewards'
            ),
 
            'public' => true,
            'menu_position' => 15,
            'supports' => array( 'title', 'editor', 'comments', 'thumbnail'),
            'taxonomies' => array( '' ),
            'menu_icon' 		=> 'dashicons-admin-post',
            'has_archive' => true
        )
    );
}

add_action( 'the_post', 'wpse_257708_the_post', 10, 1 );
function wpse_257708_the_post( $post ) {
  if( 'create_rewards' === $post->post_type ) {
     remove_filter( 'the_content', 'wpautop' );
  } 
}
remove_filter( 'the_content', 'wpautop' );
/**
 * Trim zeros in price decimals
 **/
 add_filter( 'woocommerce_price_trim_zeros', '__return_true' );
 add_action('wp_ajax_set_gold', 'set_gold');
add_action('wp_ajax_nopriv_set_gold', 'set_gold');
function set_gold()
{
	global $wpdb;
	$val=$_POST['chk'];
	$chked=$wpdb->query("UPDATE check_gold SET chk = '$val' WHERE id = '1'");
	if($chked){
		echo $val;
	} else{
		echo $val;
	}
	die();
}

add_action( 'wp_ajax_save_merchandise', 'save_merchandise' );
add_action( 'wp_ajax_nopriv_save_merchandise', 'save_merchandise' );

function save_merchandise() {
    $status = false;
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
    $post_info = array(
        'post_title' => wp_strip_all_tags( $_POST['postTitle'] ),
        'post_content' => $_POST['postContent'],
        'post_type' => 'product',
        'post_status'=>'draft'
    );
    $pid = wp_insert_post( $post_info );
    update_post_meta($pid,'_thumbnail_id',$attach_id);
    set_post_thumbnail( $pid, $thumbnail_id );
    set_post_thumbnail_size( 300,300, true );
    add_post_meta( $_POST['merchandise_video_id'], 'merchandise_video_id', $pid, true );
    wp_set_post_terms( $pid, array(157,158), 'product_cat' );
    if(isset($_POST['crowdfund'])){
        add_post_meta( $pid, '_alg_crowdfunding_enabled','yes');
        $days = $_POST['crowdfund'];
        $startDate = date("Y/m/d");
        $endDate =  date("Y/m/d",strtotime("+{$days} days"));


        add_post_meta($pid, '_alg_crowdfunding_startdate', $startDate );
        //add_post_meta($pid, '_alg_crowdfunding_starttime', $_POST['start_time']);
        add_post_meta($pid, '_alg_crowdfunding_deadline',  $endDate);
       // add_post_meta($pid, '_alg_crowdfunding_deadline_time',$_POST['end_time']);
        add_post_meta($pid, '_alg_crowdfunding_goal_sum',$_POST['goal_price']);
        // add_post_meta($pid, '_alg_crowdfunding_product_open_price_enabled','yes');
        //add_post_meta($pid, '_alg_crowdfunding_product_open_price_default_price','100');

    }
    $price= intval(update_post_meta( $pid, '_regular_price', $_POST['_regular_price'] ));
    if($price){
        $email =get_bloginfo('admin_email');
        $post_title   = $_POST['postTitle'];
        $post_content = $_POST['postContent'];
        $header .= "MIME-Version: 1.0\n";
        $header .= "Content-Type: text/html; charset=utf-8\n";
        $message = "Post Title: $post_title\n";
        $message .= "Post content: $post_content\n";
        $subject = "Product content";
        $subject = "=?utf-8?B?" . base64_encode($subject) . "?=";
        $to = $email;
        // send the email using wp_mail()
        wp_mail($to, $subject, $message, $header);
        $status =  true;
    }

    echo $status;
    wp_die();
}


add_action( 'wp_ajax_save_pledge', 'save_pledge' );
add_action( 'wp_ajax_nopriv_save_pledge', 'save_pledge' );


function save_pledge(){

    if($_POST['Pledgetype'] == 'poll'){
        // print_r($_POST);
        $post_info = array(
            'post_title' => wp_strip_all_tags( $_POST['PledgeTitle'] ),
            'post_content' => $_POST['PledgeContent'],
            'post_type' => 'poll',
            'post_status'=>'publish',

        );
        $pid = wp_insert_post( $post_info );
        $poll_value=implode(',', $_POST['poll_value']);
        if(add_post_meta( $pid, 'poll_value', $poll_value))
        {
            add_post_meta( $_POST['poll_video_id'], 'poll_id', $pid, true );
            add_post_meta( $pid, 'level', $_POST['level']);
            add_post_meta( $pid, 'silver', $_POST['silver']);

           // $msg= '<div class="wpcf7-response-output wpcf7-display-none wpcf7-validation-errors" style="display: block;">Your Poll is now successfully added. </div>';
        }

    }else {
        $post_info = array(
            'post_title' => wp_strip_all_tags($_POST['PledgeTitle']),
            'post_content' => $_POST['PledgeContent'],
            'post_type' => 'product',
            'post_status' => 'publish',

        );
        $pid = wp_insert_post($post_info);
        if ($_POST['stock']) {
            $_manage_stock = intval(update_post_meta($pid, '_manage_stock', 'yes'));
            $_stock = intval(update_post_meta($pid, '_stock', $_POST['stock']));

        }   //stock
        $gold_points = intval(update_post_meta($pid, 'gold_points', $_POST['gold']));
        $silver_points = intval(update_post_meta($pid, 'silver_points', $_POST['silver']));
        add_post_meta($pid, 'level_pledge', $_POST['level']);
        add_post_meta($pid, '_end_date', $_POST['cut']);
        if (isset($_POST['crowdfund'])) {
            add_post_meta($pid, '_alg_crowdfunding_enabled', 'yes');
            $days = $_POST['crowdfund'];
            $startDate = date("Y/m/d");
            $endDate =  date("Y/m/d",strtotime("+{$days} days"));

            add_post_meta($pid, '_alg_crowdfunding_startdate', $startDate );
            add_post_meta($pid, '_alg_crowdfunding_starttime', $_POST['start_time']);
            add_post_meta($pid, '_alg_crowdfunding_deadline',  $endDate);
            add_post_meta($pid, '_alg_crowdfunding_deadline_time',$_POST['end_time']);
            add_post_meta($pid, '_alg_crowdfunding_goal_sum',$_POST['goal_price']);
            // add_post_meta($pid, '_alg_crowdfunding_product_open_price_enabled','yes');
            //add_post_meta($pid, '_alg_crowdfunding_product_open_price_default_price','100');

        }
        wp_set_post_terms($pid, array($_POST['Pledgetype'], 157), 'product_cat');
        $havemeta = get_post_meta($_POST['video_id'], 'pledge_id', true);
        if ($havemeta) {
            $havemeta = $havemeta . ',' . $pid;

            update_post_meta($_POST['video_id'], 'pledge_id', $havemeta);
        } else {
            add_post_meta($_POST['video_id'], 'pledge_id', $pid, true);
        }
        //Add Pledge id in video meta
        if ($_POST['gold']) {
            $price = intval(update_post_meta($pid, '_regular_price', $_POST['gold']));
        } else {
            $price = intval(update_post_meta($pid, '_regular_price', $_POST['_regular_price']));
        }
    }

   echo true;

    wp_die();

}



function filter_wpcf7_ajax_json_echo($items, $result){

    $items['vd_id'] = $_SESSION['vd_slug'];
    $items['vd_title']= $_SESSION['vd_title'];



	//$res = json_decode($result);
	return $items;
	exit;
}

add_filter( 'wpcf7_ajax_json_echo', 'filter_wpcf7_ajax_json_echo', 10, 2 );

//function show_drafts_in_staging_archives( $query ) {
//
//
//    if ( is_admin() || is_feed() )
//        return;
//
//    $query->set( 'post_status', array( 'publish', 'draft' ) );
//}
//
//add_action( 'pre_get_posts', 'show_drafts_in_staging_archives' );

function getVideoCrowdfundingDuration(){

    $videoId = $_GET['videoId'];
    echo get_post_meta($videoId,"crowdfunding_duration_video",true);

   exit;



}
function my_custom_sidebar() {
    register_sidebar(
        array (
			'name' => __( 'InterviewSideBar', 'your-theme-domain' ),
			'id' => 'custom-side-bar',
			'description' => __( 'Appears on posts and pages except the optional Front Page template, which has its own widgets', 'cactusthemes' ),
			'before_widget' => '<div id="%1$s" class="widget widget-border %2$s">',
			'after_widget' => '</div>',
			'before_title' =>'<h2 class="widget-title maincolor2">',
			'after_title' => '</h2>',
        )
    );
}
add_action( 'widgets_init', 'my_custom_sidebar' );


add_action( 'wp_ajax_getVideoCrowdfundingDuration', 'getVideoCrowdfundingDuration' );
add_action( 'wp_ajax_nopriv_getVideoCrowdfundingDuration', 'getVideoCrowdfundingDuration' );
?>