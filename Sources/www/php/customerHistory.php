<?php
    $id=$_SESSION['idUser'];

    $req = setupCredentials()->prepare("SELECT idOrder,deliveryStatus FROM `ORDER` WHERE idUser=:idUser");

    $req->execute(array(
        'idUser' => htmlspecialchars($id)
    ));

    $rslt = $req->fetchAll(\PDO::FETCH_ASSOC);
?>

    <div class="customerHistory">
        <?php foreach ($rslt as $key => $value) { ?>
    <ul>
    <li><?php echo $value['idOrder']; ?></li>
    <li><?php if ($value['deliveryStatus']==0){
            echo "impayé";
        } elseif ($value['deliveryStatus']==1) {
            echo "en préparation";
        }elseif ($value['deliveryStatus']=2) {
            echo "Finis";
        } ?>
    </li>

    </ul>
    <?php } ?>
</div>
