<?php 
/*
 * Template Name: Static Page
 */

global $global_page_layout;
$layout = get_post_meta(get_the_ID(),'sidebar',true);
if(!$layout){
	$layout = $global_page_layout ? $global_page_layout : ot_get_option('page_layout','right');
}

if(function_exists('bp_current_component') && bp_current_component()){
	$layout = ot_get_option('buddypress_layout','right');
}elseif(function_exists('is_bbpress') && is_bbpress()){
	$layout = ot_get_option('bbpress_layout','right');
}
global $sidebar_width;
global $post;
global $static_bool;
$static_bool = TRUE;
get_header();
if(!is_front_page()&&!is_page_template('page-templates/front-page.php')){
$topnav_style = ot_get_option('topnav_style','dark');	

?>

<?php } ?>
    <div id="body" style="background-image: url('http://www.premise.tv/wp-content/uploads/2017/06/bg.jpg')">
    	<div class="profile_bg"></div>
        <div class="container">
            <div class="row">
            	<h2>Promotion: Double Silver's, Triple Points</h2>
            	<div class="col-md-3 package_block">
            		<h3>PACKAGE A</h3>
            		<span class="bold_span">USD $20</span><br />
            		<span>20 <img src="http://www.premise.tv/wp-content/uploads/2017/06/gold.png">, 10 <img src="http://www.premise.tv/wp-content/uploads/2017/06/silver.png"></span><br />
            		<span class="light_span">(40 points)</span><br />
            		<span>+</span><br />
            		<span>10 <img src="http://www.premise.tv/wp-content/uploads/2017/06/silver.png">, 20 pts</span>
            	</div>
            	<div class="col-md-3 package_block">test</div>
            	<div class="col-md-3 package_block">test</div>
            	<div class="col-md-3 package_block">test</div>
            	<br />
            	<h2>For content creators</h2>
            	<div class="package_block">test</div>
            </div>
        </div><!--/container-->
    </div><!--/body-->
<?php get_footer(); ?>