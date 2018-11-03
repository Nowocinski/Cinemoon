<?php
    if(!isset($_POST['miejsce']))
    {
         header('Location: index.php');
         exit();
    }

    if(session_status() == PHP_SESSION_NONE)
        session_start();

    $przechwycenie = $_POST['miejsce'];

    $i=0; $id_repertuaru = '';
    while($przechwycenie[$i] != ' ')
    {
        $id_repertuaru .= $przechwycenie[$i];
        $i++;
    }

    ++$i; $rzad='';
    while($przechwycenie[$i] != ' ')
    {
        $rzad .= $przechwycenie[$i];
        $i++;
    }

    ++$i; $miejsce='';
    while($i < strlen($przechwycenie))
    {
        $miejsce .= $przechwycenie[$i];
        $i++;
    }

    require_once "connect.php";

    //Wyłączenie worningów i włączenie wyświetlania wyjątków
    mysqli_report(MYSQLI_REPORT_STRICT);

    try
    {
        $polaczenie = new mysqli($host, $db_user, $db_password, $db_name);
            
        if($polaczenie->connect_errno != 0)
            throw new Exception(mysqli_connect_errno());
        else
        {
            $polaczenie->query("SET NAMES utf8");
            $rezultat = $polaczenie->query("SELECT * FROM rezerwacje WHERE id_repertuaru='$id_repertuaru' AND miejsce='$miejsce' AND rzad='$rzad'");
            
            if(!$rezultat)
                throw new Exception($polaczenie->error);
            else
            {
                $czy_zajete = $rezultat->num_rows;
                if($czy_zajete >= 1)
                {
                    $blad_rezerwacji = '<span style="color: red">Przepraszamy, ale ktoś zajoł to miejsce w miendzyczasie.<br>Prosimy wybrać inne.</span>';
                }
                
                else
                {
                    $id_klienta = $_SESSION['id_klienta'];
                    $rezultat2 = $polaczenie->query("SELECT typ FROM klienci WHERE id_klienta='$id_klienta'");
                    
                    if(!$rezultat2)
                        throw new Exception($polaczenie->error);
                    
                    else
                    {
                         $wiersz = $rezultat2->fetch_assoc();
                         $typ = $wiersz['typ'];
                    }

                    $rezultat3 = $polaczenie->query("SELECT cena_biletu FROM repertuar WHERE id_repertuaru='$id_repertuaru'");
                    if(!$rezultat3)
                        throw new Exception($polaczenie->error);
                    else
                    {
                        $wiersz = $rezultat3->fetch_assoc();
                        $cena_biletu = $wiersz['cena_biletu'];
                    }
                    
                    if(isset($typ) && isset($cena_biletu))
                    {
                        if($typ == "studencki") $monznik = 0.5;
                        if($typ == "normalny") $mnoznik = 1;
                        $koszt = $cena_biletu * $monznik;
                        
                        if($polaczenie->query("INSERT INTO rezerwacje VALUES (NULL, '$id_klienta', '$id_repertuaru', '$miejsce', '$rzad', '$koszt')"))
                        {
                            $_SESSION['udana_rezerwacja_miejsca'] = true;
                            header('Location: dziekujemy.php');
                        }
                            
                        else
                            throw new Exception($polaczenie->error);
                    }
                    
                    else
                        throw new Exception('Nie można zlaleźć typu konta klienta bądź ceny biletu');
                }
            }
            $polaczenie->close();
        }
    }
    
    catch(Exception $e)
    {
        echo '<span style="color: red">Błąd serwera. Spróbuj zarejestrować się później</span>';
        echo '<br>Informacja deweloperska: '.$e;
    }
?>