$(function() {
    disablePanelKelas(false);
    disablePanelNis(true);
});

function changeType(tipe)
{
    if (tipe == 1)
    {
        disablePanelKelas(false);
        disablePanelNis(true);
    }
    else
    {
        disablePanelKelas(true);
        disablePanelNis(false);    
    }
}

function disablePanelKelas(disabled)
{
    var bgcolor = disabled ? "#DDD" : "#FFF";
    
    $("#divPanelKelas span").each(function() {
        $(this).children().prop("disabled", disabled);
        $(this).children().css("background", bgcolor);
    });
}

function disablePanelNis(disabled)
{
    var bgcolor = disabled ? "#DDD" : "#FFF";
    
    $("#divPanelNis").children().prop("disabled", disabled);
    $("#divPanelNis").children().css("background", bgcolor);
}

function changeCbInfo(chname, basecbname)
{
    var disabled = $('#' + chname).is(':checked') ? false : true;
    var bgcolor = disabled ? "#DDD" : "#FFF";
    
    setCbProperties(basecbname + 'Yr30', disabled, bgcolor);
    setCbProperties(basecbname + 'Mn30', disabled, bgcolor);
    setCbProperties(basecbname + 'Dt30', disabled, bgcolor);
    
    setCbProperties(basecbname + 'Yr', disabled, bgcolor);
    setCbProperties(basecbname + 'Mn', disabled, bgcolor);
    setCbProperties(basecbname + 'Dt', disabled, bgcolor);
}

function setCbProperties(cbname, disabled, bgcolor)
{
    $('#' + cbname).prop('disabled', disabled);
    $('#' + cbname).css('background', bgcolor);
}

function changeLampiran()
{
    var disabled = $('#chLampiran').is(':checked') ? false : true;
    var bgcolor = disabled ? "#DDD" : "#FFF";
    
    setCbProperties('lampiran', disabled, bgcolor);
    $('#divLampiran').css('background', bgcolor);
}

function changeCbDepartemen()
{
    var departemen = $("#departemen").val();
    
    $("#divCbTingkat").html("..");
    
    $.ajax({
        url: "penyusunan.ajax.php",
        data: "op=getcbtingkat&departemen="+departemen,
        success: function(data) {
			$("#divCbTingkat").html(data);
            changeCbTingkat();
            
            getCbPengantar(departemen);
            getCbLampiran(departemen);
		},
		error: function(xhr, options, error) {
			alert(xhr.responseText);
		}
    });
}

function changeCbTingkat()
{
    var idtingkat = $("#tingkat").val();
    
    $("#divCbKelas").html("..");
    
    $.ajax({
        url: "penyusunan.ajax.php",
        data: "op=getcbkelas&idtingkat="+idtingkat,
        success: function(data) {
			$("#divCbKelas").html(data);
		},
		error: function(xhr, options, error) {
			alert(xhr.responseText);
		}
    });
}

function getCbPengantar(departemen)
{
    $('#divCbPengantar').html("..");
    
    $.ajax({
        url: "penyusunan.ajax.php",
        data: "op=getcbpengantar&departemen="+departemen,
        success: function(data) {
			$("#divCbPengantar").html(data);
            changeCbPengantar();
		},
		error: function(xhr, options, error) {
			alert(xhr.responseText);
		}
    });
}

function changeCbPengantar()
{
    var idpengantar = $('#pengantar').val();
    
    $("#divPengantar").html("");
    
    $.ajax({
        url: "penyusunan.ajax.php",
        data: "op=getpengantar&idpengantar="+idpengantar,
        success: function(data) {
			$("#divPengantar").html(data);
		},
		error: function(xhr, options, error) {
			alert(xhr.responseText);
		}
    });
}

function getCbLampiran(departemen)
{
    $('#divCbLampiran').html("..");
    
    $.ajax({
        url: "penyusunan.ajax.php",
        data: "op=getcblampiran&departemen="+departemen,
        success: function(data) {
			$("#divCbLampiran").html(data);
            changeCbLampiran();
		},
		error: function(xhr, options, error) {
			alert(xhr.responseText);
		}
    });
}

function changeCbLampiran()
{
    var idlampiran = $('#lampiran').val();
    
    $("#divLampiran").html("");
    
    $.ajax({
        url: "penyusunan.ajax.php",
        data: "op=getlampiran&idlampiran="+idlampiran,
        success: function(data) {
			$("#divLampiran").html(data);
		},
		error: function(xhr, options, error) {
			alert(xhr.responseText);
		}
    });
}

function cbDateChanged(cbYear, cbMonth, cbDate, divDate)
{
    var year = $('#' + cbYear).val();
    var month = $('#' + cbMonth).val();
    
    $("#" + divDate).html("..");
    
    $.ajax({
        url: "penyusunan.ajax.php",
        data: "op=getcbdate&year="+year+"&month="+month+"&cbdate="+cbDate,
        success: function(data) {
			$("#" + divDate).html(data);
		},
		error: function(xhr, options, error) {
			alert(xhr.responseText);
		}
    })
}

function validate()
{
    var tipe = $('input[name=tipe]').filter(':checked').val();
    if (2 == tipe)
    {
        var nisinfo = $.trim($('#nisinfo').val());
        if (nisinfo.length == 0)
        {
            alert('Anda harus mengisikan daftar NIS');
            $('#nisinfo').focus();
            
            return false;
        }
    }
    else
    {
        var isok = checkCbOption('departemen', 'departemen') &&
                   checkCbOption('tingkat', 'tingkat') &&
                   checkCbOption('kelas', 'kelas');
        
        if (!isok)
            return false;
    }
    
    if (!checkCbOption('pengantar', 'pengantar surat'))
        return false;
    
    if ($('#chLampiran').is(':checked') && !checkCbOption('lampiran', 'lampiran surat'))
        return false;
    
    return confirm('Data sudah benar?');
}

function checkCbOption(cbname, name)
{
    if (0 == $('#' + cbname + ' > option').length)
    {
        alert('Belum ada data ' + name + '!');
        $('#' + cbname).focus();
        
        return false;
    }
    
    return true;
}