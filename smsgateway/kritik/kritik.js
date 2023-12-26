$(function(){
	//alert('Asu');
	setInterval(function(){
		getNewMessage();
	},5000)
})
var dt;
var list;
var header;
var showheader = false;
getNewMessage = function(){
	$.ajax({
		url : 'kritik.class.php',
		data: 'cmd=showNewMsg',
		dataType:'json',
		success:function(out){
			//$("#test").html(out);
			//alert(out);
			dt = out;
			if (dt.num>0){
				$.ajax({
					url : 'kritik.class.php',
					data: 'cmd=getRowTemplate',
					success:function(out){
						list = out;
						if ($('#tableInbox').length==0){
							showheader = true;
							$.ajax({
								url : 'kritik.class.php',
								data: 'cmd=getRowHeader',
								success:function(out){
									header = out;
									showNewList();
								}
							})
						} else {
							showheader = false;
							showNewList();
						}
					}
				})
			}
		}
	})
}

showNewList = function(){
	if (dt.num>0){
		if (showheader){
			$("#nodata").hide();
			$("#tableContainer").hide();
			$("#tableContainer").append("<table cellspacing='0' cellpadding='0' border='1' width='100%' class='tab' id='tableInbox'>"+header+"</table>");
		}
		var content = "";
		var tmp = list;
		for(x=0;x<dt.num ;x++ ){
			tmp = list;
			tmp = tmp.replace(/<tr/g,"<tr class='hilite bold'");
			tmp = tmp.replace(/_SENDER_/g,dt.data[x][0]);
			tmp = tmp.replace(/_DATE_/g,dt.data[x][1]);
			tmp = tmp.replace(/_MSG_/g,dt.data[x][2]);
			tmp = tmp.replace(/_ID_/g,dt.data[x][3]);
			content += tmp;
		}
		$("#tableInbox tr:first").after(content);
		$("#tableContainer").show();

		setTimeout(function(){
			$("#tableInbox tr").removeClass('hilite');
		},10000)
	}
}

$(".btnDel").live('click',function(e){
	e.preventDefault;
	var id = $(this).attr('id');
	if (confirm('Anda yakin akan menghapus pesan ini dari Kotak Masuk?')){
		$(this).parent('td').parent('tr').remove();
		$.ajax({
			url : 'kritik.class.php',
			data: 'cmd=delete&id='+id
		})	
	}
	if ($("#tableInbox tr").length==1){
		$("#nodata").show();
		$("#tableContainer #tableInbox").remove();
	}
})

$(".btnView").live('click',function(e){
	e.preventDefault;
	var id = $(this).attr('id');
	$(this).parent('td').parent('tr').removeClass('bold');
	var dialog = $('<div style="display:hidden" align="center" id="dialogcontent" ><h3 class="ui-state-highlight">Loading</h3></div>').appendTo('body');
	dialog.dialog({title:'Detail Pesan',modal:true,show:'fade',hide:'fade',width:'300px',position:'top',
		open:function(){
			
			$.ajax({
				url : 'kritik.class.php',
				data: 'cmd=viewMsg&id='+id,
				success:function(out){
					$("#dialogcontent").attr('align','left').html(out);
					
				}
			})
			
		},
		close:function(){
			dialog.dialog('destroy');
			dialog.remove();
		},
		buttons: { 
			/*
			"Balas": 
				function(){ 
					if (confirm('Kirm pesan balasan?')){
						var msg = addslashes($("#replytext").val());
						$.ajax({
							url : 'kritik.class.php',
							data: 'cmd=replyMsg&id='+id+'&text='+msg,
							dataType:'json',
							beforeSend:function(){
								$("#replytext").attr('disabled','disabled');
							},
							success:function(out){
								if (out.status=='1'){
									alert('Pesan balasan sudah dikirim !');
									dialog.dialog('destroy');
									dialog.remove();
								} else {
									alert('Pesan balasan gagal dikirim !');
									$("#replytext").removeAttr('disabled','disabled');
								}
								//$("#dialogcontent").attr('align','left').html(out);
							}
						})
					}
				},
			*/
			"Tutup": 
				function(e){ 
					dialog.dialog('destroy');
					dialog.remove();					
				}
			}
	});
})

$("#replytext").live('keyup',function(e){
	var max = 160;
	var txt = $(this).val();
	//alert(txt.length);
	//alert(e.keyCode);
	var cur = parseInt($("#charLeft").html());
	$("#charLeft").html(max-(txt.length));
})
$(".filter").live('change',function(e){
	e.preventDefault;
	var y = $("#year").val();
	var m = $("#month").val();
	var j = $("#jenis").val();
	$.ajax({
		url : 'kritik.class.php',
		data: 'cmd=getList&y='+y+"&m="+m+"&j="+j,
		success:function(out){
			$("#tableContainer").html(out);
		}
	})
})

$(".paginationjs").live('click',function(e){
	e.preventDefault;
	var addr = $(this).attr('href');
	//alert(typeof(addr));
	if (typeof(addr)!='undefined'){
		$.ajax({
			url  : 'kritik.class.php',
			data : addr,
			beforeSend: function(){
				$("#tableContainer").html("Please wait...");
			},
			success : function(out){
				$("#tableContainer").html(out);
			}
		})
	}
	return false;
})