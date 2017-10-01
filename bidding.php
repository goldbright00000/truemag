<?php
/* Template Name: Bidding */ 
get_header();
?>
<div class="row">
	<div class="container">

	 <h2 style="font-size: 20px;"><center>Bidding</center></h2>
            <table align="center" class ="table_1">
            <tr>
                <th>S.No</th>
                <th>User Name</th>
                <th>Pledge Name</th>
                 <th>Amount</th>
                
            </tr>
<?php


	$user = get_current_user_id();
$args = array(
     'author'   => $user,
    'post_type'   =>  'product',
    
    'order'       =>  'DESC',
             'tax_query'     => array(
            array(
            'taxonomy'  => 'product_cat',
            'field'     => 'id',
            'terms'     => array(167)
        ),
    ),
    );
    
    $loop = new WP_Query( $args );
   while ( $loop->have_posts() ) : $loop->the_post(); global $product; 
   $ids[]=get_the_ID();
    endwhile;
    wp_reset_query(); 
    //print_r($ids);

	 // print_r($user);
    if(!empty($ids)){
    $ids = join("','",$ids);   
     global $wpdb;
    $table = 'bidding';
    $retrieve_data = $wpdb->get_results( "SELECT * FROM  $table WHERE pledge_id IN ('$ids') order by amount desc");//print_r($retrieve_data);

   // print_r($retrieve_data);
 $i=0;
     foreach( $retrieve_data as $get_data){$i++;?>

 <tr>
                <td><?php echo $i;?></td>
                <td><?php  $usr=$get_data->user_id; $user_info = get_userdata($usr); echo $user_info->user_login;?></td> 
                <td><?php $plg= $get_data->pledge_id; echo get_the_title( $plg)?></td>
                <td><?php echo $get_data->amount;?></td>
              
            </tr>
  <?php   }}
   ?>
	
</table>
</div>















	
	</div>
</div>
<?php get_footer(); ?>

<style>

<style>
    .table_1{ border:0px solid #ccc; width:100%;  margin: 10px 0;} 
    /*.table_1 tr:nth-child(2n+0){background: #f1f1f1;}*/
    .table_1 th{text-align: center;font-size: 17px;}
    .table_1 td{ border:2px solid #ccc; padding:12px;} 
    .table_1 th{ border:2px solid #ccc; padding:12px; background:#4db59b; color:#fff; font-weight:bold;}
</style>