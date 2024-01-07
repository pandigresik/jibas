<?php
/**[N]**
 * JIBAS Education Community
 * Jaringan Informasi Bersama Antar Sekolah
 *
 * @version: 29.0 (Sept 20, 2023)
 * @notes: JIBAS Education Community will be managed by Yayasan Indonesia Membaca (http://www.indonesiamembaca.net)
 *
 * Copyright (C) 2009 Yayasan Indonesia Membaca (http://www.indonesiamembaca.net)
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
<?php
function ShowSelectDepartemen()
{
    global $departemen;
    ?>
    <select name="departemen" id="departemen" onChange="refreshPage()" style="width:200px">
        <?php     $dep = getDepartemen(getAccess());
        foreach($dep as $value)
        {
            if ($departemen == "") $departemen = $value;
            ?>
            <option value="<?= $value ?>" <?= $departemen == $value ? "selected" : "" ?> >
                <?=$value ?>
            </option>
        <?php 	} ?>
    </select>
    <?php
}

function HapusAutoTrans($idAutoTrans)
{
    $sql = "DELETE FROM jbsfina.autotransdata WHERE idautotrans = $idAutoTrans";
    QueryDb($sql);

    $sql = "DELETE FROM jbsfina.autotrans WHERE replid = $idAutoTrans";
    QueryDb($sql);
}

function ShowDaftar($departemen)
{
    ?>
    <table border="1" id="table" cellpadding="2" cellspacing="0" style="border-collapse: collapse; border-width: 1px" width="1150">
    <tr style="height: 25px">
        <td class="header" width="50" align="center">No</td>
        <td class="header" width="250">Pengaturan</td>
        <td class="header" width="100" align="center">Urutan</td>
        <td class="header" width="100" align="center">Aktif</td>
        <td class="header" width="550">Daftar Penerimaan</td>
        <td class="header" width="100" align="center">&nbsp;</td>
    </tr>
    <?php
    $sql = "SELECT * 
              FROM jbsfina.autotrans
             WHERE departemen = '$departemen'
             ORDER BY urutan";
    $res = QueryDb($sql);
    $no = 0;
    while($row = mysqli_fetch_array($res))
    {
        $no += 1;

        $idAutoTrans = $row["replid"];
        $imgAktif = $row["aktif"] == 1 ? "../images/ico/aktif.png" : "../images/ico/nonaktif.png";
        $kelompok = $row["kelompok"] == 1 ? "Siswa" : "Calon Siswa";
        ?>
        <tr>
            <td align="center" valign="top"><?=$no?></td>
            <td valign="top"><strong><?=$row['judul']?></strong><br>Pembayaran <?=$kelompok?><br><i><?=$row['keterangan']?></i></td>
            <td align="center" valign="top"><?=$row["urutan"]?></td>
            <td align="center" valign="top">
                <input type="hidden" id="aktif-<?=$no?>" value="<?=$row['aktif']?>">
                <a onclick='setAktif(<?=$no?>, <?=$idAutoTrans?>)' style='cursor: pointer'><img id='img-<?=$no?>' src='<?=$imgAktif?>'></a>
            </td>
            <td valign="top">
                <table border="0" cellspacing="0" cellpadding="3" width="400">
<?php       $sql = "SELECT dp.nama, ad.besar
                      FROM jbsfina.autotransdata ad, jbsfina.datapenerimaan dp
                     WHERE ad.idpenerimaan = dp.replid
                       AND ad.idautotrans = $idAutoTrans
                       AND ad.aktif = 1
                     ORDER BY ad.urutan";
                    $res2 = QueryDb($sql);
                    while($row2 = mysqli_fetch_array($res2))
                    {
                        echo "<tr style='height: 22px'>";
                        echo "<td align='left' width='250'>".$row2['nama']."</td>";
                        echo "<td align='right' width='150'>" . FormatRupiah($row2['besar']) . "</td>";
                        echo "<tr>";
                    } ?>
                </table>
            </td>
            <td align="center" valign="top">
                <a onclick="edit(<?=$idAutoTrans?>)" style="cursor: pointer"><img src="../images/ico/ubah.png" title="edit"></a>&nbsp;
                <a onclick="hapus(<?=$idAutoTrans?>)" style="cursor: pointer"><img src="../images/ico/hapus.png" title="edit"></a>
            </td>
        </tr>
        <?php
    }
    ?>
    </table>
<?php
}

function SetAktif($idAutoTrans, $newAktif)
{
    $sql = "UPDATE jbsfina.autotrans SET aktif = $newAktif WHERE replid = $idAutoTrans";
    QueryDb($sql);
}
?>