<?php
session_start();	

session_unset();		// wylogowanie sesji

header('Location: wylogowany.php');	// przejscie do strony

?>



