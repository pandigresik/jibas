var emptyDate = {Year: 0, Month: 0, Date: 0, Hour: 0, Minute: 0, Second: 0};

edate_parseDate = function(dateString)
{
    dateString = dateString.replace("T", " ");
    var pos = dateString.indexOf(" ");
    if (pos === -1)
        return emptyDate;

    var temp = dateString.split(" ");
    if (temp.length !== 2)
        return emptyDate;

    var dateArr = temp[0].split("-");
    if (dateArr.length !== 3)
        return emptyDate;

    var timeArr = temp[1].split(":");
    if (timeArr.length !== 3)
        return emptyDate;

    var yr = parseInt(dateArr[0]);
    var mo = parseInt(dateArr[1]);
    var dy = parseInt(dateArr[2]);

    var hr = parseInt(timeArr[0]);
    var mn = parseInt(timeArr[1]);
    var sc = parseInt(timeArr[2]);

    return {Year: yr, Month: mo, Date: dy, Hour: hr, Minute: mn, Second: sc};
};

