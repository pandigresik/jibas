playVideo = function(url)
{
    var addr = "video.play.php?videoUrl=" + encodeURIComponent(url);
    newWindow(addr, 'PlayVideo', '700', '500', 'resizable=1,scrollbars=1,status=0,toolbar=0');
};

hapusFile = function (no, idMedia, idFile)
{

};

refreshPage = function()
{
    document.location.reload();
};

acceptInfo = function(idMedia, urutan, keterangan)
{
    parent.acceptInfo(idMedia, urutan, keterangan);
};

pilihMedia = function(idMedia)
{
    var addr = "media.browse.dialog.php?idMedia="+idMedia;
    newWindow(addr, 'InfoMedia', '600', '380', 'resizable=1,scrollbars=1,status=0,toolbar=0');
};