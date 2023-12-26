//INSERT INTO inbox SET UpdatedInDB=now(),ReceivingDateTime=now(),Text=now(),SenderNumber='+6285624084062',UDH='XXX',RecipientID='XX',Status=0;
//<img onclick="DeleteRow(this,'<?=$row['ID']?>');" src="../images/ico/hapus.png" width="16" height="16" />
function insRow(Content){
	var x=document.getElementById('KritikTable');
	var lastRow = x.rows.length;
	var iteration = lastRow;
	var row = x.insertRow(1);
	//ReadMessage(<?=$row[ID]?>);
	/*<span class='Link'>+6285624084062</span>#>09 November 2010 14:34:59#>Ini loh krtik sayah#>6#>ELLYF*/
	//alert(Content);
	row.style.backgroundColor = "#ffda09";
	row.style.fontWeight = "BOLD";
	y 	= Content.split('#>');
	row.id = y[3];
	row.style.pointer='pointer';
	var a=row.insertCell(0);
	a.style.cursor='pointer';
	a.setAttribute('onclick','ReadMessage('+y[3]+');');
	var b=row.insertCell(1);
	b.align='left';
	b.className = 'td';
	b.style.cursor='pointer';
	b.setAttribute('onclick','ReadMessage('+y[3]+');');
	var c=row.insertCell(2);
	c.className = 'td';
	c.style.cursor='pointer';
	c.setAttribute('onclick','ReadMessage('+y[3]+');');
	
	var d=row.insertCell(3);
	d.className = 'td';
	d.style.cursor='pointer';
	d.setAttribute('onclick','ReadMessage('+y[3]+');');
	var e=row.insertCell(4);
	e.className = 'td';
	e.style.cursor='pointer';
	e.setAttribute('onclick','ReadMessage('+y[3]+');');
	
	var f=row.insertCell(5);
	f.className = 'td';
	f.align		= 'center';
	//e.
	b.innerHTML = y[0];
	c.innerHTML = y[4];
	d.innerHTML = y[1];
	f.innerHTML = "<img style='cursor:pointer' onclick='DeleteRow(this,"+y[3]+");' src='../images/ico/hapus.png' width='16' height='16' />";
	e.innerHTML = y[2];
	//e.innerHTML = y[3];
	
	setTimeout('ClearBGClr('+y[3]+')',5000);
}
function ClearBGClr(x){
	document.getElementById(x).style.backgroundColor = "#ffffff";
	TableRefresh();
	
}
function TableRefresh(){
	var table = document.getElementById('KritikTable');
	var rows = table.getElementsByTagName("tr");  
	var x;
	for(i = 1; i < rows.length; i++){
		x = rows[i].getElementsByTagName("td");
        x[0].align				= 'center';
		x[0].style.fontWeight	= 'normal';
		x[0].innerHTML	= i;
		//x = rows[i].getCell[0];
		//x.innerHTML=i;
		//document.title += i+"_";
		//alert (i);
		if (i%2==0)
			rows[i].style.backgroundColor='#cfddd1';
		else
			rows[i].style.backgroundColor='#FFFFFF';
	}
	Tables('KritikTable', 1, 0);
}
function KritikOnloads(){
	
	//var x=document.getElementById('InboxTable');
	//var lastRow = x.rows[1].style;
	//alert (x);
	//setInterval('KritikOnload()',5000);
}
function KritikOnload(){
	//alert ('Masuk');
	//var x=document.getElementById('InboxTable');
	//alert(x.rows[1].ID);
	var CurrentKritikIdList	= document.getElementById('CurrentKritikIdList').value;
	var Y	= document.getElementById('Year').value;
	var M	= document.getElementById('Month').value;
	var T	= document.getElementById('Type').value;
	//var CurrentRowBG		= document.getElementById('CurrentInboxIdList').value;
	sendRequestText('kritik.ajax.php',ShowNewKritik,'CurrentKritikIdList='+CurrentKritikIdList+'&Month='+M+'&Type='+T+'&Year='+Y+'&op=GetNewID');
}
function ShowNewKritik(x){
	document.getElementById('DivGetNewKritik').innerHTML = x;
	var NewKritikIdList = document.getElementById('NewKritikIdList').value;
	var NumKritikIdList = document.getElementById('NumKritikIdList').value;
	var y;
	if (NewKritikIdList!=""){
		y = NewKritikIdList.split(',');
		for (i=0;i<NumKritikIdList;i++){
			var z = document.getElementById('Content'+y[i]).value;
			insRow(z);	
		}
	}
	var CurrentKritikIdList = document.getElementById('CurrentKritikIdList').value;
	if (NewKritikIdList!=""){
		if (CurrentKritikIdList=="")
			document.getElementById('CurrentKritikIdList').value = NewKritikIdList;
		else
			document.getElementById('CurrentKritikIdList').value = CurrentKritikIdList+','+NewKritikIdList;
	}
	
}
function ChgCmb(){
	var Y	= document.getElementById('Year').value;
	var M	= document.getElementById('Month').value;
	var T	= document.getElementById('Type').value;
	ShowWait('Kritik');
	sendRequestText('kritik.ajax.php',ShowKritik,'Month='+M+'&Year='+Y+'&Type='+T+'&op=GetKritikList');
}
function ShowKritik(x){
	document.getElementById('Kritik').innerHTML = x;
	Tables('KritikTable', 1, 0);
}
function HidePopup(){
	document.getElementById('MainDiv').style.display='block';
	document.getElementById('MainDiv').style.zIndex=1;
	document.getElementById('BlackDiv').style.display='none';
	document.getElementById('BlackDiv').style.zIndex=2;
	document.getElementById('DivPopup').innerHTML = '';
	document.getElementById('body').style.margin='5px';
}
function ReadMessage(ID){
	parent.HiddenFrame.location.href = "kritik.ajax.php?KritikID="+ID+"&op=GetMessageReaden";
	
	document.getElementById(ID).style.fontWeight='normal';
	ShowWait('DivPopup');
	document.getElementById('MainDiv').style.display='none';
	document.getElementById('MainDiv').style.zIndex=2;
	document.getElementById('BlackDiv').style.display='block';
	document.getElementById('BlackDiv').style.zIndex=1;
	document.getElementById('body').style.margin='0px';
	sendRequestText('kritik.ajax.php',ShowMessage,'KritikID='+ID+'&op=GetMessage');
	
}
function ShowMessage(x){
	document.getElementById('DivPopup').innerHTML = x;
}

function GetBrowser(a,b,c){
	  var browserName=navigator.appName; 
	  if (browserName=="Netscape") 
		  return a ;
	  else if (browserName=="Opera") 
		  return c;
	  else
		  return b;
}
function ResizeThisWin(){
	var Browser = GetBrowser(1,2,3);
	var WinHeight;
	if (Browser==1){
		WinHeight = parseInt(window.innerHeight);//ff
	} else if (Browser==2){
		WinHeight = parseInt(document.documentElement.clientHeight);//ie
	} else {
		WinHeight = parseInt(window.innerHeight);//opera
	}
	document.getElementById('BlackDiv').style.height = (parseInt(WinHeight)-1)+"px";
	var x = ((parseInt(WinHeight)/2)-((parseInt(document.getElementById('DivPopup').style.height))/2))+"px";
	document.getElementById('BlackDiv').style.paddingTop = '50px';
	document.getElementById('DivPopup').style.height = (parseInt(WinHeight)-300)+"px";//BlackDiv
}
function BalasSMS(state){
	if (state=='1'){
		document.getElementById('TxtReply').style.display = 'block';
		document.getElementById('ReplyLabel').style.display = 'block';
		document.getElementById('BtnReply').style.display = 'none';
		document.getElementById('BtnCancel').style.display = 'block';
		document.getElementById('BtnSend').style.display = 'block';
		document.getElementById('TxtReply').focus();
	} else {
		document.getElementById('TxtReply').style.display = 'none';
		document.getElementById('ReplyLabel').style.display = 'none';
		document.getElementById('BtnReply').style.display = 'block';
		document.getElementById('BtnCancel').style.display = 'none';
		document.getElementById('BtnSend').style.display = 'none';
	}
}
function KirimSMS(ID){
	//alert ('Asupo');
	var txt = document.getElementById('TxtReply').value;
	ShowWait('DivPopup');
	//alert (ID+txt);
	sendRequestText('kritik.ajax.php',ShowMessage2,'TxtToReply='+txt+'&op=ReplyMessage&ID='+ID);
	
}
function ShowMessage2(x){
	//alert ('Masuk kesini');
	//document.getElementById('DivPopup').innerHTML = x;
	HidePopup();
}
function DeleteSMS(ID){
	sendRequestText('kritik.ajax.php',ShowMessage2,'KritikID='+ID+'&op=DeleteSMS');
}
function DeleteRow(r,ID){
	if (confirm('Anda yakin akan menghapus Saran/Kritik ini?'))
	{
		DeleteSMS(ID);
		var i=r.parentNode.parentNode.rowIndex;
		document.getElementById('KritikTable').deleteRow(i);
		TableRefresh();
	}
}