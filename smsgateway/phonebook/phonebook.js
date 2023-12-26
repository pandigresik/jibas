function ResizeTabHeight() {
  var WinHeight = 0;
  if( typeof( window.innerWidth ) == 'number' ) {
    WinHeight = window.innerHeight;
  } else if( document.documentElement &&
      ( document.documentElement.clientWidth || document.documentElement.clientHeight ) ) {
    WinHeight = document.documentElement.clientHeight;
  } else if( document.body && ( document.body.clientWidth || document.body.clientHeight ) ) {
    WinHeight = document.body.clientHeight;
  }
  //alert(WinHeight+'px');DIVReceiptTable
  document.getElementById('MainTable').style.height = (parseInt(WinHeight)-50)+"px";
  //document.getElementById('DIVReceiptTable').style.height = (parseInt(WinHeight)-385)+"px";
}
var addr = "cmd=view&jenis=-1&alpha=-1";
$("#btnview").live('click',function(e){	
	e.preventDefault;
	var jenis = $("#jenis").val();
	var alpha = $("#alpha").val();
	addr = 'cmd=view&jenis='+jenis+'&alpha='+alpha;
	showData();
	/*
	$.ajax({
		url  : 'phonebook.class.php',
		data : addr,
		beforeSend: function(){
			$("#phonebookList").html("Please wait...");
		},
		success : function(out){
			$("#phonebookList").html(out);
		}
	})
	*/
})
$("#btnsearch").live('click',function(e){	
	e.preventDefault;
	var field   = $("#field").val();
	var keyword = $("#keyword").val();
	addr = 'cmd=search&field='+field+'&keyword='+keyword;
	showData();
})

function showData(){
	$.ajax({
		url  : 'phonebook.class.php',
		data : addr,
		beforeSend: function(){
			$("#phonebookList").html("Please wait...");
		},
		success : function(out){
			$("#phonebookList").html(out);
		}
	})
}

$(".btnEdit").live('click',function(e){
	e.preventDefault;
	var id = $(this).attr('id');
	newWindow("phonebook.class.php?cmd=edit&id="+id,"PHEdit",363,240,"");
})
$(".btnDel").live('click',function(e){
	e.preventDefault;
	var id = $(this).attr('id');
	if (confirm('Anda yakin akan menghapus data ini?')){
		$.ajax({
			url  : 'phonebook.class.php',
			data : 'cmd=delete&id='+id,
			success : function(out){
				showData();
			}
		})
	}
})

$("#btnAdd").live('click',function(e){
	e.preventDefault;
	newWindow("phonebook.class.php?cmd=add","PHAdd",363,240,"");
})

$("#btnSave").live('click',function(e){
	e.preventDefault;
	var nama	= addslashes($("#nama").val());
	var hp		= $("#nohp").val();
	var jenis	= $("#jenis").val();
	var ket		= addslashes($("#ket").val());
	
	if (hp==''){
		alert('Nomor HP harus diisi!');
		$("#nohp").focus();
		return false;
	}
	
	if (nama==''){
		alert('Nama harus diisi!');
		$("#nama").focus();
		return false;
	}
	
	location.href = "phonebook.class.php?cmd=add&op=save&nama="+nama+"&hp="+hp+"&jenis="+jenis+"&ket="+ket;
	//newWindow("phonebook.class.php?cmd=add","PHAdd",363,234,"");
})

$("#btnUpdate").live('click',function(e){
	e.preventDefault;
	var id		= $("#id").val();
	var nama	= addslashes($("#nama").val());
	var hp		= $("#nohp").val();
	var jenis	= $("#jenis").val();
	var ket		= addslashes($("#ket").val());
	
	if (hp==''){
		alert('Nomor HP harus diisi!');
		$("#nohp").focus();
		return false;
	}
	
	if (nama==''){
		alert('Nama harus diisi!');
		$("#nama").focus();
		return false;
	}
	
	location.href = "phonebook.class.php?cmd=edit&op=update&nama="+nama+"&hp="+hp+"&jenis="+jenis+"&ket="+ket+"&id="+id;
	//newWindow("phonebook.class.php?cmd=add","PHAdd",363,234,"");
})

function afterSave(nohp){
	$("#field").val('nohp');
	$("#keyword").val(nohp);
	var field   = $("#field").val();
	var keyword = $("#keyword").val();
	addr = 'cmd=search&field='+field+'&keyword='+keyword;
	showData();
}

$("#btnImport").live('click',function(e){
	e.preventDefault;
	if (confirm('Anda yakin akan melakukan Import Data Siswa dan Pegawai?')){
		$.ajax({
			url  : 'phonebook.class.php',
			data : 'cmd=import',
			success : function(out)
			{
				$.ajax({
					url  : 'phonebook.class.php',
					data : 'cmd=view&jenis=-1&alpha=-1',
					beforeSend: function(){
						$("#phonebookList").html("Please wait...");
					},
					success : function(out){
						$("#phonebookList").html(out);
					}
				})
			}
		})
	}
})

$(".paginationjs").live('click',function(e){
	e.preventDefault;
	var addr = $(this).attr('href');
	//alert(typeof(addr));
	if (typeof(addr)!='undefined'){
		$.ajax({
			url  : 'phonebook.class.php',
			data : addr,
			beforeSend: function(){
				$("#phonebookList").html("Please wait...");
			},
			success : function(out){
				$("#phonebookList").html(out);
			}
		})
	}
	return false;
})
