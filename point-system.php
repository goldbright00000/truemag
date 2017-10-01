<?php
/*
Template name: Point System
*/ 
get_header();
?>
<h2 style="font-size: 30px;"><center>Points System</center></h2>
 <div style="text-align: center; margin: 0px auto; width: 55%;">
      	<table class="table_1">
        	<tbody><tr>
		        <th>Your Points</th>
		        <th>Your level</th></tr>
		        <tr><td><?php $user_id = get_current_user_id();
					if ($user_id) {

						 global $wpdb;
						 $upgarde ='interactive_system';
						 $row = $wpdb->get_row( "SELECT * FROM  interactive_system WHERE user_id ='$user_id'" );
			             $free=$row->points; 
			             $transaction_points=$row->transaction_points; 
			             $gold=$row->gold_points;
			             $cal=$free+$gold*2;
			             $retrieve_data = $wpdb->get_results( "SELECT * FROM  points WHERE type='send' AND user_id ='$user_id' AND status='1'");
			             $send_points=0;
			             foreach ($retrieve_data as $retrieved_data){
			             	
			                 $send_points+=$retrieved_data->amount;	
			             }
			             $send_points;
			             $retrieve_data2 = $wpdb->get_results( "SELECT * FROM  points WHERE recevier ='$user_id' AND status='1'");
			             $receve_points=0;
			             foreach ($retrieve_data2 as $retrieved_data2){
			             	
			                $receve_points+=$retrieved_data2->amount;	
			             }
			            $receve_points;
			         //  echo  $Totel=$cal+$receve_points-$send_points;
			             echo  $Totel=$cal+$transaction_points+$receve_points-$send_points;
					  
					}?></td>
					<td><?php  echo $level=floor ($Totel/100);  //$transaction_points/100)
					         /*if($level>10){ echo 'Unlimited';}
					         	else{echo $level;}*/?></td>
		       
			</tr></tbody></table>
		<h3 style="font-size: 20px;"><center>Used Points</center></h3>
		<table class="table_1">
        	<tbody><tr><th>Type</th><th>Name</th><th>Date</th><th>Points</th></tr>

		<?php 
             $retrieve_data = $wpdb->get_results( "SELECT * FROM  points WHERE user_id ='$user_id' AND status='1'");
			             $send_points=0;
			             foreach ($retrieve_data as $retrieved_data){
			             	$user_info = get_userdata($retrieved_data->recevier); 
			             	$first_name = $user_info->first_name;
			             	echo '<tr>';
			                    echo '<td>'.$retrieved_data->type.'</td>';
			                    echo '<td>'.$first_name.'</td>';
			                    echo '<td>'.$retrieved_data->date.'</td>';
			                    echo '<td>'.$retrieved_data->amount.'</td>';
			                echo '</tr>';
			             }
		?>
		</tbody></table>
		<h3 style="font-size: 20px;"><center> Receive Points</center></h3>
		<table class="table_1">
        	<tbody><tr><th>From</th><th>Date</th><th>Points</th></tr>

		<?php 
             $retrieve_data = $wpdb->get_results( "SELECT * FROM  points WHERE recevier ='$user_id'  AND status='1'");
			             $send_points=0;
			             foreach ($retrieve_data as $retrieved_data){
			             	$user_info = get_userdata($retrieved_data->user_id); 
			             	$first_name = $user_info->first_name;
			             	echo '<tr>';
			                    
			                    echo '<td>'.$first_name.'</td>';
			                    echo '<td>'.$retrieved_data->date.'</td>';
			                    echo '<td>'.$retrieved_data->amount.'</td>';
			                echo '</tr>';
			             }
		?>
		</tbody></table>
		<div class="wallet-transactions-wrapper">
				<h4 style="font-size: 20px;" >Points transfer</h4>
				<form  style="width:95%;" action="" method="POST">
					<input name="transfer_points" placeholder="Points" type="text" required>

					<input name="user_email" placeholder="Email" type="email" required>  
				   <input type="submit" value="Send Points" class="add_wallet_money_button" name="send_money" />


				</form>
				<?php 
				if(isset($_POST['send_money']))
				{
					$sender= get_current_user_ID();
					$Amount=$_POST['transfer_points'];
					$email=$_POST['user_email'];
					$today=date("Y-m-d");
					if ($Totel >=$Amount ) {
						$user = get_user_by( 'email', $email);
                        $TransferId = $user->ID;
                        if($TransferId)
                            { 
                          	global $wpdb;
                        	$table_name = "points";
                            $data_array = array('user_id'=>$sender,'type'=>'send','recevier'=>$TransferId,'status'=>'1','amount'=>$Amount,'date'=>$today);
                               if($wpdb->insert( $table_name, $data_array)){
                                echo '<div style="display: block;" class="wpcf7-response-output wpcf7-display-none wpcf7-validation-errors">Successfully Transfer.</div>';}
                            }
                        else
                             {
                             	$invite_email=$email;
                              	$user_id=$sender;
                 	            global $wpdb;
                                $table_name2 = "413_invite_friends";
                                $check_vote = $wpdb->get_var("select count(*) FROM 413_invite_friends where invite_email='".$invite_email."'");
			                     if($check_vote==0){
			                        $data_array2 = array( 'invitator_id'=>$user_id,'invite_email'=>$invite_email,'points'=>$Amount);
			                        $wpdb->insert( $table_name2, $data_array2);

			                        $header .= "MIME-Version: 1.0\n";
								    $header .= "Content-Type: text/html; charset=utf-8\n";
								    $message .= "Open this link. <a href=\"#\">premise.com</a>";

								    $subject = "Invite Friend Get Points";
								    $to = $invite_email;
			                        mail($to, $subject, $message, $header);
			                        
					               echo '<div style="display: block;" class="wpcf7-response-output wpcf7-display-none wpcf7-validation-errors">Successfully Send Invite requst also send points.</div>';
					             } 
					                 else {
                 	                 echo '<span class="wpcf7-not-valid-tip">This Email ID already Used.</span>';
                 }

                       	
                          	//echo '<span class="wpcf7-not-valid-tip" role="alert">User Not Exist.</span>';
                          }
					   	
					   }
					else
					    {
						 echo '<span class="wpcf7-not-valid-tip" role="alert">You are currently unable to transfer due to insufficient balance.</span>';
						}
					
				}


				?>

			</div>

	</div>
<style>
	.table_1{ border:0px solid #ccc; width:100%; margin:10px 0px 10px 0px;} 
	.table_1 tr:nth-child(2n+0){background: #f1f1f1;}
	.table_1 th{text-align: center;font-size: 17px;}
	.table_1 td{ border:2px solid #ccc; padding:12px;} 
	.table_1 th{ border:2px solid #ccc; padding:12px; background:#f9c73d; color:#fff; font-weight:bold;}
</style>

<?php get_footer();?>