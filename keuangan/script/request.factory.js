RequestFactory = function ()
{
    this.paramList = [];

    this.add = function (param, value)
    {
        this.paramList.push([param, value]);
    };

    this.createQs = function ()
    {
        var qs = "";
        for(var i = 0; i < this.paramList.length; i++)
        {
            if (qs !== "") qs += "&";
            qs += this.paramList[i][0] + "=" + encodeURIComponent(this.paramList[i][1]);
        }

        return qs;
    };

    this.debug = function ()
    {
        for(var i = 0; i < this.paramList.length; i++)
        {
            console.log(this.paramList[i][0] + " " + this.paramList[i][1]);
        }
    };
};