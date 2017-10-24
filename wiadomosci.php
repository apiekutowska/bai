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
        <div class="tytul">Wiadomości:</div>
        <div id="tabela">
        <?php
            if(isset($_SESSION['login']))
            {
                echo '<div id="witaj">Witaj '.$_SESSION['login'].'! <a href="logout.php">(Wyloguj się)</a></div>';
                
                echo '<br/><br/><br/><form method="get" action="wiadomosci.php">';
                echo '<textarea cols="50" rows="5" name="tekst" >Twoja wiadomość...</textarea> ';
                //echo '<input type="hidden" name="login" value="'.$_GET['login'].'">';
                echo '<input type="hidden" name="akcja" value="dodaj">';
                echo '<input type="submit" value="Dodaj">';
                echo '</form>';	
		    }
            else 
            {
			     echo '<div id="witaj"><a href="index.php">Zaloguj się</a></div>';
            }  
            $wynik = mysql_query("SELECT id_user, data_dod, login, text, m.id FROM users u, messages m WHERE  m.id_user=u.id")or die('Błąd zapytania'); 
            echo "<br/><br/>"; 
        
            if(mysql_num_rows($wynik) > 0) 
            { 
                echo "<br/><br/><br/><table>"; 
                while($r = mysql_fetch_assoc($wynik)) 
                { 
                    echo "<tr>"; 
		            echo "<td>".$r['data_dod']."</td>";
                    echo "<td>".$r['login']."</td>"; 
                    echo "<td>".$r['text']."</td>"; 
                    if(isset($_SESSION['login']))
                    {
                        echo "<td> <a href=\"wiadomosci.php?&akcja=usun&id={$r['id']}&idus={$r['id_user']}\">USUŃ</a></td>"; 
                        echo "<td> <a href=\"wiadomosci.php?&akcja=edytuj&id={$r['id']}&idus={$r['id_user']}&text={$r['text']}\">EDYTUJ</a>  </td>";    
                    }
                    echo "</tr>"; 
                } 
                echo "</table>"; 
            }                           
            if(isset($_SESSION['login'])&&isset($_GET['akcja']))
            {

                function filtruj($zmienna)
                {           
                    if(get_magic_quotes_gpc())$zmienna = stripslashes($zmienna); // usuwamy slashe
	               // usuwamy spacje, tagi html oraz niebezpieczne znaki
                    return mysql_real_escape_string(htmlspecialchars(trim($zmienna)));
                }
                $login=filtruj($_SESSION['login']);
                $iduss= mysql_fetch_row(mysql_query("select id FROM users WHERE login='$login'")) or die('<div id="err">Błąd zapytania</div>');
                $akcja=$_GET['akcja'];
                
                if ($akcja=='dodaj') 
                {
	                   $login2 = $_SESSION['login'];
                       $data=date("Y-m-d");
	                   $idu=mysql_fetch_row(mysql_query("SELECT id FROM users WHERE login='$login2'"))or die('<div id="err">Błąd zapytania</div>');
                       $ipm=mysql_fetch_row(mysql_query("SELECT id+1 FROM messages WHERE id=(select max(id) from messages)"));
	                   $tekst = filtruj($_GET['tekst']);
	
	                   if (mysql_num_rows(mysql_query("SELECT id FROM users WHERE login = '$login2';")) == 1)
	                   {
			                 $ins=@mysql_query("INSERT INTO messages set id=NULL, id_user='$idu[0]', text='$tekst', data_dod='$data';") or die(mysql_error());
                             $inam=@mysql_query("INSERT INTO permissions set id=NULL, id_user='$idu[0]', id_message='$ipm[0]';") or die(mysql_error());
                           
				             if($ins) echo '<meta http-equiv="refresh" content="0; URL=wiadomosci.php">';
                             else echo "<br/><br/><div id='err'>Błąd nie udało się dodać wiadomości</div>"; 
		               }
                }
                
                if($akcja=='usun')
                {
                    $login = $_SESSION['login'];
                    $idd=$_GET['id'];
	                $user=$_GET['idus'];
                    $idus2= mysql_fetch_row(mysql_query("select id FROM users WHERE login='$login'")) or die('<div id="err">Błąd zapytania</div>'); 
		            $iduser2= mysql_fetch_row(mysql_query("select id_user FROM messages WHERE id='$idd'")) or die('<div id="err">Błąd zapytania</div>'); 
		            $iduser=$iduser2[0];
		            $idus=$idus2[0];
		
		            if($idus == $iduser)
                    {
                        $wynik2 = mysql_query("delete FROM messages WHERE id='$idd'") or die('<div id="err">Błąd zapytania</div>'); 
                        $wynik3 = mysql_query("delete FROM permissions WHERE id_message='$idd'") or die('<div id="err">Błąd zapytania</div>');
		  		        echo '<meta http-equiv="refresh" content="0; URL=wiadomosci.php?login='.$login.'">';
		            }
		            else echo '<br/><br/><span id="err">Brak uprawnień!</span>';
                } 
                
                if($akcja=="edytuj")
                {
                    $login=$_SESSION['login'];
                    $idd=$_GET['id'];
                    $user=$_GET['idus'];
                    $text2=$_GET['text'];
                    $idus2= mysql_fetch_row(mysql_query("select id FROM users WHERE login='$login'")) or die('<div id="err">Błąd zapytania</div>'); 
                    $idus=$idus2[0];
                    $sql=mysql_query("select id_user FROM permissions WHERE id_message='$idd' and id_user='$idus'");
                    $sql3=mysql_query("select * FROM messages WHERE id='$idd' and id_user='$idus'");

                    echo("<br>");
                    $w=mysql_num_rows($sql);
                    $w1=mysql_num_rows($sql3);

		            if($w1==1)$up=1;
		            else $up=0;
		
                    if($w==1) echo '<meta http-equiv="refresh" content="0; URL=edycja.php?akcja&text='.$text2.'&id='.$idd.'&idus='.$user.'&up='.$up.'">';
                    else echo'<div id="err">Brak uprawnień</div>';
		        }	  
            } 
        ?>
        </div>
    </body>
</html>
    

