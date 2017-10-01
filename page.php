<?php
/*
Template name: content creator dashboard
*/
get_header();
$user = get_current_user_id();
//echo $user;
?>

<div class="container main_wrap">
  <div style="width:22px;height:22px;background-color: #FBE35D;margin-top:26px;"></div>
  <h2 style="font: 30px Roboto;color: #23272C;margin-top: -18px; margin-left: -8px;">Pledges</h2>
  <div style="width: 270px;">
    <div style="border-bottom: 3px solid black;float: left;width: 24%;"></div>
    <div style="border-bottom: 1px solid black;float: left;width: 70%;margin-top: 2px;"></div>
    <br>
  </div>
  <?php 
    
    global $wpdb; 
    $table = "413_all_trasactions";
    $sum_gold=0;
    $sum_silver=0;
    $i = 0;
    $users = array();
    $flag = 1;
    if(isset($_POST['search'])){
        $search=$_POST['search_by'];
        $postid = $wpdb->get_var( "SELECT ID FROM $wpdb->posts WHERE post_title = '" . $search . "'" );
        $postid;
        $retrieve_data = $wpdb->get_results( "SELECT * FROM  $table where product_id= $postid"); 
    } else{
        $retrieve_data = $wpdb->get_results( "SELECT * FROM  $table"); 
    }
    foreach ($retrieve_data as $retrieved_data)
    {
        $order_id=$retrieved_data->order_id;
        $product_id=$retrieved_data->product_id;
        $user_id= $retrieved_data->user_id;
        $user_info = get_userdata($user_id);  
        $post_tmp = get_post($product_id);
        $author_id = $post_tmp->post_author;
        $single_amt=$retrieved_data->amount;
        if($author_id==$user)
        {
            $term_list = wp_get_post_terms($product_id, 'product_cat', array('fields' => 'ids'));
            $cat_id=$term_list[1];
            if($cat_id=='169' ||$cat_id=='170' ){
                if($retrieved_data->type=='gold'){
                    $sum_gold=$sum_gold+$single_amt;
                }
                if($retrieved_data->type=='silver'){
                    $sum_silver=$sum_silver+$single_amt;
                }               
                if ($user_info->user_email){     	
                  foreach ($users as $usered) {
                    if ($usered == $user_info->user_email) {
                        $flag = false;
                    }
                  }
                  if ($flag){
                      $users[$i] = $user_info->user_email;
                      $i++;                    
                  }
                }
              	$flag = true;
            }
        }
    }
    $sum = $sum_gold + $sum_silver;
    ?>
  <div class="tbl-wrap">
			<div style="margin-top: 20px;">
        <div style="float: left;border: 1px solid #F2F2F2;width:65%;height:64px;background-color:white;">
          <div style="height: 90px;">
              <div style="width: 100%;color: #23272c;font: 16px Roboto;font-weight: 600;">
                  <div class="sum">$<?php echo $sum;?></div>
                  <div class="sum"><?php echo $i;?></div>
                  <div class="sum"><?php echo $sum_gold;?></div>
                  <div class="sum"><?php echo $sum_silver;?></div>
              </div>
              <div style="width: 100%;font: 14px Roboto;color: #B2B2B2">
                  <div class="sum_footer">Total Amount Value</div>
                  <div class="sum_footer">Total users</div>
                  <div class="sum_footer">Gold</div>
                  <div class="sum_footer">Silver</div>
              </div>
          </div>
        </div>
        <div style="float: right;border: 1px solid #F2F2F2;width:33%;height:65px;background-color:white;">
          <div class="search_by"> 
              <form method="post" >
                <input type="text" value="" name="search_by" placeholder="Search By Pledge Name">
                <input type="submit" name="search" value="" />
              </form>
          </div>
        </div>
    </div>
    <br>
    <table class="table_1 transactions_tb">
      <form method="post">
        <tr>
          <th>Order ID</th>
          <th>OrderDate</th>
          <th>Username</th>
          <th>Email</th>
          <th>Pledge Name</th>
          <th>Type</th>
          <th>Amount</th>
          <th>Refund</th>
          <th>Selected</th>
          <th class="none_border">Feedback</th>
        </tr>
    <?php      
    
    foreach ($retrieve_data as $retrieved_data){
    $order_id=$retrieved_data->order_id;
    $product_id=$retrieved_data->product_id;
    $user_id= $retrieved_data->user_id; $user_info = get_userdata($user_id);  
    $post_tmp = get_post($product_id);
    $author_id = $post_tmp->post_author;
    $single_amt=$retrieved_data->amount;
    if($author_id==$user){
    $term_list = wp_get_post_terms($product_id, 'product_cat', array('fields' => 'ids'));
    $cat_id=$term_list[1];
    if($cat_id=='169' ||$cat_id=='170' ){
    if($retrieved_data->type=='gold'){
    $sum=$sum+$single_amt;
    }
    $i++;
    ?>
    <tr id="row_<?php echo $order_id;?>" >
      <td><?php echo '#'.$retrieved_data->order_id;?></td>
      <td><?php echo $odate=$retrieved_data->order_date;?></td>
      <td><?php echo $user_nicename = $user_info->user_nicename;?></td>
      <td><?php echo $email = $user_info->user_email;?></td>
      <td><?php $title= $retrieved_data->product_id; echo get_the_title($title);?></td>
      <td><?php echo $retrieved_data->type;?></td>
      <td><?php echo $retrieved_data->amount;?></td>
      <?php $check_refund=$retrieved_data->refund;
      $check_select=$retrieved_data->won;
      if($check_refund>0){
      echo '<td>Amount Refund</td>';echo '<td>Not Selected</td>';}
      else if($check_select>0){
      echo '<td>X</td><td>Won this pledge</td>';
      } else{
      echo '<td><input type="checkbox" class="chbox1" name="refund[]" value="'.$order_id.'" onclick="fun_check1()"></td>';
      echo '<td><input type="checkbox" class="chbox" name="won[]" value="'.$order_id.'" onclick="fun_check()"></td>';
      }
      $rowFeed = $wpdb->get_row( "SELECT * FROM  pledge_feedback WHERE o_time='$odate' AND user_id ='$user_id' AND product_id='$product_id'" );
      echo '<td class="none_border">'. $rowFeed->feedback.'</td>';
      }
      ?>

    </tr>
    <?php
    }
    }
    ?>
      <tr class="actionBtn">
        <td colspan="7" ></td>
        <td><input type="submit" class="refund2" name="refund2" value="Refund"/></td>
        <td><input type="submit" class="won2" name="won2" value="Selected"/></td>
      </tr>

     </form>
    </table>
    <div style="height: 140px;"></div>
  </div>
  <!--<div class="graph-side col-md-3">
    <div class="main_total">
      <div class="yellow_border border_left"></div>
      <div class="blue_border border_left"></div>
      <div class="lightgreen_border border_left"></div>
      <div class="green_border border_left"></div>
      <div class="total_text">
        <p>Total Amount Value</p>
        <h2>$<?php echo $sum;?></h2>
      </div>
    </div>

  </div>-->

<?php 
if(isset($_POST['refund2'])){
$refundarr=$_POST['refund'];
$size=count($refundarr);
for($i=0;$i<$size;$i++)
{
$order_id=$refundarr[$i];
$row = $wpdb->get_row( "SELECT * FROM  413_all_trasactions WHERE order_id ='$order_id'");
$userID=$row->user_id;
$type=$row->type;
$amount=$row->amount;
$user_info = get_userdata($userID);

$product_id=$row->product_id;
$title=get_the_title($product_id);
$data_array2 = array('user_id'=>$userID,'type'=>$type,'order_id'=>$order_id,'title'=>$title,'amount'=>$amount);
$point_insert= $wpdb->insert( '413_refund_transactions', $data_array2);
$refundGold = $wpdb->query("UPDATE 413_all_trasactions SET refund = '1' WHERE order_id = '$order_id'");
if($type=='gold'){
$userID;
$email = $user_info->user_email;
$gold_points = get_user_meta($userID, 'wallet-amount', true);
$gold_points2=$gold_points+$amount;

if(update_user_meta($userID, 'wallet-amount', $gold_points2 )){
$to=$email;
$subject = 'Refund Your Gold';
$headers.= "MIME-version: 1.0\n";
$headers.= "Content-type: text/html; charset= iso-8859-1\n";
$message .= "You did not won the $title Pledge. So your gold points are refundable". "\r\n";
//  $message .= $mycode;
$headers = 'X-Mailer: PHP/' . phpversion();
//print_r($message);
if( mail($to, $subject, $message, $headers))
{
//echo 'done';
}
}
//$allowed = $wpdb->query("UPDATE interactive_system SET points = '$totalPoints' WHERE user_id = '$userID'");
}
if($type=='silver'){
$email2 = $user_info->user_email;
$get_points = $wpdb->get_row( "SELECT * FROM  interactive_system where user_id='$userID'" );
$currentPoints=$get_points->points;
$totalPoints= $currentPoints+$amount;
$allowed = $wpdb->query("UPDATE interactive_system SET points = '$totalPoints' WHERE user_id = '$userID'");
if($allowed){
$to2=$email2;
$subject2 = 'Refund Your Silver Points';
$headers2.= "MIME-version: 1.0\n";
$headers2.= "Content-type: text/html; charset= iso-8859-1\n";
$message2 .= "You did not won the $title Pledge. So your silver points are refundable". "\r\n";
//  $message .= $mycode;
$headers2 = 'X-Mailer: PHP/' . phpversion();
//print_r($message);
if( mail($to2, $subject2, $message2, $headers2))
{
// echo 'done';
}
}
}
}
echo "<script>alert('Successfully refunded.')</script>";
echo '<script> location.href="http://premise.tv/content-creator-dashboard/"</script>';
}
if(isset($_POST['won2'])){

$wondarr=$_POST['won'];
$size=count($wondarr);
for($j=0;$j<$size;$j++)
{
global $wpdb; 
$order_id=$wondarr[$j];
$row = $wpdb->get_row( "SELECT * FROM  413_all_trasactions WHERE order_id ='$order_id'");
$userID=$row->user_id;
$user_info = get_userdata($userID);
$product_id=$row->product_id;
$title=get_the_title($product_id);
$update = $wpdb->query("UPDATE 413_all_trasactions SET won = '1' WHERE order_id = '$order_id'");
$userID;
$email = $user_info->user_email;
$to=$email;
$subject = 'Congratulation you won the pledge';
$headers.= "MIME-version: 1.0\n";
$headers.= "Content-type: text/html; charset= iso-8859-1\n";
$message = "You won $title Pledge.". "\r\n";
$headers = 'X-Mailer: PHP/' . phpversion();
//print_r($message);
if( mail($to, $subject, $message, $headers))
{
//echo 'done';
}
}
echo "<script>alert('Successfully email sent to selected users.')</script>";
echo '<script> location.href="http://premise.tv/content-creator-dashboard/"</script>';
}
?>
</div>
  <div class="wpcf7-response-output wpcf7-display-none wpcf7-validation-errors refund" style="display: none;">Successfully refunded.</div>
  <div class="wpcf7-response-output wpcf7-display-none wpcf7-validation-errors won" style="display: none;">Successfully email sent to selected users.</div>

<?php get_footer();?>
<style>
.table_1{ border:1px solid #F2F2F2; width:100%; margin:10px 0px 10px 0px;
  margin-top: 65px;
  color: #545454;
   background-color:#fff;
  } 

.table_1 td{ height: 41px;border-right:1px solid #ccc; padding:10px;
  border-bottom: none !important;} 
.table_1 th{ height: 63px;border-right:1px solid #ccc; padding:10px; background-color:#f8f8f8; color:#545454;font: 14px Roboto;border-bottom: none !important;}
.actionBtn td {
  border: 0 none !important;
}
.main_wrap h2 {
  padding-left: 15px;
}
footer.dark-div {
  float: left;
  width: 100%;
}
.actionBtn input {
  text-transform: capitalize;
}

/*graph css*/
.main_total {
  /*background: #eee none repeat scroll 0 0;*/
  height: 200px;
  
  width: 200px;
  position: relative;
   margin: 40px auto 0;
}
.yellow_border {
  background: transparent none repeat scroll 0 0;
  border-left: 10px solid #fd9801;
  border-top: 10px solid #fd9801;
  border-top-left-radius: 100%;
  height: 90px;
  width: 90px;
}
.main_wrap div.wpcf7-response-output {
  float: right;
  margin: 0 0 30px;
}
.blue_border {
  background: transparent none repeat scroll 0 0;
  border-right: 10px solid #1f3f5f;
  border-top: 10px solid #1f3f5f;
  border-top-right-radius: 100%;
  height: 90px;
  width: 90px;
}
.lightgreen_border {
  background: transparent none repeat scroll 0 0;
  border-bottom: 10px solid #92c125;
  border-bottom-left-radius: 100%;
  border-left: 10px solid #92c125;
  height: 90px;
  width: 90px;
}
.green_border {
  background: transparent none repeat scroll 0 0;
  border-bottom: 10px solid #20ac66;
  border-bottom-right-radius: 100%;
  border-right: 10px solid #20ac66;
  height: 90px;
  width: 90px;
}
.border_left {
  float: left;
}
.total_text {
  display: block;
  left: 0;
  margin: 0 auto;
  position: absolute;
  right: 30px;
  text-align: center;
  top: 44%;
  transform: translateY(-50%);
  -webkit-transform: translateY(-50%);
  width: auto;
}
.total_text p {
  font-size: 13px;
  margin: 0;
}
.total_text h2 {
  font-size: 22px;
  margin: 0;
}
.main_wrap {
  display: block;
  margin: 0 auto;
}
.feedback {
  float: right;
  width: 90%;
}
.search_by input[name="search_by"] {
  width: 343px;
  height: 36px;
  border: none;
  background: url("http://www.premise.tv/wp-content/themes/truemag/images/search_bac.png") no-repeat;
  margin-right: -60px;
  padding-left: 20px;
  color: #A5A5A5;
  font: 14px Roboto;
}
.search_by {
  text-align: right;
  padding-right: 16px;
}
.search_by input[name="search"] {
  border: none;
  width: 36px;
  height: 36px;
  margin: 7px 0 -7px 20px;
  padding: 13px 18px;
  background: url("http://www.premise.tv/wp-content/themes/truemag/images/search_btn.png") no-repeat;
  border-radius: 18px;
  margin-top: 3px;

}
.sum_footer{
  width: 25%;
  height: 28px;
  float: left;
  text-align: center;
  margin-top:-5px;
  }
.sum{
  width: 25%;
  height: 28px;
  float: left;
  text-align: center;
  margin-top: 16px;
  }
.refund2{
  background-color: #34495e !important;
  border-radius: 20px !important;
}
.won2{
  background-color: #34495e !important;
  border-radius: 20px !important;
}
.none_border{
  border: none !important;
  }
 #wrap{
  background-color: #F8F8F8;
  }
/*end of graph css
*/</style>
<script type="text/javascript">
  function fun_check() {
  	var col = "#fffae7";
    var lfckv = document.getElementsByClassName("chbox");
  	var lfckv1 = document.getElementsByClassName("chbox1");
    console.log(lfckv);
  	console.log(lfckv1);
    for (var i = 0; i < lfckv.length; i++) {
      var td = lfckv[i].parentNode;
      var tr = td.parentNode;     	
      if (lfckv[i].checked || lfckv1[i].checked) {        
        tr.style.backgroundColor=col;
      }
      else{
        tr.style.backgroundColor="#fff";
      }
    }
  }
  function fun_check1() {
  	var col = "#fffae7";
    var lfckv = document.getElementsByClassName("chbox1");
  	var lfckv1 = document.getElementsByClassName("chbox");
    console.log(lfckv);
  	console.log(lfckv1);
    for (var i = 0; i < lfckv.length; i++) {
      var td = lfckv[i].parentNode;
      var tr = td.parentNode;
      if (lfckv[i].checked || lfckv1[i].checked) {        
        tr.style.backgroundColor=col;
      }
      else{
        tr.style.backgroundColor="#fff";
      }
    }
  }
</script>
</script>