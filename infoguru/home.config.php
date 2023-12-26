<?php
$HOME_NLIST_PESAN = 4;
$HOME_NLIST_AGENDA = 4;
$HOME_NLIST_NOTES = 4;
$HOME_NLIST_BSEKOLAH = 4;
$HOME_NLIST_BGURU = 4;
$HOME_NLIST_BSISWA = 4;
$HOME_NLIST_SURAT = 3;

function ApplyHomeConfigJs()
{
    global $HOME_NLIST_PESAN, $HOME_NLIST_AGENDA, $HOME_NLIST_NOTES;
    global $HOME_NLIST_BSEKOLAH, $HOME_NLIST_BGURU, $HOME_NLIST_BSISWA;
    global $HOME_NLIST_SURAT;
    
    $s  = "var HOME_NLIST_PESAN = " . $HOME_NLIST_PESAN . ";\r\n";
    $s .= "var HOME_NLIST_AGENDA = " . $HOME_NLIST_AGENDA . ";\r\n";
    $s .= "var HOME_NLIST_NOTES = " . $HOME_NLIST_NOTES . ";\r\n";
    $s .= "var HOME_NLIST_BSEKOLAH = " . $HOME_NLIST_BSEKOLAH . ";\r\n";
    $s .= "var HOME_NLIST_BGURU = " . $HOME_NLIST_BGURU . ";\r\n";
    $s .= "var HOME_NLIST_BSISWA = " . $HOME_NLIST_BSISWA . ";\r\n";
    $s .= "var HOME_NLIST_SURAT = " . $HOME_NLIST_SURAT . ";\r\n";
    
    
    $f = fopen("home.config.js", "w");
    fwrite($f, $s);
    fclose($f);
}

//ApplyHomeConfigJs();
?>