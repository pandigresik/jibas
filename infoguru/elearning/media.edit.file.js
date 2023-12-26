addFile = function()
{
    var nid = parseInt($("#nFile").val()) + 1;

    var row = "<tr id='filerow" + nid + "'>\r\n";
    row += "<td align='left'><input type='file' id='file" + nid + "' name='file" + nid + "' onchange='validateFileSize(" + nid + ")' style='width: 300px; height: 32px;' class='inputbox'></td>\r\n";
    row += "<td align='left'>\r\n";
    row += "<input type='textbox' id='info" + nid + "' name='info" + nid + "' style='width: 300px; height: 32px;' class='inputbox'>\r\n";
    row += "<input type='button' class='but' value=' X ' style='height: 26px;' onclick='removeFile(" + nid + ")'>\r\n";
    row += "</td>\r\n";
    row += "</tr>\r\n";

    $("#tableFile > tbody:last").append(row);
    $("#nFile").val(nid);
};

validateFileSize = function (nid)
{
    var elId = "#file" + nid;
    var file = ($(elId))[0].files[0];

    var size = file.size / (1024 * 1024);
    if (size >= G_MAX_FILE_SIZE)
    {
        $(elId).val("");
        alert("Ukuran lampiran file tidak boleh melebihi " + G_MAX_FILE_SIZE + " MB");
    }
};

removeFile = function(id)
{
    if (!confirm('Hapus berkas ini?'))
        return;

    $("#filerow" + id).remove();
};

simpanMediaFile = function()
{
    var nFile = parseInt($("#nFile").val());
    if (nFile === 0)
    {
        alert("Pilih minimal satu file tambahan!")
        return;
    }

    if (!confirm("Data sudah lengkap dan benar?"))
        return;

    var no = $("#no").val();
    var idMedia = $("#idMedia").val();

    var formData = new FormData();
    formData.append("idMedia", idMedia);

    var cnt = 0;

    for(var i = 1; i <= nFile; i++)
    {
        if ($('#filerow' + i).length === 0)
            continue;

        var file = $("#file" + i).val();
        if ($.trim(file).length === 0)
            continue;

        cnt += 1;
        formData.append("file" + cnt, $("#file" + i)[0].files[0]);
        formData.append("info" + cnt, $("#info" + i).val());
    }
    formData.append("nFile", cnt);

    if (cnt === 0)
    {
        alert("Pilih minimal satu file tambahan!")
        return;
    }

    $.ajax({
        url: "media.edit.file.save.php",
        type: 'POST',
        data: formData,
        async: false,
        cache: false,
        contentType: false,
        processData: false,
        success: function ()
        {
            opener.refreshFile(no, idMedia);
            window.close();
        },
        error: function (xhr)
        {
            alert(xhr.responseText);
        }
    });
};