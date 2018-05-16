<html>
	<head><title>Search page</title><head>
	<body>
	<?php
	session_start();
	require_once("oracle-functions.php");
	$conn = connectDB();				
	echo "<b>User name:".$_SESSION['custname']."</b>  | <a href=\"logout.php\">Logout</a></br><hr />";
	$key=$_POST['keyWord'];
	if(!isset($_POST['order'])){echo"Please enter order.";
	if($_SESSION['custid']!='admin'){die("<a href=\"main.php\">return to main page.</a>");}
	else{die("<a href=\"adminPage.php\">return to main page.</a>");}
	}
	$order=$_POST['order'];
	
	echo"<b>Search: ".$key."</b></br><hr />";
	
	$sql="select * from Product where proID like'%".$key."%' or proName like'%".$key."%' 
	or topic like '%".$key."%' or PRODESCRIPTION like '%".$key."%' or pet like '%".$key."%'
	order by ".$order;
	echo"<table BORDERCOLOR=black>";
	if($_SESSION['custid']!='admin'){
		if($statement=oci_parse($conn,$sql)){
					oci_execute($statement);
					
					while($row=oci_fetch_assoc($statement)){	
		echo"<form action=\"buy.php?proID=".$row['PROID']."\" method=\"POST\">";	
			 echo"<tr>
				<td rowspan=\"5\"><IMG SRC='showPhoto.php?proID=".$row['PROID']."' width='200' height='150'/></td>";
			 
			 printf("<td><b>Product ID: </b></td><td>%s</td><td><b>Product Name: </b></td><td>%s</td></tr>",
			 htmlspecialchars($row['PROID']),htmlspecialchars($row['PRONAME']));
			
				
				
				printf("<tr><td><b>Product Type :</b></td><td>%s</td><td><b>Product for: </b></td><td>%s</td></tr>",htmlspecialchars($row['TOPIC']),htmlspecialchars($row['PET']));
				
				printf("<tr><td rowspan=\"2\" colspan=\"2\"><b>Product description: </b></td><td colspan=\"2\">%s</td></tr>", 
				htmlspecialchars($row['PRODESCRIPTION']));
				
				printf("<tr></tr><tr><td><b>Price(per one): </b></td><td colspan=\"2\">%.2f</td><td colspan=\"2\"><input type=\"submit\" value=\"Buy\" /></td></tr><tr></tr>",floatval($row['PRICE'])); 
				echo"</form>";
								
					}
	}
	echo"<a href=\"main.php\">return to main page.</a>";
	}else{
		if($statement=oci_parse($conn,$sql)){
					oci_execute($statement);
						while($row=oci_fetch_assoc($statement)){	
			
			 echo"<tr>
				<td rowspan=\"5\"><IMG SRC='showPhoto.php?proID=".$row['PROID']."' width='200' height='150'/></td>";
			 
			 printf("<td><b>Product ID: </b></td><td>%s</td><td><b>Product Name: </b></td><td>%s</td></tr>",
			 htmlspecialchars($row['PROID']),htmlspecialchars($row['PRONAME']));
			
				
				
				printf("<tr><td><b>Product Type :</b></td><td>%s</td><td><b>Product for: </b></td><td>%s</td></tr>",htmlspecialchars($row['TOPIC']),htmlspecialchars($row['PET']));
				
				printf("<tr><td rowspan=\"2\" colspan=\"2\"><b>Product description: </b></td><td colspan=\"2\">%s</td></tr>", 
				htmlspecialchars($row['PRODESCRIPTION']));
				
				printf("<tr></tr><tr><td><b>Price(per one): </b></td><td colspan=\"3\">%.2f</td></tr><tr></tr>",floatval($row['PRICE'])); 
								
					}
					}
		echo"<a href=\"adminPage.php\">return to main page.</a>";
		}
				oci_free_statement($statement);
				oci_close($conn);	
	?>
		</table>
	
</html>