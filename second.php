<?php  
		session_start();
		$userid = $_SESSION['userid_data'];
		echo $userid;
?>
<!DOCTYPE html>
<html>
<head>
	<title>transaction page</title>
	<style type="text/css">
		.tran{background-color: lightblue;}
	</style>
</head>
<body>
	<br>
	<div>
		<h2>Please select username with ID number to transfer credit :</h2>
		<form action =''method="POST">
		<select name="selected">
			
			<?php 
			$connection = mysqli_connect('localhost','root','','sproject');
			if(!$connection){
				die("database connection failed  ".mysqli_connect_error());
		}
		echo "database connected"; 
			$query = 'SELECT * FROM users';
			$result = mysqli_query($connection,$query);


				while($row = mysqli_fetch_assoc($result)){
					$id=$row['ID'];
					$name=$row['Name'];
					if($id==$userid)
						continue;

					echo "<option value='$id'>$name , $id</option>   ";
				}

			?>
			</select>
			<br>
			<div>
				<h3>Please enter the credit to be transfer :</h3>
				<input type="text" name="tcredit">
			</div><br>
			<div><input type="submit" name="submit" value="Transact Now" ></div>
		
		</form>

	</div>
	<?php 
		if(isset($_POST['submit'])){
			if(!$connection){
				echo "ooh noo";
			}
			$ammount = $_POST['tcredit'];
			$to_user = $_POST['selected'];
			echo $ammount;
			echo $userid;
			echo $to_user;
			$query2 =" SELECT * FROM users WHERE ID = $userid ";
			$query3 = "SELECT * FROM users WHERE ID = $to_user ";
			$result2 = mysqli_query($connection,$query2);
			if(!$result2){
			die("table not created ".mysqli_error($connection));
		}
			$result3 = mysqli_query($connection,$query3);
			if(!$result3){
			die("table not created ".mysqli_error($connection));
		}
			$row2 = mysqli_fetch_assoc($result2);
			$row3 = mysqli_fetch_assoc($result3);
			$sender = $row2['Name'];
			echo $sender;
			$receiver = $row3['Name'];
			echo $receiver;
			$row2['Current Credit'] -= $ammount;
			$row3['Current Credit'] += $ammount;
			$sender_credit = $row2['Current Credit'];
			$receiver_credit = $row3['Current Credit'];
			echo $sender_credit;
			echo $receiver_credit;


			$query_updt2 = 'UPDATE users SET ';
			$query_updt2 .=  "Current Credit = ' $sender_credit ' ";
			$query_updt2 .=  " WHERE ID = $userid ";

			$result_updt2 = mysqli_query($connection,$query_updt2 );

			if(!$result_updt2){
				echo "sender data not updated ".mysqli_error($connection);
			}


			$query_updt3 = "UPDATE users SET ";
			$query_updt3 .= " Current Credit =  '$receiver_credit' ";
			$query_updt3 .= "  WHERE ID = $to_user " ;
			$result_updt3 = mysqli_query($connection,$query_updt3 );


			if(!$result_updt3){
				echo "receiver data not updated ".mysqli_error($connection);
			}

			mysqli_close($connection); 
			$conn = mysqli_connect('localhost','root','','sproject');
			$trans_query = 'INSERT INTO transfer( Sender ,Receiver ,Credit Transfer) ';
			$trans_query .= " VALUES ('$sender','$receiver',$ammount) ";
			$trans_result = mysqli_query($conn,$trans_query);
			if(!$trans_result){
				echo "<br>";
				echo "hello ".mysqli_error($conn);
			}
			echo "<br>".$ammount." credit is transferred from ".$sender." to ".$receiver;
 
		}

	?>

</body>
</html>