<html>
<head><title>Buy page</title></head>
<body>
<?php
	session_start();
	require_once("oracle-functions.php");
	$conn = connectDB();	
	echo "<b>User name:".$_SESSION['custname']."</b>  | <a href=\"logout.php\">Logout</a></br><hr />";
	$proID=$_GET['proID'];
	$_SESSION['proID']=$proID;
	echo"<form action=\"buyProduct.php\" method=\"POST\"> ";
	?>
	
		<table border="1">
			<tr>
				<td>Product ID</td>
				<td>Product name</td>
				<td>Quantity</td>
			</tr>
			<tr>
				<?php
				$sql="select * from Product where proID='".$proID."'";
				if($statement=oci_parse($conn,$sql)){
				oci_execute($statement);
				$row=oci_fetch_assoc($statement);
				 printf("<td>%s</td><td>%s</td>",
				htmlspecialchars($row['PROID']),htmlspecialchars($row['PRONAME']));}
				
				echo"<td><input type=\"number\" name=\"quantity\" /></td>
			</tr>
			<tr><input type=\"submit\" value=\"Buy\" /></tr>"?>
		</table>
	</form>
	<br/>
	<a href="main.php">Return main page</a></br>
	</body>
</html>