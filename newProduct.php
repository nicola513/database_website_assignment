<html>
<?php
	session_start();
			require_once("oracle-functions.php");
			$conn = connectDB();	
			
		echo "<b>User name:".$_SESSION['custname']."</b>| <a href=\"logout.php\">Logout</a></br><hr />";
	?>
<?php
if (!isset($_FILES['upload'])) {
$name="";
$desc="";
$price="";
if(isset($_GET['name'])){$name=$_GET['name'];}
if(isset($_GET['desc'])){$desc=$_GET['desc'];}
if(isset($_GET['price'])){$price=$_GET['price'];}
?>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" enctype="multipart/form-data">
<table>
				<tr>
					<td>Product name: </td>
					<td><input name="proName" type="text" maxlength="50" value="<?php echo $name?>"/></td>
				</tr>
				<tr>
					<td>Product description : </td>
					<td><input name="proDesc" type="text" maxlength="100" value="<?php echo $desc?>"/></td>
				</tr>
				<tr>
					<td>Price: </td>
					<td><input name="price" type="number" maxlength="7" step='0.01' value="<?php echo $price?>"/></td>
				</tr>
				<tr>
					<td>Product topic(food/toy): </td>
					<td><input type="radio" name="topic" value="Food" checked>Food
					<input type="radio" name="topic" value="Toy" checked>Toy</td>
				</tr>
				<tr>
					<td>Pet (dog/cat): </td>
					<td><input type="radio" name="pet" value="Dog" checked>Dog
					<input type="radio" name="pet" value="Cat" checked>Cat</td>
				</tr>
	</table>   
    Image filename: <input type="file" name="upload">
    <input type="submit" value="Upload">	

    </form>

    <?php
  }
  else {
    require_once("oracle-functions.php");
    $conn = connectDB();

	$proName=$_POST['proName'];
		$price=$_POST['price'];
		$topic=$_POST['topic'];
		$pet=$_POST['pet'];
		$proDesc=$_POST['proDesc'];
		
		if ($_FILES['upload']['size'] == 0 && $_FILES['upload']['error'] == 4)
		{die ("Image file must not null!!<br>
			<a href=\"addProduct.php\">return to create account</a>");}
			else if($proName==null){$_FILES['upload']=null;
			die("Product name cannot be null.
			<a href=\"newProduct.php?desc=".$proDesc."&price=".$price."\">
			write again</a>");
		}else if($price==null){$_FILES['upload']=null;
			die("Please enter price if you don't will to give the product to customer.
			<a href=\"newProduct.php?name=".$proName."&desc=".$proDesc."\">write agein</a>");
		}else if($proDesc==null){$_FILES['upload']=null;
			die("Please enter price if you don't will to give the product to customer.
			<a href=\"newProduct.php?name=".$proName."&price=".$price."\">write agein</a>");
		}
    
	//Assign the BLOBID value    
    $query = 'select decode(max(PROID)+1, NULL, 1, max(PROID)+1) as MAX_ID from Product';
    $statement = oci_parse ($conn, $query);
    oci_execute($statement, OCI_DEFAULT);
    $arr = oci_fetch_assoc($statement);
    $myblobid = $arr['MAX_ID'];


    // Delete any existing BLOB so the query at the bottom
    // displays the new data

    $query = 'DELETE FROM Product WHERE PROID = :MYBLOBID';
    $statement = oci_parse ($conn, $query);
    oci_bind_by_name($statement, ':MYBLOBID', $myblobid);
    $e = oci_execute($statement, OCI_COMMIT_ON_SUCCESS);

    if (!$e) {
      die;
    }

    oci_free_statement($statement);

	$sql1="INSERT INTO Product (proID,BLOBDATA)"
			."VALUES(:MYBLOBID,EMPTY_BLOB()) RETURNING BLOBDATA INTO :BLOBDATA";
    
	// Insert the BLOB from PHP's tempory upload area
    $lob = oci_new_descriptor($conn, OCI_D_LOB);
    $statement = oci_parse($conn,$sql1);

    oci_bind_by_name($statement, ':MYBLOBID', $myblobid);
    oci_bind_by_name($statement, ':BLOBDATA', $lob, -1, OCI_B_BLOB);
    oci_execute($statement, OCI_DEFAULT);
	
    if ($lob->savefile($_FILES['upload']['tmp_name'])) {
      oci_commit($conn);
    }

    else {
      echo "Couldn't upload Blob\n";
    }
    

    $lob->free();
    oci_free_statement($statement);


	$sql2="update Product set 
	PRODESCRIPTION='".$proDesc."',
	PRONAME='".$proName."',PRICE='".$price."',TOPIC='".$topic."',pet='".$pet."'where proID=".$myblobid;
	
	$statement = oci_parse ($conn, $sql2);
    oci_execute($statement,OCI_COMMIT_ON_SUCCESS);
	
    // Now query the uploaded BLOB and display it
    
	?>
	
	<table BORDERCOLOR=black>";

<?php
$sql3 = "SELECT * FROM Product WHERE PROID =".$myblobid;
	if($statement=oci_parse($conn,$sql3)){
		
					oci_execute($statement);
					$row=oci_fetch_assoc($statement);
    // If any text (or whitespace!) is printed before this header is sent,
    // the text won't be displayed and the image won't display properly.
	     echo"<tr>";
				 echo"<td rowspan=\"5\"><IMG SRC='showPhoto.php?proID=".$row['PROID']."' width='200' height='150'/></td>";
			 
			 printf("<td><b>Product ID: </b></td><td>%s</td><td><b>Product Name: </b></td><td>%s</td></tr>",
			 htmlspecialchars($row['PROID']),htmlspecialchars($row['PRONAME']));
			
				
				
				printf("<tr><td><b>Product Type :</b></td><td>%s</td><td><b>Product for: </b></td><td>%s</td></tr>",htmlspecialchars($row['TOPIC']),htmlspecialchars($row['PET']));
				
				printf("<tr><td rowspan=\"2\" colspan=\"2\"><b>Product description: </b></td><td colspan=\"2\">%s</td></tr>", 
				htmlspecialchars($row['PRODESCRIPTION']));
				
				printf("<tr></tr><tr><td><b>Price(per one): </b></td><td colspan=\"3\">%.2f</td></tr><tr></tr>",floatval($row['PRICE'])); 
	}

    oci_free_statement($statement);
    oci_close($conn); // log off
    }
?>
</table>
</br>
<a href="adminPage.php">Return main page</a></br>
</html>