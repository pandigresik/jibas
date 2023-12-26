sr_startSearch = function ()
{
    var searchBy = parseInt($("#searchBy").val());
    var searchKey = $.trim($("#searchKey").val());
    var searchDept = $("#searchDept").val();
    if (searchKey.length === 0)
        return;

    if (searchKey.length < 5)
    {
        $("#searchInfo").html("Panjang kata kunci pencarian minimal 5 karakter");
        return;
    }

    if (searchBy === 0)
        sr_startVideoSearch(searchDept, searchKey);
    else
        sr_startChannelModulSearch(searchBy, searchDept, searchKey);
};