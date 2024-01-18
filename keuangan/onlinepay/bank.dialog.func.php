<?php
function LoadValue()
{
    global $bankReplid;
    global $bank, $bankNo, $bankName, $bankLoc, $keterangan;
    global $rekKas, $rekPendapatan, $useInTrans;

    if ($bankReplid == 0)
        return;

    $sql = "SELECT * FROM jbsfina.bank WHERE replid = $bankReplid";
    $res = QueryDb($sql);
    if ($row = mysqli_fetch_array($res))
    {
        $bank = $row["bank"];
        $bankNo = $row["bankno"];
        $bankName = $row["bankname"];
        $bankLoc = $row["bankloc"];
        $keterangan = $row["keterangan"];
        $rekKas = $row["rekkas"];
        $rekPendapatan = $row["rekpendapatan"];
    }

    $sql = "SELECT COUNT(*) FROM jbsfina.banktrans WHERE bankno = '".$bankNo."'";
    $res = QueryDb($sql);
    $row = mysqli_fetch_row($res);
    $useInTrans = $row[0] != 0;
}

function ShowSelectRek($kategori, $nama, $defValue)
{
    global $isReadOnly;

    $sql = "SELECT kode, nama FROM jbsfina.rekakun WHERE kategori='$kategori' ORDER BY kode";
    $res = QueryDb($sql);

    $readOnly = $isReadOnly ? "disabled" : "";

    echo "<select id='$nama' name='$nama' class='inputbox' style='width: 250px' $readOnly>";
    while($row = mysqli_fetch_row($res))
    {
        $sel = $row[0] == $defValue ? "selected" : "";
        echo "<option value='".$row[0]."' $sel>".$row[0]. $row[1]."</option>";
    }
    echo "</select>";
}


function SimpanBank()
{
    try
    {
        $bankReplid = $_REQUEST["bankreplid"];
        $dept = $_REQUEST["dept"];
        $bank = SafeValueHtml($_REQUEST["bank"]);
        $bankLoc = SafeValueHtml($_REQUEST["bankloc"]);
        $bankNo = SafeValueHtml($_REQUEST["bankno"]);
        $bankName = SafeValueHtml($_REQUEST["bankname"]);
        $keterangan = SafeValueHtml($_REQUEST["keterangan"]);
        $rekKas = $_REQUEST["rekkas"];
        $rekPendapatan = $_REQUEST["rekpendapatan"];

        if ($bankReplid == 0)
        {
            $sql = "SELECT COUNT(replid)
                      FROM jbsfina.bank 
                     WHERE bankno = '$bankNo'
                       AND departemen = '$dept' ";
            $nData = FetchSingle($sql);
            if ($nData > 0)
            {
                return createJsonReturn(-1, "Nomor rekening $bankNo sudah terdata!");
            }

            $sql = "INSERT INTO jbsfina.bank 
                       SET departemen = '$dept', bank = '$bank', bankloc = '$bankLoc', bankno = '$bankNo', 
                           bankname = '$bankName', keterangan = '$keterangan', rekkas = '$rekKas', rekpendapatan = '$rekPendapatan',
                           aktif = 1, issync = 0";
            QueryDbEx($sql);

            return createJsonReturn(1, "OK");
        }

        $sql = "SELECT COUNT(replid)
                  FROM jbsfina.bank 
                 WHERE bankno = '$bankNo'
                   AND departemen = '$dept' 
                   AND replid <> $bankReplid";
        $nData = FetchSingle($sql);
        if ($nData > 0)
        {
            return createJsonReturn(-1, "Nomor rekening $bankNo sudah terdata!");
        }

        $sql = "UPDATE jbsfina.bank
                   SET bank = '$bank', bankloc = '$bankLoc', bankno = '$bankNo', bankname = '$bankName', 
                       rekkas = '$rekKas', rekpendapatan = '$rekPendapatan',
                       keterangan = '$keterangan', issync = 0 
                 WHERE replid = $bankReplid ";
        QueryDbEx($sql);

        return createJsonReturn(1, "OK");
    }
    catch (Exception $ex)
    {
        return createJsonReturn(-1, $ex->getMessage());
    }

}

function createJsonReturn($status, $message)
{
    $ret = [$status, $message];
    return json_encode($ret, JSON_THROW_ON_ERROR);
}
?>