<html>
<body>
	<?php
			session_start();
			session_unset();
			session_destroy();
			echo"Logout success<br>";
			echo"<a href=\"login.html\">return to login</a>";
	
	?>
</body>

</html>