<?php session_start();
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
        <div class="tytul">
        <?php
            function filtruj($zmienna)
            {
                if(get_magic_quotes_gpc())$zmienna = stripslashes($zmienna); // usuwamy slashe
	           // usuwamy spacje, tagi html oraz niebezpieczne znaki
                return mysql_real_escape_string(htmlspecialchars(trim($zmienna)));
            }
            $up=$_GET['up'];
            $login=filtruj($_SESSION['login']);
            $text=$_GET['text'];
            $idm=$_GET['id'];
            $idu=$_GET['idus'];
            $akcja=$_GET['akcja'];
            $wynik = mysql_query("SELECT login FROM users WHERE login<>'$login'")or die('Błąd zapytania'); 

            echo '<form method="get" action="edycja.php?akcja">';
                echo '<textarea cols="50" rows="5" name="text" >'.$text.'</textarea> ';
                //echo '<input type="hidden" name="login" value="'.$login.'">';
                echo '<input type="hidden" name="id" value="'.$idm.'">';
                echo '<input type="hidden" name="up" value="'.$up.'">';
                echo '<input type="hidden" name="idus" value="'.$idu.'">';
                echo '<input type="hidden" name="akcja" value="edytuj">';
                echo '<br/><input type="submit" value="Edytuj">';
            echo '</form>';
        
            if($up==1)
            {
                echo '<br/><form method="get" action="edycja.php?up='.$up.'&akcja">';
                    echo'Wybierz użytkownika:<select name="iduprawnienia">';
                        while($r = mysql_fetch_assoc($wynik)) 
                        { 
                              echo'<option value="'.$r['login'].'">'.$r['login'].'</option>';	
                        } 
                    echo'</select>'; 
                    //echo '<input type="hidden" name="login" value="'.$login.'">';
                    echo '<input type="hidden" name="id" value="'.$idm.'">';
                    echo '<input type="hidden" name="idus" value="'.$idu.'">';
                    echo '<input type="hidden" name="up" value="'.$up.'">';
                    echo '<input type="hidden" name="text" value="'.$text.'">';
                    echo '<br/><br/><button type="submit" name="akcja" value="dodaj">Dodaj uprawnienia</button>
                          <button type="submit" name="akcja" value="odbierz">Odbierz uprawnienia</button>';										
                echo '</form>';
            }
        
            echo'<br/><form method="get" action="wiadomosci.php">';
                //echo '<input type="hidden" name="login" value="'.$login.'">';
                echo '<button type="submit">Wróć</button>';
            echo '</form>'; 

            if($akcja=="edytuj")
            {
	             $idd=$_GET['id'];
	             $user=$_GET['idus'];
		         $ins=@mysql_query("UPDATE messages set text='$text' where id='$idd';") or die('blad');
		         if($ins) echo "<div class='info'>Wiadomość została zmieniona</div>"; 
                 else echo "<div id='err'>Błąd nie udało się wprowadzić zmian</div>"; 
		    }
                
            if($akcja=="dodaj"&&$up==1)
            {
                $idd=$_GET['id'];
                $user=$_GET['idus'];
                $idupr=$_GET['iduprawnienia'];
                $idupr2= mysql_fetch_row(mysql_query("select id FROM users WHERE login='$idupr'")) or die('Nie ma takiego użytkownika'); 
		        $idupr3=$idupr2[0];
                if(mysql_num_rows(mysql_query("SELECT * FROM permissions WHERE id_user='$idupr3' and id_message='$idd';")) == 0)
	            {
		  		     $ins=@mysql_query("INSERT INTO permissions (id_user, id_message) values('$idupr3','$idd');") or die('blad');
				     if($ins) echo "<div class='info'>Uprawnienia zostały nadane.</div>"; 
                     else echo "<div id='err'>Błąd nie udało się dodać uprawnień</div>"; 
		        }
                else echo"<div class='info'>Ten użytkownik posiada już uprawnienia.</div>";
            }
    
            if($akcja=="odbierz"&&$up==1)
            {
                $idd=$_GET['id'];
                $user=$_GET['idus'];
                $idupr=$_GET['iduprawnienia'];
                $idupr2= mysql_fetch_row(mysql_query("select id FROM users WHERE login='$idupr'")) or die('Nie ma takiego użytkownika'); 
                $idupr3=$idupr2[0];
                if(mysql_num_rows(mysql_query("SELECT * FROM permissions WHERE id_user='$idupr3' and id_message='$idd';")) == 1)
                {
		  		     $ins=@mysql_query("DELETE FROM permissions where id_user='$idupr3' and id_message='$idd' ;") or die('blad');
				     if($ins) echo "<div class='info'>Uprawnienia zostały odebrane.</div>"; 
                     else echo "<div id='err'>Błąd nie udało się usunąć uprawnień</div>"; 
                }
                 else echo"<div class='info'>Ten użytkownik nie posiada uprawnień.</div>";
            }
        ?>
            </div>
    </body>
</html>