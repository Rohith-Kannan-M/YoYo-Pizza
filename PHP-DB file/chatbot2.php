<?php
function get_chatbot_msg(){

	$msg = strtolower($_POST["message_sent"]);
	$prev_ques = strtolower($_POST["prev_ques"]);
	$items = strtolower($_POST["items"]);
	$name_in_order = strtolower($_POST["name_in_order"]);
	$delivery_addr = strtolower($_POST["delivery_addr"]);
	$phone = strtolower($_POST["phone"]);
	$menu_list=array("cheese pizza","pepperoni pizza","margherita pizza","green salads","dinner salads","bound salads","freezing chill cola","tangy lemon crush");
	$menu_category=array("pizza","cool drinks","salads");
	$cat_list = array (
	  array("cheese pizza","pepperoni pizza","margherita pizza"),
	 array("Freezing chill cola","Tangy lemon crush"),
	  array("green salads","dinner salads","bound salads")
	);

	if($msg==''){
		$category_list = 'Welcome to YoYo Pizza!<br> Choose from our varieties,<br> ';
		for($j=0;$j<count($menu_category);$j++){
			$category_list=$category_list.$menu_category[$j].'<br> ';
		}
		$category_list = $category_list."or Type 'Track My Order' to track your order.";
		$data= ["message" => $category_list, "prev_ques" => $prev_ques, "items" => $items, "name_in_order" => $name_in_order, "delivery_addr" => $delivery_addr, "phone" => $phone];
		return $data;
	}

	if($prev_ques=='quantity'){
		$arr = explode(" ",$msg);
		$flag=0;
		for($i=0;$i<count($arr);$i++){
			if(is_numeric($arr[$i])){
				$items=$items.'-'.$arr[$i].', ';
				$prev_ques='';
				$flag=1;
			}
		}
		if($flag==0){
			$data= ["message" => "Please give us a number!", "prev_ques" => $prev_ques, "items" => $items, "name_in_order" => $name_in_order, "delivery_addr" => $delivery_addr, "phone" => $phone];
			return $data;
		}
		else{
		    $prev_ques='add_list';
			$data= ["message" => "Do you like to have other items in the menu? (Yes/No)", "prev_ques" => $prev_ques, "items" => $items, "name_in_order" => $name_in_order, "delivery_addr" => $delivery_addr, "phone" => $phone];
			return $data;
		}
	}

	if($prev_ques=='add_list'){
		if(strpos($msg, 'yes') !== false){
			$prev_ques='';
			$category_list = 'Choose from our categories,<br>';
			for($j=0;$j<count($menu_category);$j++){
				$category_list=$category_list.$menu_category[$j].'<br>';
			}
			$data= ["message" => $category_list, "prev_ques" => $prev_ques, "items" => $items, "name_in_order" => $name_in_order, "delivery_addr" => $delivery_addr, "phone" => $phone];
			return $data;
		}
		else{
			$prev_ques='place_order';
			$data= ["message" => "Do you like to place the order? (Yes/No)", "prev_ques" => $prev_ques, "items" => $items, "name_in_order" => $name_in_order, "delivery_addr" => $delivery_addr, "phone" => $phone];
			return $data;
		}
	}

	if($prev_ques=='place_order'){
		if(strpos($msg, 'yes') !== false){
			$prev_ques='get_name';
			$data= ["message" => 'Name in which you place the order?', "prev_ques" => $prev_ques, "items" => $items, "name_in_order" => $name_in_order, "delivery_addr" => $delivery_addr, "phone" => $phone];
			return $data;
		}
		else{
			$prev_ques='';
			$items='';
			$data= ["message" => "Your order has been cancelled. Your can order by restarting the app.", "prev_ques" => $prev_ques, "items" => $items, "name_in_order" => $name_in_order, "delivery_addr" => $delivery_addr, "phone" => $phone];
			return $data;
		}
	}

	if($prev_ques=='get_name'){
			$name_in_order=$msg;
			$prev_ques='get_address';
			$data= ["message" => 'Address to deliver the order?', "prev_ques" => $prev_ques, "items" => $items, "name_in_order" => $name_in_order, "delivery_addr" => $delivery_addr, "phone" => $phone];
			return $data;
	}

	if($prev_ques=='get_address'){
			$delivery_addr=$msg;
			$prev_ques='get_phone';
			$data= ["message" => 'Please give your phone number?', "prev_ques" => $prev_ques, "items" => $items, "name_in_order" => $name_in_order, "delivery_addr" => $delivery_addr, "phone" => $phone];
			return $data;
	}

	if($prev_ques=='get_phone'){
			$phone=$msg;
			$prev_ques='order_confirmation';
			$data= ["message" => 'Do you like to confirm the order? (Yes/No)', "prev_ques" => $prev_ques, "items" => $items, "name_in_order" => $name_in_order, "delivery_addr" => $delivery_addr, "phone" => $phone];
			return $data;
	}

	if($prev_ques=='order_confirmation'){
			if(strpos($msg, 'yes') !== false || strpos($msg, 'ok') !== false || strpos($msg, 'okay') !== false || strpos($msg, 'conform') !== false || strpos($msg, 'confirm') !== false
			|| strpos($msg, 'conformation') !== false || strpos($msg, 'confirmation') !== false || strpos($msg, 'confirming') !== false || strpos($msg, 'conforming') !== false ||
			strpos($msg, 'conformed') !== false || strpos($msg, 'confirmed') !== false){
				$prev_ques='';
				
				
				//place insert query here
				
				
				
				$servername = "localhost";
                $username = "unistamgroups_yypizza";
                $password = "yypizza@06";
                $dbname = "unistamgroups_yypizza";
                

                $conn = new mysqli($servername, $username, $password, $dbname);
				$sql="select * from orders";
				$query=mysqli_query($conn,$sql);
				$c=mysqli_num_rows($query)+1;
				$id='YYORD'.$c;
				
				
				$sql="insert into orders(`ord_id`,`items`,`name`,`address`,`phone`) values('$id','$items','$name_in_order','$delivery_addr','$phone')";
				$query=mysqli_query($conn,$sql);
				
				
				$data= ["message" => 'Your order '.$items.' has been confirmed.<br>Please note your Order ID : '.$id.'. <br> We will deliver at your doorstep and collect the cash!<br> Happy meal!', "prev_ques" => $prev_ques
				, "items" => $items, "name_in_order" => $name_in_order, "delivery_addr" => $delivery_addr, "phone" => $phone];
				
				
				return $data;
			}
			else{
				$prev_ques='';
				$items='';
				$data= ["message" => "Your order has been cancelled. Your can order by restarting the app.", "prev_ques" => $prev_ques, "items" => $items, "name_in_order" => $name_in_order, "delivery_addr" => $delivery_addr, "phone" => $phone];
				return $data;
			}
	}
	
	if($prev_ques=='track_order'){
			$order_id=$msg;
			$prev_ques='';
			
			
			$servername = "localhost";
                $username = "unistamgroups_yypizza";
                $password = "yypizza@06";
                $dbname = "unistamgroups_yypizza";
                

                $conn = new mysqli($servername, $username, $password, $dbname);
				$sql="select * from orders where ord_id='$order_id' ";
				$query=mysqli_query($conn,$sql);
			    while($row=mysqli_fetch_assoc($query)){
			        $details='Name : '.$row['name'].'<br>OrderID : '.$row['ord_id'].'<br> Items : '.$row['items'].'<br> Address :'.$row['address'].'<br> Status : '.$row['ord_status'];
			        
			    }
			$data= ["message" => $details, "prev_ques" => $prev_ques, "items" => $items, "name_in_order" => $name_in_order, "delivery_addr" => $delivery_addr, "phone" => $phone];
			return $data;
	}
	
	if(strpos($msg, 'track my order') !== false){

		$prev_ques='track_order';
		$data= ["message" => "Enter your Order ID!", "prev_ques" => $prev_ques, "items" => $items, "name_in_order" => $name_in_order, "delivery_addr" => $delivery_addr, "phone" => $phone];
		return $data;
		
	}

	for($i=0;$i<count($menu_list);$i++){
		if(strpos($msg, $menu_list[$i]) !== false){

		$items=$items.$menu_list[$i];
		$prev_ques='quantity';
		$data= ["message" => "How many ".$menu_list[$i]."s do you like to have?", "prev_ques" => $prev_ques, "items" => $items, "name_in_order" => $name_in_order, "delivery_addr" => $delivery_addr, "phone" => $phone];
		return $data;
		break;
		
		}
	}

	for($i=0;$i<count($menu_category);$i++){
		if(strpos($msg, $menu_category[$i]) !== false){

		$category_list = 'We have a varieties of '.$menu_category[$i].',<br>';
		for($j=0;$j<count($cat_list[$i]);$j++){
			$category_list=$category_list.$cat_list[$i][$j].'<br>';
		}
		$category_list = $category_list.'Please choose one!';
		$data= ["message" => $category_list, "prev_ques" => $prev_ques, "items" => $items, "name_in_order" => $name_in_order, "delivery_addr" => $delivery_addr, "phone" => $phone];
		return $data;
		break;
		
		}
	}

	$data= ["message" => "Your message is not acceptable. Please restart the app and order!", "prev_ques" => $prev_ques, "items" => $items, "name_in_order" => $name_in_order, "delivery_addr" => $delivery_addr, "phone" => $phone];
	return $data;

}

$data_res = get_chatbot_msg();

echo json_encode($data_res);
?>