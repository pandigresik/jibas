dateutil_getMaxDay = function(year, month)
{
    var nday = 30;
    switch (month)
    {
        case 1:
        case 3:
        case 5:
        case 7:
        case 8:
        case 10:
        case 12:
            nday = 31;
            break;
        case 2:
            nday = parseInt(year % 4) === 0 ? 29 : 28;
            break;
        default:
            nday = 30;
            break;
    }

    return nday;
};

dateutil_daysInMonth = function(year, month)
{
    return dateutil_getMaxDay(year, month);
};

dateutil_daysInYear = function(year)
{
    var nDiv4 = parseInt(year / 4);
    var nNon4 = year - nDiv4;
    return nDiv4 * 366 + nNon4 * 365;
};

dateutil_dateToMinute = function(year, month, day, hour, minute)
{
    var total = minute;
    total += hour * 60;
    total += day * 24 * 60;
    for (var i = 1; i <= month; i++)
    {
        total += dateutil_daysInMonth(year, i) * 24 * 60;
    }
    total += dateutil_daysInYear(year) * 24 * 60;
    return total;
};

dateutil_inaNameMonth = function(month)
{
    switch (month)
    {
        case 1:
            return "Januari";
        case 2:
            return "Februari";
        case 3:
            return "Maret";
        case 4:
            return "April";
        case 5:
            return "Mei";
        case 6:
            return "Juni";
        case 7:
            return "Juli";
        case 8:
            return "Agustus";
        case 9:
            return "September";
        case 10:
            return "Oktober";
        case 11:
            return "Nopember";
        default:
            return "Desember";
    }
};

dateutil_formatInaDate = function(date)
{
    var lsDate = date.split("-");
    var y = parseInt(lsDate[0]);
    var m = parseInt(lsDate[1]);
    var d = parseInt(lsDate[2]);

    return stringutil_padLeft(d, 2, "0") + " " + dateutil_inaNameMonth(m) + " " + y;
};

dateutil_getCurDate = function ()
{
    var dt = new Date();
    return new Date(dt.getFullYear(), dt.getMonth(), dt.getDate());
};

dateutil_stringToDate = function (dateString)
{
    var lsDate = dateString.split("-");
    var y = parseInt(lsDate[0]);
    var m = parseInt(lsDate[1]);
    var d = parseInt(lsDate[2]);

    return new Date(y, m-1, d);
};

