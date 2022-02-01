<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Solidao</title>
</head>
<body>
    <header>
        <h1>SOLI<span id="dao">DAO</span></h1>
    </header>
    <main>
        <section class="section1">
            <div class="container">
                <h3>Facture d'électricité simulée</h3>
                <form method="post">
                    <div class="ancien">
                        <label>Ancien index :</label>
                        <input type="number" name="Aindex" placeholder="ancien index" class="input">
                        <label>: القيمة السابقة</label>
                    </div>
                        <?php 
                            $Aindex = $_POST['Aindex'];
                            $Nindex = $_POST['Nindex'];                         
                            if (($Aindex == "")){
                                echo '<p style="color: red;">*Fill this blank</p>';
                            }
                            elseif (($Aindex < 0)){
                                echo '<p style="color: red;">*enter a valid index</p>';
                            }
                            elseif ($Aindex >= $Nindex){
                                echo '<p style="color: red;">*ancien index doit etre inferieur</p>';
                            }
                        ?>
                    <div class="nouvel">
                        <label>Nouvel index :</label>
                        <input type="number" name="Nindex" placeholder="nouvel index" class="input">
                        <label>: القيمة الجديدة</label>
                    </div>
                        <?php 
                            $Nindex = $_POST['Nindex'];                            
                            if (($Nindex == "")){
                                echo '<p style="color: red;">*Fill this blank</p>';
                            }
                            elseif (($Nindex < 0)){
                                echo '<p style="color: red;">*enter a valid index</p>';
                            }
                        ?>
                    <div class="calibre">
                        <label>calibre :</label>
                        <select name="Calibre">
                            <option hidden selected disabled value="0">calibre</option>
                            <option>5-10</option>
                            <option>15-20</option>
                            <option>>30</option>
                        </select>
                        <label>: عيار</label>
                    </div>
                        <?php 
                            $Calibre = $_POST['Calibre'];
                            if (($Calibre == "0")){
                                echo '<p style="color: red;">*Fill this blank</p>';
                            }
                        ?>
                    <div class="submit">
                        <span></span>
                        <button type="submit">Submit</button>
                        <span></span>
                    </div>
                </form>
                <div class="totalContainer">
                    <div class="total">
                        <p>Total à régler (DH TTC):</p>
                        <?php
                            $Aindex = $_POST['Aindex'];
                            $Nindex = $_POST['Nindex'];
                            $Calibre = $_POST['Calibre'];
                            
                            if (($Aindex != "") or ($Nindex != "") or ($Calibre != "0")){
                                $Findex = $Nindex - $Aindex;
                                // echo "Consommation : " . $Findex ;

                                //check wich tranche is the finale index
                                function get_tranche($Tranche){
                                    if (($Tranche > 0)and ($Tranche <= 100)){
                                        $Tranche = 1;
                                        return $Tranche;
                                    }
                                    elseif(($Tranche >=101) and ($Tranche <= 150)){
                                        $Tranche = 2;
                                        return $Tranche;
                                    }
                                    elseif(($Tranche >= 151) and ($Tranche <= 210)){
                                        $Tranche = 3;
                                        return $Tranche;
                                    }
                                    elseif(($Tranche >= 211) and ($Tranche <= 310)){
                                        $Tranche = 4;
                                        return $Tranche;
                                    }
                                    elseif(($Tranche >= 311) and ($Tranche <= 510)){
                                        $Tranche = 5;
                                        return $Tranche;
                                    }
                                    else{
                                        $Tranche = 6;
                                        return $Tranche;
                                    }
                                }

                                //get Tarif du Calibre
                                function get_TC($TC){
                                    if($TC == "5-10"){
                                        $TC = 22.65;
                                        return $TC;
                                    }
                                    elseif($TC == "15-20"){
                                        $TC = 37.05;
                                        return $TC;
                                    }
                                    else{
                                        $TC = 46.20;
                                        return $TC;
                                    }
                                }

                                $TVA = 0.14;
                                $timber = 0.45;
                                // 
                                if (get_tranche($Findex) == 1){
                                    $MHT_T1 = $Findex * 0.794;
                                    $MT_1 = $TVA * $MHT_T1;
                                    //
                                    $totalTVA = $MT_1 + (get_TC($Calibre) * $TVA) + $timber;
                                    $totalMHT = $MHT_T1 + get_TC($Calibre);
                                    $total = $totalTVA + $totalMHT;
                                }
                                elseif (get_tranche($Findex) == 2){
                                    $MHT_T1 = 100 * 0.794;
                                    $MT_1 = $TVA * $MHT_T1;
                                    $MHT_T2 = ($Findex - 100) * 0.883;
                                    $MT_2 = $TVA * $MHT_T2;
                                    //
                                    $totalTVA = $MT_1 + $MT_2 + (get_TC($Calibre) * $TVA) + $timber;
                                    $totalMHT = $MHT_T1 + $MHT_T2 + get_TC($Calibre);
                                    $total = $totalTVA + $totalMHT;
                                }
                                elseif (get_tranche($Findex) == 3){
                                    $MHT_T3 = $Findex * 0.9451;
                                    $MT_3 = $TVA * $MHT_T3;
                                    //
                                    $totalTVA = $MT_3 + (get_TC($Calibre) * $TVA) + $timber;
                                    $totalMHT = $MHT_T3 + get_TC($Calibre);
                                    $total = $totalTVA + $totalMHT;
                                }
                                elseif (get_tranche($Findex) == 4){
                                    $MHT_T3 = 210 * 0.9451;
                                    $MT_3 = $TVA * $MHT_T3;
                                    $MHT_T4 = ($Findex - 210) * 1.0489;
                                    $MT_4 = $TVA * $MHT_T4;
                                    //
                                    $totalTVA = $MT_3 + $MT_4 + (get_TC($Calibre) * $TVA) + $timber;
                                    $totalMHT = $MHT_T3 + $MHT_T4 + get_TC($Calibre);
                                    $total = $totalTVA + $totalMHT;
                                }
                                elseif (get_tranche($Findex) == 5){
                                    $MHT_T3 = 210 * 0.9451;
                                    $MT_3 = $TVA * $MHT_T3;
                                    $MHT_T4 = 310 * 1.0489;
                                    $MT_4 = $TVA * $MHT_T4;
                                    $MHT_T5 = ($Findex - 310) * 1.2915;
                                    $MT_5 = $TVA * $MHT_T5;
                                    //
                                    $totalTVA = $MT_3 + $MT_4 + $MT_5 + (get_TC($Calibre) * $TVA) + $timber;
                                    $totalMHT = $MHT_T3 + $MHT_T4 + $MHT_T5 + get_TC($Calibre);
                                    $total = $totalTVA + $totalMHT;
                                }
                                else{
                                    $MHT_T3 = 210 * 0.9451;
                                    $MT_3 = $TVA * $MHT_T3;
                                    $MHT_T4 = 310 * 1.0489;
                                    $MT_4 = $TVA * $MHT_T4;
                                    $MHT_T5 = 510 * 1.2915;
                                    $MT_5 = $TVA * $MHT_T5;
                                    $MHT_T6 = ($Findex - 510) * 1.4975;
                                    $MT_6 = $TVA * $MHT_T6;
                                    //
                                    $totalTVA = $MT_3 + $MT_4 + $MT_5 + $MT_6 + (get_TC($Calibre) * $TVA) + $timber;
                                    $totalMHT = $MHT_T3 + $MHT_T4 + $MHT_T5 + $MHT_T6 + get_TC($Calibre);
                                    $total = $totalTVA + $totalMHT;
                                }
                                if (($Aindex == "") or ($Nindex == "") or ($Calibre == "")){
                                    echo '<p id="total">00.00</p>';
                                }
                                else{
                                    echo '<p id="total"><b>' . $total . '</b></p>';
                                }
                            }
                        ?>
                        <p>: الواجب أداؤه</p>
                    </div>
                    <div class="totalTVA">
                        <p>Dont total taxes :</p>
                            <?php 
                                if (($Aindex == "") or ($Nindex == "") or ($Calibre == "")){
                                    echo '<p >00.00</p>';
                                }
                                else{
                                    echo "<p><b>$totalTVA</b></p>";
                                }
                            ?>
                        <p>: مجموع الرسوم</p>
                    </div>
                </div>
            </div>
        </section>
        <section class="factureContainer">
                <div class="row1">
                    <?php
                        if (($Aindex == "") or ($Nindex == "") or ($Calibre == "")){
                            echo '<p id="Aindex">Ancien Index : 00</p>';
                            echo '<p id="Nindex">Nouvel Index : 00</p>';
                            echo '<p id="Consommation">Consommation : 00 kwh</p>';
                        }
                        else{
                            echo '<p id="Aindex">Ancien Index : ' . $Aindex . '</p>';
                            echo '<p id="Nindex">Ancien Index : ' . $Nindex . '</p>';
                            echo '<p id="Consommation">Consommation : ' . $Findex . '</p>';
                        }
                    ?>
                </div>
                <div class="row2">
                    <p id="facture">Facturé</p>
                    <p id="pu">P.U</p>
                    <p id="mht">Montant HT</p>
                    <p id="TVA">Taux TVA</p>
                    <p id="MT">Montant Taxes</p>
                </div>
                <div class="row3">
                    <p id="r3c1"><b>Consommation Electricite</b></p>
                    <p id="r3c7"><b>إستھلاك الكھرباء</b></p>
                </div>
                <?php 
                    if (($Aindex == "") or ($Nindex == "") or ($Calibre == "")){
                        echo '<div class="row4">
                                <p class="tranche">Tranche</p>
                                <p class="trancheA">الشطر</p>
                            </div>';
                    }
                    elseif (get_tranche($Findex) == 1){
                        echo '<div class="row4">
                                <p class="tranche">Tranche 1:</p>
                                <p>'.$Findex .'</p>
                                <p>0.794</p>
                                <p>'.$MHT_T1.'</p>
                                <p>14%</p>
                                <p>'.$MT_1.'</p>
                                <p class="trancheA">: 1 الشطر</p>
                            </div>';
                    }
                    elseif (get_tranche($Findex) == 2){
                        echo '<div class="row4">
                                <p class="tranche">Tranche 1:</p>
                                <p>100</p>
                                <p>0.794</p>
                                <p>'.$MHT_T1.'</p>
                                <p>14%</p>
                                <p>'.$MT_1.'</p>
                                <p class="trancheA">: 1 الشطر</p>
                            </div>';
                        echo '<div class="row5">
                                <p class="tranche">Tranche 2:</p>
                                <p>'.$Findex - 100 .'</p>
                                <p>0.883</p>
                                <p>'.$MHT_T2.'</p>
                                <p>14%</p>
                                <p>'.$MT_2.'</p>
                                <p class="trancheA">: 2 الشطر</p>
                            </div>';
                    }
                    elseif (get_tranche($Findex) == 3){
                        echo '<div class="row4">
                                <p class="tranche">Tranche 3:</p>
                                <p>'.$Findex .'</p>
                                <p>0.9451</p>
                                <p>'.$MHT_T3.'</p>
                                <p>14%</p>
                                <p>'.$MT_3.'</p>
                                <p class="trancheA">: 3 الشطر</p>
                            </div>';
                    }
                    elseif (get_tranche($Findex) == 4){
                        echo '<div class="row4">
                                <p class="tranche">Tranche 3:</p>
                                <p>210</p>
                                <p>0.9451</p>
                                <p>'.$MHT_T3.'</p>
                                <p>14%</p>
                                <p>'.$MT_3.'</p>
                                <p class="trancheA">: 3 الشطر</p>
                            </div>';
                        echo '<div class="row5">
                                <p class="tranche">Tranche 4:</p>
                                <p>'.$Findex - 210 .'</p>
                                <p>1.0489</p>
                                <p>'.$MHT_T4.'</p>
                                <p>14%</p>
                                <p>'.$MT_4.'</p>
                                <p class="trancheA">: 4 الشطر</p>
                            </div>';
                    }
                    elseif (get_tranche($Findex) == 5){
                        echo '<div class="row4">
                                <p class="tranche">Tranche 3:</p>
                                <p>210</p>
                                <p>0.9451</p>
                                <p>'.$MHT_T3.'</p>
                                <p>14%</p>
                                <p>'.$MT_3.'</p>
                                <p class="trancheA">: 3 الشطر</p>
                            </div>';
                        echo '<div class="row5">
                                <p class="tranche">Tranche 4:</p>
                                <p>310</p>
                                <p>1.0489</p>
                                <p>'.$MHT_T4.'</p>
                                <p>14%</p>
                                <p>'.$MT_4.'</p>
                                <p class="trancheA">: 4 الشطر</p>
                            </div>';
                        echo '<div class="row6">
                                <p class="tranche">Tranche 5:</p>
                                <p>'.$Findex - 310 .'</p>
                                <p>1.2915</p>
                                <p>'.$MHT_T5.'</p>
                                <p>14%</p>
                                <p>'.$MT_5.'</p>
                                <p class="trancheA">: 5 الشطر</p>
                            </div>';
                    }
                    else{
                        echo '<div class="row4">
                                <p class="tranche">Tranche 3:</p>
                                <p>210</p>
                                <p>0.9451</p>
                                <p>'.$MHT_T3.'</p>
                                <p>14%</p>
                                <p>'.$MT_3.'</p>
                                <p class="trancheA">: 3 الشطر</p>
                            </div>';
                        echo '<div class="row5">
                                <p class="tranche">Tranche 4:</p>
                                <p>310</p>
                                <p>1.0489</p>
                                <p>'.$MHT_T4.'</p>
                                <p>14%</p>
                                <p>'.$MT_4.'</p>
                                <p class="trancheA">: 4 الشطر</p>
                            </div>';
                        echo '<div class="row6">
                                <p class="tranche">Tranche 5:</p>
                                <p>510</p>
                                <p>1.2915</p>
                                <p>'.$MHT_T5.'</p>
                                <p>14%</p>
                                <p>'.$MT_5.'</p>
                                <p class="trancheA">: 5 الشطر</p>
                            </div>';
                        echo '<div class="row7">
                                <p class="tranche">Tranche 6:</p>
                                <p>'.$Findex - 510 .'</p>
                                <p>1.4975</p>
                                <p>'.$MHT_T6.'</p>
                                <p>14%</p>
                                <p>'.$MT_6.'</p>
                                <p class="trancheA">: 6 الشطر</p>
                            </div>';
                    }
                    echo '<div class="row8">
                            <p class="tranche"><b>REDEVANCE FIXE ELECTRICITE</b></p>
                            <span></span>
                            <span></span>
                            <p>'.get_TC($Calibre) .'</p>
                            <p>14%</p>
                            <p>'. get_TC($Calibre) * $TVA.'</p>
                            <p class="trancheA"><b>اثاوة ثابتة الكهرباء</b></p>
                        </div>';
                ?>
                <div class="row9">
                    <p id="r3c1"><b>TAXES POUR LE COMPTE DE L’ETAT</b></p>
                    <p id="r3c7"><b>الرسوم المؤداة لفائدة الدولة</b></p>
                </div>
                <?php
                    echo '<div class="row10">
                            <p class="tranche">TOTAL TVA</p>
                            <span></span>
                            <span></span>
                            <span></span>
                            <span></span>
                            <p>'.$totalTVA - $timber .'</p>
                            <p>مجموع.ض.ق.م</p>
                        </div class="trancheA">';
                        echo '<div class="row10">
                            <p class="tranche">TIMBER</p>
                            <span></span>
                            <span></span>
                            <span></span>
                            <span></span>
                            <p>'.$timber .'</p>
                            <p>الطابع</p>
                        </div class="trancheA">';
                        echo '<div class="row9">
                                <p id="r3c1"><b>SOUS-TOTAL</b></p>
                                <span></span>
                                <span></span>
                                <p><b>'.$totalMHT.'</b></p>
                                <span></span>
                                <p><b>'.$totalTVA.'</b></p>
                                <p id="r3c7"><b>المجموع الجزئي</b></p>
                            </div>';
                        echo '<div class="row12">
                                <p class="tranche"><b>TOTAL ÉLECTRICITÉ</b></p>
                                <span></span>
                                <span></span>
                                <p><b>'.$total.'</b></p>
                                <span></span>
                                <span></span>
                                <p class="trancheA"><b>مجموع الكهرباء</b></p>
                            </div>' ;
                ?>
                
        </section>
    </main>
    <style>
        <?php include('style.css'); ?>
    </style>
</body>
</html>