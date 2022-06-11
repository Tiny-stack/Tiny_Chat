<?php
				session_start();
				//require('db.php');
				$u_name=$_SESSION['user_name'];
				$pass=$_SESSION['pass'];
				date_default_timezone_set('UTC');
   $currenttime=time();
        echo $u_name;
        //echo "    ";
        echo $pass;
				$db=mysqli_connect('localhost','root','12345','sol_tech');

				$query="SELECT user_ID,user_name,user_pass FROM user_details WHERE user_name = ?";   //Password authentication
   				$stmt=$db->prepare($query);
   				$stmt->bind_param('s',$u_name);
   				$stmt->execute();
   				$stmt->bind_result($ID,$name,$en_pass);
   				$stmt->store_result();
   				
   				while ($stmt->fetch()) // In case of more than one user of given name 
   				{
      				if($u_name==$name && ($pass==$en_pass))//Encryted password verification
      				{
                $id=$ID;
                //$zero=NULL;
                $qu="INSERT INTO user_pic VALUES(0,$id,'uploads/1637879339.png',99)";
         
          			$mt=$db->prepare($qu);
          			$mt->execute();
          			$qu="INSERT INTO banner_pic VALUES(0,$id,'uploads/1637879339.png',99)";
         
          			$mt=$db->prepare($qu);
          			$mt->execute();
                echo "creating_table";
			  
			         	
			         	break;   
			         }

     			 }


?>
<!DOCTYPE html>
<html>
<head>
  <title>nice</title>
</head>
<body>
  <script type="text/javascript">
    window.location.replace('login.html');


    </script>
</body>
</html>