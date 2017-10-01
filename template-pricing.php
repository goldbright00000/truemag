<?php 
/* This is default template for page: Right Sidebar 
 * Template name:Pricing
 * Check theme option to display default layout
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
get_header();
if(!is_front_page()&&!is_page_template('page-templates/front-page.php')){
$topnav_style = ot_get_option('topnav_style','dark');	
?>

<?php } ?>
<style>
.package{max-width:245px;
margin:auto;
  margin-bottom:30px;
color:#ffdf4f;
text-align:center;
font-size: 26px;
letter-spacing: 2px;    font-family: 'berlinregular';
border: 2px solid;
padding: 20px;
background: rgba(0, 0, 0, 0.57);
position:relative;}
.package:hover{background: rgba(255, 255, 255, 0.57);}
.package h3{font-family: 'berlin_boldbold';color:#ffdf4f;font-size:35px;}</style>
    <div id="body" style="background:url(<?php bloginfo('template_url');?>/images/prcing_bg.jpg);background-size:cover;background-position:center;">
    	<div class="profile_bg"></div>
        <div class="container">
            <div class="row">
              <h2 style="text-align:center;color:#fff;font-family: 'berlin_xboldxbold';letter-spacing: 3px;font-size: 50px;margin: 50px 0px;padding-top: 50px;">Promotion: Double Silvers, Triple Points</h2>
             <a href="http://www.premise.tv/my-account/wallet/"> <div class="col-md-3 col-sm-6">
				<div class="package">
					<span style="font-size:25px;">PACKAGE A</span>
					<h3>USD $20</h3>
					20<img src="<?php bloginfo('template_url');?>/images/gold.png" style="max-width:25px;position: relative;top: -5px;"> ,10<img src="<?php bloginfo('template_url');?>/images/silver.png" style="max-width:25px;position: relative;top: -5px;"><br/>
					<span style="font-size:12px;">(40 points)</span><br/>
					+<br/>
					10<img src="<?php bloginfo('template_url');?>/images/silver.png" style="max-width:25px;position: relative;top: -5px;"> ,20 pts
				</div>
               </div></a>
			  <a href="http://www.premise.tv/my-account/wallet/"><div class="col-md-3 col-sm-6">
				<div class="package">
					<span style="font-size:25px;">PACKAGE B</span>
					<h3>USD $40</h3>
					40<img src="<?php bloginfo('template_url');?>/images/gold.png" style="max-width:25px;position: relative;top: -5px;"> ,20<img src="<?php bloginfo('template_url');?>/images/silver.png" style="max-width:25px;position: relative;top: -5px;"><br/>
					<span style="font-size:12px;">(80 points)</span><br/>
					+<br/>
					20<img src="<?php bloginfo('template_url');?>/images/silver.png" style="max-width:25px;position: relative;top: -5px;"> ,40 pts
				</div>
          </div></a>
			  <a href="http://www.premise.tv/my-account/wallet/"><div class="col-md-3 col-sm-6">
				<div class="package">
					<img src="<?php bloginfo('template_url');?>/images/bestvalue.png" style="position:absolute;max-width:78px;top:-35px;right:-35px;"><span style="font-size:25px;">PACKAGE C</span>
					<h3>USD $60</h3>
					60<img src="<?php bloginfo('template_url');?>/images/gold.png" style="max-width:25px;position: relative;top: -5px;"> ,30<img src="<?php bloginfo('template_url');?>/images/silver.png" style="max-width:25px;position: relative;top: -5px;"><br/>
					<span style="font-size:12px;">(120 points)</span><br/>
					+<br/>
					30<img src="<?php bloginfo('template_url');?>/images/silver.png" style="max-width:25px;position: relative;top: -5px;"> ,60 pts
				</div>
          </div></a>
			  <a href="http://www.premise.tv/my-account/wallet/"><div class="col-md-3 col-sm-6">
				<div class="package">
					<span style="font-size:25px;">PACKAGE D</span>
					<h3>USD $100</h3>
					100<img src="<?php bloginfo('template_url');?>/images/gold.png" style="max-width:25px;position: relative;top: -5px;"> ,50<img src="<?php bloginfo('template_url');?>/images/silver.png" style="max-width:25px;position: relative;top: -5px;"><br/>
					<span style="font-size:12px;">(120 points)</span><br/>
					+<br/>
					50<img src="<?php bloginfo('template_url');?>/images/silver.png" style="max-width:25px;position: relative;top: -5px;"> ,100 pts
				</div>
          </div></a>
			  <div style="clear:both;"></div>
              <h2 style="text-align:center;color:#fff;font-family: 'berlin_xboldxbold';font-size: 40px;margin: 50px 0px;letter-spacing: 3px;">For Content Creators</h2>
			  
				<div class="package">
					<span style="font-size:25px;">MARKETPLACE</span>
					<h3>5%</h3>
					<span style="font-size:12px;">platform fees</span><br/><br/>
					3.4% + 50c<br/>
					<span style="font-size:12px;">third-party credit card fees</span>
				</div>
				<div style="clear:both;height:60px;"></div>
            </div><!--/row-->
        </div><!--/container-->
    </div><!--/body-->
<?php get_footer(); ?>