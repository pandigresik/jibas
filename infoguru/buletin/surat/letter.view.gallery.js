var currBerkas = 1;

movePrev = function()
{
    if (currBerkas == 1)
        return;
    
    var ndata = $('#ndata').val();
    for(i = 1; i <= ndata; i++)
        $('#img' + i).css({visibility : 'hidden'});
        
    currBerkas -= 1;
    $('#cbNomor').val(currBerkas);
    
    showImage();
    showInfoMsg();
}

moveNext = function()
{
    var ndata = $('#ndata').val();
    if (currBerkas == ndata)
        return;
    
    for(i = 1; i <= ndata; i++)
        $('#img' + i).css({visibility : 'hidden'});
        
    currBerkas += 1;
    $('#cbNomor').val(currBerkas);
    
    showImage();
    showInfoMsg();
}

changeBerkas = function() {
    var no = $('#cbNomor').val();
    var ndata = $('#ndata').val();
    
    for(i = 1; i <= ndata; i++)
        $('#img' + i).css({visibility : 'hidden'});

    currBerkas = parseInt(no);
    
    showImage();
    showInfoMsg();
}

showImage = function()
{
    var margin = 7;
    var size = $('#cbSize').val();
    
    var docWidth = $(window).width();
    if (document.body.clientWidth)
        docWidth = document.body.clientWidth;//To get the browser height in IE
        
    var docHeight = $(window).height();
    if (document.body.clientHeight)
        docHeight = document.body.clientHeight;//To get the browser height in IE
    
    if (size == 0)
    {
        $('#img' + currBerkas).css({height : '', width : '', left : 0});
    }
    else if (size == 1)
    {
        var imgWidth = docWidth - 40;    
        $('#img' + currBerkas).css({width : imgWidth, height : '', left : 0});    
    }
    
    else if (size == 2)
    {
        var ctlHeight = $('#control').height();        
        var imgHeight = docHeight - ctlHeight - 2 * margin - 20;
        
        //alert(imgHeight);
        $('#img' + currBerkas).css({height : imgHeight, width : ''});
        
        var imgWidth = $('#img' + currBerkas).width();
        var leftPos = (docWidth - imgWidth) / 2;
        
        $('#img' + currBerkas).css({left : leftPos});
    }
    
    $('#img' + currBerkas).css({visibility : 'visible'});
    $('#container').scrollTop(0);
}

showInfoMsg = function()
{
    var info = $('#info' + currBerkas).val();
    if ($.trim(info).length == 0)
    {
        $('#infobox').css({visibility : 'hidden'});
    }
    else
    {
        $('#infobox').css({visibility : 'visible'});
        $('#infomsg').html(info);
    }
}

reArrangeContainer = function() {
    var margin = 7;
    
    var docHeight = $(window).height();
    var docWidth = $(window).width();
    
    if (document.body.clientHeight)
        docHeight = document.body.clientHeight;//To get the browser height in IE
        
    if (document.body.clientWidth)
        docWidth = document.body.clientWidth;//To get the browser height in IE         
    
    var ctlHeight = $('#control').height();
    var divHeight = docHeight - ctlHeight - 2 * margin;
    var divWidth = docWidth - 2 * margin;
    
    $('#container').css({top : margin + ctlHeight, left : margin, height : divHeight, width : divWidth, position : 'absolute'});
}

showInfoBox = function()
{
    var margin = 7;
    
    var docWidth = $(window).width();
    if (document.body.clientWidth)
        docWidth = document.body.clientWidth;//To get the browser height in IE         
    
    var docHeight = $(window).height();
    if (document.body.clientHeight)
        docHeight = document.body.clientHeight;//To get the browser height in IE
        
    var ctlHeight = $('#control').height();    
    var divHeight = docHeight - ctlHeight;
    var divWidth = docWidth - 2 * margin;
    
    var topPos = divHeight - $('#infobox').height();
    var leftPos = (divWidth - $('#infobox').width()) / 2;
    
    $('#infobox').css({top : topPos, left : leftPos});
}

// ON WINDOW RESIZED
$(window).resize(function() {
    reArrangeContainer();
    showInfoBox();
});

// ON START UP
$(function() {
    reArrangeContainer();
    showInfoBox();
    
    currBerkas = 1;
    showImage();
    showInfoMsg();
})