<?php
function SetVendorAktif()
{
    $vendorReplid = $_REQUEST["replid"];
    $newAktif = $_REQUEST["newaktif"];

    $sql = "UPDATE jbsfina.vendor SET aktif = $newAktif WHERE replid = $vendorReplid";
    QueryDb($sql);
}


function createJsonReturn($status, $message)
{
    $ret = [$status, $message];
    return json_encode($ret, JSON_THROW_ON_ERROR);
}

function HapusVendor()
{
    $vendorId = $_REQUEST["vendorid"];

    $sql = "SELECT COUNT(replid) FROM jbsfina.paymenttrans WHERE vendorid = '".$vendorId."'";
    $nData = FetchSingle($sql);
    if ($nData != 0)
        return createJsonReturn(-1, "Tidak dapat menghapus vendor ini karena sudah digunakan dalam transaksi!");

    $sql = "DELETE FROM jbsfina.vendoruser WHERE vendorid = '".$vendorId."'";
    QueryDb($sql);

    $sql = "DELETE FROM jbsfina.vendor WHERE vendorid = '".$vendorId."'";
    QueryDb($sql);

    return createJsonReturn(1, "OK");
}

function HapusUserVendor()
{
    $vendorId = $_REQUEST["vendorid"];
    $userId = $_REQUEST["userid"];

    $sql = "DELETE FROM jbsfina.vendoruser WHERE vendorid = '$vendorId' AND userid = '".$userId."'";
    QueryDb($sql);
}

function ShowDaftarPetugas($vendorId)
{
    $sql = "SELECT vu.userid, u.nama, vu.tingkat, vu.replid
              FROM jbsfina.vendoruser vu, jbsfina.userpos u 
             WhERE vu.userid = u.userid
               AND vu.vendorid = '$vendorId'
             ORDER BY vu.tingkat, u.nama";
    $res = QueryDb($sql);
    $num = mysqli_num_rows($res);
    if ($num == 0)
    {
        echo "(belum ada data petugas)";
        return;
    }

    $sb = new StringBuilder();
    $sb->AppendLine("<table border='0' cellspacing='0' cellpadding='2'>");
    while($row = mysqli_fetch_row($res))
    {
        $userId = $row[0];
        $rowId = $row[3];

        $sb->AppendLine("<tr id='rowVendorUser$rowId'>");
        $sb->AppendLine("<td width='180' align='left' valign='top'>");
        $sb->AppendLine($row[1]);
        $sb->AppendLine("</td>");
        $sb->AppendLine("<td width='100' align='left' valign='top'>");
        if ($row[2] == 1)
            $sb->AppendLine(" Manager");
        else
            $sb->AppendLine(" Operator");
        $sb->AppendLine("</td>");
        $sb->AppendLine("<td width='50' align='left' valign='top'>");
        if (getLevel() != 2) {
            $sb->AppendLine("<a href=\"#\" onclick=\"hapusVendorUser($rowId, '$vendorId','$userId')\"><img src='../images/ico/hapus.png' border='0'></a>");
        }
        $sb->AppendLine("</td>");
        $sb->AppendLine("</tr>");
    }
    $sb->AppendLine("</table>");

    echo $sb->ToString();
}
?>