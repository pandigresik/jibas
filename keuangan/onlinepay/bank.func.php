<?php
function SetBankAktif()
{
    $replid = $_REQUEST["replid"];
    $newAktif = $_REQUEST["newaktif"];

    $sql = "UPDATE jbsfina.bank SET aktif = $newAktif WHERE replid = $replid";
    QueryDb($sql);
}


function createJsonReturn($status, $message)
{
    $ret = array($status, $message);
    return json_encode($ret);
}

function HapusBank()
{
    $replid = $_REQUEST["replid"];

    $sql = "DELETE FROM jbsfina.bank WHERE replid = '$replid'";
    QueryDb($sql);

    return createJsonReturn(1, "OK");
}
?>