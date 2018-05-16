<html>
	<head><title>Create account</title></head>
	<body>
		<?php
			require_once("oracle-functions.php");    
			$conn = connectDB(); 
			$custID=$_POST['custID'];
			$password=$_POST['password'];
			$password2=$_POST['password2'];
			$custName=$_POST['custName'];
			$phoneNum=$_POST['phoneNum'];
			$address =$_POST['address'];
			
			if ($custID==null){die ("Customer account must not null!!<br>
			<a href=\"creating.html\">return to create account</a>");}
			else if ($password==null){die ("Password must not null!!<br>
			<a href=\"creating.html\">return to create account</a>");}
			else if ($password!=$password2){die ("Password and again password must be same!!
			<a href=\"creating.html\">return to create account</a>");}
			else if ($custName==null){die ("Customer name must not null!!<br>
			<a href=\"creating.html\">return to create account</a>");}
			else if ($phoneNum==null){die ("Phone number must not null!!<br>
			<a href=\"creating.html\">return to create account</a>");}
			else if ($address==null){die ("Address must not null!!<br>
			<a href=\"creating.html\">return to create account</a>");}
			
			$sql1="select custID from Customer where custID='".$custID."'";
			$sql2="insert into Customer values('".$custID."','".$password."','".$custName."','".$phoneNum."','".$address."')";
			
			if($statement=oci_parse($conn,$sql1)){
				oci_execute($statement);
				$row=oci_fetch_assoc($statement);
				if($row['CUSTID']==$custID){
					die("Create account failed! Customer account is exists.<br>
					<a href=\"creating.html\">return to create account.</a><br>
					<a href=\"login.html\">return to login page.</a>");
				}
			}else die("Create account failed! <a href=\"creating.html\">return to create account.</a>.");
			
			if($statement = oci_parse($conn, $sql2)){
				oci_execute($statement);
				echo "Account create success<br>".'
				<a href="login.html">return to login page.</a>';
			}else die("<b>Create account failed!</b><br> <a href=\"creating.html\">Please try again.</a>.");
			
		?>
	</body>
</html>