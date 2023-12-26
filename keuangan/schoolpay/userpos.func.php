<?php
function SetUserAktif()
{
    $userReplid = $_REQUEST["replid"];
    $newAktif = $_REQUEST["newaktif"];

    $sql = "UPDATE jbsfina.userpos SET aktif = $newAktif WHERE replid = $userReplid";
    QueryDb($sql);
}


function createJsonReturn($status, $message)
{
    $ret = array($status, $message);
    return json_encode($ret);
}

function HapusUser()
{
    $userId = $_REQUEST["userid"];

    $sql = "SELECT COUNT(replid) FROM jbsfina.paymenttrans WHERE userid = '$userId'";
    $nData = FetchSingle($sql);
    if ($nData != 0)
        return createJsonReturn(-1, "Tidak dapat menghapus petugas ini karena sudah digunakan dalam transaksi!");

    $sql = "DELETE FROM jbsfina.vendoruser WHERE userid = '$userId'";
    QueryDb($sql);

    $sql = "DELETE FROM jbsfina.userpos WHERE userid = '$userId'";
    QueryDb($sql);

    return createJsonReturn(1, "OK");
}
?>