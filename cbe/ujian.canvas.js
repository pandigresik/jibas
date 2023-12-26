var canvas, context, bMouseIsDown = false, iLastX, iLastY;

ujian_initCanvas = function ()
{
    bMouseIsDown = false;
    iLastX = 0;
    iLastY = 0;

    canvas = document.getElementById('cvs');
    var topCanvas = canvas.getBoundingClientRect().top;
    var leftCanvas = canvas.getBoundingClientRect().left;

    context = canvas.getContext('2d');
    context.fillStyle = '#ffffff';
    context.fillRect(0, 0, canvas.width, canvas.height);
    context.strokeStyle = "#000000";

    canvas.onmousedown = function(e)
    {
        bMouseIsDown = true;
        iLastX = e.clientX - leftCanvas;
        iLastY = e.clientY - topCanvas;
    };

    canvas.onmouseup = function()
    {
        bMouseIsDown = false;
        iLastX = -1;
        iLastY = -1;
    };

    canvas.onmousemove = function(e)
    {
        if (bMouseIsDown)
        {
            var iX = e.clientX - leftCanvas;
            var iY = e.clientY - topCanvas;
            context.moveTo(iLastX, iLastY);
            context.lineTo(iX, iY);
            context.stroke();
            iLastX = iX;
            iLastY = iY;
        }
    };
};

ujian_clearCanvas = function()
{
    if (canvas === undefined)
        return;

    if (!confirm("Bersihkan canvas?"))
        return;

    context.clearRect(0, 0, canvas.width, canvas.height);
    context.fillStyle = '#ffffff';
    context.fillRect(0, 0, canvas.width, canvas.height);
    context.strokeStyle = "#000000";
    context.beginPath();
};

ujian_loadImage = function(jpeg64)
{
    if (canvas === undefined)
        return;

    var image = new Image();
    image.onload = function()
    {
        context.drawImage(image, 0, 0);
    };
    image.src = "data:image/jpeg;base64," + jpeg64;
};

ujian_getDrawingImage = function ()
{
    if (canvas === undefined)
        return "";

    var image = new Image();
    image.src = canvas.toDataURL("image/jpeg");
    return image.src;
};