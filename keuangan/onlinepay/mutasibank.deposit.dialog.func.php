<?php
function LoadValue()
{
    global $idDeposit, $deposit, $keterangan;

    if ($idDeposit == 0)
        return;

    $sql = "SELECT nama, keterangan
              FROM jbsfina.bankdeposit
             WHERE replid = $idDeposit";
    $res = QueryDb($sql);
    if ($row = mysqli_fetch_row($res))
    {
        $deposit = $row[0];
        $keterangan = $row[1];
    }
}
?>