<?php
/* Template Name: edit content Creator Page */ 
?>

<?php get_header();?>
<?php if (!is_user_logged_in() ) {
 wp_redirect(home_url());
}
?>
<script>
	jQuery(document).ready(function(){
		jQuery('ul.tabs li').click(function(){
			var tab_id = jQuery(this).attr('data-tab');
			jQuery('ul.tabs li').removeClass('current');
			jQuery('.tab-content').removeClass('current');
			jQuery(this).addClass('current');
			jQuery("#"+tab_id).addClass('current');
		});
	});
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
	padding: 12px 15px;
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
 .tab_video_merch li {
  text-align: center;
  width: 24%;
}
 .video_merch_contant {
  background: transparent none repeat scroll 0 0 !important;
  border: 1px solid #ededed;
  margin-bottom: 20px;
}
ul.tabs li.current {
  background: #ededed none repeat scroll 0 0;
  border-bottom: 2px solid #f9c73d;
  color: #222;
}
.tab_video_merch {
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
.manage_btn {
  background: #f9c73d none repeat scroll 0 0;
  border: 1px solid #f9c73d;
  border-radius: 3px;
  color: #fff;
  display: inline-block;
  line-height: 1.5;
  margin: 26px 0;
  outline: medium none;
  padding: 7px 18px;
  text-align: center;
  transition: all 0.2s ease 0s;
}
.voting_list img {
  max-width: 150px;
}

            </style>
<div class="container">
	<div class="row">
	<div class="col-md-8 col-md-offset-2">
		<ul class="tabs tab_video_merch">
			<li class="tab-link current" data-tab="tab-1">Manage Video</li>
			<li class="tab-link" data-tab="tab-2">Manage Merchandise</li>
			<li class="tab-link" data-tab="tab-3">Manage Pledge</li>
			<li class="tab-link" data-tab="tab-4">Manage Poll</li>
		</ul>
		<div id="tab-1" class="tab-content current video_merch_contant">
			<div class="col-md-12 col-sm-12 ">	
<?php 
		$login_id = get_current_user_id();
		//$retrieve_data = $wpdb->get_results( "SELECT * FROM 413_posts where post_status='pending' OR post_status='publish' OR post_status='draft' AND post_type='post' AND  && post_author=$login_id" );
		$arg = array(
				'posts_per_page'   => -1,
			    'author' => $login_id,
			    'post_type' => 'post',
			    'post_status' => array('publish', 'pending', 'draft' )
				);
		$retrieve_data = get_posts( $arg );
			// echo"<pre>";
		//print_r($retrieve_data);
		// die();
		echo'<table class="voting_list">';
		echo'<thead>';
		echo'<tr>';
		echo'<th>Title</th>';
		echo'<th>Video Image</th>';
		echo'<th>Status</th>';
		echo'<th>Edit</th>';
		echo'<th>Delete</th>';
		echo'</tr>';
		echo'</thead>';
		echo'<tbody>';
		foreach($retrieve_data as $data){
			echo'<tr id="'.$data->ID.'" >';
			//echo '<td>'.get_post_meta( $data->ID, 'tm_video_url', true ).'</td>';
			echo'<td>'.$data->post_title.'</td>';
			echo '<td> <a href="'.get_permalink($data->ID ).'"><img src="'.get_post_meta( $data->ID, '_video_thumbnail', true ).'" alt="" height="50" width="150"></a> </td>';
			echo '<td>'.$data->post_status.'</td>';
			echo '<td><a href="'.site_url().'/update-content/?edit='.$data->ID.'">Edit</a></td>';
			 //if( current_user_can( 'delete_post' ) ) : ?>
      		 <td> <a href="#" data-id="<?php echo $data->ID; ?>" data-nonce="<?php echo wp_create_nonce('my_delete_post_nonce') ?>" class="delete-post">delete</a></td>
    	<?php //endif ;
			echo'</tr>';
		}
			echo'</tbody>';
			echo'</table>';
		?>
		</div>
	</div>
	<div id="tab-2" class="tab-content video_merch_contant">
		<div class="col-md-12 col-sm-12 ">
		<?php 
		$args = array(
					'posts_per_page'   => -1,
				    'author' => $login_id,
				    'post_type' => 'product',
				    'post_status' => array('publish', 'pending', 'draft' ),
				    'product_cat' => 'merchandise',
				);
		$author_posts = get_posts( $args );

		//echo"<pre>";
	//print_r($author_posts);
		echo'<table class="voting_list">';
		echo'<thead>';
		echo'<tr>';
		echo'<th>Title</th>';
		echo'<th>Merchandise Image</th>';
		echo'<th>Status</th>';	
		echo'<th>Edit</th>';
		echo'<th>Delete</th>';
		echo'</tr>';
		echo'</thead>';
		echo'<tbody>';
          foreach ($author_posts as $author_post) {
      	  $attachment_ids[0] = get_post_thumbnail_id( $author_post->ID );
                         $attachment = wp_get_attachment_image_src($attachment_ids[0], 'full' );
    
			echo'<tr id="'.$author_post->ID.'">';
			echo '<td>'.$author_post->post_title.'</td>';
			echo '<td> <a href="'.get_permalink($author_post->ID ).'"><img src="'.$attachment[0].'" alt="" height="50" width="150"></a> </td>';
			//echo '<td><a href="'.get_permalink($author_post->ID ).'"><img height="50" width="150" src="'. get_the_post_thumbnail($author_post->ID).'" /></a></td>';
			echo '<td>'.$author_post->post_status.'</td>';				
			echo '<td><a href="'.site_url().'/update-content/?edit='.$author_post->ID.'">Edit</a></td>';
				
			// if( current_user_can( 'delete_post' ) ) : ?>
       <td> <a href="#" data-id="<?php echo $author_post->ID; ?>" data-nonce="<?php echo wp_create_nonce('my_delete_post_nonce') ?>" class="delete-product">delete</a></td>
    <?php //endif ;
			echo'</tr>';
          }
			echo'</tbody>';
		echo'</table>';
		?>
		</div>
	</div>
	

<!--pledge update code start here-->
	<div id="tab-3" class="tab-content video_merch_contant">
		<div class="col-md-12 col-sm-12 ">
		<?php 
		$args = array(
					'posts_per_page'   => -1,
				    'author' => $login_id,
				    'post_type' => 'product',
				    'post_status' => array('publish', 'pending', 'draft' ),
				    'tax_query' => array(array(
			        'taxonomy' => 'product_cat',
			        'field' => 'slug',
			        'terms' => array('bid','ballot'),
			        'operator' => 'IN'
			        ) )
         );
		$author_posts = get_posts( $args );


		//echo"<pre>";
		//print_r($author_posts);
		echo'<table class="voting_list">';
		echo'<thead>';
		echo'<tr>';
		echo'<th>Title</th>';
		echo'<th>Description</th>';
		echo'<th>Status</th>';	
		echo'<th>Edit</th>';
		echo'<th>Delete</th>';
		echo'</tr>';
		echo'</thead>';
		echo'<tbody>';
          foreach ($author_posts as $author_post) {
      	  $attachment_ids[0] = get_post_thumbnail_id( $author_post->ID );
                         $attachment = wp_get_attachment_image_src($attachment_ids[0], 'full' );
    
			echo'<tr id="'.$author_post->ID.'">';
			echo '<td>'.$author_post->post_title.'</td>';
			echo '<td>'.$author_post->post_content.'</td>';
			//echo '<td> <a href="'.get_permalink($author_post->ID ).'"><img src="'.$attachment[0].'" alt="" height="50" width="150"></a> </td>';
			//echo '<td><a href="'.get_permalink($author_post->ID ).'"><img height="50" width="150" src="'. get_the_post_thumbnail($author_post->ID).'" /></a></td>';
			echo '<td>'.$author_post->post_status.'</td>';				
			echo '<td><a href="'.site_url().'/pledge-content/?edit='.$author_post->ID.'">Edit</a></td>';
				
			// if( current_user_can( 'delete_post' ) ) : ?>
       <td> <a href="#" data-id="<?php echo $author_post->ID; ?>" data-nonce="<?php echo wp_create_nonce('my_delete_post_nonce') ?>" class="delete-product">delete</a></td>
    	<?php //endif ;
			echo'</tr>';
          }
			echo'</tbody>';
		echo'</table>';
		?>
		</div>
	</div>
	<!--poll update code start here-->
	<div id="tab-4" class="tab-content video_merch_contant">
		<div class="col-md-12 col-sm-12 ">
		<?php 
		$arg = array(
				'posts_per_page'   => -1,
			    'author' => $login_id,
			    'post_type' => 'poll',
			    'post_status' => array('publish', 'pending', 'draft' )
				);
		$retrieve_data = get_posts( $arg );
		//echo"<pre>";
		//print_r($retrieve_data);
		// die();
		echo'<table class="voting_list">';
		echo'<thead>';
		echo'<tr>';
		echo'<th>Title</th>';
		echo'<th>Description</th>';
		echo'<th>Status</th>';	
		echo'<th>Edit</th>';
		echo'<th>Delete</th>';
		echo'</tr>';
		echo'</thead>';
		echo'<tbody>';
		foreach($retrieve_data as $data){
			echo'<tr id="'.$data->ID.'" >';
			//echo '<td>'.get_post_meta( $data->ID, 'tm_video_url', true ).'</td>';
			echo'<td>'.$data->post_title.'</td>';
			echo '<td>'.$data->post_content.'</td>';
			echo '<td>'.$data->post_status.'</td>';
			echo '<td><a href="'.site_url().'/poll-content/?edit='.$data->ID.'">Edit</a></td>';
			 //if( current_user_can( 'delete_post' ) ) : ?>
      		 <td> <a href="#" data-id="<?php echo $data->ID; ?>" data-nonce="<?php echo wp_create_nonce('my_delete_post_nonce') ?>" class="delete-post">delete</a></td>
    	<?php //endif ;
			echo'</tr>';
		}
			echo'</tbody>';
			echo'</table>';
		?>
		</div>
	</div>

	</div>
	<div class="add_content"><a href="<?php echo site_url();?>/addcontent/" class="manage_btn">Add Content</a></div>
</div>
</div>
<?php get_footer(); ?>