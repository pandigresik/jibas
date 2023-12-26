function tryParseInt(data, defaultValue)
{
    data = $.trim(data);
    if (data.length === 0)
        return defaultValue;

    if (isNaN(data))
        return defaultValue;

    return parseInt(data);
}

function tryParseFloat(data, defaultValue)
{
    data = $.trim(data);
    if (data.length === 0)
        return defaultValue;

    if (isNaN(data))
        return defaultValue;

    return parseFloat(data);
}

function removeRpSymbol(rp)
{
    rp = $.trim(rp);
    var rpLen = rp.length;

    if (rpLen === 0)
        return rp;

    var result = "";
    for(var i = 0; i < rpLen; i++)
    {
        var c = rp.charAt(i);

        if (i === 0)
        {
            if (c === "(" || c === "R")
                continue;
        }
        else if (i === 1)
        {
            if (c === "R" || c === "p")
                continue;
        }
        else if (i === 2)
        {
            if (c === "p")
                continue;
        }
        else if (i === rpLen - 1)
        {
            if (c === ")")
                continue;
        }

        if (c === "." || c === " ")
            continue;

        result += c;
    }

    return result;
}