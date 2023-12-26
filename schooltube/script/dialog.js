var dialog_Instance = undefined;
var dialog_Div = undefined;

dialog_initDialog = function (divDialog)
{
    dialog_Div = divDialog;

    dialog_Instance = $(divDialog).dialog({
        modal: true,
        autoOpen: false,
        draggable: false,
        resizable: false,
        width: 420,
        height: 180,
        show: {effect: 'drop', direction: "up", mode: "slow", speed: 1000},
        hide: {effect: 'fade', mode: "slow", speed: 1000},
        dialogClass: 'ui-dialog-osx'
    });

    $(".ui-dialog-titlebar").css({
        "float": "right",
        border: 0,
        padding: 0
    });

    $(".ui-dialog-title").css({
        display: "none"
    });

    $(".ui-dialog-titlebar-close").css({
        top: 0,
        right: 0,
        margin: 0,
        "z-index": 999
    });
};

dialog_initDialog = function (divDialog, width, height)
{
    dialog_Div = divDialog;

    dialog_Instance = $(divDialog).dialog({
        modal: true,
        autoOpen: false,
        draggable: false,
        resizable: false,
        width: width,
        height: height,
        show: {effect: 'drop', direction: "up", mode: "slow", speed: 1000},
        hide: {effect: 'fade', mode: "slow", speed: 1000},
        dialogClass: 'ui-dialog-osx'
    });

    $(".ui-dialog-titlebar").css({
        "float": "right",
        border: 0,
        padding: 0
    });

    $(".ui-dialog-title").css({
        display: "none"
    });

    $(".ui-dialog-titlebar-close").css({
        top: 0,
        right: 0,
        margin: 0,
        "z-index": 999
    });
};

dialog_showDialog = function (content)
{
    if (dialog_Instance === undefined)
        return;

    $(dialog_Div).html(content);
    dialog_Instance.dialog("open");
};

dialog_showApplicationError = function(message, data)
{
    var msg = "<font style='color: red; font-weight: bold;'>APPLICATION ERROR</font><br>";
    msg += "<i>" + message + "</i><br><br>";
    msg += "Data: " + data;

    dialog_showDialog(msg);
};