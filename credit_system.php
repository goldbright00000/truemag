<?php
/*
Template name: points.php
*/ 
get_header();
 $user = get_current_user_id();

?>
<div class="container">
    <div style="width:22px;height:22px;background-color: #FBE35D;margin-top:238px;"></div>
    <h2 style="font: 30px Myriad Pro;color: #23272C;margin-top: -20px; margin-left: 10px;font-weight:600;height: 42px;">Credit System</h2>
    <div style="width: 270px;height: 36px;">
      <div style="border-bottom: 3px solid black;float: left;width: 24%;"></div>
      <div style="border-bottom: 1px solid black;float: left;width: 70%;margin-top: 2px;"></div>
      <br>
    </div>
<div class="mainHead" style="width: 100%;height: 50px;background-color: #34485D;">
    <ul class="nav navbar-nav">
        <li id="gold" class="active" onclick="gold()">
            <div style="width: 75px;text-align: center;margin-top: 10px;"><img src="http://www.premise.tv/wp-content/uploads/2017/06/gold.png" width="25" height="25"></div>
        </li>
        <li id="silver" onclick="silver()">
            <div style="width: 75px;text-align: center;margin-top: 10px;"><img src="http://www.premise.tv/wp-content/uploads/2017/06/silver.png" width="25" height="25"></div>
        </li>
    </ul>
</div>
<!-- ...........................column first................................ -->

<div class="contentLower">
<div style="width: 23%;margin-top: 30px;float: left;">
    <ul class="list-group" id="gold_list">
        <li id="list1" class="list-group-item active" onclick="list(1)"><div style="float: left;"><img src="http://www.premise.tv/wp-content/themes/truemag/images/TopUp.png"></div><div class="list_item">Top up</div></li>
        <li id="list2" class="list-group-item" onclick="list(2)"><div style="float: left;"><img src="http://www.premise.tv/wp-content/themes/truemag/images/TransferCredit.png"></div><div class="list_item">Transfer Credit</div></li>
        <li id="list3" class="list-group-item" onclick="list(3)"><div style="float: left;"><img src="http://www.premise.tv/wp-content/themes/truemag/images/MerchandiseTransactions.png"></div><div class="list_item">Merchandise Transactions</div></li>
        <li id="list4" class="list-group-item" onclick="list(4)"><div style="float: left;"><img src="http://www.premise.tv/wp-content/themes/truemag/images/RefundTransaction.png"></div><div class="list_item">Refund Transaction</div></li>
    </ul>
    <ul class="list-group" id="silver_list" style="display: none;">
        <li id="list5" class="list-group-item active" onclick="list(5)"><div style="float: left;"><img src="http://www.premise.tv/wp-content/themes/truemag/images/DailyLogin.png"></div><div class="list_item">Daily Login</div></li>
        <li id="list6" class="list-group-item" onclick="list(6)"><div style="float: left;"><img src="http://www.premise.tv/wp-content/themes/truemag/images/InviteFriend.png"></div><div class="list_item">Invite Friend</div></li>
        <li id="list7" class="list-group-item" onclick="list(7)"><div style="float: left;"><img src="http://www.premise.tv/wp-content/themes/truemag/images/Bonus+ProfileComplete.png"></div><div class="list_item">Bonus+Profile Complete</div></li>
        <li id="list8" class="list-group-item" onclick="list(8)"><div style="float: left;"><img src="http://www.premise.tv/wp-content/themes/truemag/images/Transactions.png"></div><div class="list_item">Transactions</div></li>
        <li id="list9" class="list-group-item" onclick="list(9)"><div style="float: left;"><img src="http://www.premise.tv/wp-content/themes/truemag/images/RatingofContent.png"></div><div class="list_item">Rating of Content</div></li>
        <li id="list10" class="list-group-item" onclick="list(10)"><div style="float: left;"><img src="http://www.premise.tv/wp-content/themes/truemag/images/Socialmediaplatform.png"></div><div class="list_item">Social media platform</div></li>
        <li id="list11" class="list-group-item" onclick="list(11)"><div style="float: left;"><img src="http://www.premise.tv/wp-content/themes/truemag/images/Postthreadforum.png"></div><div class="list_item">Post thread forum</div></li>
        <li id="list12" class="list-group-item" onclick="list(12)"><div style="float: left;"><img src="http://www.premise.tv/wp-content/themes/truemag/images/RefundTransactionSilver.png"></div><div class="list_item">Refund Transaction Silver</div></li>
        <li id="list13" class="list-group-item" onclick="list(13)"><div style="float: left;"><img src="http://www.premise.tv/wp-content/themes/truemag/images/SurveyPoints.png"></div><div class="list_item">Survey Points</div></li>
    </ul>
</div>
<div style="width: 74.5%;margin-top: 30px;float: right;">   

    
<!-- fetching gold credit points table Top up code start here -->
<div style="display: block;"class="visible"  id="table_list1">
        <div style="width: 100%; height: 50px; background-color: #34485D;"></div>
        <table class="table table_1 table-fixed">
            <thead>
                <tr>
                    <th class="col-xs-4">S.No</th>
                    <!--<th>user_id</th>-->
                    <th class="col-xs-4">Amount</th>
                    <th class="col-xs-4 none_border">Date</th>   
                </tr>
            </thead>
        
            <tbody class="style-1">
                <?php 
                global $wpdb;
                    $table = 'gold_points_details';
                    $retrieve_data = $wpdb->get_results( "SELECT * FROM  $table WHERE user_id = '" . $user . "' AND type = 'add' ");
                    $i=0;
                        foreach ($retrieve_data as $retrieved_data){ $i++;?>
                            <tr>
                                <td class="col-xs-4"><?php echo $i;?></td>
                                <!--<td><?php //echo $retrieved_data->user_id;?></td>-->
                                <td class="col-xs-4"><?php echo $retrieved_data->amount;?></td>
                                <td class="col-xs-4 none_border"><?php echo $retrieved_data->date;?></td>
                            </tr>
                <?php   }
                if (sizeof($retrieve_data) > 8) {
                    ?>
                        <style type="text/css">
                        #table_list1 thead {
                          width: 99% !important;
                          height: 50px;
                        }
                        </style>
                    <?php
                }
                else{
                    ?>
                        <style type="text/css">
                        #table_list1 thead {
                          width: 100% !important;
                          height: 50px;
                        }
                        </style>
                    <?php
                }
                ?>
            </tbody>
        </table>
</div>
<!-- fetching gold credit points table Transfer Credit code start here -->
    <div style="display: none;" class="visible" id="table_list2">       
           <div style="width: 100%; height: 50px; background-color: #34485D;"></div>
            <table class="table table_1 table-fixed">
                <thead>
                    <tr>
                        <th class="col-xs-3">S.No</th>
                        <!--<th>user_id</th>-->
                        <th class="col-xs-3">Receiver</th>
                        <th class="col-xs-3">Amount</th>
                        <th class="col-xs-3 none_border">Date</th>   
                    </tr>
                </thead>
                <tbody class="style-1">
<?php 
 global $wpdb;
    $table = 'gold_points_details';
    $retrieve_data = $wpdb->get_results( "SELECT * FROM  $table WHERE user_id = '" . $user . "' AND type = 'send' ");
    $i=0;
        foreach ($retrieve_data as $retrieved_data){ $i++;
            $user_info = get_userdata($retrieved_data->reciver); 
               $first_name = $user_info->first_name;?>
            <tr>
                <td class="col-xs-3"><?php echo $i;?></td>
                <!--<td><?php //echo $retrieved_data->user_id;?></td>-->
                <td class="col-xs-3"><?php echo $first_name ;?></td>
                <!-- <td><?php //echo $retrieved_data->$first_name;?></td> -->
                <td class="col-xs-3"><?php echo $retrieved_data->amount;?></td>
                <td class="col-xs-3 none_border"><?php echo $retrieved_data->date;?></td>
            </tr>
            <?php   }
                if (sizeof($retrieve_data) > 8) {
                    ?>
                        <style type="text/css">
                        #table_list2 thead {
                          width: 99% !important;
                          height: 50px;
                        }
                        </style>
                    <?php
                }
                else{
                    ?>
                        <style type="text/css">
                        #table_list2 thead {
                          width: 100% !important;
                          height: 50px;
                        }
                        </style>
                    <?php
                }
                ?>
</tbody>
</table>
</div>
<!-- fetching gold credit points table Merchandise Trasactions code start here -->
<div style="display: none;" class="visible" id="table_list3">
<div style="width: 100%; height: 50px; background-color: #34485D;"></div>
<table class ="table table_1 table-fixed">
    <thead>
        <tr>
            <th class="col-xs-3">S.No</th>
            <th class="col-xs-3">#Order id</th>
            <th class="col-xs-3">Amount</th>
            <th class="col-xs-3 none_border">Date</th>   
        </tr>
    </thead>
    <tbody class="style-1">
    <?php 
     global $wpdb;
        $table = 'gold_points_details';
        $retrieve_data = $wpdb->get_results("SELECT * FROM  $table WHERE user_id = '" . $user . "' AND type = 'transaction' ");
            $i=0;
            foreach ($retrieve_data as $retrieved_data){ $i++;?>
                <tr>
                    <td class="col-xs-3"><?php echo $i;?></td>
                    <td class="col-xs-3"><?php echo $retrieved_data->order_id;?></td> 
                    <td class="col-xs-3"><?php echo $retrieved_data->amount;?></td>
                    <td class="col-xs-3 none_border"><?php echo $retrieved_data->date;?></td>
                </tr>
            <?php   }
                if (sizeof($retrieve_data) > 8) {
                    ?>
                        <style type="text/css">
                        #table_list3 thead {
                          width: 99% !important;
                          height: 50px;
                        }
                        </style>
                    <?php
                }
                else{
                    ?>
                        <style type="text/css">
                        #table_list3 thead {
                          width: 100% !important;
                          height: 50px;
                        }
                        </style>
                    <?php
                }
                ?>
    </tbody>
</table>
</div>
<!-- fetching redund transactions table 413 refund transactions code start here -->
<div style="display: none;" class="visible" id="table_list4">        
   <div style="width: 100%; height: 50px; background-color: #34485D;"></div>
    <table class="table table_1 table-fixed">
        <thead>
            <tr>
                <th class="col-xs-1">S.No</th>
                <th class="col-xs-2">#orderid</th>
                <th class="col-xs-4">Product_Name</th>
                <th class="col-xs-3">Type</th>
                <th class="col-xs-2 none_border">Amount</th>    
            </tr>
        </thead>
        <tbody class="style-1">
            <?php 
             global $wpdb;
                $table = '413_refund_transactions';
                $retrieve_data = $wpdb->get_results( "SELECT * FROM  $table WHERE user_id = '" . $user . "' AND type = 'gold' ");
                $i=0;
                    foreach ($retrieve_data as $retrieved_data){ $i++;
                        //$user_info = get_userdata($retrieved_data->reciver); 
                           //$first_name = $user_info->first_name;?>
                        <tr>
                            <td class="col-xs-1"><?php echo $i;?></td>
                            <td class="col-xs-2"><?php echo $retrieved_data->order_id;?></td>
                            <td class="col-xs-4"><?php echo $retrieved_data->title;?></td>
                            <td class="col-xs-3"><?php echo $retrieved_data->type;?></td>
                            <td class="col-xs-2 none_border"><?php echo $retrieved_data->amount;?></td>
                        </tr>
            <?php   }
                if (sizeof($retrieve_data) > 8) {
                    ?>
                        <style type="text/css">
                        #table_list4 thead {
                          width: 99% !important;
                          height: 50px;
                        }
                        </style>
                    <?php
                }
                else{
                    ?>
                        <style type="text/css">
                        #table_list4 thead {
                          width: 100% !important;
                          height: 50px;
                        }
                        </style>
                    <?php
                }
                ?>
        </tbody>
    </table>
</div>


<!-- fetching free credit points table daily login code start here -->

<div style="display: none;" class="visible" id="table_list5">
    <div style="width: 100%; height: 50px; background-color: #34485D;"></div>
    <table class ="table table_1 table-fixed">
        <thead> 
        <tr>
            <th class="col-xs-4">S.No</th>
            <th class="col-xs-4">Date</th> 
            <th class="col-xs-4 none_border">Points</th>
        </tr>
        </thead>
        <tbody class="style-1">
        <?php 
         global $wpdb;
            $table = 'free_points_details';
            $retrieve_data = $wpdb->get_results("SELECT * FROM  $table WHERE user_id = '" . $user . "' AND type = 'Daily Login' ");
                $i=0;
                foreach ($retrieve_data as $retrieved_data){ $i++;?>
                    <tr>
                        <td class="col-xs-4"><?php echo $i;?></td>
                        <td class="col-xs-4"><?php echo $retrieved_data->date;?></td>
                        <td class="col-xs-4 none_border"><?php echo $retrieved_data->points;?></td> 
                        
                    </tr>
        <?php   }
                if (sizeof($retrieve_data) > 8) {
                    ?>
                        <style type="text/css">
                        #table_list5 thead {
                          width: 99% !important;
                          height: 50px;
                        }
                        </style>
                    <?php
                }
                else{
                    ?>
                        <style type="text/css">
                        #table_list5 thead {
                          width: 100% !important;
                          height: 50px;
                        }
                        </style>
                    <?php
                }
                ?>
        </tbody>
    </table>
</div>

<!-- fetching free credit points table invite friend code start here -->
<div style="display: none;" class="visible" id="table_list6">
    <div style="width: 100%; height: 50px; background-color: #34485D;"></div>
    <table class ="table table_1 table-fixed">
        <thead>
            <tr>
                <th class="col-xs-3">S.No</th>
                <th class="col-xs-3">Date</th> 
                <th class="col-xs-3">Email</th> 
                <th class="col-xs-3 none_border">Points</th>          
            </tr>
        </thead>
        <tbody class="style-1">
            <?php 
             global $wpdb;
                $table = 'free_points_details';
                $retrieve_data = $wpdb->get_results("SELECT * FROM  $table WHERE user_id = '" . $user . "' AND type = 'Invite Friend' ");
                    $i=0;
                    foreach ($retrieve_data as $retrieved_data){ $i++;?>
                        <tr>
                            <td class="col-xs-3"><?php echo $i;?></td>
                            <td class="col-xs-3"><?php echo $retrieved_data->date;?></td>
                            <td class="col-xs-3"><?php echo $retrieved_data->femail;?></td>
                            <td class="col-xs-3 none_border"><?php echo $retrieved_data->points;?></td> 
                            
                        </tr>
            <?php   }
                if (sizeof($retrieve_data) > 8) {
                    ?>
                        <style type="text/css">
                        #table_list6 thead {
                          width: 99% !important;
                          height: 50px;
                        }
                        </style>
                    <?php
                }
                else{
                    ?>
                        <style type="text/css">
                        #table_list6 thead {
                          width: 100% !important;
                          height: 50px;
                        }
                        </style>
                    <?php
                }
                ?>
        </tbody>
    </table>
</div>

<!-- fetching free credit points table bonus profile completeness code start here -->
<div style="display: none;" class="visible" id="table_list7">
    <div style="width: 100%; height: 50px; background-color: #34485D;"></div>
    <table class ="table table_1 table-fixed">
        <thead>
            <tr>
                <th class="col-xs-4">S.No</th>
                <th class="col-xs-4">Type</th> 
                <th class="col-xs-4 none_border">Points</th>         
            </tr>
        </thead>
        <tbody class="style-1">
        <?php 
         global $wpdb;
            $table = 'free_points_details';
            $retrieve_data = $wpdb->get_results("SELECT * FROM `free_points_details` WHERE `user_id`=$user AND (type = '30 Days Login Bouns' OR type = 'profile complete')");
                $i=0;


                foreach ($retrieve_data as $retrieved_data){ $i++;?>
                    <tr>
                        <td class="col-xs-4"><?php echo $i;?></td>
                        <td class="col-xs-4"><?php echo $retrieved_data->type;?></td>
                        <td class="col-xs-4 none_border"><?php echo $retrieved_data->points;?></td> 
                        
                    </tr>
        <?php   }
                if (sizeof($retrieve_data) > 8) {
                    ?>
                        <style type="text/css">
                        #table_list7 thead {
                          width: 99% !important;
                          height: 50px;
                        }
                        </style>
                    <?php
                }
                else{
                    ?>
                        <style type="text/css">
                        #table_list7 thead {
                          width: 100% !important;
                          height: 50px;
                        }
                        </style>
                    <?php
                }
                ?>
        </tbody>
    </table>
</div>

<!-- fetching free credit points table Transactions code start here -->
<div style="display: none;" class="visible" id="table_list8">
    <div style="width: 100%; height: 50px; background-color: #34485D;"></div>
    <table class ="table table_1 table-fixed">
        <thead>
            <tr>
                <th class="col-xs-3">S.No</th>
                <th class="col-xs-3">#Order id</th>
                <th class="col-xs-3">Points</th>
                <th class="col-xs-3 none_border">Date</th>   
            </tr>
        </thead>
        <tbody class="style-1">
            <?php 
             global $wpdb;
                $table = 'free_credit_trasactions';
                $retrieve_data = $wpdb->get_results("SELECT * FROM  $table WHERE user_id = '" . $user . "' ");
                    $i=0;
                    foreach ($retrieve_data as $retrieved_data){ $i++;?>
                        <tr>
                            <td class="col-xs-3"><?php echo $i;?></td>
                            <td class="col-xs-3"><?php echo $retrieved_data->order_id;?></td> 
                            <td class="col-xs-3"><?php echo $retrieved_data->amount;?></td>
                            <td class="col-xs-3 none_border"><?php echo $retrieved_data->date;?></td>
                        </tr>
            <?php   }
                if (sizeof($retrieve_data) > 8) {
                    ?>
                        <style type="text/css">
                        #table_list8 thead {
                          width: 99% !important;
                          height: 50px;
                        }
                        </style>
                    <?php
                }
                else{
                    ?>
                        <style type="text/css">
                        #table_list8 thead {
                          width: 100% !important;
                          height: 50px;
                        }
                        </style>
                    <?php
                }
                ?>
        </tbody>
    </table>
</div>

<!--20-may-17 fetching free credit points table Rating of Content code start here-->
<div style="display: none;" class="visible" id="table_list9">
    <div style="width: 100%; height: 50px; background-color: #34485D;"></div>
    <table class ="table table_1 table-fixed">
        <thead>
            <tr>
                <th class="col-xs-3">S.No</th>
                <th class="col-xs-3">Date</th> 
                <th class="col-xs-3">Video Title</th> 
                <th class="col-xs-3 none_border">Points</th>          
            </tr>
        </thead>
        <tbody class="style-1">
            <?php 
            global $wpdb;
            $table = 'free_points_details';
            $retrieve_data = $wpdb->get_results("SELECT * FROM  $table WHERE user_id = '" . $user . "' AND type = 'Rating of Content' ");
                $i=0;
                foreach ($retrieve_data as $retrieved_data){ $i++;?>
                    <tr>
                        <td class="col-xs-3"><?php echo $i;?></td>
                        <td class="col-xs-3"><?php echo $retrieved_data->date;?></td>
                        <td class="col-xs-3"><?php echo $retrieved_data->femail;?></td>
                        <td class="col-xs-3 none_border"><?php echo $retrieved_data->points;?></td> 
                    </tr>
            <?php   }
                if (sizeof($retrieve_data) > 8) {
                    ?>
                        <style type="text/css">
                        #table_list9 thead {
                          width: 99% !important;
                          height: 50px;
                        }
                        </style>
                    <?php
                }
                else{
                    ?>
                        <style type="text/css">
                        #table_list9 thead {
                          width: 100% !important;
                          height: 50px;
                        }
                        </style>
                    <?php
                }
                ?>
        </tbody>
    </table>
</div>

<!--20-may-17 fetching free credit points table Social media platform code start here-->
<div style="display: none;" class="visible" id="table_list10"> 
    <div style="width: 100%; height: 50px; background-color: #34485D;"></div>
    <table class ="table table_1 table-fixed">
    <thead>
        <tr>
            <th class="col-xs-3">S.No</th>
            <th class="col-xs-3">Date</th> 
            <th class="col-xs-3">Video Title</th> 
            <th class="col-xs-3 none_border">Points</th>          
        </tr>
    </thead>
    <tbody class="style-1">
        <?php 
         global $wpdb;
            $table = 'free_points_details';
            $retrieve_data = $wpdb->get_results("SELECT * FROM  $table WHERE user_id = '" . $user . "' AND type = 'Social media platform' ");
                $i=0;
                foreach ($retrieve_data as $retrieved_data){ $i++;?>
                    <tr>
                        <td class="col-xs-3"><?php echo $i;?></td>
                        <td class="col-xs-3"><?php echo $retrieved_data->date;?></td>
                        <td class="col-xs-3"><?php echo $retrieved_data->femail;?></td>
                        <td class="col-xs-3 none_border"><?php echo $retrieved_data->points;?></td> 
                    </tr>
        <?php   }
                if (sizeof($retrieve_data) > 8) {
                    ?>
                        <style type="text/css">
                        #table_list10 thead {
                          width: 99% !important;
                          height: 50px;
                        }
                        </style>
                    <?php
                }
                else{
                    ?>
                        <style type="text/css">
                        #table_list10 thead {
                          width: 100% !important;
                          height: 50px;
                        }
                        </style>
                    <?php
                }
                ?>
    </tbody>
</table>
</div>
<!--20-may-17 fetching Social media platform table post thread created on the forum code start here-->
<div style="display: none;" class="visible" id="table_list11">
    <div style="width: 100%; height: 50px; background-color: #34485D;"></div>
    <table class ="table table_1 table-fixed">
        <thead>
            <tr>
                <th class="col-xs-3">S.No</th>
                <th class="col-xs-3">Date</th> 
                <th class="col-xs-3">Video Title</th> 
                <th class="col-xs-3 none_border">Points</th>
                  
            </tr>
        </thead>
        <tbody class="style-1">
            <?php 
             global $wpdb;
                $table = 'free_points_details';
                $retrieve_data = $wpdb->get_results("SELECT * FROM  $table WHERE user_id = '" . $user . "' AND type = 'post thread created on the forum' ");
                    $i=0;
                    foreach ($retrieve_data as $retrieved_data){ $i++;?>
                        <tr>
                            <td class="col-xs-3"><?php echo $i;?></td>
                            <td class="col-xs-3"><?php echo $retrieved_data->date;?></td>
                            <td class="col-xs-3"><?php echo $retrieved_data->femail;?></td>
                            <td class="col-xs-3 none_border"><?php echo $retrieved_data->points;?></td> 
                        </tr>
            <?php   }
                if (sizeof($retrieve_data) > 8) {
                    ?>
                        <style type="text/css">
                        #table_list11 thead {
                          width: 99% !important;
                          height: 50px;
                        }
                        </style>
                    <?php
                }
                else{
                    ?>
                        <style type="text/css">
                        #table_list11 thead {
                          width: 100% !important;
                          height: 50px;
                        }
                        </style>
                    <?php
                }
                ?>
        </tbody>
    </table>
</div>
<!-- fetching redund transactions table 413 refund transactions code start here -->
<div style="display: none;" class="visible" id="table_list12" >
   <div style="width: 100%; height: 50px; background-color: #34485D;"></div>
    <table class="table table_1 table-fixed">
        <thead>
            <tr>
                <th class="col-xs-1">S.No</th>
                <th class="col-xs-2">#orderid</th>
                <th class="col-xs-4">Product_Name</th>
                <th class="col-xs-3">Type</th>
                <th class="col-xs-2 none_border">Amount</th>    
            </tr>
        </thead>
        <tbody class="style-1">
            <?php 
             global $wpdb;
                $table = '413_refund_transactions';
                $retrieve_data = $wpdb->get_results( "SELECT * FROM  $table WHERE user_id = '" . $user . "' AND type = 'silver' ");
                $i=0;
                    foreach ($retrieve_data as $retrieved_data){ $i++;
                        //$user_info = get_userdata($retrieved_data->reciver); 
                           //$first_name = $user_info->first_name;?>
                        <tr>
                            <td class="col-xs-1"><?php echo $i;?></td>
                            <td class="col-xs-2"><?php echo $retrieved_data->order_id;?></td>
                            <td class="col-xs-4"><?php echo $retrieved_data->title;?></td>
                            <td class="col-xs-3"><?php echo $retrieved_data->type;?></td>
                            <td class="col-xs-2 none_border"><?php echo $retrieved_data->amount;?></td>
                        </tr>
            <?php   }
                if (sizeof($retrieve_data) > 8) {
                    ?>
                        <style type="text/css">
                        #table_list12 thead {
                          width: 99% !important;
                          height: 50px;
                        }
                        </style>
                    <?php
                }
                else{
                    ?>
                        <style type="text/css">
                        #table_list12 thead {
                          width: 100% !important;
                          height: 50px;
                        }
                        </style>
                    <?php
                }
                ?>
        </tbody>
</table>
</div>

<!-- fetching survey points table Structure free_points_details code start here -->
<div style="display: none;" class="visible" id="table_list13">
    <div style="width: 100%; height: 50px; background-color: #34485D;"></div>
    <table class ="table table_1 table-fixed">
        <thead>
            <tr>
                <th class="col-xs-3">S.No</th>
                <th class="col-xs-3">Date</th> 
                <th class="col-xs-3">Survey Name</th> 
                <th class="col-xs-3 none_border">Points</th>
                  
            </tr>
        </thead>
        <tbody class="style-1">
            <?php 
             global $wpdb;
            $title= get_the_title(3184);
             //echo $title;
                $table = 'free_points_details';
                $retrieve_data = $wpdb->get_results("SELECT * FROM  $table WHERE user_id = '" . $user . "' AND type = 'Survey Points' ");
                    $i=0;
                    foreach ($retrieve_data as $retrieved_data){ $i++;?>
                        <tr>
                            <td class="col-xs-3"><?php echo $i;?></td>
                            <td class="col-xs-3"><?php echo $retrieved_data->date;?></td>
                             <td class="col-xs-3"><?php $title= get_the_title($retrieved_data->femail); echo $title;?></td>
                            <td class="col-xs-3 none_border"><?php echo $retrieved_data->points;?></td> 
                        </tr>
            <?php   }
                if (sizeof($retrieve_data) > 8) {
                    ?>
                        <style type="text/css">
                        #table_list13 thead {
                          width: 99% !important;
                          height: 50px;
                        }
                        </style>
                    <?php
                }
                else{
                    ?>
                        <style type="text/css">
                        #table_list13 thead {
                          width: 100% !important;
                          height: 50px;
                        }
                        </style>
                    <?php
                }
                ?>
        </tbody>
    </table>
</div>
</div>
</div>
</div>
<div style="height: 90px;"></div>
</div>
<!--style start form here-->

<style>
    
    .dark-div {
  float: left;
  width: 100%;
}
.mainHead, .contentLower {
  float: left;
  width: 100%;
}
.pad-Left
{
    padding-left: 0px;
}
.pad-Right
{
    padding-right: 0px;
}
.pad-Right th, .add_credit th {
  background: #e0a902 none repeat scroll 0 0 !important;
}
.pad-Left th {
  background: #ccc none repeat scroll 0 0 !important;
}
.daily_login th {
  background: #ccc none repeat scroll 0 0 !important;
}
.table-flow
{
 overflow: auto;
 max-height: 500px;   
}
.active{
  background-color: #3A526C !important;
  height: 50px;
  }
.list-group-item{
  background-color: #34485D;
  border: none;
  color: #fff;
  font: 15px Myriad Pro;
  height: 47px;
  padding-left: 20px;
}
.table{
    background-color: #34485D;
    width: 99.2%;
    margin-top: -50px;
}

/*.table-fixed thead {
  width: 100%;
  height: 50px;
}*/
.table-fixed tbody {
    background-color: #fff;
    height: 376px;
    overflow-y: auto;
    width: 100%;
}
.table-fixed thead, .table-fixed tbody, .table-fixed tr, .table-fixed td, .table-fixed th {
  display: block;
}
.table-fixed tbody td, .table-fixed thead > tr> th {
  float: left;
}

.table thead>tr>th,
 .table tbody>tr>th,
  .table tfoot>tr>th,
   .table thead>tr>td,
    .table tbody>tr>td,
     .table tfoot>tr>td{
        border: none;
        text-align: center;
        vertical-align: inherit;
        font-weight: normal;
        font: 14px Myriad Pro;
        height: 40px;
        color: #7e7e7e;
        padding-top: 16px;
        border-right:1px solid #ccc; 
     }


.none_border{
  border: none !important;
}
.table thead>tr>th,
 .table tbody>tr>th{
    background-color: #34485D;
    height: 50px;
    color: #fff;
    font-weight: normal;

 }
.list_item{
    margin-left: 45px;
    margin-top: 5px;
}

.style-1::-webkit-scrollbar-track
{
    width: 16px;
    border-radius: 10px;
    background-color: #FFF;
    border: none;
    margin-top: 15px;
    margin-right: 8px;
}

.style-1::-webkit-scrollbar
{
    width: 8px;
    background-color: #FFF;
    border: none;
}

.style-1::-webkit-scrollbar-thumb
{
    border-radius: 10px;
    background-color: #DCDCDC;
    border: none;
}

</style>
  <script type="text/javascript">
    
    function gold() {
        var gold = document.getElementById("gold").classList;
            var silver = document.getElementById("silver").classList;
        silver.remove("active");         
        gold.add("active");
            document.getElementById("gold_list").style.display = 'block';
            document.getElementById("silver_list").style.display = 'none';
            list(1);
    }
    function silver() {
        var gold = document.getElementById("gold").classList;
            var silver = document.getElementById("silver").classList;
        gold.remove("active");         
        silver.add("active");
            document.getElementById("silver_list").style.display = 'block';
            document.getElementById("gold_list").style.display = 'none';
            list(5);
    }
   function list(k) {
        var x = document.getElementsByClassName("list-group-item");
        var y = document.getElementsByClassName("visible");
        for (var i = 0; i < 13; i++) {
            xx = x[i].classList;
            xx.remove("active");
            y[i].style.display = 'none';
        }
        if (k == 1) {var a = document.getElementById("list1").classList;
        document.getElementById("table_list1").style.display = 'block';}
        if (k == 2) {var a = document.getElementById("list2").classList;
        document.getElementById("table_list2").style.display = 'block';}
        if (k == 3) {var a = document.getElementById("list3").classList;
        document.getElementById("table_list3").style.display = 'block';}
        if (k == 4) {var a = document.getElementById("list4").classList;
        document.getElementById("table_list4").style.display = 'block';}
        if (k == 5) {var a = document.getElementById("list5").classList;
        document.getElementById("table_list5").style.display = 'block';}
        if (k == 6) {var a = document.getElementById("list6").classList;
        document.getElementById("table_list6").style.display = 'block';}
        if (k == 7) {var a = document.getElementById("list7").classList;
        document.getElementById("table_list7").style.display = 'block';}
        if (k == 8) {var a = document.getElementById("list8").classList;
        document.getElementById("table_list8").style.display = 'block';}
        if (k == 9) {var a = document.getElementById("list9").classList;
        document.getElementById("table_list9").style.display = 'block';}
        if (k == 10) {var a = document.getElementById("list10").classList;
        document.getElementById("table_list10").style.display = 'block';}
        if (k == 11) {var a = document.getElementById("list11").classList;
        document.getElementById("table_list11").style.display = 'block';}
        if (k == 12) {var a = document.getElementById("list12").classList;
        document.getElementById("table_list12").style.display = 'block';}
        if (k == 13) {var a = document.getElementById("list13").classList;
        document.getElementById("table_list13").style.display = 'block';}
        a.add("active");
    }
</script>
<?php get_footer();?>