<?php
session_start();

if (isset($_POST['email']))
	{
		//Udana walidacja? Załóżmy, że tak!
		$wszystko_ok=true;
		
		//Sprawdź poprawność nickname'a
		$name = $_POST['name'];
		
		//Sprawdzenie długości imienia
		if ((strlen($name)<3) || (strlen($name)>20)) // funkcja sprawdza długość ciągu znaków
		{
			$wszystko_ok=false;
			$_SESSION['e_name']="Imię musi posiadać od 3 do 20 znaków!";
		}
		if(ctype_alpha($name)==false) // funkcja sprawdza czy ciąg znaków to litery
		{
			$wszystko_ok=false;
			$_SESSION['e_name']="Imię musi składać się z liter bez polskich znaków";
		}
		//Sprawdzanie poprawności nazwiska
		$lastName = $_POST['lastName'];
		
		if ((strlen($lastName)<2)) // funkcja sprawdza długość ciągu znaków
		{
			$wszystko_ok=false;
			$_SESSION['e_lastName']="Nazwisko musi posiadać conajmniej 2 litery bez polskich znaków!";
		}
		if(ctype_alpha($lastName)==false) // funkcja sprawdza czy ciąg znaków to litery
		{
			$wszystko_ok=false;
			$_SESSION['e_lastName']="Nazwisko musi składać się z liter bez polskich znaków";
		}
		
		//sprawdzanie email
		$email = $_POST['email'];
		$emailB= filter_var($email, FILTER_SANITIZE_EMAIL); //sprawdza poprawność email - polskie znaki. 1 argument co ma sprawdza, 2. stała -filtr
		
		if((filter_var($emailB, FILTER_VALIDATE_EMAIL)==false) || ($email!=$emailB)) 	// sprawdza poprawność budowy adresu email
		{
			$wszystko_ok=false;
			$_SESSION['e_email']="Podaj poprawny adres email";
			
		}
		
		//sprawdz poprawnosc hasla
		$password1 = $_POST['password1'];
		$password2 = $_POST['password2'];
		
		if ((strlen($password1)<8) || (strlen($password1)>20)) // funkcja sprawdza długość ciągu znaków
		{
			$wszystko_ok=false;
			$_SESSION['e_password']="Hasło musi posiadać od 8 do 20 znaków!";
		}
		
		if ($password1!=$password2)
		{
			$wszystko_ok=false;
			$_SESSION['e_password2']="Hasła muszą być identyczne!";
		}
	
		$password_hash = password_hash($password1, PASSWORD_DEFAULT);		//hashowanie hasła
		
		
		// czy zaakceptowano regulamin 
		if(!isset($_POST['regulamin']))
		{
			$wszystko_ok=false;
			$_SESSION['e_regulamin']="Musisz zaakceptować regulamin!";
		}
		
		// sprawdz recaptcha
		$sekret = "6LcKeHEUAAAAABzTCEyHb-z3OoDeqGAIgpoFAVDu";
		
		$sprawdz = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$sekret.'&response='.$_POST['g-recaptcha-response']);
		
		$odpowiedz= json_decode($sprawdz);
		
		if($odpowiedz->success==false)
		{
			$wszystko_ok=false;
			$_SESSION['e_bot']="Potwierdź że nie jesteś botem";
		}

	
		require_once "conn.php";
		mysqli_report(MYSQLI_REPORT_STRICT); //funkcja która mówi ze zamiast ostrzezenia rzuca wyjącek
		try 
		{
			$polaczenie = new mysqli($host, $db_user, $db_pass, $db_name);
			if ($polaczenie->connect_errno!=0)
			{
				throw new Exception(mysqli_connect_errno());
			}
			else
			{
				//czy email istnieje?
				
				$rezultat = $polaczenie->query("SELECT userId FROM user WHERE email='$email'");
				
				if(!$rezultat) throw new Exception($polaczenie->error);
				
				$ile_takich_maili= $rezultat->num_rows;
				if($ile_takich_maili>0)
				{
					$wszystko_ok=false;
					$_SESSION['e_email']="Istnieje już konto o takim adresie email!";	
				}
				
				
				if($wszystko_ok==true)
				{
					if($polaczenie->query("INSERT INTO user VALUES (NULL, 1, 87111123498, '$email', '$name', '$lastName', '$password_hash')"))
					
					{
						$_SESSION['udana_rejestracja']=true;
						header('Location: welcome.php');
					}
					else
					{
						if(!$rezultat) throw new Exception($polaczenie->error);
					}
					exit();
				}
				
				$polaczenie->close();
			}
						
			
		}
		catch(Exception $e)
		{
			echo '<span style="color:red;">Błąd serwera! Przepraszamy za niedogodności i prosimy o rejestrację w innym terminie!</span>';
			echo '<br />Informacja developerska: '.$e;
		}
		
		
		
	
}
?>



<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="utf-8">
    <title>Bank - symulator</title>
    <meta name="description" content="Symulator bankowości internetowej">
    <meta name="keywords" content="bank, acount, przelew, nauka">
    <meta http-equiv="X-Ua-Compatible" content="IE=edge">

    <link rel="stylesheet" href="bankstylee.css">
    <link href="https://fonts.googleapis.com/css?family=Lobster|Open+Sans:400,700&amp;subset=latin-ext" rel="stylesheet">
    <!--[if lt IE 9]>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7.3/html5shiv.min.js"></script>
    <![endif]-->
	
	<script src='https://www.google.com/recaptcha/api.js'></script> 
	
	<style>
	.error
	{
		color: red;
		margin-top: 10px;
		margin-bottom: 10px;
	}
	
	</style>
</head>

<body>
	<nav>
		<div class="navbar">
			<div class="zaloguj">
				<a href="index.php">Wstecz</a>
			</div>
		</div>
	</nav>
	
<form method="post">
	
		Imię: </br><input type="text" name="name" /></br>
		<?php
			if(isset($_SESSION['e_name']))
			{
				echo '<div class="error">'.$_SESSION['e_name'].'</div>';
				unset($_SESSION['e_name']);	
			}
		?>
		Nazwisko: </br><input type="text" name="lastName" /></br>
		<?php
			if(isset($_SESSION['e_lastName']))
			{
				echo '<div class="error">'.$_SESSION['e_lastName'].'</div>';
				unset($_SESSION['e_lastName']);	
			}
		?>
		E-mail: </br><input type="text" name="email" /></br>
		<?php
			if(isset($_SESSION['e_email']))
			{
				echo '<div class="error">'.$_SESSION['e_email'].'</div>';
				unset($_SESSION['e_email']);	
			}
		?>
		
		Hasło: </br><input type="password" name="password1" /></br>
		<?php
			if(isset($_SESSION['e_password']))
			{
				echo '<div class="error">'.$_SESSION['e_password'].'</div>';
				unset($_SESSION['e_password']);	
			}
		?>
		Powtórz hasło: </br><input type="password" name="password2" /></br>
		<?php
			if(isset($_SESSION['e_password2']))																																																											
			{
				echo '<div class="error">'.$_SESSION['e_password2'].'</div>';
				unset($_SESSION['e_password2']);	
			}
		?>
		<label><input type="checkbox" name="regulamin">Akceptuję regulamin</label>
		<?php
			if(isset($_SESSION['e_regulamin']))
			{
				echo '<div class="error">'.$_SESSION['e_regulamin'].'</div>';
				unset($_SESSION['e_regulamin']);	
			}
		?>
		
		<div class="g-recaptcha" data-sitekey="6LcKeHEUAAAAAAYTaonvNjD-frKL4PDmDug8UbyY"></div></br>
		
		<?php
			if(isset($_SESSION['e_bot']))
			{
				echo '<div class="error">'.$_SESSION['e_bot'].'</div>';
				unset($_SESSION['e_bot']);	
			}
		?>
		<input type="submit" value="Zarejestruj"/>
	
	</form>
</body>

</html>