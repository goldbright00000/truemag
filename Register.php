<?php
if (!is_user_logged_in()) {

    session_start();
        if(!empty($_GET['invite_by'])){
       $_SESSION['invite_by'] = $_GET['invite_by'];
   }
      // print_r($_SESSION);
   }
/*
 Template Name: Register Page
*/
 get_header();
?>
<div class="container">

<?php 
 
 
echo do_shortcode('[ms-membership-register-user membership_id="14203"]');
$_SESSION['invite_by']; ?>

</div>
<?php get_footer();?>