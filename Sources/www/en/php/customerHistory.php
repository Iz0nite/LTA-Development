<?php
    $id=$_SESSION['idUser'];

    $req = setupCredentials()->prepare("SELECT idOrder,deliveryStatus,deliveryType,creationDate,(SELECT COUNT(*) FROM PACKAGES WHERE idOrder=ORDER.idOrder) FROM `ORDER` WHERE idUser=:idUser");

    $req->execute(array(
        'idUser' => htmlspecialchars($id)
    ));

    $rslt = $req->fetchAll(\PDO::FETCH_ASSOC);
?>

    <div class="customerHistory">
        <?php foreach ($rslt as $key => $value) { ?>
            <div class="orderAndPackages" id="orderAndPackages<?=$value['idOrder']?>">
                <div class="order">
                    <label>Facture
                    <?php
                        $input = $value['creationDate'];
                        $date = strtotime($input);
                        echo date('d/m/Y', $date)
                    ?>
                    </label>
                    <div class="orderInfo">
                        <ul>
                            <li>Order: <?php echo $value['idOrder']; ?></li>
                            <li>Number Package: <?php echo $value['(SELECT COUNT(*) FROM PACKAGES WHERE idOrder=ORDER.idOrder)']; ?></li>
                            <li>Delivery type: <?php
                                if ($value['deliveryType']==0) {
                                echo "standard";
                                }elseif ($value['deliveryType']==1) {
                                echo "express";
                                }
                            ?></li>
                            <?php
                                if ($value['deliveryStatus']==0) {
                                    ?><a href="./../config/cookieBillConfig.php?idOrder=<?=$value['idOrder']?>">Pay your delivery</a>
                                    <?php
                                }else {
                                    ?><a href="./../config/cookieBillConfig.php?idOrder=<?=$value['idOrder']?>">Invoice details</a>
                                    <?php
                                }  ?>
                        </ul>
                        <label status="<?=$value['deliveryStatus'] ?>"><?php
                            if ($value['deliveryStatus']==0){
                                echo "waiting for payment";
                            } elseif ($value['deliveryStatus']==1) {
                                echo "in preparation";
                            }elseif ($value['deliveryStatus']=2) {
                                echo "Finish";
                            }
                            ?></label>
                    </div>
                    <div id="orderButtonOpen<?=$value['idOrder'] ?>" class="buttonPackages" onclick="showPackage(<?=$value['idOrder'] ?>)">
                        <a>View content</a>
                        <svg width="19" height="18" viewBox="0 0 19 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M9.5 1V9M9.5 17V9M9.5 9H17.5H1.5" stroke="#394967" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                </div>
                <div class="packages" id="packages<?=$value['idOrder'] ?>"></div>
            </div>
    <?php } ?>
</div>
