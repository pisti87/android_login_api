<?php

if (isset($_REQUEST['tag']) && $_REQUEST['tag'] != '') {
    
    $tag = $_REQUEST['tag'];
    require_once 'DB_Functions.php';
    $db = new DB_Functions();
    $response = array("tag" => $tag, "success" => 0, "error" => 0);

    if ($tag == 'login') {
        $email = $_REQUEST['email'];
        $password = $_REQUEST['password'];
        $user = $db->getUserByEmailAndPassword($email, $password);
        if ($user != false) {
            $response["success"] = 1;
            $response["uid"] = $user["unique_id"];
            $response["user"]["name"] = $user["name"];
            $response["user"]["email"] = $user["email"];
            $response["user"]["created_at"] = $user["created_at"];
            $response["user"]["updated_at"] = $user["updated_at"];
            echo json_encode($response);
        } else {
            $response["error"] = 1;
            $response["error_msg"] = "Hibás E-mail cím/jelszó";
            echo json_encode($response);
        }
    } else if ($tag == 'register') {
        $name = $_REQUEST['name'];
        $email = $_REQUEST['email'];
        $password = $_REQUEST['password'];

        if ($db->isUserExisted($email)) {
            $response["error"] = 2;
            $response["error_msg"] = "A felhasználó már létezik";
            echo json_encode($response);
        } else {
            $user = $db->storeUser($name, $email, $password);
            if ($user) {
                $response["success"] = 1;
                $response["uid"] = $user["unique_id"];
                $response["user"]["name"] = $user["name"];
                $response["user"]["email"] = $user["email"];
                $response["user"]["created_at"] = $user["created_at"];
                $response["user"]["updated_at"] = $user["updated_at"];
                echo json_encode($response);
            } else {
                $response["error"] = 1;
                $response["error_msg"] = "Hiba! A regisztráció sikertelen";
                echo json_encode($response);
            }
        }
    } else {
        echo "Hibás kérés";
    }
} else {
    echo "Hozzáférés megtagadva!";
}
?>
