<?php
function ShowControl()
{
    global $idsurat;
    
    $sql = "SELECT COUNT(replid)
              FROM jbsletter.berkassurat
             WHERE idsurat = $idsurat";
    $res = QueryDb($sql);
    $row = mysqli_fetch_row($res);
    $ndata = $row[0];
    
    echo "<input onclick='movePrev()' class='inputbox' type='button' value=' < '>";
    echo "&nbsp;berkas ";
    echo "<select class='inputbox' id='cbNomor' onchange='changeBerkas()'>";
    for($i = 1; $i <= $ndata; $i++)
    {
        echo "<option value='$i'>$i</option>";
    }
    echo "</select>";
    echo " dari $ndata ";
    echo "<input onclick='moveNext()' class='inputbox' type='button' value=' > '>";
    echo "<input type='hidden' id='ndata' value='$ndata'>";
}

function ShowImage()
{
    global $idsurat;
    
    $sql = "SELECT berkas, deskripsi
              FROM jbsletter.berkassurat
             WHERE idsurat = $idsurat";
    $res = QueryDb($sql);
    $n = 1;
    while($row = mysqli_fetch_row($res))
    {
        $deskripsi = str_replace("'", '"', (string) $row[1]);
        
        echo "<img id='img$n' style='position: absolute; top: 0px; left: 0px; visibility: hidden;' src='data:image/jpeg;base64," . base64_encode((string) $row[0]) . "'>\r\n";
        echo "<input type='hidden' id='info$n' value='$deskripsi'>\r\n";
        $n += 1;
    }
}
?>