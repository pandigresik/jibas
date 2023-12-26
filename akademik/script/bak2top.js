function winWid(){ return (ns4||ns6)?window.innerWidth:document.body.clientWidth; }
function winHei(){ return (ns4||ns6)?window.innerHeight:document.body.clientHeight; }
function winOfy(){ return (ns4||ns6)?window.pageYOffset:document.body.scrollTop; }
function toplink(){

rt=(!ns4)?'<div id="bttl" style="position:absolute;">':'<layer name="bttl">';
rt+='<a href="JavaScript:scrollTo(0,0)"><nobr>Top of Page</nobr></a>';
rt+=(!ns4)?'</div>':'</layer>';
document.write(rt);
mtoplink();
}
function mtoplink(){
	with(eval(bttll)){
		left=(winOfy()>0)?winWid()-110:-500;
		top=(winOfy()>0)?winHei()-40+winOfy():-500;
	}
	setTimeout('mtoplink()',updatespeed);
}
var ns4=(document.layers)?1:0;
var ie4=(document.all)?1:0;
var ns6=(document.getElementById&&!document.all)?1:0;
if(ie4)	bttll="document.all['bttl'].style";
if(ns4) bttll="document.layers['bttl']";
if(ns6) bttll="document.getElementById('bttl').style";
var updatespeed=25;
toplink();