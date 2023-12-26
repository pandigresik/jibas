function ValidatorX()
{
    this.EmptyText = function (elementId, title)
    {
        var val = $.trim($("#" + elementId).val());
        console.log(val);
        if (val.length === 0)
        {
            alert(title + " harus diisi!");
            $("#" + elementId).focus();
            return false;
        }

        return true;
    };

    this.TextLength = function (elementId, title, minLen, maxLen)
    {
        var val = $.trim($("#" + elementId).val());
        if (val.length < minLen || val.length > maxLen)
        {
            alert("Panjang " + title + " harus antara " + minLen + " dan " + maxLen + " karakter!");
            $("#" + elementId).focus();
            return false;
        }

        return true;
    };

    this.CharInSet = function(char, set)
    {
        var found = false;
        for(var i = 0; !found && i < set.length; i++)
        {

            found = char === set.substr(i, 1);
        }

        return found;
    };

    this.IsInteger = function (elementId, title)
    {
        var val = $.trim($("#" + elementId).val());

        var intSet = "0123456789-";
        var isValid = true;
        for(var i = 0; isValid && i < val.length; i++)
        {
            var check = val.substr(i, 1);
            if (!this.CharInSet(check, intSet))
                isValid = false;
        }

        if (!isValid)
        {
            alert(title + " harus bilangan Integer!");
            $("#" + elementId).focus();
            return false;
        }

        return true;
    };

    this.IsDecimal = function (elementId, title)
    {
        var val = $.trim($("#" + elementId).val());

        var intSet = "0123456789-.";
        var isValid = true;
        for(var i = 0; isValid && i < val.length; i++)
        {
            var check = val.substr(i, 1);
            if (!this.CharInSet(check, intSet))
                isValid = false;
        }

        if (!isValid)
        {
            alert(title + " harus bilangan Desimal!");
            $("#" + elementId).focus();
            return false;
        }

        return true;
    }
}



