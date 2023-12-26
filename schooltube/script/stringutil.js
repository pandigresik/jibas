stringutil_replaceAll = function (search, replace, target)
{
    return target.replace(new RegExp(search, 'g'), replace);
};