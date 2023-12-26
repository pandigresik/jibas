function DialogBox (divElement, width, height)
{
    this.showMethodName = true;

    this.divElement = divElement;

    this.dialogBox = $(divElement).dialog({
        modal: true,
        autoOpen: false,
        draggable: false,
        resizable: false,
        width: width,
        height: height,
        clickOutside: true,
        show: {effect: 'drop', direction: "up", mode: "slow", speed: 1000},
        hide: {effect: 'fade', mode: "slow", speed: 1000},
        dialogClass: 'ui-dialog-osx',
        open: function(event, ui)
        {
            $('.ui-widget-overlay').bind('click', function()
            {
                $(divElement).dialog('close');
            });
        }
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
}

DialogBox.prototype.show = function(content, method)
{
    if (this.dialogBox === undefined)
        return;

    if (method === undefined) method = "";

    if (this.showMethodName && method.trim().length > 0)
        content += "<br><br>Method: <i>" + method + "</i>";

    $(this.divElement).html(content);
    //this.dialog_Instance.dialog("open");
    this.dialogBox.dialog("open");

    //logToConsole("DialogBox 2dialog_Instance", this.dialogBox);
    //logToConsole("DialogBox 2dialog_Div", this.divElement);
};

DialogBox.prototype.showError = function(message, data, method)
{
    var content = "<font style='color: red; font-weight: bold;'>APPLICATION ERROR</font><br>";
    content += "<i>" + message + "</i><br><br>";
    content += "Data: " + data;

    this.show(content, method);
};