<?php
    $id=$_SESSION['idUser'];

    $req = setupCredentials()->prepare("SELECT idOrder,deliveryStatus,deliveryType,creationDate,(SELECT COUNT(*) FROM PACKAGES WHERE idOrder=ORDER.idOrder) FROM `ORDER` WHERE idUser=:idUser");
    $req->execute(array(
        'idUser' => htmlspecialchars($id)
    ));
    $rslt = $req->fetchAll(\PDO::FETCH_ASSOC);

    include_once("./../config/configLanguage.php");

    $customerHistoryTextLoad = loadCustomerHistoryText();
?>

    <div class="customerHistory" id="customerHistory">
        <?php foreach ($rslt as $key => $value) { ?>
            <div class="orderAndPackages" id="orderAndPackages<?=$value['idOrder']?>">
                <div class="order">
                    <label><?= $customerHistoryTextLoad['invoice'][$_COOKIE['language']]; ?>
                    <?php
                        $input = $value['creationDate'];
                        $date = strtotime($input);
                        echo date('d/m/Y', $date)
                    ?>
                    </label>
                    <div class="orderInfo">
                        <ul>
                            <li><?= $customerHistoryTextLoad['order'][$_COOKIE['language']]; ?>: <?php echo $value['idOrder']; ?></li>
                            <li><?= $customerHistoryTextLoad['packageNumber'][$_COOKIE['language']]; ?>: <?php echo $value['(SELECT COUNT(*) FROM PACKAGES WHERE idOrder=ORDER.idOrder)']; ?></li>
                            <li><?= $customerHistoryTextLoad['deliveryType'][$_COOKIE['language']]; ?>:
                                <?php
                                    if ($value['deliveryType']==0)
                                        echo $customerHistoryTextLoad['standard'][$_COOKIE['language']];
                                    elseif ($value['deliveryType']==1)
                                        echo $customerHistoryTextLoad['express'][$_COOKIE['language']];
                                ?>
                            </li>
                            <?php
                                if ($value['deliveryStatus']==0) {
                                    ?><a href="./../config/cookieBillConfig?idOrder=<?=$value['idOrder']?>"><?= $customerHistoryTextLoad['pay'][$_COOKIE['language']]; ?></a>
                                      <label onclick="deleteOrder(<?=$value['idOrder'] ?>)"><?= $customerHistoryTextLoad['deleteOrder'][$_COOKIE['language']]; ?></label>
                                    <?php
                                }else {
                                    ?><a href="./../config/cookieBillConfig?idOrder=<?=$value['idOrder']?>"><?= $customerHistoryTextLoad['invoiceDetail'][$_COOKIE['language']]; ?></a>
                                    <?php
                                }  ?>
                        </ul>
                        <label status="<?=$value['deliveryStatus'] ?>"><?php
                            if ($value['deliveryStatus']==0){
                                echo $customerHistoryTextLoad['waitPayment'][$_COOKIE['language']];
                            } elseif ($value['deliveryStatus']==1) {
                                echo $customerHistoryTextLoad['preparation'][$_COOKIE['language']];
                            }elseif ($value['deliveryStatus']=2) {
                                echo $customerHistoryTextLoad['delivered'][$_COOKIE['language']];
                            }
                            ?></label>
                    </div>
                    <div id="orderButtonOpen<?=$value['idOrder'] ?>" class="buttonPackages" onclick="showPackage(<?=$value['idOrder'] ?>)">
                        <a><?= $customerHistoryTextLoad['viewContent'][$_COOKIE['language']]; ?></a>
                        <svg width="19" height="18" viewBox="0 0 19 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M9.5 1V9M9.5 17V9M9.5 9H17.5H1.5" stroke="#394967" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                </div>
                <div class="packages" id="packages<?=$value['idOrder'] ?>"></div>
            </div>
    <?php } ?>
</div>
