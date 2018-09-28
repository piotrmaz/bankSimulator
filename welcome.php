<?php
session_start();

	if(isset($_SESSION['udana_rejestracja'])) // jesli zmienna sesyjna 'zalogowany' istnieje i jest 'true'
	{
		require_once "conn.php";
		$polaczenie = new mysqli($host, $db_user, $db_pass, $db_name);
		if ($polaczenie->connect_errno!=0)
			{
				throw new Exception(mysqli_connect_errno());
			}
			else
			{
				$randNumberLength = 18;  // length of your giant random number
				$randNumber = NULL;

				for ($i = 0; $i < $randNumberLength; $i++) 
				{
					$randNumber .= rand(1, 9);  // add random number to growing giant random number
				}
				
				$polaczenie->query("INSERT INTO account VALUES (NULL, '$randNumber', NULL)");
				$polaczenie->close();
			}
			
		header('Location: index.php');									  // przejdz do strony gra.php
		exit();	
		
	}
	else
	{
		unset($_SESSION['udana_rejestracja']);
	}

?>



<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="utf-8">
    <title>Bank - symulator</title>
    <meta name="description" content="Symulator bankowości internetowej. Aplikacja ma pomóc w nauce obsługi konta bankowego w bankowości internetowej.">
    <meta name="keywords" content="bank, acount, przelew, nauka">
    <meta http-equiv="X-Ua-Compatible" content="IE=edge">

    <link rel="stylesheet" href="bankstylee.css">
    <link href="https://fonts.googleapis.com/css?family=Lobster|Open+Sans:400,700&amp;subset=latin-ext" rel="stylesheet">
    <!--[if lt IE 9]>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7.3/html5shiv.min.js"></script>
    <![endif]-->
</head>

<body>
	<nav>
		<div class="navbar">
			<div class="zaloguj">
				<a href="zaloguj.php" target="_blank">Zaloguj</a>
				<div style="clear:both;"></div>	
			</div>	
		</div>
	</nav>
	
	<div class="rectangle">

		<div class="header">
			
			<div class="text">
				<p>Pomyślnie utworzone nowe konto użytkownika. Teraz możesz zalogować się do serwisu.</p>
			</div>
		</div>
	
	</div>
	
	<div class="footer">
			banksimulator.pl &copy; 2018 Wszelkie prawa zastrzeżone.
	</div>
</body>

</html>