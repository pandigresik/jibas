$(function() {
    adjustContentHeight();
    
	initDialogBox();
	
    showLastPesan();
	showLastAgenda();
	showLastNotes();
	showBirthdayList();
	
	showLastBeritaSekolah();
	showLastBeritaGuru();
	showLastBeritaSiswa();
	
	showLastSurat();
});

// ON WINDOW RESIZED
$(window).resize(function() {
    adjustContentHeight();
});

initDialogBox = function()
{
	$("#pesanDialogBox").dialog({
        autoOpen: false,
        position: 'center',
		height: 420,
		width: 580,
		modal: true,
        buttons: {
            'Tutup': function() {
                $("#pesanDialogBox").dialog('close');
            }
        },
        close: function() {
            
        }
    });
	
	$("#agendaDialogBox").dialog({
        autoOpen: false,
        position: 'center',
		height: 420,
		width: 370,
		modal: true,
        buttons: {
            'Tutup': function() {
                $("#agendaDialogBox").dialog('close');
            }
        },
        close: function() {
            
        }
    });
	
	$("#bdayDialogBox").dialog({
        autoOpen: false,
        position: 'center',
		height: 320,
		width: 370,
		modal: true,
        buttons: {
            'Tutup': function() {
                $("#bdayDialogBox").dialog('close');
            }
        },
        close: function() {
            
        }
    });
	
	$("#notesDialogBox").dialog({
        autoOpen: false,
        position: 'center',
		height: 420,
		width: 580,
		modal: true,
        buttons: {
            'Tutup': function() {
                $("#notesDialogBox").dialog('close');
            }
        },
        close: function() {
            
        }
    });
	
	$("#beritaSekolahDialogBox").dialog({
        autoOpen: false,
        position: 'center',
		height: 520,
		width: 780,
		modal: true,
        buttons: {
            'Tutup': function() {
                $("#beritaSekolahDialogBox").dialog('close');
            }
        },
        close: function() {
            
        }
    });
	
	$("#beritaGuruDialogBox").dialog({
        autoOpen: false,
        position: 'center',
		height: 520,
		width: 780,
		modal: true,
        buttons: {
            'Tutup': function() {
                $("#beritaGuruDialogBox").dialog('close');
            }
        },
        close: function() {
            
        }
    });
	
	$("#beritaSiswaDialogBox").dialog({
        autoOpen: false,
        position: 'center',
		height: 520,
		width: 780,
		modal: true,
        buttons: {
            'Tutup': function() {
                $("#beritaSiswaDialogBox").dialog('close');
            }
        },
        close: function() {
            
        }
    });
}

function adjustContentHeight()
{
    docHeight = window.innerHeight;
    if (document.documentElement.clientHeight)
        docHeight = document.documentElement.clientHeight; //To get the browser height in IE
        
    docHeight = docHeight - 10;
    $('#tabMain').css({height : docHeight});
    //$('#lbInfo').html(docHeight);
    
    var secHeight = docHeight / 4;
    var divSecHeight = secHeight - 20;
    for(i = 1; i <= 4; i++)
    {
        $('#secInfo' + i).css({height : secHeight });
        $('#divSecInfo' + i).css({height : divSecHeight });
    }
}

refreshListPesan = function()
{
	$('#tabListPesan > tbody').empty();
	$('#tabListPesan > tfoot').empty();
	
	$('#minListPesanId').val(0);
	
	showLastPesan();
}

showLastPesan = function() {
    
	var minListPesanId = $('#minListPesanId').val();
	
    $.ajax({
        type: "POST",
        url: "home.ajax.php",
        data: "op=getlastpesan&minListPesanId="+minListPesanId,
        success: function(data) {
			var obj = $.parseJSON(data);
			
			if (obj.minLastPesanId == -1)
			{
				// Tidak ada data pesan
				$('#tabListPesan > tbody').empty().append(obj.html);
				$('#tabListPesan > tfoot').empty();
			}
			else
			{
				$('#minListPesanId').val(obj.minLastPesanId);
				$('#tabListPesan > tbody:last').append(obj.html);
				
				showLinkNextListPesan(obj.minLastPesanId);	
			}
        },
        error: function(xhr, ajaxOptions, thrownError) {
            alert(xhr.responseText);
        }
	})
}

showLinkNextListPesan = function(minListPesanId)
{
	$.ajax({
        type: "POST",
        url: "home.ajax.php",
        data: "op=getlinknextlistpesan&minListPesanId="+minListPesanId,
        success: function(html) {
            $('#tabListPesan > tfoot').empty().append(html);
        },
        error: function(xhr, ajaxOptions, thrownError) {
            alert(xhr.responseText);
        }
	})
}

showLastAgenda = function() {
    
	var maxListAgendaTs = $('#maxListAgendaTs').val();
	
    $.ajax({
        type: "POST",
        url: "home.ajax.php",
        data: "op=getlastagenda&maxListAgendaTs="+maxListAgendaTs,
        success: function(data) {
            var obj = $.parseJSON(data);
			
			if (obj.maxListAgendaTs == "NA")
			{
				// Tidak ada data agenda
				$('#tabListAgenda > tbody').empty().append(obj.html);
				$('#tabListAgenda > tfoot').empty();
			}
			else
			{
				$('#maxListAgendaTs').val(obj.maxListAgendaTs);
				$('#tabListAgenda > tbody:last').append(obj.html);
				
				showLinkNextListAgenda(obj.maxListAgendaTs);	
			}
        },
        error: function(xhr, ajaxOptions, thrownError) {
            alert(xhr.responseText);
        }
	})
}

showLinkNextListAgenda = function(maxListAgendaTs)
{
	$.ajax({
        type: "POST",
        url: "home.ajax.php",
        data: "op=getlinknextlistagenda&maxListAgendaTs="+maxListAgendaTs,
        success: function(html) {
            $('#tabListAgenda > tfoot').empty().append(html);
        },
        error: function(xhr, ajaxOptions, thrownError) {
            alert(xhr.responseText);
        }
	})
}

refreshListAgenda = function()
{
	$('#tabListAgenda > tbody').empty();
	$('#tabListAgenda > tfoot').empty();
	
	$('#maxListAgendaTs').val(0);
	
	showLastAgenda();
}

showLastNotes = function() {
    
	var departemen = $("#departemen").val();
	var minListNotesId = $('#minListNotesId').val();
	
    $.ajax({
        type: "POST",
        url: "home.ajax.php",
        data: "op=getlastnotes&minListNotesId="+minListNotesId+"&departemen="+departemen,
        success: function(data) {
			var obj = $.parseJSON(data);
			
			if (obj.minListNotesId == -1)
			{
				// Tidak ada data pesan
				$('#tabListNotes > tbody').empty().append(obj.html);
				$('#tabListNotes > tfoot').empty();
			}
			else
			{
				$('#minListNotesId').val(obj.minListNotesId);
				$('#tabListNotes > tbody:last').append(obj.html);
				
				showLinkNextListNotes(obj.minListNotesId);	
			}
        },
        error: function(xhr, ajaxOptions, thrownError) {
            alert(xhr.responseText);
        }
	})
}

showLinkNextListNotes = function(minListNotesId)
{
	var departemen = "ALL";
	
	$.ajax({
        type: "POST",
        url: "home.ajax.php",
        data: "op=getlinknextlistnotes&minListNotesId="+minListNotesId+"&departemen="+departemen,
        success: function(html) {
            $('#tabListNotes > tfoot').empty().append(html);
        },
        error: function(xhr, ajaxOptions, thrownError) {
            alert(xhr.responseText);
        }
	})
}

changeDepartemen = function()
{
	$('#tabListNotes > tbody').empty();
	$('#tabListNotes > tfoot').empty();
	
	$('#minListNotesId').val(0);
	
	showLastNotes();
}

bacaPesan = function(replid)
{
	$.ajax({
		type: "POST",
		url: "home.pesan.dialog.php",
		data: "replid="+replid,
		success: function(html) {
			$("#pesanDialogBox").html(html);
			$("#pesanDialogBox").dialog('option', 'title', 'Pesan');
			$("#pesanDialogBox").dialog('open');
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(xhr.responseText);
        }
	})
}

bacaAgenda = function(replid)
{
	$.ajax({
		type: "POST",
		url: "home.agenda.dialog.php",
		data: "replid="+replid,
		success: function(html) {
			$("#agendaDialogBox").html(html);
			$("#agendaDialogBox").dialog('option', 'title', 'Agenda');
			$("#agendaDialogBox").dialog('open');
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(xhr.responseText);
        }
	})
}

bacaNotes = function(replid)
{
	$.ajax({
		type: "POST",
		url: "home.notes.dialog.php",
		data: "replid="+replid,
		success: function(html) {
			$("#notesDialogBox").html(html);
			$("#notesDialogBox").dialog('option', 'title', 'Catatan Siswa');
			$("#notesDialogBox").dialog('open');
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(xhr.responseText);
        }
	})
}

showBirthdayList = function()
{
	var tahun = $('#tahun').val();
	var bulan = $('#bulan').val();
	var tanggal = $('#tanggal').val();
	
	$.ajax({
        type: "POST",
        url: "home.ajax.php",
        data: "op=showbirthdaylist&tahun="+tahun+"&bulan="+bulan+"&tanggal="+tanggal,
        success: function(html) {
            $('#tabListBirthday > tbody').empty().append(html);
        },
        error: function(xhr, ajaxOptions, thrownError) {
            alert(xhr.responseText);
        }
	})
}

changeCbTahun = function()
{
	var tahun = $('#tahun').val();
	var bulan = $('#bulan').val();
	
	$.ajax({
        type: "POST",
        url: "home.ajax.php",
        data: "op=changecbtanggal&tahun="+tahun+"&bulan="+bulan,
        success: function(html) {
            $('#divCbTanggal').html(html);
			
			showBirthdayList();
        },
        error: function(xhr, ajaxOptions, thrownError) {
            alert(xhr.responseText);
        }
	})
}

changeCbBulan = function()
{
	changeCbTahun();
}

changeCbTanggal = function()
{
	showBirthdayList();
}

testOpen = function(replid)
{
	$.ajax({
		type: "POST",
		url: "home.pesan.dialog.php",
		data: "replid="+replid,
		success: function(html) {
			$("#dialogBox").html(html);
			$("#dialogBox").dialog('option', 'title', 'Pesan');
			$("#dialogBox").dialog('open');
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(xhr.responseText);
        }
	})
}

showLastBeritaSekolah = function() {
    
	var maxListBSekolahTs = $('#maxListBSekolahTs').val();
	
    $.ajax({
        type: "POST",
        url: "home.ajax.php",
        data: "op=getlastberitasekolah&maxListBSekolahTs="+maxListBSekolahTs,
        success: function(data) {
            var obj = $.parseJSON(data);
			
			//$('#lbInfo').text(data);
			//$('#lbInfo2').text(obj.maxListBSekolahTs);
			//$('#lbInfo3').text(obj.html);
			
			if (obj.maxListBSekolahTs == "NA")
			{
				// Tidak ada data berita sekolah
				$('#tabListBSekolah > tbody').empty().append(obj.html);
				$('#tabListBSekolah > tfoot').empty();
			}
			else
			{
				$('#maxListBSekolahTs').val(obj.maxListBSekolahTs);
				$('#tabListBSekolah > tbody:last').append(obj.html);
			
				showLinkNextListBeritaSekolah(obj.maxListBSekolahTs);	
			}
        },
        error: function(xhr, ajaxOptions, thrownError) {
            alert(xhr.responseText);
        }
	})
}

showLinkNextListBeritaSekolah = function(maxListBSekolahTs)
{
	$.ajax({
        type: "POST",
        url: "home.ajax.php",
        data: "op=getlinknextlistberitasekolah&maxListBSekolahTs="+maxListBSekolahTs,
        success: function(html) {
            $('#tabListBSekolah > tfoot').empty().append(html);
        },
        error: function(xhr, ajaxOptions, thrownError) {
            alert(xhr.responseText);
        }
	})
}

bacaBSekolah = function(replid)
{
	$.ajax({
		type: "POST",
		url: "home.bsekolah.dialog.php",
		data: "replid="+replid,
		success: function(html) {
			$("#beritaSekolahDialogBox").html(html);
			$("#beritaSekolahDialogBox").dialog('option', 'title', 'Berita Sekolah');
			$("#beritaSekolahDialogBox").dialog('open');
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(xhr.responseText);
        }
	})	
}

refreshListBeritaSekolah = function()
{
	$('#tabListBSekolah > tbody').empty();
	$('#tabListBSekolah > tfoot').empty();
	
	$('#maxListBSekolahTs').val(0);
	
	showLastBeritaSekolah();	
}

showLastBeritaGuru = function() {
    
	var maxListBGuruTs = $('#maxListBGuruTs').val();
	
    $.ajax({
        type: "POST",
        url: "home.ajax.php",
        data: "op=getlastberitaguru&maxListBGuruTs="+maxListBGuruTs,
        success: function(data) {
            var obj = $.parseJSON(data);
			
			//$('#lbInfo').text(data);
			//$('#lbInfo2').text(obj.maxListBSekolahTs);
			//$('#lbInfo3').text(obj.html);
			
			if (obj.maxListBGuruTs == "NA")
			{
				// Tidak ada data berita guru
				$('#tabListBGuru > tbody').empty().append(obj.html);
				$('#tabListBGuru > tfoot').empty();
			}
			else
			{
				$('#maxListBGuruTs').val(obj.maxListBGuruTs);
				$('#tabListBGuru > tbody:last').append(obj.html);
			
				showLinkNextListBeritaGuru(obj.maxListBGuruTs);	
			}
        },
        error: function(xhr, ajaxOptions, thrownError) {
            alert(xhr.responseText);
        }
	})
}

showLinkNextListBeritaGuru = function(maxListBGuruTs)
{
	$.ajax({
        type: "POST",
        url: "home.ajax.php",
        data: "op=getlinknextlistberitaguru&maxListBGuruTs="+maxListBGuruTs,
        success: function(html) {
            $('#tabListBGuru > tfoot').empty().append(html);
        },
        error: function(xhr, ajaxOptions, thrownError) {
            alert(xhr.responseText);
        }
	})
}

refreshListBeritaGuru = function()
{
	$('#tabListBGuru > tbody').empty();
	$('#tabListBGuru > tfoot').empty();
	
	$('#maxListBGuruTs').val(0);
	
	showLastBeritaGuru();	
}

bacaBGuru = function(replid)
{
	$.ajax({
		type: "POST",
		url: "home.bguru.dialog.php",
		data: "replid="+replid,
		success: function(html) {
			$("#beritaGuruDialogBox").html(html);
			$("#beritaGuruDialogBox").dialog('option', 'title', 'Berita Guru');
			$("#beritaGuruDialogBox").dialog('open');
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(xhr.responseText);
        }
	})	
}

showLastBeritaSiswa = function() {
    
	var maxListBSiswaTs = $('#maxListBSiswaTs').val();
	
    $.ajax({
        type: "POST",
        url: "home.ajax.php",
        data: "op=getlastberitasiswa&maxListBSiswaTs="+maxListBSiswaTs,
        success: function(data) {
            var obj = $.parseJSON(data);
			
			//$('#lbInfo').text(data);
			//$('#lbInfo2').text(obj.maxListBSekolahTs);
			//$('#lbInfo3').text(obj.html);
			
			if (obj.maxListBSiswaTs == "NA")
			{
				// Tidak ada data berita siswa
				$('#tabListBSiswa > tbody').empty().append(obj.html);
				$('#tabListBSiswa > tfoot').empty();
			}
			else
			{
				$('#maxListBSiswaTs').val(obj.maxListBSiswaTs);
				$('#tabListBSiswa > tbody:last').append(obj.html);
			
				showLinkNextListBeritaSiswa(obj.maxListBSiswaTs);	
			}
        },
        error: function(xhr, ajaxOptions, thrownError) {
            alert(xhr.responseText);
        }
	})
}

showLinkNextListBeritaSiswa = function(maxListBSiswaTs)
{
	$.ajax({
        type: "POST",
        url: "home.ajax.php",
        data: "op=getlinknextlistberitasiswa&maxListBSiswaTs="+maxListBSiswaTs,
        success: function(html) {
            $('#tabListBSiswa > tfoot').empty().append(html);
        },
        error: function(xhr, ajaxOptions, thrownError) {
            alert(xhr.responseText);
        }
	})
}

bacaBSiswa = function(replid)
{
	$.ajax({
		type: "POST",
		url: "home.bsiswa.dialog.php",
		data: "replid="+replid,
		success: function(html) {
			$("#beritaSiswaDialogBox").html(html);
			$("#beritaSiswaDialogBox").dialog('option', 'title', 'Berita Siswa');
			$("#beritaSiswaDialogBox").dialog('open');
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(xhr.responseText);
        }
	})	
}

refreshListBeritaSiswa = function()
{
	$('#tabListBSiswa > tbody').empty();
	$('#tabListBSiswa > tfoot').empty();
	
	$('#maxListBSiswaTs').val(0);
	
	showLastBeritaSiswa();	
}

showLastSurat = function() {
    
	var jenis = $('#jenissurat').val();
	var minListSuratTs = $('#minListSuratTs').val();
	
    $.ajax({
        type: "POST",
        url: "home.ajax.php",
        data: "op=getlastsurat&jenis="+jenis+"&minListSuratTs="+minListSuratTs,
        success: function(data) {
			
			var obj = $.parseJSON(data);
			
			//$('#lbInfo').text(data);
			//$('#lbInfo2').text(obj.maxListBSekolahTs);
			//$('#lbInfo3').text(obj.html);
			
			if (obj.minListSuratTs == "NA")
			{
				// Tidak ada data berita siswa
				$('#tabListSurat > tbody').empty().append(obj.html);
				$('#tabListSurat > tfoot').empty();
			}
			else
			{
				$('#minListSuratTs').val(obj.minListSuratTs);
				$('#tabListSurat > tbody:last').append(obj.html);
			
				showLinkNextListSurat(jenis, obj.minListSuratTs);	
			}
			
			//$('#tabListSurat > tbody').empty().append(data);
			
        },
        error: function(xhr, ajaxOptions, thrownError) {
            alert(xhr.responseText);
        }
	})
}

showLinkNextListSurat = function(jenis, minListSuratTs)
{
	$.ajax({
        type: "POST",
        url: "home.ajax.php",
        data: "op=getlinknextlistsurat&jenis="+jenis+"&minListSuratTs="+minListSuratTs,
        success: function(html) {
            $('#tabListSurat > tfoot').empty().append(html);
        },
        error: function(xhr, ajaxOptions, thrownError) {
            alert(xhr.responseText);
        }
	})
}

viewLetter = function(idsurat)
{
	var addr = "buletin/surat/letter.view.php?idsurat="+idsurat;
	var name = "surat"+idsurat;
	
	newWindow(addr, name, 1200, 620, 'resizable=0,scrollbars=0,status=0,toolbar=0');
}

changeJenisSurat = function()
{
	refreshSurat();
}

refreshSurat = function()
{
	$('#tabListSurat > tbody').empty();
	$('#tabListSurat > tfoot').empty();
	
	$('#minListSuratTs').val(0);
	
	showLastSurat();	
}

showBdayInfo = function(id, jenis)
{
	$.ajax({
		type: "POST",
		url: "home.bday.dialog.php",
		data: "id="+id+"&jenis="+jenis,
		success: function(html) {
			$("#bdayDialogBox").html(html);
			$("#bdayDialogBox").dialog('option', 'title', 'Ulang Tahun');
			$("#bdayDialogBox").dialog('open');
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(xhr.responseText);
        }
	})	
}