<?php
require_once("oracle-functions.php");     
$conn = connectDB(); 

$custID=$_POST['custID'];
$password=$_POST['password'];

if($custID==null){
	die("UserID should not null.Please enter again!<br><a href=\"login.html\">return to login page.</a>");
	}
else if($password==null){
	die("Password should not null. Please enter again!<br><a href=\"login.html\">return to login page.</a>");
	}

$sql="select * from Customer where custID='".$custID."'and PASSWORD='".$password."'";
  session_start();
  if($statement=oci_parse($conn,$sql)){
	  oci_execute($statement);
	  $row=oci_fetch_assoc($statement);
	  if($row['CUSTID']==null){echo"Customer account is not exist.<a href=\"login.html\">return to login page.</a>";
								return 0;
								}
	if($row['PASSWORD'] !=null){	
		$_SESSION['custid']=$row['CUSTID'];
		$_SESSION['custname']=$row['CUSTNAME'];
		$_SESSION['phonenum']=$row['PHONENUM'];
		$_SESSION['address']=$row['ADDRESS'];
		echo "ID: ".$row['CUSTID']."|Name: ".$row['CUSTNAME'];
			if($row['CUSTID']!='admin'){
			header("Location: main.php");}
			else{
				header("Location: adminPage.php");
			}
		}else die("Password incorrect!!<br><a href=\"login.html\">return to login page.</a>");
	}
   else die( "Login Failed<br><a href=\"login.html\">return to login page.</a>");



?>