
		<?php
		session_start();
		echo "<br><b>User name: ".$_SESSION["custname"]."</b> | <a href=\"logout.php\">Logout</a></br><hr />";
		if (!isset($_FILES['upload'])) {
		?>
		<hr />
		<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" enctype="multipart/form-data">
			<table>
				<tr>
					<td>Product name: </td>
					<td><input name="proName" type="text" maxlength="50"/></td>
				</tr>
				<tr>
					<td>Product description : </td>
					<td><input name="proDesc" type="text" maxlength="100"/></td>
				</tr>
				<tr>
					<td>Price: </td>
					<td><input name="price" type="text"  maxlength="7"/></td>
				</tr>
				<tr>
					<td>Product topic(food/toy): </td>
					<td><input name="topic" type="text" maxlength="10"/></td>
				</tr>
				<tr>
					<td>Pet (dog/cat): </td>
					<td><input name="pet" type="text" maxlength="3"/></td>
				</tr>
				<tr>
					<td>Image filename: <input type="file" name="upload" id="upload"></td>
				</tr>
				<tr>
					<td> <input type="submit" value="Upload new product"></td>
				</tr>
			</table>
		</form>
		<?php
		} 
		else {require_once("oracle-functions.php");
		$conn = connectDB();

		$imageFiles=$_FILES['upload']['tmp_name'];
		$proName=$_POST['proName'];
		$price=$_POST['price'];
		$topic=$_POST['topic'];
		$pet=$_POST['pet'];
		$proDesc=$_POST['proDesc'];

		if ($_FILES['upload']['error'] == UPLOAD_ERR_NO_FILE)
		{die ("Image file must not null!!<br>
			<a href=\"addProduct.html\">return to create account</a>");}
			else if ($proName==null){die ("Product number must not null!!<br>
			<a href=\"addProduct.html\">return to create account</a>");}
			else if ($price==null){die ("price must not null!!<br>
			<a href=\"addProduct.html\">return to create account</a>");}
			else if ($topic==null){die ("Topic must not null!!<br>
			<a href=\"addProduct.html\">return to create account</a>");}
			else if ($pet==null){die ("Pet topic must not null!!<br>
			<a href=\"addProduct.html\">return to create account</a>");}
			else if ($proDesc==null){die ("Product description must not null!!<br>
			<a href=\"addProduct.html\">return to create account</a>");}
			
		
		 $query = 'select decode(max(proID)+1, NULL, 1, max(proID)+1) as MAX_ID from Product';
			$statement = oci_parse ($conn, $query);
			oci_execute($statement, OCI_DEFAULT);
			$arr = oci_fetch_assoc($statement);
			$proID = $arr['MAX_ID'];
			
		 $query = 'DELETE FROM Product WHERE proID = :PROID';
		 $statement = oci_parse ($conn, $query);
		 oci_bind_by_name($statement, ':PROID', $proID);
		 $e = oci_execute($statement, OCI_COMMIT_ON_SUCCESS);

			if (!$e) {
			  die("error");
			}
			
			oci_free_statement($statement);
			
			$lob = oci_new_descriptor($conn, OCI_D_LOB);
			$sql="INSERT INTO PRODUCT (PROID,BLOBDATA )"."
			VALUES(:PROID,EMPTY_BLOB()) 
			returning BLOBDATA INTO :BLOBDATA";
			$statement = oci_parse($conn,$sql);

			oci_bind_by_name($statement, ':PROID', $proID);
			oci_bind_by_name($statement, ':BLOBDATA', $lob, -1, OCI_B_BLOB);
			$e=oci_execute($statement, OCI_DEFAULT);
			
			if (!$e) {
			  die("error".$proID);
			}
			
			 if ($lob->savefile($imageFiles)) {
			  oci_commit($conn);
			   echo "BLOB uploaded";
			}else {
			  echo "Couldn't upload Blob\n";
			}
			$lob->free();
			oci_free_statement($statement);
			
			$sql="update Product set proDescription='".$proDesc."', proName='".$proName."' ,price=".$price." , topic='".$topic."' ,pet='".$pet."' where proID=:MYBLOBID";
			$statement = oci_parse ($conn, $sql);
			oci_bind_by_name($statement, ':MYBLOBID', $proID);
			$e = oci_execute($statement, OCI_COMMIT_ON_SUCCESS);
			oci_free_statement($statement);
				echo"<a href=\"adminPage.php\">return to main page.</a>";

				oci_close($conn); // log off
				}
				?>
