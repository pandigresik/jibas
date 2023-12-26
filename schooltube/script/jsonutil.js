jsonutil_tryParseJson = function(json)
{
    try
    {
        var decode = $.parseJSON(json);
        return {Code : 1, Message : "OK", Data : decode};
    }
    catch(exception)
    {
        return {Code : -1, Message : exception, Data : json};
    }
};