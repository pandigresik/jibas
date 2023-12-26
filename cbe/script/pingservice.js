var pingSvc_Interval = 1000 * 60 * 1.5;
var pingSvc_RootPath = ".";
var pingSvc_Enable = true;

pingSvc_Start = function(rootPath)
{
    pingSvc_RootPath = rootPath;
    setTimeout(pingSvc_SendPing, pingSvc_Interval);
};

pingSvc_SetEnable = function(enable)
{
    pingSvc_Enable = enable;
};

pingSvc_SendPing = function()
{
    if (!pingSvc_Enable)
        return;

    $.ajax({
        url: pingSvc_RootPath + "/library/pingservice.php",
        success: function (html) {
            //logToConsole("Ping Service-OK", "berhasil mengirim ping ke server!")
        },
        error: function (xhr, response, error)
        {
            //logToConsole("Ping Service-ERROR", xhr.responseText);
        }
    });

    setTimeout(pingSvc_SendPing, pingSvc_Interval);
};

