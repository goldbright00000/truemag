<?php
/* Template Name: vote */ 
get_header();
?>
<div class="row">
	<div class="container">
	<?php 
	$latest = get_posts("post_type=post&numberposts=1");
	$latest_post= $latest[0]->ID;
	 global $wpdb;
	$post_title=get_the_title( $latest_post );
	$states = $wpdb->get_results("SELECT * FROM vote_system WHERE post_id=$latest_post");
	echo'<table class="voting_list">';
	echo'<thead>';
	echo'<tr>';
	echo'<th>Video Title</th>';
	echo'<th>Username</th>';
	echo'<th>Email</th>';
	echo'<th>Vote</th>';
	echo'<tr>';
	echo'</thead>';
	echo'<tbody>';
	foreach ( $states as $state ) {
		echo'<tr>';
		echo'<td>'. $post_title.'</td>';
		$user_id = $state->user_id;
		$all_meta_for_user = get_user_meta( $user_id );
		echo '<td>'.$all_meta_for_user['first_name'][0].'</td>';
		$user_info = get_userdata($user_id);
		echo '<td>'.$user_info->user_email.'</td>';
		echo '<td>'. $state->vote.'</td>';
		echo'</tr>';
	}
	echo'</tbody>';
	echo'</table>';
	?>
	</div>
</div>
<?php get_footer(); ?>