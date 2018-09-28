<?php
session_start();

if((isset($_SESSION['zalogowany']))&&($_SESSION['zalogowany']==true)) // jesli zmienna sesyjna 'zalogowany' istnieje i jest 'true'
{
	header('Location: simulator.php');									  // przejdz do strony gra.php
	exit();															  // zamyka sesje
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
				<a href="logowanie.php" target="_blank">Zaloguj</a>
				<div style="clear:both;"></div>	
			</div>	
		</div>
	</nav>
	
	<div class="rectangle">

		<form action="zaloguj.php" method="post">
		Email</br><input type="text" name="email"/>
		</br></br>
		Hasło<br/><input type="password" name="password"/>
	
		<br/></br>
		<input type="submit" value="zaloguj"/>
	</form>
	
	</div>
	
	<div class="footer">
			banksimulator.pl &copy; 2018 Wszelkie prawa zastrzeżone.
	</div>
	
	<?php
	
		if(isset($_SESSION['blad']))		// wyswietla informacje o błędnych logowaniu
		{
			echo $_SESSION['blad'];
		}
	
	?>
</body>

</html>