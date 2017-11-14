<?php session_start();
require "connection.php"; 
connection(); 
if(!isset($_SESSION['login']))
    {
        echo '<div id="info"><span id="err">Błąd. </span>';
        echo '<a href="index.php">Strona główna</a></div>';
        exit();
    }
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
            
            $login=filtruj($_SESSION['login']);
            $text=$_GET['text'];
            $idm=$_GET['id'];
            $idu=$_GET['idus'];
            $akcja=$_GET['akcja'];
            
            $aaa=$_SESSION['login'];
            $sql = mysql_query("SELECT id FROM users WHERE login = '$aaa'");
            
        
            if(mysql_num_rows($sql)) 
            { 
                $r = mysql_fetch_assoc($sql);
                $r3=$r['id'];  
            }
            
            if (mysql_num_rows(mysql_query("SELECT * FROM messages WHERE id='".$_GET['id']."' and id_user = '".$_GET['idus']."';")) == 1) 
                $cos=1;
            else $cos=0;
            if (mysql_num_rows(mysql_query("SELECT * FROM permissions  WHERE id_user='".$r3."' and id_message = '".$_GET['id']."';")) == 1)           $cos2=1;
            else $cos2=0;
            if($r3==$_GET['idus']&&$cos==1||$cos2==1)
            {
                $wynik = mysql_query("SELECT login FROM users WHERE login<>'$login'")or die('Błąd zapytania'); 
                echo '<form method="get" action="edycja.php?akcja">';
                    echo '<textarea cols="50" rows="5" name="text" >'.$text.'</textarea> ';
                    //echo '<input type="hidden" name="login" value="'.$login.'">';
                    echo '<input type="hidden" name="id" value="'.$idm.'">';
                    echo '<input type="hidden" name="idus" value="'.$idu.'">';
                    echo '<input type="hidden" name="akcja" value="edytuj">';
                    echo '<br/><input type="submit" value="Edytuj">';
                echo '</form>';
        
                if($r3==$_GET['idus']&&$cos==1)
                {
                    echo '<br/><form method="get" action="edycja.php?akcja">';
                        echo'Wybierz użytkownika:<select name="iduprawnienia">';
                            while($r = mysql_fetch_assoc($wynik)) 
                            { 
                                  echo'<option value="'.$r['login'].'">'.$r['login'].'</option>';	
                            } 
                        echo'</select>'; 
                        //echo '<input type="hidden" name="login" value="'.$login.'">';
                        echo '<input type="hidden" name="id" value="'.$idm.'">';
                        echo '<input type="hidden" name="idus" value="'.$idu.'">';
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

                
                if($akcja=="dodaj"&&$cos==1&&$r3==$_GET['idus'])
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

    
                if($akcja=="odbierz"&&$cos==1&&$r3==$_GET['idus'])
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
            }
            else
            {
                echo'<div id="err">Brak uprawnień</div>';
                echo'<br/><form method="get" action="wiadomosci.php">';
                    echo '<button type="submit">Wróć</button>';
                echo '</form>'; 
            }
        ?>
            </div>
    </body>
</html>