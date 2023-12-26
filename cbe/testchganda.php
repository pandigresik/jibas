<link rel="stylesheet" href="ujian.css">
<link href="script/jquery-ui/jquery-ui.min.css" type="text/css" media="screen" rel="stylesheet" />
<link href="style/mainstyle.css" rel="stylesheet" />
<link href="main.css" rel="stylesheet" />
<link href="ujian.css" rel="stylesheet" />
<script type="text/javascript" src="script/jquery-1.11.3.min.js"></script>
<script type="text/javascript" src="script/jquery.cookie.js"></script>
<script type="text/javascript" src="script/jquery-ui/jquery-ui.min.js"></script>
<script>
    saveJawaban = function(rbGroup)
    {
        var lsJawaban = [];
        var group = "input[name=" + rbGroup + "]";
        $(group).each(function ()
        {
            if (this.checked)
                lsJawaban.push($(this).val());

            //var val = this.checked ? $(this).val() : "";
            // console.log(lsJawaban);
        });

        console.log(JSON.stringify(lsJawaban));
    }
</script>
<div class='divJawaban' id='divJawabanMultiGanda'>

    <table border="0">
    <tr>
        <td width="80" style="line-height: 30px" valign="top">
            <span style='font-size: 20px'><input type='checkbox' class='checkBox' name='chGanda' id='chA' value='A' checked>&nbsp;A</span><br>
            <span style='font-size: 20px'><input type='checkbox' class='checkBox' name='chGanda' id='chB' value='B' checked>&nbsp;B</span><br>
            <span style='font-size: 20px'><input type='checkbox' class='checkBox' name='chGanda' id='chC' value='C' checked>&nbsp;C</span><br>
            <span style='font-size: 20px'><input type='checkbox' class='checkBox' name='chGanda' id='chD' value='D' checked>&nbsp;D</span><br>
            <span style='font-size: 20px'><input type='checkbox' class='checkBox' name='chGanda' id='chE' value='E' checked>&nbsp;E</span><br>
        </td>
        <td width="80" style="line-height: 30px" valign="top">
            <span style='font-size: 20px'><input type='checkbox' class='checkBox' name='chGanda' id='chF' value='F' checked>&nbsp;F</span><br>
            <span style='font-size: 20px'><input type='checkbox' class='checkBox' name='chGanda' id='chG' value='G' checked>&nbsp;G</span><br>

        </td>
    </tr>
    </table>

    <input type="button" onclick="saveJawaban('chGanda')" value="Save">

</div>



