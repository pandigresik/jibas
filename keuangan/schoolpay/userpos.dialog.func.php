<?php
function LoadValue()
{
    global $userReplid;
    global $userId, $userName, $origPass, $keterangan;

    if ($userReplid == 0)
        return;

    $sql = "SELECT * FROM jbsfina.userpos WHERE replid = $userReplid";
    $res = QueryDb($sql);
    if ($row = mysqli_fetch_array($res))
    {
        $userId = $row["userid"];
        $userName = $row["nama"];

        $passLen = $row["passlength"];

        $origPass = $row["password"];
        $origPass = substr((string) $origPass, 0, $passLen);

        $keterangan = $row["keterangan"];
    }
}

function SimpanPetugas()
{
    $userReplid = $_REQUEST["userreplid"];
    $userId = SafeValue($_REQUEST["userid"]);
    $userName = SafeValue($_REQUEST["username"]);
    $origPass = SafeValue($_REQUEST["origpass"]);
    $password = SafeValue($_REQUEST["password"]);
    $keterangan = SafeValue($_REQUEST["keterangan"]);

    if ($userReplid == 0)
    {
        $sql = "SELECT COUNT(replid)
                  FROM jbsfina.userpos 
                 WHERE userid = '".$userId."'";
        $nData = FetchSingle($sql);
        if ($nData > 0)
        {
            return createJsonReturn(-1, "User Id $userId sudah digunakan. Pilih user id yang lain");
        }

        $sql = "INSERT INTO jbsfina.userpos 
                   SET userid = '$userId', nama = '$userName', password = md5('$password'), 
                       passlength = LENGTH('$password'), keterangan = '$keterangan', aktif = 1";
        QueryDb($sql);

        return createJsonReturn(1, "OK");
    }

    $sql = "SELECT COUNT(replid)
              FROM jbsfina.userpos 
             WHERE userid = '$userId'
               AND replid <> $userReplid";
    $nData = FetchSingle($sql);
    if ($nData > 0)
    {
        return createJsonReturn(-1, "User Id $userId sudah digunakan. Pilih user id yang lain");
    }

    if ($origPass == $password)
    {
        $sql = "UPDATE jbsfina.userpos
                   SET userid = '$userId', nama = '$userName', keterangan = '$keterangan'
                 WHERE replid = $userReplid ";
    }
    else
    {
        $sql = "UPDATE jbsfina.userpos
                   SET userid = '$userId', nama = '$userName', keterangan = '$keterangan', 
                       password = md5('$password'), passlength = LENGTH('$password')
                 WHERE replid = $userReplid ";
    }
    QueryDb($sql);

    return createJsonReturn(1, "OK");
}

function createJsonReturn($status, $message)
{
    $ret = [$status, $message];
    return json_encode($ret, JSON_THROW_ON_ERROR);
}

?>