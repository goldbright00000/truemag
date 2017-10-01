<?php
	// Navigation part of template
	$topnav_style = ot_get_option('topnav_style','dark');
	$topnav_layout = ot_get_option('topnav_layout');
	$fixed = ot_get_option('topnav_fixed');
	$search_icon = ot_get_option('search_icon');
	$using_yt_param = ot_get_option('using_yout_param');
?>
	<?php tm_display_ads('ad_top_1');?>
		<?php if(ot_get_option('disable_mainmenu') != "1"){
		ob_start(); //get top nav html
		if (is_user_logged_in()) {
			$login_user = get_current_user_id();
		?>
		<div class="user_head">
			<div class="container-fluid">
				<div class="row">
                    <div class="col-md-4 clearfix">
                        <button type="button" class="custom-btn" >
                            <span class="sr-only">Toggle navigation</span>
                            <i class="fa fa-reorder fa-bars fa-lg"></i>
                        </button>
                    </div>
					<div class="col-md-8">
						<div class="inner_user">
							<ul>
								<li>
									<?php
									 $current_amount = get_user_meta($login_user, 'wallet-amount', true);
                                     if($current_amount>0){
                                      echo $gold=$current_amount;
                                      }
								        else{
								        	echo '0';
								      }


									?><img src="http://www.premise.tv/wp-content/uploads/2017/06/gold.png" /></li>
								<li>
								<?php
								global $wpdb;
                                $table = 'interactive_system';
                                $retrieve_data = $wpdb->get_results( "SELECT * FROM  $table WHERE user_id = '" . $login_user . "'");//print_r($retrieve_data);
                                 if(!empty($retrieve_data)){
                                  echo $silver=$retrieve_data[0]->points;
                                 }
                                 else{
                                 echo '0';
                                  }
								?>
								 <img src="http://www.premise.tv/wp-content/uploads/2017/06/silver.png" /></li>
								<li>Level<span class="lvl_rank">
                                <?php
                                global $wpdb;
								 $upgarde ='interactive_system';
								 $row = $wpdb->get_row( "SELECT * FROM  interactive_system WHERE user_id ='$login_user'" );
					             $free=$row->points;
					             $transaction_points=$row->transaction_points;
					             $gold=$row->gold_points;
					             $cal=$free+$gold*2;
					             $retrieve_data = $wpdb->get_results( "SELECT * FROM  points WHERE type='send' AND user_id ='$login_user' AND status='1'");
					             $send_points=0;
					             foreach ($retrieve_data as $retrieved_data){

					                 $send_points+=$retrieved_data->amount;
					             }
					             $send_points;
					             $retrieve_data2 = $wpdb->get_results( "SELECT * FROM  points WHERE recevier ='$login_user' AND status='1'");
					             $receve_points=0;
					             foreach ($retrieve_data2 as $retrieved_data2){

					                $receve_points+=$retrieved_data2->amount;
					             }
					            $receve_points;
					            $Totel=$cal+$transaction_points+$receve_points-$send_points;


							echo $level=floor ($Totel/100);
							 global $bp;
                                ?>

								</span></li>
								<!--<li>Lvl Emblem <img src="http://www.premise.tv/wp-content/uploads/2017/06/silver.png" /></li>-->
								<li class="user_profileDetail"><a href="<?php echo site_url();?>/members/<?php echo bp_core_get_username( $login_user );?>/media/ "><span class="user_name"><?php  echo bp_core_get_username( $login_user );?></span><?php global $bp;
                                echo bp_core_fetch_avatar ( array( 'item_id' => $login_user, 'type' => 'small' ) ); ?></a>
									<div class="profileDropdown">
										<div class="profile_img">
											<?php   echo get_avatar( $login_user, 200); ?>
										</div>
										<div class="text_detail">
											<ul>
												<li class="name_usr"><? echo bp_core_get_username( $login_user );?></li>
												<li class="rank_usr">Level <span><?php echo $level;?></span></li>
												<li><span class="rank_gold"><?php echo $gold;?> <img src="http://www.premise.tv/wp-content/uploads/2017/06/gold.png"></span><span class="rank_silver"><?php echo $silver;?><img src="http://www.premise.tv/wp-content/uploads/2017/06/silver.png"></span></li>
											</ul>
										</div>
										<h1><a href="http://www.premise.tv/my-account/wallet/"><i class="fa fa-plus" aria-hidden="true"></i>Top up now</a></h1>
										<div class="profile_link">
											<ul>
												<li><a href="http://www.premise.tv/my-account/wallet/"><img src="http://www.premise.tv/wp-content/uploads/2016/10/Invite-Friends.png"> <span>invite friends</span></a></li>
												<li><a href="<?php echo site_url();?>/members/<?php echo bp_core_get_username( $login_user );?>/media/ "><img src="http://www.premise.tv/wp-content/uploads/2016/10/Profile.png"> <span>Edit profile</span></a></li>
												<li><a href="http://www.premise.tv/credit_system/"><img src="http://www.premise.tv/wp-content/uploads/2016/10/History.png"> <span>Transaction History</span></a></li>
												<li><a href="http://www.premise.tv/point-system/"><img src="http://www.premise.tv/wp-content/uploads/2016/10/Transfers.png"> <span>Transfer Points</span></a></li>
												<li><a href="http://www.premise.tv/content-creator-dashboard/"><img src="http://www.premise.tv/wp-content/uploads/2016/10/Pledges.png"> <span>Pledges</span></a></li>
												<li><a href="<?php echo wp_logout_url(  home_url() ); ?>"><i class="fa fa-sign-out"></i><span>LogOut</span></a></li>
											</ul>
										</div>
									</div>
								</li>
							<ul>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php }?>
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
                                        <li><a href="<?php echo wp_logout_url( home_url() ); ?>"><?php _e('Logout','cactusthemes') ?></a></li>
                                    </ul>
                                    </li>
                            	</ul>
                            </div>
                        <?php } ?>

                       <?php
	          			global $wpdb;
	          			$current_user = wp_get_current_user();
	          			$user_id=$current_user->ID;
	          			$check_video_form = $wpdb->get_var("select count(*) FROM 413_submit_video where userid='".$user_id."'");
						?>

         				<?php
						    if(ot_get_option('user_submit')==1) {
								$text_bt_submit = ot_get_option('text_bt_submit');
								if($text_bt_submit==''){ $text_bt_submit = 'Submit Video';}
								if(ot_get_option('only_user_submit',1)){
									if(is_user_logged_in()){?>
                                    <ul class="nav navbar-nav navbar-right hidden-xs user_submit">
                                    	<li class="main-menu-item">
                                            <a class="submit-video" href="<?php  echo wp_logout_url(home_url());?>" ><span class="btn btn-xs bgcolor1">Logout</span></a>
                                        </li>
                                        <li class="main-menu-item">

                                            <a class="submit-video" href="#" data-toggle="modal" data-target="#submitModal"><span class="btn btn-xs bgcolor1"><?php _e($text_bt_submit,'cactusthemes'); ?></span></a>
                                        </li>
									 </ul>
                       			<?php }
								} else{
									if($check_video_form){?>
                                    <ul class="nav navbar-nav navbar-right hidden-xs user_submit">

                                        <li class="main-menu-item">
                                            <a class="submit-video" href="http://premise.tv/addcontent/" data-toggle="modal" ><span class="btn btn-xs bgcolor1"><?php _e($text_bt_submit,'cactusthemes'); ?></span></a>
                                        </li><li class="main-menu-item">
                                            <a class="submit-video" href="<?php  echo wp_logout_url(home_url());?>" ><span class="btn btn-xs bgcolor1">Logout</span></a>
                                        </li>
                                    </ul>
                       			<?php
								} else if($user_id){
									echo '<ul class="nav navbar-nav navbar-right hidden-xs user_submit">

                                        <li class="main-menu-item">
                                            <a class="submit-video" href="#" data-toggle="modal"  data-target="#submitModal"><span class="btn btn-xs bgcolor1">'.$text_bt_submit.'</span></a>
                                        </li><li class="main-menu-item">
                                            <a class="submit-video" href="'.wp_logout_url(home_url()).'" ><span class="btn btn-xs bgcolor1">Logout</span></a>
                                        </li>
                                    </ul>';
								} else{
									echo '<ul class="nav navbar-nav navbar-right hidden-xs user_submit">

                                        <li class="main-menu-item">
                                            <a class="not_login" href="#" data-toggle="modal" ><span class="btn btn-xs bgcolor1">Submit Films</span></a>
                                        </li>
                                        <li class="main-menu-item">
                                            <a class="submit-video" href="'.site_url().'/registration/?step=register" ><span class="btn btn-xs bgcolor1">Sign up</span></a>
                                        </li>
                                        <li class="main-menu-item">
                                            <a class="submit-video" href="'.site_url().'/account/" ><span class="btn btn-xs bgcolor1">Login</span></a>
                                        </li>

                                    </ul>';
								}}
								if($limit_tags = ot_get_option('user_submit_limit_tag')){ ?>
                                <script>
                                jQuery(document).ready(function(e) {
                                    jQuery("form.wpcf7-form").submit(function (e) {
										if(jQuery("input[name=tag].wpcf7-form-control", this).length){
											var submit_tags = jQuery('input[name=tag].wpcf7-form-control').val().split(",");
											if(submit_tags.length > <?php echo $limit_tags ?>){
												if(jQuery('.limit-tag-alert').length==0){
													jQuery('.wpcf7-form-control-wrap.tag').append('<span role="alert" class="wpcf7-not-valid-tip limit-tag-alert"><?php _e('Please enter less than or equal to '.$limit_tags.' tags','cactusthemes') ?>.</span>');
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
						} ?>
                        <?php if($topnav_layout && $topnav_layout!='layout-4' && $search_icon!=1){ ?>
						<ul class="nav navbar-nav hidden-xs nav-search-box">
                            <li class="main-menu-item">
                            	<?php if ( is_active_sidebar( 'search_sidebar' ) ) : ?>
                                	<?php dynamic_sidebar( 'search_sidebar' ); ?>
								<?php else: ?>
                                    <form class="<?php echo $topnav_style=='light'?'light-form':''; ?> dark-form" action="<?php echo home_url() ?>">
                                        <div class="input-group">
                                            <input type="text" name="s" class="form-control" placeholder="<?php echo __('Search...','cactusthemes');?>">
                                            <span class="input-group-btn">
                                                <button class="btn btn-default maincolor1 maincolor1hover" type="submit"><i class="fa fa-search"></i></button>
                                            </span>
                                        </div>
                                    </form>
                                <?php endif; ?>
                            </li>
                        </ul>
                        <?php } ?>
						<ul class="nav navbar-nav nav-ul-menu <?php echo $topnav_layout?'':'navbar-right'; ?> hidden-xs">
						<?php
							$megamenu = ot_get_option('megamenu', 'off');
							if($megamenu == 'on' && function_exists('mashmenu_load')){
								mashmenu_load();
							}elseif(has_nav_menu( 'main-navigation' )){
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
                                if($megamenu == 'on' && function_exists('mashmenu_load')){
									global $in_mobile_menu;
									$in_mobile_menu = true;
									mashmenu_load();
									$in_mobile_menu = false;
								}elseif(has_nav_menu( 'main-navigation' )){
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
										<li class="menu-item"><a href="<?php echo wp_logout_url( home_url() ); ?>"><?php _e('Logout','cactusthemes') ?></a></li>
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
					<div class="user_logo"><?php $current_user = wp_get_current_user();
				if (is_user_logged_in()) {
					 $login_user = get_current_user_id();
					 $user_meta=get_userdata($login_user);
                     $user_roles=$user_meta->roles;
                    $role= $user_roles[0];

				}

				//if($role=='content_creator')
				//{
					//?>
           <!--  <style type="text/css">
             #requst_cc{display: none;}</style>-->

					<?php
				//}
			/*	global $wpdb;

                $tables = '413_role_request_form';

   $check_vote = $wpdb->get_var("select count(*) FROM 413_role_request_form where user_id='".$login_user."'");
     if($check_vote ==1){
     	echo ' <style type="text/css">
             #requst_cc{display: none;}</style>';

	}*/

			?></div>
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
			'vk'
		);
		global $static_bool;
		?>
		<?php if (!$static_bool){?>
        <div id="headline" class="<?php echo $topnav_style=='light'?'topnav-light light-div':'topnav-dark'; echo $fixed?' is-fixed-nav':''; echo ' '.$topnav_layout; ?>">
            <div class="container-fluid">
                <div class="row">
                	<?php if(is_front_page()||is_page_template('page-templates/front-page.php')){
						$count = 0;
						foreach($social_account as $social){
							if($link = ot_get_option('acc_'.$social,false)){
								$count++;
							}
						}
						if($search_icon==1 && $show_top_headline == 0 && $count == 0 ){
							 echo '<style scoped="scoped">#headline {height:0; overflow: hidden; opacity:0; border: 0;}</style>';
						}
					?>
                    <div class="headline-content col-md-3 col-sm-3 hidden-xs">
                    	<?php if ( is_active_sidebar( 'headline_sidebar' ) ) : ?>
							<?php dynamic_sidebar( 'headline_sidebar' ); ?>
                        <?php else:

								$number_item = ot_get_option('number_item_head_show');
								$icon_headline = ot_get_option('icon_headline');
								$title_headline = ot_get_option('title_headline');
								$cat= ot_get_option('cat_head_video');
								$headline_orderby = ot_get_option('headline_orderby','rand');
								if($show_top_headline!=0){
                             		echo do_shortcode('[headline link="yes" icon="'.$icon_headline.'" sortby="'.$headline_orderby.'" cat="'.$cat.'" posttypes="post" number="'.$number_item.'" title="'.$title_headline.'" ]');
								}?>
                        <?php endif; ?>
                    </div>
                    <?php }elseif(is_active_sidebar('pathway_sidebar')){
							echo '<div class="pathway pathway-sidebar col-md-6 hidden-xs">';
							dynamic_sidebar('pathway_sidebar');
							echo '</div>';
						}else{?>
                    <div class="pathway col-md-3 col-sm-3 hidden-xs">
                    	<?php if(function_exists('tm_breadcrumbs')){ tm_breadcrumbs(); } ?>
                    </div>
                    <?php } ?>
                    <div class="">
                    	<?php $id =get_the_ID(); ?>
                       	<div class="pull-left wrap_gud_bad_bar col-md-6 col-sm-6">
													<div class="countleft">
														<?php $latest_cpt = get_posts("post_type=post&numberposts=1");
	                        	$lp= $latest_cpt[0]->ID;
	                          $total_row = $wpdb->get_var("select count(*) FROM vote_system where vote='like'");
														echo $total_row
														?>
													</div>
	                        <div class="countright">
														<?php $latest_cpt = get_posts("post_type=post&numberposts=1");
	                        	$lp= $latest_cpt[0]->ID;
	                          $total_row = $wpdb->get_var("select count(*) FROM vote_system where vote='dislike'");
														echo $total_row
														?>
													</div>
													<div class="voting_bar" style="margin: 0;">
													<?php $latest_cpt = get_posts("post_type=post&numberposts=1");
                        	$lp= $latest_cpt[0]->ID;
                          $total_row = $wpdb->get_var("select count(*) FROM vote_system where vote='like'");
                          echo '<span class="vote_good">Good</span><span class="vote_like"> '.$total_row.'</span>
													<progress id="goodProgress" value="0" max="100">0%</progress>';
                          $total_row = $wpdb->get_var("select count(*) FROM vote_system where vote='dislike'");
                          echo '<strong class="main_post_vs"><span class="leftCut"></span>VS<span class="rightCut"></span></strong><progress id="evilProgress" value="0" max="100">0%</progress>'.'<span class="vote_like_evil"> '.$total_row.'</span><span class="vote_evil">Evil</span>';
													?>
												</div>
												</div>

                        <?php } else {?>
                        	<div id="static_headline">TEST</div>
                        <?php }?>
                    	<div class=" col-md-3 col-sm-3 social_right social-links">
                        <?php
						$file = get_post_meta($post->ID, 'tm_video_file', true);
						$url = trim(get_post_meta($post->ID, 'tm_video_url', true));
						$user_turnoff = ot_get_option('user_turnoff_load_next');
						$auto_load= ot_get_option('auto_load_next_video');
						$replay_state= ot_get_option('replay_state');
						if((strpos($file, 'youtube.com') !== false)&&($using_yt_param !=1) ||(strpos($url, 'youtube.com') !== false )&&($using_yt_param !=1) || (strpos($file, 'vimeo.com') !== false)||(strpos($url, 'vimeo.com') !== false )) {
						if(is_single()){
						?>
                            <div class="tm-autonext" id="tm-autonext">
                            <?php if($user_turnoff==1){?>
                            <span class="<?php if($replay_state!='on'){?>autonext<?php }?>" id="autonext" >
                                <a href="#" data-toggle="tooltip" title="<?php _e('Auto Next ON','cactusthemes') ?>" class="gptooltip turnoffauto" data-animation="true">
                                    <i class="fa fa-play"></i>
                                </a>
                            </span>
                            <script>
                            jQuery(document).ready(function(e) {
								var className = jQuery('#tm-autonext span#autonext').attr('class');
								if(className==''){
									jQuery('#tm-autonext .tooltip-inner').html('<?php _e('Auto Next OFF','cactusthemes') ?>');
									jQuery('#tm-autonext .gptooltip.turnoffauto').attr('title', '<?php _e('Auto Next OFF','cactusthemes') ?>');
									jQuery('#tm-autonext').toggle(function(){
										  jQuery('#tm-autonext span#autonext').addClass('autonext');
										  jQuery('#tm-autonext .tooltip-inner').html('<?php _e('Auto Next ON','cactusthemes') ?>');
										  jQuery('#tm-autonext .gptooltip.turnoffauto').attr('data-original-title', '<?php _e('Auto Next ON','cactusthemes') ?>');
									},
									function(){
										  jQuery('#tm-autonext span#autonext').removeClass('autonext');
										  jQuery('#tm-autonext .tooltip-inner').html('<?php _e('Auto Next OFF','cactusthemes') ?>');
										  jQuery('#tm-autonext .gptooltip.turnoffauto').attr('data-original-title', '<?php _e('Auto Next OFF','cactusthemes') ?>');
									});
								}else{
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
								}
                            });
                            </script>
                            <?php }?>
                            </div>
                        <?php
						}
						} ?>

                        <?php if (class_exists('Woocommerce')) {
                            global $woocommerce;
                            $cart_url = $woocommerce->cart->get_cart_url();
                            $checkout_url = $woocommerce->cart->get_checkout_url();
                            if($woocommerce->cart->get_cart_contents_count()){
                            ?>
                            <a href="<?php echo $cart_url;?>" class="social-icon shopping maincolor1"><i class="fa fa-shopping-cart"></i><?php echo ' ('.$woocommerce->cart->get_cart_contents_count(),')';?></a>
                        <?php } }?>


                        <?php
						$social_link_open = ot_get_option('social_link_open');
						foreach($social_account as $social){
							if($link = ot_get_option('acc_'.$social,false)){
								if($social=='envelope'){ ?>
                                	<a class="social-icon<?php echo $topnav_style=='dark'?' maincolor1 bordercolor1hover bgcolor1hover':'' ?>" href="mailto:<?php echo $link ?>" <?php if($social_link_open==1){?>target="_blank" <?php }?>><i class="fa fa-<?php echo $social ?>"></i></a>
                                <?php } else{?>
                        			<a class="social-icon<?php echo $topnav_style=='dark'?' maincolor1 bordercolor1hover bgcolor1hover':'' ?>" href="<?php echo $link ?>" <?php if($social_link_open==1){?>target="_blank" <?php }?>><i class="fa fa-<?php echo $social ?>"></i></a>
                        <?php } }
						}
						if($custom_acc = ot_get_option('custom_acc')){
							foreach($custom_acc as $a_social){ ?>
                                <a class="social-icon<?php echo $topnav_style=='dark'?' maincolor1 bordercolor1hover bgcolor1hover':'' ?>" href="<?php echo $a_social['link'] ?>" <?php if($social_link_open==1){?>target="_blank" <?php }?>><i class="fa <?php echo $a_social['icon'] ?>"></i></a>
							<?php }
						}
						?>
                        <?php
						if($search_icon!=1){ ?>
                        <a class="search-toggle social-icon<?php echo $topnav_style=='dark'?' maincolor1 bordercolor1hover bgcolor1hover':''; if($topnav_layout!=''&& $topnav_layout!='layout-4'){echo ' visible-xs';} ?>" href="#"><i class="fa fa-search"></i></a>
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
