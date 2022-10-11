<?
// Скрипт проверки
    $mybd = new mysqli("127.0.0.1", "root", "root", "php_lab4",3306);
    if(!$mybd)
        die(json_encode(["ERROR"=>"BAD_SQL_CONN"]));
    if (isset($_COOKIE['id']) && isset($_COOKIE['password_hash']))                                              //Если в куках находятся данные
    {                                                                                                           // проверяем их с соответствующими
        $query = $mybd->query("SELECT * FROM users WHERE id = {$_COOKIE['id']} LIMIT 1");                       //данными из БД
        $userdata = $query->fetch_assoc();
        if(($userdata['password_hash'] != $_COOKIE['password_hash']) || ($userdata['id'] != $_COOKIE['id']) )   //Если куки не совпадают, пишем об этом
        {                                                                                                       //и удаляем куки.
            setcookie("id", "", time() - 3600*24*30*12, "/");
            setcookie("password_hash", "", time() - 3600*24*30*12, "/");
            print 'Хм, что-то не получилось<br><a href="login.php">Войти</a>';
        }
        else                                                                                                    //Если все успешно, выводим приветствие
        {
            print "Привет, ".$userdata['login'].". Всё работает! <br>";
            print '<form method="POST"><input name="submit" type="submit" value="Выйти"></form>';
        }
    }
    else
        print 'Войдите <br><a href="login.php">Войти</a>';
    if(isset($_POST['submit']))
    {
        $mybd->query("UPDATE users SET password_hash='' WHERE id='{$_COOKIE['id']}'");
        setcookie("id", null, -1, "/");
        unset($_COOKIE['id']);
        unset($_COOKIE['password_hash']);
        setcookie("password_hash", null, -1, "/");
        header("Location: registration.php"); exit();
    }
?>

