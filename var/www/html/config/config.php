<?php
    if(!isset($_SESSION))
        session_start();

    if(isset($_POST['formType']))
    {
        if($_POST['formType'] == "addPackage") {
            addPackage();
        }
        else if ($_POST['formType'] == "signUpCustomers") {
            signUpCustomers();
        }
        else if ($_POST['formType'] == "signUpDelivery") {
            signUpDelivery();
        }
        else if ($_POST['formType'] == "signIn") {
            connexion();
        }
    }



    /* Setup SQL connexion*/
    function setupCredentials()
    {
        try
        {
            $bdd = new PDO('mysql:dbname=LTA;host=localhost;port=3306;charset=utf8', 'ltaSuperUser', 'n73r96uxZbfC', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
        }
        catch(Exception $e)
        {
            die('Erreur : '.$e->getMessage());
        }

        return $bdd;
    }


    /* Get user data according to the key passed in parameter  */
    function getUserData($email, $key)
    {
        $req = setupCredentials()->prepare("SELECT " . $key . " FROM USERS WHERE email=:email");

        $req->execute(array(
            'email' => htmlspecialchars($email)
        ));

    	$rslt = $req->fetchAll(\PDO::FETCH_ASSOC);

        return $rslt[0][$key];
    }



    /* Add a new package to the database */
    function addPackage()
    {
        $req = setupCredentials()->prepare("INSERT INTO PACKAGES(weight, dimension, address, statusDelivery, idUser, idDeposit)
            VALUES(:weight, :dimension, :address, :statusDelivery, :idUser, :idDeposit)");

        $req->execute(array(
            'weight' => intval(htmlspecialchars($_POST['weight']), 10),
            'dimension' => intval(htmlspecialchars($_POST['dimension']), 10),
            'address' => htmlspecialchars($_POST['address']),
            'statusDelivery' => 0,
            'idUser' => $_SESSION['id'],
            'idDeposit' => 1
        ));

        header('location: ./../createPackage');
    }



    function signUpCustomers()
    {
        $req = setupCredentials()->prepare("SELECT email FROM USERS WHERE email=?");
        $req->execute([
            $_POST['email'],
        ]);

        $res=$req->fetchAll(\PDO::FETCH_ASSOC);

        if ($res)
        {
            header('location: ./../mainPage');
            exit();
        }

        if (strlen($_POST['password'])<8) {
            header('location: ./../mainPage');
            exit();
        }

        if (strcmp($_POST['password'],$_POST['confirmPassword'])!=0) {
            header('location: ./../mainPage');
            exit();
        }

        $status = 0;

        $req = setupCredentials()->prepare("INSERT INTO USERS(companyName,email,address,telNumber,password,status) VALUES (:companyName, :email, :address, :telNumber, :password, :status)");
        $req->bindParam(':companyName', trim(htmlspecialchars($_POST['companyName'])), PDO::PARAM_STR);
        $req->bindParam(':email', trim(htmlspecialchars($_POST['email'])), PDO::PARAM_STR);
        $req->bindParam(':address', trim(htmlspecialchars($_POST['address'])), PDO::PARAM_STR);
        $req->bindParam(':telNumber', trim(htmlspecialchars($_POST['numTel'])), PDO::PARAM_STR);
        $req->bindParam(':password', hash('sha256', trim(htmlspecialchars($_POST['password']))), PDO::PARAM_STR);
        $req->bindParam(':status', $status, PDO::PARAM_INT);
        $req->execute();

        header('location: ./../mainPage');
    }


    function signUpDelivery()
    {
        $req = setupCredentials()->prepare("SELECT email FROM USERS WHERE email=?");
        $req->execute([
            $_POST['email'],
        ]);

        $res=$req->fetchAll(\PDO::FETCH_ASSOC);

        if ($res)
        {
            header('location: ./../mainPage');
            exit();
        }

        if (strlen($_POST['password'])<8) {
            header('location: ./../mainPage');
            exit();
        }

        if (strcmp($_POST['password'],$_POST['confirmPassword'])!=0) {
            header('location: ./../mainPage');
            exit();
        }

        $status = 1;

        $req = setupCredentials()->prepare("INSERT INTO USERS(firstName,name,email,telNumber,password,vehicleVolume,imatriculation,geoArea,status) VALUES (:firstName, :name, :email, :telNumber, :password, :vehicleVolume, :imatriculation, :geoArea, :status)");
        $req->bindParam(':firstName', trim(htmlspecialchars($_POST['firstName'])), PDO::PARAM_STR);
        $req->bindParam(':name', trim(htmlspecialchars($_POST['name'])), PDO::PARAM_STR);
        $req->bindParam(':email', trim(htmlspecialchars($_POST['email'])), PDO::PARAM_STR);
        $req->bindParam(':telNumber', trim(htmlspecialchars($_POST['numTel'])), PDO::PARAM_STR);
        $req->bindParam(':password', hash('sha256', trim(htmlspecialchars($_POST['password']))), PDO::PARAM_STR);
        $req->bindParam(':vehicleVolume', intval(trim(htmlspecialchars($_POST['vehicleVolume']))), PDO::PARAM_INT);
        $req->bindParam(':imatriculation', trim(htmlspecialchars($_POST['imatriculation'])), PDO::PARAM_STR);
        $req->bindParam(':geoArea', intval(trim(htmlspecialchars($_POST['geoArea']))), PDO::PARAM_INT);
        $req->bindParam(':status', $status, PDO::PARAM_INT);
        $req->execute();

        header('location: ./../mainPage');
    }


    function connexion()
    {
        $req = setupCredentials()->prepare("SELECT idUser,status FROM USERS WHERE email=? AND password=?");
        $req->execute([
            $_POST['email'],
            hash('sha256',$_POST['password'])
        ]);

        $res=$req->fetchAll(\PDO::FETCH_ASSOC);

        var_dump($res);

        if ($res)
        {
            $_SESSION['idUser'] = $res[0]['idUser'];
            $_SESSION['status'] = $res[0]['status'];
            header('location: ./../mainPage');
        }
        else
        {
            session_unset();
            header('location: ./../mainPage');
        }

    }
?>
