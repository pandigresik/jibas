<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="../style/style.css" />
<link rel="stylesheet" type="text/css" href="../script/ui/jquery.ui.all.css" />
<script language="javascript" src="../script/jquery-1.4.2.js"></script>
<script language="javascript" src="../script/jquery-ui.js"></script>
<script language="javascript" src="../script/tools.js"></script>
<script>
$("#bag").live('change',function(e){
	e.preventDefault;
	var bagian = $(this).val();
	$.ajax({
		url : 'pegawai.class.php',
		data: 'op=pilih&bagian='+bagian,
		success: function(out){
			$("#pilih").html(out);
		}	
	})	
})
$("#btnCari").live('click',function(e){
	e.preventDefault;
	var nip	 = $('#nip').val();
	var nama = $('#nama').val();
	$.ajax({
		url : 'pegawai.class.php',
		data: 'op=cari&nip='+nip+'&nama='+nama,
		success: function(out){
			$("#cari").html(out);
		}	
	})	
})

$(".btnSelectPeg").live('click',function(e){
	var nip = $(this).attr('nip');
	var nama = $(this).attr('nama');
	var hp = $(this).attr('hp');
	opener.selectPegawai(nip,nama,hp);
	window.close();
})
</script>
</head>
<body>
	<div id="tabs">
		<ul>
			<li><a href="#pilih">Pilih Pegawai</a></li>
			<li><a href="#cari">Cari Pegawai</a></li>
		</ul>
		<div id="pilih">
			Loading...
		</div>
		<div id="cari">
			Loading...
		</div>
	</div>
</body>
</html>
<script>
$(function() {
	$( "#tabs" ).tabs();
	$.ajax({
		url : 'pegawai.class.php',
		data: 'op=pilih',
		success: function(out){
			$("#pilih").html(out);
		}	
	})
	$.ajax({
		url : 'pegawai.class.php',
		data: 'op=cari',
		success: function(out){
			$("#cari").html(out);
		}	
	})
});
</script>