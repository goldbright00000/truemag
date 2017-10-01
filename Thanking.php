<?php
//echo "hello";
session_start();
/*
 Template Name: Thanks Page
*/
 get_header();
?>
<script type="text/javascript">
 jQuery(document).ready(function() {
  jQuery('.page-template-Thanking').hide();
 // alert('hi');

 });
</script>
<style>
.thank > h2 {
  margin: 20% 0;
}
.page-template-Thanking {
  display: none;
}
</style>
<div class="container">
	<div class="thank">
<h2><?php $user_id = get_current_user_id();
$user_info = get_userdata($user_id );
  
if (isset($_GET['ms_relationship_id']) && $user_id>0) {
	$user_login= $user_info->user_login;
   // echo 'You are logged in as user '.$user_id;
   $invite_by=$_SESSION['invite_by'];
   $user_login=$user_login.' by facebook';
    global $wpdb;
    $today=date("Y-m-d");
    $row3= $wpdb->get_row( "SELECT * FROM  point_editable WHERE id ='3'");
    $invited_guest=$row3->points;
    $data_array = array( 'user_id'=> $invite_by ,'type'=>'Invite Friend','femail'=>$user_login,'points'=>$invited_guest,'date'=>$today);
     $amount_insert= $wpdb->insert( 'free_points_details', $data_array);
     if($amount_insert){
     	 $get_points = $wpdb->get_row( "SELECT * FROM  interactive_system where user_id='$invite_by'" );
        $currentPoints=$get_points->points;
        $totalPoints= $currentPoints+$invited_guest;
        $allowed = $wpdb->query("UPDATE interactive_system SET points = '$totalPoints' WHERE user_id = '$invite_by'");
        if($allowed){
        	session_destroy(); 
        }

     }
} 
echo do_shortcode('[ms-note type="info"]'.'Thank you for signing up for Premise TV!
We look forward to bringing you the latest content.'.'[/ms-note]'); 
$url= site_url().'/members/'.bp_core_get_username( $user_id ).'/profile/edit/';


?></h2>
</div></div>
<?php get_footer(); wp_redirect( $url );?>