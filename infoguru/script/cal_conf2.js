
//Define calendar(s): addCalendar ("Unique Calendar Name", "Window title", "Form element's name", Form name")
/*addCalendar("Calendar1", "Select Date", "tcicilan", "main");
addCalendar("Calendar2", "Select Date", "tanggal", "main");
addCalendar("Calendartglmulai", "Select Date", "tglmulai", "main");
addCalendar("Calendartglakhir", "Select Date", "tglakhir", "main");
addCalendar("Calendartglmutasi", "Select Date", "tanggal", "simpan_mutasi");*/
addCalendar("Calendar1", "Pilih Tanggal", "tglawal", "panel5");
addCalendar("Calendar2", "Pilih Tanggal", "tglakhir", "panel5");
addCalendar("Calendar3", "Pilih Tanggal", "tanggal", "main");

// default settings for English
// Uncomment desired lines and modify its values
setFont("verdana", 9);
setWidth(200, 1, 15, 1);
setColor("#cfc925", "#cccccc", "#ffffff", "#ffffff", "#333333", "#cccccc", "#333333");
setFontColor("#333333", "#333333", "#333333", "#ffffff", "#333333");
setFormat("dd-mm-yyyy");
setSize(200, 200, -200, 16);

// setWeekDay(0);
setMonthNames("Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember");
// setDayNames("Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday");
setLinkNames("[Tutup]", "[Clear]");
