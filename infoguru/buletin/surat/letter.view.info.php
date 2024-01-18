<?php
require_once("../../include/config.php");
require_once("../../include/db_functions.php");

$idsurat = $_REQUEST['idsurat'];

OpenDb();

$sql = "SELECT *, IF(s.jenis = 'IN', 'MASUK', 'KELUAR') AS fjenis, DATE_FORMAT(s.tanggal, '%d %b %Y') AS ftanggal
          FROM jbsletter.surat s, jbsletter.kategori k
         WHERE s.idkategori = k.replid 
           AND s.replid = '".$idsurat."'";
$res = QueryDb($sql);
$row = mysqli_fetch_array($res);

?>
<table border='0' cellpadding='5' width='100%'>
<tr><td align='left' valign='top'>
    
<font style='font-family: arial; font-size: 14px; font-weight: bold;'>INFORMASI</font><br>
<table border='0' cellpadding='2' width='100%'>
<tr>
    <td align='right' width='30%'>Departemen:</td>
    <td align='left' style='font-weight: bold;' width='*'><?=$row['departemen']?></td>
</tr>
<tr>
    <td align='right'>Nomor:</td>
    <td align='left' style='font-weight: bold;'><?=$row['nomor']?></td>
</tr>
<tr>
    <td align='right'>Perihal:</td>
    <td align='left' style='font-weight: bold;'><?=$row['perihal']?></td>
</tr>
<tr>
    <td align='right'>Tanggal:</td>
    <td align='left' style='font-weight: bold;'><?=$row['ftanggal']?></td>
</tr>
<tr>
    <td align='right'>Kategori:</td>
    <td align='left' style='font-weight: bold;'><?=$row['kategori']?></td>
</tr>
<tr>
    <td align='right'>Jenis:</td>
    <td align='left' style='font-weight: bold;'><?= $row['fjenis'] ?></td>
</tr>
<tr>
    <td valign='top' align='right'>Deskripsi:</td>
    <td valign='top' align='left'><?= $row['deskripsi'] ?></td>
</tr>
<tr>
    <td valign='top' align='right'>Keterangan:</td>
    <td valign='top' align='left'><?= $row['keterangan'] ?></td>
</tr>
<tr>
    <td valign='top' align='right'>Berkas:</td>
    <td valign='top' align='left'>&nbsp;</td>
</tr>
<tr>
    <td valign='top' align='left' colspan="2">
<?php
        $jsonFile = $row["idfile"];
        $lsFile = json_decode((string) $jsonFile, null, 512, JSON_THROW_ON_ERROR);

        for($i = 0; $i < count($lsFile); $i++)
        {
            $item = $lsFile[$i];
            $dtFile = $item[1];

            $pos = -1;
            $nDot = 0;
            do
            {
                $pos = strpos((string) $dtFile, ".", $pos + 1);
                if ($pos !== FALSE) $nDot += 1;
            }
            while($pos !== FALSE && $nDot < 3);

            if ($pos !== FALSE && $nDot == 3)
            {
                $nmFile = substr((string) $dtFile,$pos + 1);
                $lnFile = "$FILESHARE_ADDR/jibasls/$dtFile";
                echo "&bull;&nbsp;<a style='color: blue; font-weight: normal; text-decoration: underline' href='$lnFile' target='_blank'>$nmFile</a><br>";
            }
        }
?>
    </td>
</tr>
</table>

<?php
if ($row['fjenis'] == "MASUK")
{
    $sql = "SELECT s.sumber
              FROM jbsletter.suratinsrc src, jbsletter.sumberin s
             WHERE src.idsumber = s.replid
               AND src.idsurat = '".$idsurat."'";
    $res = QueryDb($sql);
    $row = mysqli_fetch_array($res);
?>
    <br>
    <font style='font-family: arial; font-size: 14px; font-weight: bold;'>SUMBER</font><br>
    <table border='0' cellpadding='2' width='100%'>
    <tr>
        <td valign='top' align='right' width='30%'>Sumber:</td>
        <td valign='top' align='left' style='font-weight: bold;' width='*'><?=$row['sumber']?></td>
    </tr>
    </table>
    
    <br>
    <font style='font-family: arial; font-size: 14px; font-weight: bold;'>TUJUAN</font><br>
    <table border='0' cellpadding='2' width='100%'>
    <tr>
        <td valign='top' align='right' width='30%'>Kelompok:</td>
        <td valign='top' align='left' style='font-weight: bold;' width='*'>
<?php
        $sql = "SELECT k.kelompok
                  FROM jbsletter.suratindstgroup dst, jbsletter.kelompok k
                 WHERE dst.idkelompok = k.replid
                   AND dst.idsurat = '$idsurat'
                 ORDER BY k.kelompok";
        $res = QueryDb($sql);
        $kelompok = "";
        while($row = mysqli_fetch_row($res))
        {
            if ($kelompok != "")
                $kelompok .= ", ";
            $kelompok .= $row[0];    
        }
        echo $kelompok == "" ? "-" : $kelompok;
?>
        </td>
    </tr>
    <tr>
        <td valign='top' align='right' width='30%'>Perorangan:</td>
        <td valign='top' align='left' style='font-weight: bold;' width='*'>
<?php
        $sql = "SELECT p.nama
                  FROM jbsletter.suratindstuser dst, jbssdm.pegawai p
                 WHERE dst.iduser = p.nip
                   AND dst.idsurat = '$idsurat'
                 ORDER BY p.nama";
        $res = QueryDb($sql);
        $nama = "";
        while($row = mysqli_fetch_row($res))
        {
            if ($nama != "")
                $nama .= ", ";
            $nama .= $row[0];    
        }
        echo $nama == "" ? "-" : $nama;
?>
        </td>
    </tr>
    <tr>
        <td valign='top' align='right' width='30%'>Tembusan:</td>
        <td valign='top' align='left' style='font-weight: bold;' width='*'>
<?php
        $sql = "SELECT p.nama
                  FROM jbsletter.suratindstcc dst, jbssdm.pegawai p
                 WHERE dst.iduser = p.nip
                   AND dst.idsurat = '$idsurat'
                 ORDER BY p.nama";
        $res = QueryDb($sql);
        $nama = "";
        while($row = mysqli_fetch_row($res))
        {
            if ($nama != "")
                $nama .= ", ";
            $nama .= $row[0];    
        }
        echo $nama == "" ? "-" : $nama;
?>
        </td>
    </tr>
    </table>
<?php
}
else
{
    $sql = "SELECT t.tujuan
              FROM jbsletter.suratoutdst dst, jbsletter.tujuanout t
             WHERE dst.idtujuan = t.replid
               AND dst.idsurat = '".$idsurat."'";
    $res = QueryDb($sql);
    $row = mysqli_fetch_array($res);
?>
    <br>
    <font style='font-family: arial; font-size: 14px; font-weight: bold;'>TUJUAN SURAT</font><br>
    <table border='0' cellpadding='2' width='100%'>
    <tr>
        <td valign='top' align='right' width='30%'>Tujuan:</td>
        <td valign='top' align='left' style='font-weight: bold;' width='*'><?=$row['tujuan']?></td>
    </tr>
    </table>
    
    <br>
    <font style='font-family: arial; font-size: 14px; font-weight: bold;'>SUMBER SURAT</font><br>
    <table border='0' cellpadding='2' width='100%'>
    <tr>
        <td valign='top' align='right' width='30%'>Kelompok:</td>
        <td valign='top' align='left' style='font-weight: bold;' width='*'>
<?php
        $sql = "SELECT k.kelompok
                  FROM jbsletter.suratoutsrcgroup src, jbsletter.kelompok k
                 WHERE src.idkelompok = k.replid
                   AND src.idsurat = '$idsurat'
                 ORDER BY k.kelompok";
        $res = QueryDb($sql);
        $kelompok = "";
        while($row = mysqli_fetch_row($res))
        {
            if ($kelompok != "")
                $kelompok .= ", ";
            $kelompok .= $row[0];    
        }
        echo $kelompok == "" ? "-" : $kelompok;
?>
        </td>
    </tr>
    <tr>
        <td valign='top' align='right' width='30%'>Perorangan:</td>
        <td valign='top' align='left' style='font-weight: bold;' width='*'>
<?php
        $sql = "SELECT p.nama
                  FROM jbsletter.suratoutsrcuser src, jbssdm.pegawai p
                 WHERE src.iduser = p.nip
                   AND src.idsurat = '$idsurat'
                 ORDER BY p.nama";
        $res = QueryDb($sql);
        $nama = "";
        while($row = mysqli_fetch_row($res))
        {
            if ($nama != "")
                $nama .= ", ";
            $nama .= $row[0];    
        }
        echo $nama == "" ? "-" : $nama;
?>
        </td>
    </tr>
    <tr>
        <td valign='top' align='right' width='30%'>Tembusan:</td>
        <td valign='top' align='left' style='font-weight: bold;' width='*'>
<?php
        $sql = "SELECT p.nama
                  FROM jbsletter.suratoutsrccc src, jbssdm.pegawai p
                 WHERE src.iduser = p.nip
                   AND src.idsurat = '$idsurat'
                 ORDER BY p.nama";
        $res = QueryDb($sql);
        $nama = "";
        while($row = mysqli_fetch_row($res))
        {
            if ($nama != "")
                $nama .= ", ";
            $nama .= $row[0];    
        }
        echo $nama == "" ? "-" : $nama;
?>
        </td>
    </tr>
    </table>
<?php
}
?>
</td></tr></table>

<?php
CloseDb();
?>