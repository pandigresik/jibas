// JavaScript Document
var timerID = null;
var timerRunning = false;
var timerElementId = null;

var months=new Array(13);
months[1]="Januari";
months[2]="Februari";
months[3]="Maret";
months[4]="April";
months[5]="Mei";
months[6]="Juni";
months[7]="Juli";
months[8]="Agustus";
months[9]="September";
months[10]="Oktober";
months[11]="Nopember";
months[12]="Desember";

var days = new Array(8);
days[1]="Senin";
days[2]="Selasa";
days[3]="Rabu";
days[4]="Kamis";
days[5]="Jumat";
days[6]="Sabtu";
days[7]="Minggu";


function stopclock (){
	if(timerRunning)
		clearTimeout(timerID);
	timerRunning = false;
}

function getCurrentDate() {
	var time = new Date();
	var lmonth = months[time.getMonth() + 1];
	var date = time.getDate();
	var year = time.getYear();
	var lday = days[time.getDay()];
	if (year < 2000)    // Y2K Fix, Isaac Powell
		year = year + 1900; // http://onyx.idbsu.edu/~ipowell

	return lday + ", " + date + " " + lmonth + " " + year;
}

function showtime() {
	var now = new Date();
	var hours = now.getHours();
	var hours2, minutes2, seconds2; 
	var ampm;
	if (hours>12){
		hours = hours - 12;
		ampm = get_digit("pm");
	} else {
		ampm = get_digit("am");
	}

	if (hours>=10){
		hours2 = get_digit(Math.floor(hours/10))+get_digit(hours%10);
	} else {
		hours2 = get_digit(hours);
	}
	
	var minutes = now.getMinutes();
	if (minutes>=10){
		minutes2 = get_digit(Math.floor(minutes/10))+get_digit(minutes%10);
	} else {
		minutes2 = get_digit(minutes);
	}
	
	var seconds  = now.getSeconds()
	if (seconds>=10){
		seconds2 = get_digit(Math.floor(seconds/10))+get_digit(seconds%10);
	} else {
		seconds2 = get_digit(seconds);
	}
	
	
	var timeValue = ((hours < 10) ? get_digit(0) : "") + hours2
	timeValue += ((minutes < 10) ? get_digit("colon")+get_digit(0) : get_digit("colon")) + minutes2
	//timeValue += ((seconds < 10) ? get_digit("colon")+get_digit(0) : get_digit("colon")) + seconds2
	//timeValue = getCurrentDate() + "   " + timeValue;
	timeValue = timeValue + ampm ;// + hours + minutes + seconds;
	//alert (timeValue);
	document.getElementById(timerElementId).innerHTML = timeValue;
	timerID = setTimeout("showtime()", 60000);
	timerRunning = true;
	
}

function get_digit(no){
	if (no=="colon")
	{
	return "<img src='script/digit/colon.gif' alt=':'>";
	} else {
	return "<img src='script/digit/c"+no+".gif' alt='"+no+"'>";
	}
}

function startclock(elementId) {
	timerElementId = elementId;
	stopclock();
	showtime();
}
