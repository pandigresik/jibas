<?php
/**[N]**
 * JIBAS Education Community
 * Jaringan Informasi Bersama Antar Sekolah
 * 
 * @version: 23.0 (November 12, 2020)
 * @notes: 
 * 
 * Copyright (C) 2024 JIBAS (http://www.jibas.net)
 * 
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 **[N]**/ ?>
<?
function GetPhoneList($jenis, $nis)
{
    $phonelist = array();
    if ($jenis == 'SISPAY' || $jenis == 'SISTAB' || $jenis == 'SISTUNG')
        $sql = "SELECT IF(s.hportu IS NULL, '', TRIM(s.hportu)),
                       IF(s.info1 IS NULL, '', TRIM(s.info1)),
                       IF(s.info2 IS NULL, '', TRIM(s.info2))
                  FROM jbsakad.siswa s
                 WHERE nis = '$nis'";
    else if ($jenis == 'PEGTAB')
        $sql = "SELECT IF(p.handphone IS NULL, '', TRIM(p.handphone)) AS hp
                  FROM jbssdm.pegawai p
                  WHERE p.nip = '$nis'";
    else
        $sql = "SELECT IF(cs.hportu IS NULL, '', TRIM(cs.hportu)),
                       IF(cs.info1 IS NULL, '', TRIM(cs.info1)),
                       IF(cs.info2 IS NULL, '', TRIM(cs.info2))
                  FROM jbsakad.calonsiswa cs
                 WHERE nopendaftaran = '$nis'";
    //echo "$sql<br>";
    $res = QueryDb($sql);
    if (mysqli_num_rows($res) > 0)
    {
        $row = mysqli_fetch_row($res);
        $temp = array($row[0], $row[1], $row[2]);
        $j = 0;
        for($i = 0; $i < count($temp); $i++)
        {
            $phone = trim($temp[$i]);
            if (strlen($phone) < 5)
                continue;
            if (substr($phone, 0, 1) == "#")
                continue;

            $phonelist[$j] = $phone;
            $j++;
        }
    }

    return $phonelist;
}

function GetChatIdList($jenis, $nis)
{
    $chatIdList = array();

    if ($jenis == 'SISPAY' || $jenis == 'SISTAB' || $jenis == 'SISTUNG')
        $sql = "SELECT chatid
                  FROM jbstgram.member
                 WHERE nis = '$nis'
                   AND aktif = 1";
    else if ($jenis == 'PEGTAB')
        $sql = "SELECT chatid
                  FROM jbstgram.member
                 WHERE nip = '$nis'
                   AND aktif = 1";
    else
        $sql = "SELECT chatid
                  FROM jbstgram.member
                 WHERE nic = '$nis'
                   AND aktif = 1";
    $res = QueryDb($sql);
    while($row = mysqli_fetch_row($res))
    {
        $chatIdList[] = $row[0];
    }
    return $chatIdList;
}

function GetFcmTokenList($jenis, $userId)
{
    $fcmTokenList = array();

    if ($jenis == 'SISPAY' || $jenis == 'SISTAB' || $jenis == 'SISTUNG')
        $sql = "SELECT token
                  FROM jbsjs.usertoken
                 WHERE nis = '$userId'
                   AND aktif = 1
				   AND loggedin = 1";
    else if ($jenis == 'PEGTAB')
        $sql = "SELECT token
                  FROM jbsjs.usertoken
                 WHERE nip = '$userId'
                   AND aktif = 1
				   AND loggedin = 1";
    else
        $sql = "SELECT token
                  FROM jbsjs.usertoken
                 WHERE nic = '$userId'
                   AND aktif = 1
				   AND loggedin = 1";

    $res = QueryDb($sql);
    while($row = mysqli_fetch_row($res))
    {
        $fcmTokenList[] = $row[0];
    }

    return $fcmTokenList;
}

function CreateSMSTunggakan($jenis, $departemen, $nis, $nama, $sms, &$success)
{
    $success = true;

    $phonelist = GetPhoneList($jenis, $nis);
    $chatIdList = GetChatIdList($jenis, $nis);
    $fcmTokenList = GetFcmTokenList($jenis, $nis);

    if (count($phonelist) == 0 && count($chatIdList) == 0 && count($fcmTokenList) == 0)
    {
        $success = true; // Tidak ada pengiriman Notifikasi
        return;
    }

    // 2020-07-24
    // Telegram Gateway
    $nTgwSend = 0;
    $nJsSend = 0;
    if (count($chatIdList) > 0)
    {
        for($i = 0; $success && $i < count($chatIdList); $i++)
        {
            $chatId = $chatIdList[$i];

            $sql = "INSERT INTO jbstgram.send
                       SET msgdate = NOW(), destchatid = $chatId, destname = '$nama', srcchatid = 0, msgtext = '$sms', msgtype = 1, msgsource = '$jenis'";
            QueryDbTrans($sql, $success);

            $sql = "SELECT LAST_INSERT_ID()";
            $idSend = FetchSingle($sql);

            $sql = "INSERT INTO jbstgram.sendhistory
                       SET idsend = $idSend, msgdate = NOW(), destchatid = $chatId,  destname = '$nama', srcchatid = 0, msgtext = '$sms', msgtype = 1, msgsource = '$jenis'";
            QueryDbTrans($sql, $success);

            $nTgwSend += 1;
        }
    }

    if ($success && count($fcmTokenList) > 0)
    {
        $jsonTokenList = json_encode($fcmTokenList);
        $jsonTokenList = str_replace('"', '\"', $jsonTokenList);

        $sql = "INSERT INTO jbsjs.notif SET msgdate = NOW(), desttoken = '$jsonTokenList', topicid = '', msgtitle = 'Info Tunggakan', msgbody = '$sms', msgsource = '$jenis'";
        QueryDbTrans($sql, $success);

        $sql = "INSERT INTO jbsjs.notifmessage SET nis = '$nis', nip = null, nic = null, message = '$sms', notifdate = NOW(), source = '$jenis', title = 'Info Tunggakan'";
        QueryDbTrans($sql, $success);

        $sql = "INSERT INTO jbsjs.notifmessagehistory SET nis = '$nis', nip = null, nic = null, message = '$sms', notifdate = NOW(), source = '$jenis', title = 'Info Tunggakan'";
        QueryDbTrans($sql, $success);

        $nJsSend = 1;
    }

    /*
    if (!$success)
        return;

    if ($nTgwSend > 0 || $nJsSend > 0)
        return;
    */

    // SMS Gateway
    $sql = "SELECT replid
              FROM jbsakad.departemen
             WHERE departemen = '$departemen'";
    $deptid = (int)FetchSingle($sql);

    $idsmsgeninfo = 0;
    $sql = "SELECT COUNT(replid)
              FROM jbssms.smsgeninfo
             WHERE tanggal = CURDATE()
               AND tipe = 0
               AND info LIKE '[$jenis.$deptid]%'";
    $ndata = (int)FetchSingle($sql);
    if ($ndata == 0)
    {
        $info = $jenis == 'SISTUNG' ? "Siswa" : "Calon Siswa";

        $sql = "INSERT INTO jbssms.smsgeninfo
                   SET tanggal = CURDATE(), tipe = 0,
                       info = '[$jenis.$deptid] Pengiriman Otomatis SMS Informasi Tunggakan $info departemen $departemen',
                       pengirim = 'KEU.$deptid'";
        QueryDbTrans($sql, $success);

        $sql = "SELECT LAST_INSERT_ID()";
        $idsmsgeninfo = (int)FetchSingle($sql);
    }
    else
    {
        $sql = "SELECT replid
                  FROM jbssms.smsgeninfo
                 WHERE tanggal = CURDATE()
                   AND tipe = 0
                   AND info LIKE '[$jenis.$deptid]%'";
        $idsmsgeninfo = (int)FetchSingle($sql);
    }

    for($i = 0; $success && $i < count($phonelist); $i++)
    {
        $phone = $phonelist[$i];

        $sql = "INSERT INTO jbssms.outbox 
                   SET UpdatedInDB = NOW(), InsertIntoDB = NOW(), SendingDateTime = NOW(),
                       Text = '$sms', DestinationNumber = '$phone', Coding = '8bit', UDH = NULL,
                       Class = -1, TextDecoded = '', MultiPart = 'false', RelativeValidity = -1, SenderID = 'JIBAS.KEU', 
                       SendingTimeOut = '0000-00-00 00:00:00', DeliveryReport = 'default', CreatorID = 'JIBAS.KEU',
                       idsmsgeninfo = '$idsmsgeninfo', status = 0";
        QueryDbTrans($sql, $success);

        $sql = "INSERT INTO jbssms.outboxhistory
                   SET InsertIntoDB = NOW(),
                       SendingDateTime = NOW(),
                       Text = '$sms',
                       DestinationNumber = '$phone',
                       idsmsgeninfo = '$idsmsgeninfo',
                       status = 0,
                       SenderID = 'JIBAS.KEU'";
        QueryDbTrans($sql, $success);
    }
}

function CreateSMSPaymentInfo($jenis, $departemen, $nis, $nama, $tanggal, $besar, $pembayaran, &$success)
{
    $success = true;

    $sql = "SELECT COUNT(*)
              FROM jbsfina.formatsms
             WHERE jenis = '$jenis'
               AND departemen = '$departemen'";
    $ndata = FetchSingle($sql);
    if ($ndata == 0)
    {
        $format = "Terima kasih, kami telah menerima pembayaran dari {NAMA} tanggal {TANGGAL} sebesar {BESAR} untuk {PEMBAYARAN} - Bag. Keuangan";
        $sql = "INSERT INTO jbsfina.formatsms
				   SET jenis = '$jenis', departemen = '$departemen', format = '$format'";
        QueryDbTrans($sql, $success);
    }

    $sql = "SELECT format
              FROM jbsfina.formatsms
             WHERE jenis = '$jenis'
               AND departemen = '$departemen'";
    $formatsms = FetchSingle($sql);
    if (strlen(trim($formatsms)) == 0)
    {
        $success = true; // Tidak ada pengiriman SMS
        return;
    }

    $phonelist = GetPhoneList($jenis, $nis);
    $chatIdList = GetChatIdList($jenis, $nis); // 2020-06-24
    $fcmTokenList = GetFcmTokenList($jenis, $nis);

    if (count($phonelist) == 0 && count($chatIdList) == 0 && count($fcmTokenList) == 0)
    {
        $success = true; // Tidak ada pengiriman SMS
        return;
    }

    $sms = $formatsms;
    $sms = str_replace("{NIS}", $nis, $sms);
    $sms = str_replace("{NAMA}", $nama, $sms);
    $sms = str_replace("{TANGGAL}", $tanggal, $sms);
    $sms = str_replace("{BESAR}", $besar, $sms);
    $sms = str_replace("{PEMBAYARAN}", $pembayaran, $sms);

    // 2020-07-24
    // Telegram Gateway
    $nTgwSend = 0;
    $nJsSend = 0;
    if (count($chatIdList) > 0)
    {
        for($i = 0; $success && $i < count($chatIdList); $i++)
        {
            $chatId = $chatIdList[$i];

            $sql = "INSERT INTO jbstgram.send
                       SET msgdate = NOW(), destchatid = $chatId, destname = '$nama', srcchatid = 0, msgtext = '$sms', msgtype = 1, msgsource = '$jenis'";
            QueryDbTrans($sql, $success);

            $sql = "SELECT LAST_INSERT_ID()";
            $idSend = FetchSingle($sql);

            $sql = "INSERT INTO jbstgram.sendhistory
                       SET idsend = $idSend, msgdate = NOW(), destchatid = $chatId, destname = '$nama', srcchatid = 0, msgtext = '$sms', msgtype = 1, msgsource = '$jenis'";
            QueryDbTrans($sql, $success);

            $nTgwSend += 1;
        }
    }

    if ($success && count($fcmTokenList) > 0)
    {
        $jsonTokenList = json_encode($fcmTokenList);
        $jsonTokenList = str_replace('"', '\"', $jsonTokenList);

        $sql = "INSERT INTO jbsjs.notif SET msgdate = NOW(), desttoken = '$jsonTokenList', topicid = '', msgtitle = 'Pembayaran', msgbody = '$sms', msgsource = '$jenis'";
        QueryDbTrans($sql, $success);

        $sql = "INSERT INTO jbsjs.notifmessage SET nis = '$nis', nip = null, nic = null, message = '$sms', notifdate = NOW(), source = '$jenis', title = 'Pembayaran'";
        QueryDbTrans($sql, $success);

        $sql = "INSERT INTO jbsjs.notifmessagehistory SET nis = '$nis', nip = null, nic = null, message = '$sms', notifdate = NOW(), source = '$jenis', title = 'Pembayaran'";
        QueryDbTrans($sql, $success);

        $nJsSend = 1;
    }

    if (!$success)
        return;

    /*
    if ($nTgwSend > 0 || $nJsSend > 0)
        return;
    */

    // SMS Gateway
    $sql = "SELECT replid
              FROM jbsakad.departemen
             WHERE departemen = '$departemen'";
    $deptid = (int)FetchSingle($sql);

    $idsmsgeninfo = 0;
    $sql = "SELECT COUNT(replid)
              FROM jbssms.smsgeninfo
             WHERE tanggal = CURDATE()
               AND tipe = 0
               AND info LIKE '[$jenis.$deptid]%'";
    $ndata = (int)FetchSingle($sql);
    if ($ndata == 0)
    {
        $info = $jenis == 'SISPAY' ? "Siswa" : "Calon Siswa";

        $sql = "INSERT INTO jbssms.smsgeninfo
                   SET tanggal = CURDATE(), tipe = 0,
                       info = '[$jenis.$deptid] Pengiriman Otomatis SMS Informasi Pembayaran $info departemen $departemen',
                       pengirim = 'KEU.$deptid'";
        QueryDbTrans($sql, $success);

        $sql = "SELECT LAST_INSERT_ID()";
        $idsmsgeninfo = (int)FetchSingle($sql);
    }
    else
    {
        $sql = "SELECT replid
                  FROM jbssms.smsgeninfo
                 WHERE tanggal = CURDATE()
                   AND tipe = 0
                   AND info LIKE '[$jenis.$deptid]%'";
        $idsmsgeninfo = (int)FetchSingle($sql);
    }

    if (!$success)
        return;


    for($i = 0; $success && $i < count($phonelist); $i++)
    {
        $phone = $phonelist[$i];

        $sql = "INSERT INTO jbssms.outbox 
                   SET UpdatedInDB = NOW(), InsertIntoDB = NOW(), SendingDateTime = NOW(),
                       Text = '$sms', DestinationNumber = '$phone', Coding = '8bit', UDH = NULL,
                       Class = -1, TextDecoded = '', MultiPart = 'false', RelativeValidity = -1, SenderID = 'JIBAS.KEU', 
                       SendingTimeOut = '0000-00-00 00:00:00', DeliveryReport = 'default', CreatorID = 'JIBAS.KEU',
                       idsmsgeninfo = '$idsmsgeninfo', status = 0";
        QueryDbTrans($sql, $success);

        $sql = "INSERT INTO jbssms.outboxhistory
                   SET InsertIntoDB = NOW(),
                       SendingDateTime = NOW(),
                       Text = '$sms',
                       DestinationNumber = '$phone',
                       idsmsgeninfo = '$idsmsgeninfo',
                       status = 0,
                       SenderID = 'JIBAS.KEU'";
        QueryDbTrans($sql, $success);
    }
}

function CreateSMSTabungan($jenis, $departemen, $nis, $nama, $tanggal, $besar, $saldo, $pembayaran, $keterangan, &$success)
{
    $success = true;

    $sql = "SELECT COUNT(*)
              FROM jbsfina.formatsms
             WHERE jenis = '$jenis'
               AND departemen = '$departemen'";
    $ndata = FetchSingle($sql);
    if ($ndata == 0)
    {
        $format = "Kami informasikan transaksi tabungan dari {NAMA} tanggal {TANGGAL} sebesar {BESAR} untuk {PEMBAYARAN} saldo {SALDO} keterangan {KETERANGAN} - Bag. Keuangan";
        $sql = "INSERT INTO jbsfina.formatsms
				   SET jenis = '$jenis', departemen = '$departemen', format = '$format'";
        QueryDbTrans($sql, $success);
    }

    $sql = "SELECT format
              FROM jbsfina.formatsms
             WHERE jenis = '$jenis'
               AND departemen = '$departemen'";
    $formatsms = FetchSingle($sql);
    if (strlen(trim($formatsms)) == 0)
    {
        $success = true; // Tidak ada pengiriman Notifikasi
        return;
    }

    $phonelist = GetPhoneList($jenis, $nis);
    $chatIdList = GetChatIdList($jenis, $nis);
    $fcmTokenList = GetFcmTokenList($jenis, $nis);

    if (count($phonelist) == 0 && count($chatIdList) == 0 && count($fcmTokenList) == 0)
    {
        $success = true; // Tidak ada pengiriman Notifikasi
        return;
    }

    // 2020-07-24
    // Telegram Gateway

    $sms = $formatsms;
    $sms = str_replace("{NIS}", $nis, $sms);
    $sms = str_replace("{NAMA}", $nama, $sms);
    $sms = str_replace("{TANGGAL}", $tanggal, $sms);
    $sms = str_replace("{BESAR}", $besar, $sms);
    $sms = str_replace("{PEMBAYARAN}", $pembayaran, $sms);
    $sms = str_replace("{SALDO}", $saldo, $sms);
    $sms = str_replace("{KETERANGAN}", $keterangan, $sms);

    $nTgwSend = 0;
    $nJsSend = 0;
    if (count($chatIdList) > 0)
    {
        for($i = 0; $success && $i < count($chatIdList); $i++)
        {
            $chatId = $chatIdList[$i];

            $sql = "INSERT INTO jbstgram.send
                       SET msgdate = NOW(), destchatid = $chatId, destname = '$nama', srcchatid = 0, msgtext = '$sms', msgtype = 1, msgsource = '$jenis'";
            QueryDbTrans($sql, $success);

            $sql = "SELECT LAST_INSERT_ID()";
            $idSend = FetchSingle($sql);

            $sql = "INSERT INTO jbstgram.sendhistory
                       SET idsend = $idSend, msgdate = NOW(), destchatid = $chatId, destname = '$nama', srcchatid = 0, msgtext = '$sms', msgtype = 1, msgsource = '$jenis'";
            QueryDbTrans($sql, $success);

            $nTgwSend += 1;
        }
    }

    if ($success && count($fcmTokenList) > 0)
    {
        $jsonTokenList = json_encode($fcmTokenList);
        $jsonTokenList = str_replace('"', '\"', $jsonTokenList);

        $sql = "INSERT INTO jbsjs.notif SET msgdate = NOW(), desttoken = '$jsonTokenList', topicid = '', msgtitle = 'Tabungan', msgbody = '$sms', msgsource = '$jenis'";
        QueryDbTrans($sql, $success);

        if ($jenis == "SISTAB")
        {
            $sql = "INSERT INTO jbsjs.notifmessage SET nis = '$nis', nip = null, nic = null, message = '$sms', notifdate = NOW(), source = '$jenis', title = 'Tabungan'";
            QueryDbTrans($sql, $success);

            $sql = "INSERT INTO jbsjs.notifmessagehistory SET nis = '$nis', nip = null, nic = null, message = '$sms', notifdate = NOW(), source = '$jenis', title = 'Tabungan'";
            QueryDbTrans($sql, $success);
        }
        else if ($jenis == "PEGTAB")
        {
            $sql = "INSERT INTO jbsjs.notifmessage SET nip = '$nis', nis = null, nic = null, message = '$sms', notifdate = NOW(), source = '$jenis', title = 'Tabungan'";
            QueryDbTrans($sql, $success);

            $sql = "INSERT INTO jbsjs.notifmessagehistory SET nip = '$nis', nis = null, nic = null, message = '$sms', notifdate = NOW(), source = '$jenis', title = 'Tabungan'";
            QueryDbTrans($sql, $success);
        }

        $nJsSend = 1;
    }

    if (!$success)
        return;

    /*
    if ($nTgwSend > 0 || $nJsSend > 0)
        return;
    */

    // SMS Gateway

    $sql = "SELECT replid
              FROM jbsakad.departemen
             WHERE departemen = '$departemen'";
    $deptid = (int)FetchSingle($sql);

    $idsmsgeninfo = 0;
    $sql = "SELECT COUNT(replid)
              FROM jbssms.smsgeninfo
             WHERE tanggal = CURDATE()
               AND tipe = 0
               AND info LIKE '[$jenis.$deptid]%'";
    $ndata = (int)FetchSingle($sql);
    if ($ndata == 0)
    {
        $info = $jenis == 'SISTAB' ? "Siswa" : "Calon Siswa";

        $sql = "INSERT INTO jbssms.smsgeninfo
                   SET tanggal = CURDATE(), tipe = 0,
                       info = '[$jenis.$deptid] Pengiriman Otomatis SMS Informasi Tabungan $info departemen $departemen',
                       pengirim = 'KEU.$deptid'";
        QueryDbTrans($sql, $success);

        $sql = "SELECT LAST_INSERT_ID()";
        $idsmsgeninfo = (int)FetchSingle($sql);
    }
    else
    {
        $sql = "SELECT replid
                  FROM jbssms.smsgeninfo
                 WHERE tanggal = CURDATE()
                   AND tipe = 0
                   AND info LIKE '[$jenis.$deptid]%'";
        $idsmsgeninfo = (int)FetchSingle($sql);
    }

    if (!$success)
        return;

    for($i = 0; $success && $i < count($phonelist); $i++)
    {
        $phone = $phonelist[$i];

        $sql = "INSERT INTO jbssms.outbox 
                   SET UpdatedInDB = NOW(), InsertIntoDB = NOW(), SendingDateTime = NOW(),
                       Text = '$sms', DestinationNumber = '$phone', Coding = '8bit', UDH = NULL,
                       Class = -1, TextDecoded = '', MultiPart = 'false', RelativeValidity = -1, SenderID = 'JIBAS.KEU', 
                       SendingTimeOut = '0000-00-00 00:00:00', DeliveryReport = 'default', CreatorID = 'JIBAS.KEU',
                       idsmsgeninfo = '$idsmsgeninfo', status = 0";
        QueryDbTrans($sql, $success);

        $sql = "INSERT INTO jbssms.outboxhistory
                   SET InsertIntoDB = NOW(),
                       SendingDateTime = NOW(),
                       Text = '$sms',
                       DestinationNumber = '$phone',
                       idsmsgeninfo = '$idsmsgeninfo',
                       status = 0,
                       SenderID = 'JIBAS.KEU'";
        QueryDbTrans($sql, $success);
    }
}
?>