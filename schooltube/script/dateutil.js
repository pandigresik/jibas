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