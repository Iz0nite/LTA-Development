<?php
    $id=$_SESSION['idUser'];
    $req = setupCredentials()->prepare("SELECT idRoadMap,status,distance,creationDate,(SELECT COUNT(*) FROM PACKAGES WHERE idRoadMap=ROADMAP.idRoadMap) FROM ROADMAP WHERE idUser=:idUser");
    $req->bindParam(':idUser', $id, PDO::PARAM_INT);
    $req->execute();
    $res = $req->fetchAll(\PDO::FETCH_ASSOC);

?>

<div class="deliveryManPage" >
    <span>
        <a href="./newDelivery">New delivery</a>
    </span>

    <div class="deliveryManHistory" id="deliveryManHistory">
        <?php foreach ($res as $key => $value) {
            $input = $value['creationDate'];
            $date = strtotime($input);
            $dateNumber = date('Ym', $date);
            ?>

            <div class="delivery" id="deliveryForm<?=$value['idRoadMap']?>">
                <label>
                    <?php
                    $input = $value['creationDate'];
                    $date = strtotime($input);
                    echo date('d/m/Y', $date)
                    ?>
                </label>

                <div class="deliveryInfo">
                    <ul>
                        <li>Delivery: D<?php echo $value['idRoadMap']; echo $dateNumber?></li>
                        <li>Distance: <?php echo $value['distance']/1000;?> km</li>
                        <li>Number of packages: <?php echo $value['(SELECT COUNT(*) FROM PACKAGES WHERE idRoadMap=ROADMAP.idRoadMap)']?></li>
                        <?php
                            if ($value['status'] != 3 ){
                                ?> <span>
                                        <a href="./newDelivery">Resume the delivery</a>
                                   </span> <?php
                            }
                        ?>
                    </ul>
                    <label status="<?=$value['status'] ?>"><?php
                        if ($value['status']==0){
                            echo "Not start";
                        } elseif ($value['status']==1 || $value['status']==2) {
                            echo "In progress";
                        }elseif ($value['status']=3) {
                            echo "Finish";
                        }
                        ?></label>
                </div>


            </div>

        <?php } ?>
    </div>
</div>
