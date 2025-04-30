<?php 
    $dsn = "mysql:host=localhost;dbname=todo_list;charset=utf8";
    $user = "root";
    $pass = "";
try {
    $db = new PDO($dsn, $user, $pass);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                // echo"You are connected";
        } catch (PDOException $e) {     
            echo "You are not connected: " . $e->getMessage();
        }
?>