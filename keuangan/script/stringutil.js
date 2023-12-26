stringutil_replaceAll = function (search, replace, target)
{
    return target.replace(new RegExp(search, 'g'), replace);
};

stringutil_padLeft = function (string, max, padChar)
{
    string = string.toString();
    return string.length < max ? stringutil_padLeft(padChar + string, max) : string;
};