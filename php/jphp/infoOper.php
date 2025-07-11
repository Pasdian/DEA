<?php 
require("conf.php");
require("objetos.php");

$FB=new FireBird(usuario,passwd,server);

$FB=new FireBird(usuario,passwd,server);

/*Consulta anterior a la inclusion de fechas en operacion

"SELECT CC.NOM_IMP,CC.RFC_IMP,SR.NOM_REP,SC.RFC_AA,SP.PAT_AGEN,SP.NUM_REFE,SP.NUM_PEDI,SP.CVE_PEDI,SP.IMP_EXPO,SP.ADU_DESP FROM SAAIO_PEDIME AS SP INNER JOIN CTRAC_CLIENT AS CC ON SP.CVE_IMPO=CC.CVE_IMP INNER JOIN SAAIG_CONFIG AS SC ON SP.PAT_AGEN=SC.PAT_AA AND SP.ADU_DESP=SC.CVE_ADUA INNER JOIN SAAIC_REPRES AS SR ON SC.RFC_AA=SR.RFC_REP WHERE NUM_REFE='".$ref=$_POST['r']."'"

*/

$resultados=$FB->consulta("SELECT CC.NOM_IMP,CC.RFC_IMP,SR.NOM_REP,SC.RFC_AA,SP.PAT_AGEN,ce.NUM_REFE,sp.NUM_PEDI,sp.CVE_PEDI,SP.IMP_EXPO,SP.ADU_DESP,(SELECT CAST(FEC_ETAP AS date) FROM CTRAO_ETAPAS WHERE NUM_REFE=ce.NUM_REFE AND CVE_ETAP ='070') AS RE,(SELECT CAST(FEC_ETAP AS date) FROM CTRAO_ETAPAS WHERE NUM_REFE=ce.NUM_REFE AND CVE_ETAP ='030') AS EP,(SELECT CAST(FEC_ETAP AS date) FROM CTRAO_ETAPAS WHERE NUM_REFE=ce.NUM_REFE AND CVE_ETAP ='130') AS MSA FROM CTRAO_ETAPAS ce INNER JOIN SAAIO_PEDIME sp ON sp.num_refe=ce.num_refe INNER JOIN CTRAC_CLIENT CC ON SP.CVE_IMPO=CC.CVE_IMP INNER JOIN SAAIG_CONFIG AS SC ON SP.PAT_AGEN=SC.PAT_AA AND SP.ADU_DESP=SC.CVE_ADUA INNER JOIN SAAIC_REPRES AS SR ON SC.RFC_AA=SR.RFC_REP WHERE ce.num_refe='".$ref=$_POST['r']."' GROUP BY CC.NOM_IMP,CC.RFC_IMP,SR.NOM_REP,SC.RFC_AA,SP.PAT_AGEN,ce.NUM_REFE,sp.NUM_PEDI,sp.CVE_PEDI,SP.IMP_EXPO,SP.ADU_DESP");

$Etapas=$FB->consulta("SELECT CET.DES_ETAPA,CAST(FEC_ETAP AS DATE) FROM CTRAO_ETAPAS AS CE INNER JOIN CTRAC_ETAPAS AS CET ON CE.CVE_ETAP=CET.CVE_ETAP WHERE NUM_REFE='".$ref=$_POST['r']."' ORDER BY CE.CVE_ETAP");

$Comercial=$FB->consulta("SELECT NUM_FACT,SC.E_DOCUMENT,COUNT(SFP.NUM_REFE) FROM SAAIO_FACTUR AS SF JOIN SAAIO_COVE AS SC ON SF.CONS_FACT=SC.CONS_FACT AND SF.NUM_REFE =SC.NUM_REFE JOIN SAAIO_FACPAR AS SFP  ON SF.CONS_FACT=SFP.CONS_FACT AND SF.NUM_REFE =SFP.NUM_REFE WHERE SF.NUM_REFE = '".$ref=$_POST['r']."' GROUP BY SF.NUM_REFE,NUM_FACT,SC.E_DOCUMENT");

$gastos=$FB->consulta("SELECT(SELECT DES_EGRE FROM CCAJC_EGRES WHERE CVE_EGRE = CCAJO_MOVADU.CVE_MOVI) AS CONCEPTO
, COALESCE((SELECT FIRST(1)IMP_CPTO FROM CCGO_REFIS2 WHERE NUM_REFE = CCAJO_MOVADU.NUM_REFE AND CVE_MOVI = CCAJO_MOVADU.CVE_MOVI AND CVE_CPTO = '07'),0) AS SUBTOTAL
, COALESCE((SELECT FIRST(1)IMP_CPTO FROM CCGO_REFIS2 WHERE NUM_REFE = CCAJO_MOVADU.NUM_REFE AND CVE_MOVI = CCAJO_MOVADU.CVE_MOVI AND CVE_CPTO = '06' ),0) AS IVA
, COALESCE((SELECT FIRST(1)IMP_CPTO FROM CCGO_REFIS2 WHERE NUM_REFE = CCAJO_MOVADU.NUM_REFE AND CVE_MOVI = CCAJO_MOVADU.CVE_MOVI AND CVE_CPTO = '08' ),0) AS RETENCION
, (COALESCE((SELECT FIRST(1)IMP_CPTO FROM CCGO_REFIS2 WHERE NUM_REFE = CCAJO_MOVADU.NUM_REFE AND CVE_MOVI = CCAJO_MOVADU.CVE_MOVI AND CVE_CPTO = '07'), 0)
+COALESCE((SELECT FIRST(1)IMP_CPTO FROM CCGO_REFIS2 WHERE NUM_REFE = CCAJO_MOVADU.NUM_REFE AND CVE_MOVI = CCAJO_MOVADU.CVE_MOVI AND CVE_CPTO = '06'),0)
-COALESCE((SELECT FIRST(1)IMP_CPTO FROM CCGO_REFIS2 WHERE NUM_REFE = CCAJO_MOVADU.NUM_REFE AND CVE_MOVI = CCAJO_MOVADU.CVE_MOVI AND CVE_CPTO = '08'),0)) AS TOTAL
 FROM CCAJO_MOVADU WHERE NUM_REFE = '".$ref=$_POST['r']."'
 AND(SELECT DES_EGRE FROM CCAJC_EGRES WHERE CVE_EGRE = CCAJO_MOVADU.CVE_MOVI) IS NOT NULL
 AND(SELECT FIRST(1)IMP_CPTO FROM CCGO_REFIS2 WHERE NUM_REFE = CCAJO_MOVADU.NUM_REFE
 AND CVE_MOVI = CCAJO_MOVADU.CVE_MOVI AND CVE_CPTO = '07') IS NOT NULL
 ORDER BY BEN_EROG;");

$serviciosAdu=$FB->consulta("SELECT IMP_CPTO FROM CCGO_FACTUR WHERE NUM_REFE='".$ref=$_POST['r']."' AND CVE_COMP='CTA' AND IMP_CPTO!=0 AND CVE_CPTO='HONT'");

$serviciosAA=$FB->consulta("SELECT CC.DES_COMP, IMP_CPTO FROM CCGO_FACTUR CF LEFT JOIN CCGC_COMPLE CC ON CF.CVE_COMP=CC.CVE_COMP WHERE NUM_REFE='".$ref=$_POST['r']."' AND IMP_CPTO!=0 AND CF.CVE_COMP !='CTA'");

$FB->terminar();


?>

<div class="IOflex-container">
    <div class="IOflex-item">
        <table>
           <caption comp=<?php echo $resultados[0]["NUM_REFE"]."_".$resultados[0]["NUM_PEDI"] ?>>Operación</caption>
            <thead>
                <tr>
                    <td>Referencia</td>
                    <td>Pedimento</td>
                    <td>Clave</td>
                    <td>Operación</td>
                    <td>Aduana</td>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><?php echo $resultados[0]["NUM_REFE"] ?></td>
                    <td><?php echo $resultados[0]["NUM_PEDI"] ?></td>
                    <td><?php echo $resultados[0]["CVE_PEDI"] ?></td>
                    <td>
                        <?php switch($resultados[0]["IMP_EXPO"]){
                            case 1:
                                echo "IMP";
                                break;
                            case 2:
                                echo "EXP";
                                break;
                            default:
                                echo "S/D";}  
                        ?>                    
                    </td>
                    <td id="ADU_DESP"><?php echo $resultados[0]["ADU_DESP"] ?></td>
                </tr>
                <tr>
                    <td>Registro de embarque</td>
                    <td>Entrada al país</td>
                    <td></td>
                    <td></td>
                    <td>M.S.A</td>
                </tr>
                <tr>
                    <td><?php echo $resultados[0]["RE"] ?></td>
                    <td><?php echo $resultados[0]["EP"] ?></td>
                    <td></td>
                    <td></td>
                    <td><?php echo $resultados[0]["MSA"] ?></td>
                </tr>
                <tr>
                    <td colspan=5>
                        <table>
                            <caption>Importador/Exportador</caption>
                            <tbody>
                                <tr>
                                    <td>Nombre/Razón Social/Denominación</td>
                                    <td>RFC</td>
                                </tr>
                                <tr>
                                    <td><?php echo utf8_encode($resultados[0]["NOM_IMP"]) ?></td>
                                    <td><?php echo utf8_encode($resultados[0]["RFC_IMP"]) ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td colspan=5>
                        <table>
                            <caption>Agente Aduanal</caption>
                            <tbody>
                                <tr>
                                    <td>Nombre/Denominación</td>
                                    <td>RFC</td>
                                    <td>PATENTE</td>
                                </tr>
                                <tr>
                                    <td><?php echo utf8_encode($resultados[0]["NOM_REP"]) ?></td>
                                    <td><?php echo utf8_encode($resultados[0]["RFC_AA"]) ?></td>
                                    <td id="PAT_AGEN"><?php echo utf8_encode($resultados[0]["PAT_AGEN"]) ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td colspan=5>
                        <table>
                            <caption>Datos Comerciales</caption>
                            <tbody>
                                <tr>
                                    <td>Factura</td>
                                    <td>COVE</td>
                                    <td>Número de Partidas</td>
                                </tr>
                                    <?php 
                                        foreach($Comercial as $key => $value){
                                            if($value){
                                                echo "<tr><td>".$value["NUM_FACT"]."</td>";
                                                echo "<td>".$value["E_DOCUMENT"]."</td>";
                                                echo "<td>".$value["COUNT"]."</td></tr>";
                                            }
                                        }             
                                    ?>
                            </tbody>
                        </table>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="IOflex-item">
        <table>
            <caption>Gastos</caption>
            <tr>
                <td>
                    <table>
                        <caption>Rembolsables</caption>
                        <tr>
                            <td>Concepto</td>
                            <td>Subtotal</td>
                            <td>IVA</td>
                            <td>Reten.</td>
                            <td>TOTAL</td>
                        </tr>
                        <?php 
                            foreach($gastos as $key => $value){
                               if($value){
                                    echo "<tr>";
                                    echo     "<td>".$value["CONCEPTO"]."</td>";
                                    echo     "<td style='text-align: right;width:65px;'>".$value["SUBTOTAL"]."</td>";
                                    echo     "<td style='text-align: right;width:65px;'>".$value["IVA"]."</td>";
                                    echo     "<td style='text-align: right;width:65px;'>".$value["RETENCION"]."</td>";
                                    echo     "<td style='text-align: right;width:65px;'>".$value["TOTAL"]."</td>";
                                    echo "</tr>";
                               }
                            }
                        ?>
                    </table>
                </td>
            </tr>
            <tr>
                <td>
                    <table>
                        <caption>Servicios Agencia Aduanal</caption>
                        <tr>
                            <td>Concepto</td>
                            <td>Subtotal</td>
                        </tr>
                        <tr>
                        <?php 
                            foreach($serviciosAA as $key => $value){
                               if($value){
                                    echo "<tr>";
                                    echo     "<td>".$value["DES_COMP"]."</td>";
                                    echo     "<td style='text-align: right;width:65px;'>".$value["IMP_CPTO"]."</td>";
                                    echo "</tr>";
                               }
                            }
                        ?> 
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </div>
    <div class="IOflex-item">
        <table >
            <caption>Seguimiento</caption>
           <thead >
               <tr><th></th></tr>
           </thead>
            <tr>
            <?php 
                foreach($Etapas as $key => $value){
                    if($value){
                        echo "<tr><td>".$value["DES_ETAPA"]."</td>";
                        echo "<td style='text-align: right;width:85px;'>".$value["CAST"]."</td></tr>";                    
                    }
                }             
            ?>
            </tr>
        </table>                   
    </div>
    <div class="IOflex-item" id="auditCarpeta">
        <table >
        <caption>Carpetas</caption>
        <thead >
        <tr><th></th></tr>
        </thead>
        <tbody>
            
        </tbody>

        </table>
    </div>
    <div class="IOflex-item" id="pathFotosNvo" path="Centralizada/<?php echo utf8_encode($resultados[0]["PAT_AGEN"]) ?>/<?php echo $resultados[0]["ADU_DESP"] ?>/Previos/<?php echo $resultados[0]["NUM_REFE"] ?>"></div>
    <div class="IOflex-item"></div>
    <div class="IOflex-item"></div>
    <div class="IOflex-item"></div>
    
    
</div>
