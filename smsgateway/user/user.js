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

$("#btnAdd").live('click',function(e){
	e.preventDefault;
	var addr="userlist.php?cmd=add";
	newWindow(addr,'TambahPengguna','554','267','resizeable=0,scrollbars=0,status=0,toolbar=0');
})

$("#caripegawai,#nip,#nama").live('click',function(e){
	e.preventDefault;
	var addr="../library/pegawai.php";
	newWindow(addr,'PilihPegawai','469','400','resizeable=0,scrollbars=0,status=0,toolbar=0');
})

selectPegawai = function(nip,nama,hp){
	$(".nip").val(nip);
	$(".nip").html(nip);
	$(".nama").val(nama);
	$(".nama").html(nama);
	if (hp=='true'){
		$('.passfield').hide();
		$('.hasspassword').show();
		$("#hp").val('1');
	} else {
		$('.passfield').show();
		$('.hasspassword').hide();
		$("#hp").val('0');
	}
}

saveUser = function(){
	var nip = $("#nip").val();
	var nama= $("#nama").val();
	var hp  = $("#hp").val();
	var addr2 = "";
	if (nip==""){
		alert("Pegawai harus dipilih !");
		$("#nip").focus();
		return false;
	}
	if (hp=='0'){
		var password1= $("#password1").val();
		var password2= $("#password2").val();
		if (password1==""){
			alert("Password harus diisi !");
			$("#password1").focus();
			return false;
		}
		if (password2==""){
			alert("Password (ulangi) harus diisi !");
			$("#password2").focus();
			return false;
		}
		if (password1!=password2){
			alert("Kedua password harus sama!");
			$("#password2").focus();
			return false;
		}
	}
}

$(".btnDel").live('click',function(e){
	e.preventDefault;
	var id = $(this).attr('id');
	if (confirm('Anda yakin akan menghapus data pengguna ini?'))
		location.href = "userlist.php?cmd=del&id="+id;
})

$(".btnEdit").live('click',function(e){
	e.preventDefault;
	var id = $(this).attr('id');
	var addr="userlist.php?cmd=edit&id="+id;
	newWindow(addr,'UbahPengguna','460','224','resizeable=0,scrollbars=0,status=0,toolbar=0');
})