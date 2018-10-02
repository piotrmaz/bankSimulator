<style>
.pulpit
{
	background-color: white;
	width: 950px;
	height: 450px;
	margin: auto;
	
}

.dane
{
	width: 800px;
	height: 300px;
	margin-top: 35px;
	padding: 25px;
}

.dane p
{
	font-size: 24px;
}

</style>

<div class="pulpit">

	<div class="dane">
			
	<?php
	echo "<p><b><i>Witaj ".$_SESSION['name'].' !</p></i></b></br>';
	
	require_once "conn.php"; 

	
	// połączenie z bazą danych 
	$polaczenie = @new mysqli($host, $db_user, $db_pass, $db_name); // @ wycisza powiadomienia 
	
	if($polaczenie->connect_errno!=0)
	{
		echo "Error".$polaczenie->connect_errno;
	}
	else
	{
		$a= $_SESSION['userId'];
		$sql = "SELECT account FROM account WHERE userId='$a'";
		$result=mysqli_query($polaczenie,$sql);
		
		$row=mysqli_fetch_array($result,MYSQLI_ASSOC);
		echo '<h3>'."Numer rachunku ".'</br></br></h3>';
		echo $row["account"];
	}
	?>
	<h3>Dostępne środki</h3>
	<?php
	$a= $_SESSION['userId'];
		$sql = "SELECT saldo FROM account WHERE userId='$a'";
		$result=mysqli_query($polaczenie,$sql);
		
		$row=mysqli_fetch_array($result,MYSQLI_ASSOC);
		echo $row["saldo"]." PLN";
	?>
	
	</div>
</div>
