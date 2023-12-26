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
days[0]="Minggu";


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
	var minutes = now.getMinutes();
	var seconds = now.getSeconds()
	var timeValue = ((hours < 10) ? "0" : "") + hours
	timeValue += ((minutes < 10) ? ":0" : ":") + minutes
	timeValue += ((seconds < 10) ? ":0" : ":") + seconds
	timeValue = getCurrentDate() + "   " + timeValue;
	document.getElementById(timerElementId).innerHTML = timeValue;
	timerID = setTimeout("showtime()", 1000);
	timerRunning = true;
}

function startclock(elementId) {
	timerElementId = elementId;
	stopclock();
	showtime();
}
