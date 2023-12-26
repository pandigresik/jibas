function ShowError(DivId,Msg,InputId){
	document.getElementById(DivId).style.backgroundImage = 'url(../images/ico/error.gif)';
	document.getElementById(DivId).style.backgroundRepeat = 'no-repeat';
	document.getElementById(DivId).style.paddingLeft = '20px';
	document.getElementById(DivId).style.marginTop = '5px';
	document.getElementById(DivId).innerHTML = Msg;
	if (InputId.length>0)
		document.getElementById(InputId).focus();
}
function ShowError2(DivId,Msg,InputId){
	document.getElementById(DivId).style.backgroundImage = 'url(images/ico/error.gif)';
	document.getElementById(DivId).style.backgroundRepeat = 'no-repeat';
	document.getElementById(DivId).style.paddingLeft = '20px';
	document.getElementById(DivId).style.marginTop = '5px';
	document.getElementById(DivId).innerHTML = Msg;
	if (InputId.length>0)
		document.getElementById(InputId).focus();
}
function HideError(DivId){
	document.getElementById(DivId).style.backgroundImage = '';
	document.getElementById(DivId).style.backgroundRepeat = 'no-repeat';
	document.getElementById(DivId).style.paddingLeft = '0px';
	document.getElementById(DivId).style.marginTop = '0px';
	document.getElementById(DivId).innerHTML = '';
}
function ShowWait(DivID){
	var x = "<img src='../images/loading1.gif'>&nbsp;Please wait...";
	document.getElementById(DivID).innerHTML = x;
}
function InputHover(txt,id,state){
	var x = document.getElementById(id).value;
	if (state=='1'){
		if (x==txt){
			document.getElementById(id).value='';
			document.getElementById(id).style.color='#000';
		} else {
			document.getElementById(id).style.color='#000';			
		}
	} else {
		if (x==txt || x==''){
			document.getElementById(id).style.color='#636363';
			document.getElementById(id).value=txt;
		} else {
			document.getElementById(id).style.color='#000';
		}
	}
}