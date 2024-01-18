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
require_once('../include/errorhandler.php');
require_once('../include/sessioninfo.php');
require_once('../include/common.php');
require_once('../include/config.php');
require_once('../include/theme.php');
require_once('../include/db_functions.php');
require_once('../library/departemen.php');
openDb();
//=======================================Mode edit penentuan nilai akhir========================================

//===========================================++++++++++++++++++++++++++++++=====================================
if($num == 0) {
        echo "
            <font color='red' size='2'><b>Nilai Akhir Ujian untuk pelajaran</font>
            <font color='black' size='2'>'".$row_p['nama']."'</font><font color='red' size='2'>belum ada.
            Masukkanlah terlebih dahulu nilai akhir pelajaran tersebut !</b></font>
        ";
}else {
        $n = 0;
        foreach($my_data as $tt => $yy) {
            $n++;
}
	
    ?>
	
    <form action="mode_edit_penentuan.php" method="post" name="tampil_penentuan" onSubmit="return cek()">
    <table width="95%">
        <tr>
			<td><?echo "Jumlah data : $n ";?>
            <input type="hidden" name="pelajaran" value="<?=$pelajaran?>">
            <input type="hidden" name="semester" value="<?=$semester?>">
            <input type="hidden" name="kelas" value="<?=$kelas?>">
            <input type="hidden" name="tingkat" value="<?=$tingkat?>">
            <input type="hidden" name="info" value="<?=$row_cek['replid']?>">
        	</td>
		</tr>
    </table>
    <table width="95%" class="tab" border="1" id="table">
    <tr>
        <td rowspan="2" class="headerlong" width="30">No</td>
        <td rowspan="2" class="headerlong" width="70">NIS</td>
        <td rowspan="2" class="headerlong" width="150">Nama</td>
        <?php
        $query_ju = "SELECT replid, jenisujian FROM jbsakad.jenisujian WHERE idpelajaran = '".$pelajaran."'";
        $result_ju = QueryDb($query_ju) or die(mysqli_error($mysqlconnection));
        $num_ju = @mysqli_num_rows($result_ju);
		
		$cnt = 0;
		while($row_ju = @mysqli_fetch_array($result_ju)) {
			$idx_ju[$cnt] = $row_ju['replid'];
			$cnt++;
		}
		$result_ju = QueryDb($query_ju) or die(mysqli_error($mysqlconnection));
        ?>

        <td class="headerlong" colspan="<?=$num_ju;?>" align="center">Nilai Akhir</td>

        <?php
        $query_nhb = "SELECT replid, dasarpenilaian, bobot ".
                     "FROM jbsakad.aturannhb WHERE idpelajaran = '$pelajaran' ".
                     "AND idtingkat = '".$tingkat."'";
        $result_nhb = QueryDb($query_nhb) or die(mysqli_error($mysqlconnection));
        $num_nhb = @mysqli_num_rows($result_nhb);

        $r = 0;
        $v = 0;
		$idpraktek = "#";
		$idkonsep = "#";
        while($row_nhb = @mysqli_fetch_array($result_nhb)) {
            $plit = explode(";", (string) $row_nhb['bobot']);
            if($plit != "") {
                foreach($plit as $pl) {
                    $r++;
                    [$ujian, $bobot] = explode(":", $pl);
                    if($bobot != "") {
                        $cnt = 0;
						$found = false;
						while (($cnt < $num_ju) && !$found) {
							if ($idx_ju[$cnt] == $ujian)
								$found = true;
							else
								$cnt++;
						}
					    $as[$cnt] = $bobot;
                    }
					if ($row_nhb['dasarpenilaian'] == "Praktik") {
						$idpraktek = $idpraktek . "[" . $ujian . "]";
					} else {
						$idkonsep = $idkonsep . "[" . $ujian . "]";
					}
                }
            }
            $v++;
			$r_aturan[] = $row_nhb['replid'];
			$color = "white";
			if ($row_nhb['dasarpenilaian'] == "Praktik")
				$color = "cyan";
			else if ($row_nhb['dasarpenilaian'] == "Pemahaman Konsep")
				$color = "yellow";
            echo "<td class='headerlong' colspan='2' align='center'>
                <input type='hidden' name='aturan$v' value='".$row_nhb['replid']."'>
                <font size='1' color='$color'>Nilai '".$row_nhb['dasarpenilaian']."'</font></td>";
        }
        ?>
        <td rowspan="2" class="headerlong" align="center">
        <input type='hidden' name='num_nhb' value='<?=$num_nhb?>'>Predikat</td>
    </tr>
    <tr>
        <?php
        $c = 0;
        while($row_ju = @mysqli_fetch_array($result_ju)) {
            $c++;
			$pos = (int)strpos($idpraktek, "[" . $row_ju['replid'] . "]");
			$color = "white";
			if ($pos > 0) {
				$color = "cyan";
			} else {
				$pos = (int)strpos($idkonsep, "[" . $row_ju['replid'] . "]");
				if ($pos > 0) 
					$color = "yellow";
			}
			$cnt = 0;
			$found = false;
			while ($cnt < $num_ju && !$found) {
				if ($idx_ju[$cnt] == $row_ju['replid'])
					$found = true;
				else
					$cnt++;
			}
            echo "<td class='headerlong' align='center'><font color='$color'>".$row_ju['jenisujian'].  $as[$cnt]."</font></td>";
            $kolom[$row_ju['replid']] = $row_ju['replid'];
        }

        for($i=1;$i<=$num_nhb;$i++) {
            echo "<td class='headerlong' align='center'>Angka</td><td class='header' align='center'>Huruf</td>";
        }
        ?>
    </tr>
    <?php
	
    if($my_data != "") {
        $i = 0;
        foreach($my_data as $ns => $d) {
            $i++;
            echo "
                <tr>
                <td align='center'>$i</td>
                <td>$ns <input type='hidden' name='nis$i' value='$ns'></td>
                <td>".$d['nama']."</td>
            ";

            foreach($kolom as $k => $v) {
                echo "<td align='center'>".$d[$k]."</td>";
            }
            
            $id_aturan1 = null;
            $nau_b1 = null;
            $ttl_bbt1 = null;
            $ttl_nau_b1 = null;
            $nilaiangka1 = null;
			$t=0;
            foreach($r_aturan as $id_aturan1){
				$t++;
				$query_nhb = "SELECT bobot ".
							 "FROM jbsakad.aturannhb WHERE aturannhb.replid = '$id_aturan1'";				 
				$result_nhb = QueryDb($query_nhb) or die(mysqli_error($mysqlconnection));
				
				while($row_nhb = @mysqli_fetch_array($result_nhb)) {
					$plit = explode(";", (string) $row_nhb['bobot']);
					if($plit != "") {
					$r=0;
						$ttl_nau_b = 0;
						$ttl_bbt = 0;
						foreach($plit as $pl) {
							$r++;
							[$ujian, $bobot] = explode(":", $pl);
							if($bobot != "") {
								$as[$r] = $bobot;
							}
							
							$query_nau = "SELECT nau.nilaiAU FROM jbsakad.nau WHERE nis  = '$ns' ".
                                        "AND idjenis = '".$ujian."'";
							$result_nau = QueryDb($query_nau);
							$row_nau = mysqli_fetch_array($result_nau);
							
							$nau_b1 = $row_nau['nilaiAU'] * $bobot;
							$ttl_bbt1[$id_aturan1] += $bobot;
							$ttl_nau_b1[$id_aturan1] += $nau_b1;
							//echo "$ujian-$row_nau['NilaiAU']-$nau_b-$ttl_nau_b<br><br>";														
						}
					}
				}
				
				$query_nap = "SELECT nap.nilaihuruf, nap.nilaiangka FROM jbsakad.nap WHERE nis = '$ns' ".
                             "AND idaturan = '$id_aturan1' AND idinfo = '".$row_cek['replid']."'";
				$result_nap = QueryDb($query_nap);
				$row_nap = mysqli_fetch_array($result_nap);
				$nilaiangka1[$id_aturan1] = $ttl_nau_b1[$id_aturan1]/$ttl_bbt1[$id_aturan1];
                $f1 = sprintf("%01.2f", $nilaiangka1[$id_aturan1]);
                
                //Nilai akhir harus nya sesuai perhitungan ->$f hehehehe.....
                 echo "
                    <td align='center'><input type='text' name='nA$i$t' value='".$row_nap['nilaiangka']."' size='5'></td>
                    <td align='center'><input type='text' name='nH$i$t' value='".$row_nap['nilaihuruf']."' maxlength='2' size='5'></td>
                ";				
            }
			$query_kom = "SELECT predikat FROM jbsakad.komennap WHERE nis='$ns' AND idinfo = '".$row_cek['replid']."'";
            $result_kom = QueryDb($query_kom);
            $h = 0;
            $row_kom = @mysqli_fetch_array($result_kom);
                $h++;
                $predikat = $row_kom['predikat'];
                
                if($predikat == '0') {
                    $sel1 = "selected";
					$sel2 = "";
					$sel3 = "";
					$sel4 = "";
					$sel5 = "";
                }elseif($predikat == '1') {
					$sel1 = "";
                    $sel2 = "selected";
					$sel3 = "";
					$sel4 = "";
					$sel5 = "";
                }elseif($predikat == '2') {
					$sel1 = "";				
					$sel2 = "";
                    $sel3 = "selected";
					$sel4 = "";
					$sel5 = "";
                }elseif($predikat == '3') {
					$sel1 = "";				
					$sel2 = "";
					$sel3 = "";									
                    $sel4 = "selected";
					$sel5 = "";					
                }elseif($predikat == '4') {
					$sel1 = "";				
					$sel2 = "";
					$sel3 = "";
					$sel4 = "";				
                    $sel5 = "selected";
                }else {
				  	$sel1 = "selected";
					$sel2 = "";
					$sel3 = "";
					$sel4 = "";
					$sel5 = "";
				}
           
            echo "
                <td align='center'><select name='predikat$i'>
                <option value='0' $sel1></option>
                <option value='1' $sel2>Amat Baik</option>
                <option value='2' $sel3>Baik</option>
                <option value='3' $sel4>Cukup</option>
                <option value='4' $sel5>Kurang</option>
                </select>
				<img src='../images/ico/refresh.png' onMouseOver=\"showhint('Perhitungan Ulang Nilai Rapor',this,event,'100px')\" border=0 onclick='javascript:hitungulang($ns)'>
                </td>
                </tr>
            ";
        }
    }
    ?>
    </table>
        <script language='JavaScript'>
            Tables('table', 1, 0);
        </script>
	<input type="hidden" name="num_t" value="<?=$t ?>">
    <table width="95%" bgcolor="#a5ae0e" border="1">
        <tr><td align='left'>Nilai Standar Kelulusan : <input type="text" name="nlulus" value="<?=$row_cek['nilaimin']?>">
            <input type="submit" value="Ubah" name="simpan" class="but">
            <input type="button" value="Tambah Siswa" name="" class="but" onClick="newWindow('tambah_siswa_pn.php?departemen=<?=$departemen; ?>&tingkat=<?=$tingkat ?>&pelajaran=<?=$pelajaran ?>&semester=<?=$semester ?>&kelas=<?=$kelas ?>&tahun=<?=$tahun ?>&idinfo=<?=$row_cek['replid'] ?>',
            'Penilaian Pelajaran','900','250','resizable=1,scrollbars=1,status=0,toolbar=0')">
			<a href="#" onClick="delnap()"><img src="../images/ico/hapus.png" border="0">Hapus Nilai dan Komentar Rapor Pelajaran Ini</a>
        </td></tr>
    </table>
    </form>
<?php }?>
    </td>
    </tr>
</table>
</body>
</html>
<?php
if(isset($_POST['simpan'])) {
	
    $query_p = "UPDATE jbsakad.infonap SET ".
               "nilaimin = '".$_POST['nlulus']."' WHERE replid  = '".$_POST['info']."'";
    $result_p = QueryDb($query_p) or die(mysqli_error($mysqlconnection));
	
	//echo $query_p;

    //Nih tuk ambil replid komennap terus disimpen di dalam array===========================
    $query_ko = "SELECT komennap.replid FROM jbsakad.komennap, jbsakad.siswa " .
				" WHERE komennap.nis = siswa.nis AND idkelas = '".$_POST['kelas']."' AND aktif = 1 " .
				" AND idinfo = '".$_POST['info']."' ORDER BY siswa.nama";
    $result_ko = QueryDb($query_ko) or die (mysqli_error($mysqlconnection));
    $num_ko = @mysqli_num_rows($result_ko);
   
    $u = 0;
    while($row_ko = @mysqli_fetch_array($result_ko)) {
        $u++;
        $repinfo[$u] = $row_ko['replid'];
    }
    //======================================================================================
    
    //Nih tuk ambil replid nap terus disimpan didalam array================================
    $query_n = "SELECT DISTINCT idaturan FROM jbsakad.nap WHERE idinfo  = '".$_POST['info']."'";
    $result_n = QueryDb($query_n) or die (mysqli_error($mysqlconnection));
    $num_n = @mysqli_num_rows($result_n);

    $u = 0;
    while($row_n = @mysqli_fetch_array($result_n)) {
        $u++;
        $repinfo2[$u] = $row_n['idaturan'];
    }
	
    //=====================================================================================
    for($k=1;$k<=$num_ko;$k++) {
        $pre = "predikat$k";
		$ns = "nis$k";
		
		$query_kom = "UPDATE jbsakad.komennap SET ".
                     "predikat   = '".$_POST[$pre]."' WHERE nis  = '".$_POST[$ns]."' AND idinfo  = '".$_POST['info']."'";
        $result_kom = QueryDb($query_kom) or die (mysqli_error($mysqlconnection));
        
		//echo "$query_kom<br>";
		//echo $num_n;
        for($b=1;$b<=$_POST['num_t'];$b++) {
             $nang = "nA$k$b";
             $nihu = "nH$k$b";
			 
			 if (strlen(trim((string) $_POST[$ns])) > 0) {
             	$query_nap = "UPDATE jbsakad.nap SET ".
                             "nilaiangka   = '".$_POST[$nang]."' nilaihuruf  = '".$_POST[$nihu]."' WHERE nis  = '".$_POST[$ns]."' AND idaturan = '".$repinfo2[$b]."' AND idinfo  = '".$_POST['info']."'";
				//echo $query_nap . "<br>";						 
    	        $result_nap = QueryDb($query_nap) or die (mysqli_error($mysqlconnection));
			}
			//echo "repinfo2[$b],$query_nap<br>";
        }
    }
    if($result_nap) {
        //echo $query_p;
        ?>
        <script languange="javascript">
			alert ('Pel=<?=$_POST['pelajaran']?>,Tkt=<?=$_POST['tingkat']?>,Kls=<?=$_POST['kelas']?>,Smst=<?=$_POST['semester']?>');
            document.location.href = "tampil_penentuan.php?pelajaran=<?=$_POST['pelajaran']?>&tingkat=<?=$_POST['tingkat']?>&kelas=<?=$_POST['kelas']?>&semester=<?=$_POST['semester']?>";
        </script>
<?php
    }
}
?>