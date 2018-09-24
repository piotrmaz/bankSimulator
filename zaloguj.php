<?php
	session_start(); //musi byc na początku plików które maja mieć dostęp do zmiennych sesyjnych
	
	if((!isset($_POST['email'])) || (!isset($_POST['password'])))
	{
		header('Location: index.php'); // przekierowanie do podanej strony
		exit(); //zamyka sesje
	}

require_once "conn.php"; 

	
	// połączenie z bazą danych 
	$polaczenie = @new mysqli($host, $db_user, $db_pass, $db_name); // @ wycisza powiadomienia 
	
	if($polaczenie->connect_errno!=0)
	{
		echo "Error".$polaczenie->connect_errno;
	}
	else
	{
		$email = $_POST['email'];
		$password = $_POST['password'];
		
		/* zabezpieczenie przed sql injection*/
		$email = htmlentities($email, ENT_QUOTES, "UTF-8");
	
		
		/* przepuszcza dane od uzytkownika przez funkcje, mysqli_real_escape_string podnienia dane w miejscu '%s' */
		if($rezultat = $polaczenie->query(sprintf("SELECT * FROM user WHERE email='%s'",
		mysqli_real_escape_string($polaczenie, $email))));
		{
			$ile_emaili = $rezultat->num_rows;
			if($ile_emaili==1)
			{
				$wiersz= $rezultat->fetch_assoc(); // umieszcza o	dczytane dane w tablicy asocjacyjnej
				if(password_verify($password, $wiersz['password']))
				{
				
					$_SESSION['zalogowany'] = true;
					$_SESSION['userId'] = $wiersz['userId'];
					$_SESSION['name'] = $wiersz['name'];
					$_SESSION['lastName'] = $wiersz['drewno'];
					$_SESSION['email'] = $wiersz['email'];
					$_SESSION['pesel'] = $wiersz['pesel'];
		
					
					unset($_SESSION['blad']);
					$rezultat->close();
					header('Location: simulator.php');
				}
				else // informacja o błędnych danych logowania
				{
				$_SESSION['blad'] = '<span style="color:red"> Zły login lub hasło</span>';
 				header('Location: index.php');
				
				}
			}
			else // informacja o błędnych danych logowania
			{
				$_SESSION['blad'] = '<span style="color:red"> Zły login lub hasło</span>';
 				header('Location: index.php');
				
			}
		}
		$polaczenie->close();  // zamyka połączenie z baza danych 
	}
	
?>