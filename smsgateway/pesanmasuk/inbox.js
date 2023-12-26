//INSERT INTO inbox SET UpdatedInDB=now(),ReceivingDateTime=now(),Text=now(),SenderNumber='+6285624084062',UDH='XXX',RecipientID='XX',Status=0;
//<img onclick="DeleteRow(this,'<?=$row['ID']?>');" src="../images/ico/hapus.png" width="16" height="16" />
function insRow(Content){
	var x=document.getElementById('InboxTable');
	var lastRow = x.rows.length;
	var iteration = lastRow;
	var row = x.insertRow(1);
	//ReadMessage(<?=$row['ID']?>);
	
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
	e.align		= 'center';
	//e.
	b.innerHTML = y[0];
	c.innerHTML = y[1];
	d.innerHTML = y[2];
	//e.innerHTML = y[3];
	e.innerHTML = "<img style='cursor:pointer' onclick='DeleteRow(this,"+y[3]+");' src='../images/ico/hapus.png' width='16' height='16' />";
	setTimeout('ClearBGClr('+y[3]+')',5000);
}
function ClearBGClr(x){
	document.getElementById(x).style.backgroundColor = "#ffffff";
	TableRefresh();
	
}
function TableRefresh(){
	var table = document.getElementById('InboxTable');
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
	Tables('InboxTable', 1, 0);
}
function InboxOnloads(){
	
	//var x=document.getElementById('InboxTable');
	//var lastRow = x.rows[1].style;
	//alert (x);
	setInterval('InboxOnload()',5000);
}
function InboxOnload(){
	//alert ('Masuk');
	//var x=document.getElementById('InboxTable');
	//alert(x.rows[1].ID);
	var CurrentInboxIdList	= document.getElementById('CurrentInboxIdList').value;
	var Y	= document.getElementById('Year').value;
	var M	= document.getElementById('Month').value;
	//var CurrentRowBG		= document.getElementById('CurrentInboxIdList').value;
	sendRequestText('inbox.ajax.php',ShowNewInbox,'CurrentInboxIdList='+CurrentInboxIdList+'&Month='+M+'&Year='+Y+'&op=GetNewID');
}
function ShowNewInbox(x){
	document.getElementById('DivGetNewInbox').innerHTML = x;
	var NewInboxIdList = document.getElementById('NewInboxIdList').value;
	var NumInboxIdList = document.getElementById('NumInboxIdList').value;
	var y;
	if (NewInboxIdList!=""){
		y = NewInboxIdList.split(',');
		for (i=0;i<NumInboxIdList;i++){
			var z = document.getElementById('Content'+y[i]).value;
			insRow(z);	
		}
	}
	var CurrentInboxIdList = document.getElementById('CurrentInboxIdList').value;
	if (NewInboxIdList!=""){
		if (CurrentInboxIdList=="")
			document.getElementById('CurrentInboxIdList').value = NewInboxIdList;
		else
			document.getElementById('CurrentInboxIdList').value = CurrentInboxIdList+','+NewInboxIdList;
	}
	
}
function ChgCmb(){
	var Y	= document.getElementById('Year').value;
	var M	= document.getElementById('Month').value;
	ShowWait('Inbox');
	sendRequestText('inbox.ajax.php',ShowInbox,'Month='+M+'&Year='+Y+'&op=GetInboxList');
}
function ShowInbox(x){
	document.getElementById('Inbox').innerHTML = x;
	Tables('InboxTable', 1, 0);
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
	parent.HiddenFrame.location.href = "inbox.ajax.php?InboxID="+ID+"&op=GetMessageReaden";
	
	document.getElementById(ID).style.fontWeight='normal';
	ShowWait('DivPopup');
	document.getElementById('MainDiv').style.display='none';
	document.getElementById('MainDiv').style.zIndex=2;
	document.getElementById('BlackDiv').style.display='block';
	document.getElementById('BlackDiv').style.zIndex=1;
	document.getElementById('body').style.margin='0px';
	sendRequestText('inbox.ajax.php',ShowMessage,'InboxID='+ID+'&op=GetMessage');
	
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
	sendRequestText('inbox.ajax.php',ShowMessage2,'TxtToReply='+txt+'&op=ReplyMessage&ID='+ID);
	
}
function ShowMessage2(x){
	//alert ('Masuk kesini');
	//document.getElementById('DivPopup').innerHTML = x;
	HidePopup();
}
function DeleteSMS(ID){
	sendRequestText('inbox.ajax.php',ShowMessage2,'InboxID='+ID+'&op=DeleteSMS');
}
function DeleteRow(r,ID){
	if (confirm('Anda yakin akan menghapus SMS ini?'))
	{
		DeleteSMS(ID);
		var i=r.parentNode.parentNode.rowIndex;
		document.getElementById('InboxTable').deleteRow(i);
		TableRefresh();
	}
}