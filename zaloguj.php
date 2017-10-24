<?php 
    session_start();
    require "connection.php"; 
    connection(); 
?>

<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <link href="css/style.css" rel="stylesheet" type="text/css" />
        <title>Forum</title>
    </head>
    <body>
        <div id="tytul">Forum</div>
        <?php 
            $akcja = $_GET['akcja'];
            if ($akcja == "Zaloguj się")  
            {
		          if (isset($_GET['login']) && isset($_GET['password'])) 
                  {
                      $login = mysql_real_escape_string($_GET['login']);
                      $password = mysql_real_escape_string($_GET['password']);
                      $sql = mysql_num_rows(mysql_query("SELECT * FROM users WHERE login = '$login' AND haslo = '$password'"));

                      if ($sql == 1) 
                      {
                        //$_GET['login'] = $login;
                        $_SESSION['login'] = $login;
                        $_SESSION['log'] = TRUE;
                        header('Location:index.php');
                      }
                      else 
                      {
                        echo '<div id="info"><span id="err">Błąd podczas logowania do systemu. </span>';
                        echo '<a href="index.php">Wróć do formularza</a></div>';
                     }
		          }
                  else 
                  {
                    echo '<div id="info"><span id="err">Błąd podczas logowania do systemu. </span>';
                    echo '<a href="index.php">Wróć do formularza</a></div>';	
                  }
            }
        ?>
    </body> 
</html>