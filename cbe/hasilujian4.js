var boxSoal = null;

$(document).ready(function ()
{
    boxSoal = new DialogBox("#divDialogSoal", 720, 520);
});

hasilujian_showImageSoal = function(idSoal)
{
    var soalTag = $("#tag-" + idSoal).val();

    var json = stringutil_replaceAll("`", "\"", soalTag);

    var gnrt = jsonutil_tryParseJson(json);
    if (parseInt(gnrt.Code) < 0)
    {
        mainBox.showError(gnrt.Message, gnrt.Data, "hasilujian_showImageSoal()");
        return;
    }

    var tag = gnrt.Data;
    if (parseInt(tag.ViewSoal) === 0)
    {
        alert("Tidak diijinkan melihat soal ujian!");
        return;
    }
    else if (parseInt(tag.ViewAfter) !== 0 && parseInt(tag.DateDiff) < parseInt(tag.ViewAfter))
    {
        alert("Soal dapat dilihat setelah " + tag.ViewAfter + " hari setelah tanggal ujian");
        return;
    }

    $.ajax({
        url: "hasilujian.ajax.php",
        data: "op=getsoalpenjelasan&idsoal=" + idSoal + "&viewexp=" + tag.ViewExp,
        type: "post",
        success: function (json)
        {
            var parse = jsonutil_tryParseJson(json);
            if (parseInt(parse.Code) < 0)
            {
                mainBox.showError(parse.Message, parse.Data, "hasilujian_showImageSoal()");
                return false;
            }

            var gnrt = parse.Data;
            if (parseInt(gnrt.Code) < 0)
            {
                mainBox.showError(gnrt.Message, parse.Data, "hasilujian_showImageSoal()");
                return false;
            }

            var jsonImageData = gnrt.Data;
            parse = jsonutil_tryParseJson(jsonImageData);
            if (parseInt(parse.Code) < 0)
            {
                mainBox.showError(parse.Message, parse.Data, "hasilujian_showImageSoal()");
                return false;
            }

            var imageData = parse.Data;

            var content = "<div style='width: 710px; height: 510px; overflow: auto>'>";
            content += "<img class='noRightClick' src='" + imageData.ImageSoal + "'>";

            if (parseInt(imageData.JenisJawaban) === 2)
            {
                content += "<br><strong>Jawaban:</strong><br><img class='noRightClick' src='data:image/jpeg;base64," + imageData.Jawaban + "'>";
            }
            else
            {
                var jawaban = imageData.Jawaban;
                jawaban = jawaban.replace(new RegExp("<br>", "g"), "\r\n");
                jawaban = jawaban.replace(new RegExp("<", "g"), "&lt;");
                jawaban = jawaban.replace(new RegExp(">", "g"), "&gt;");
                jawaban = jawaban.replace(new RegExp("\r\n", "g"), "<br>");
                content += "<br><strong>Jawaban:</strong><br>" + jawaban;
            }

            if (parseInt(tag.ViewExp) === 1)
                //content += "<br><br><strong>Penjelasan:</strong><br><img class='noRightClick' src='data:image/jpeg;base64," + imageData.ImagePenjelasan + "'>";
                content += "<br><br><strong>Penjelasan:</strong><br><img class='noRightClick' src='" + imageData.ImagePenjelasan + "'>";
            else
                content += "<br><br></strong>Penjelasan:</strong><br><i>Tidak diperkenankan melihat penjelasan</i>";

            content += "<br><br><br></div>";

            boxSoal.show(content);

            $('.noRightClick').bind('contextmenu', function(e) {
                return false;
            });
        },
        error: function (xhr) {
            alert(xhr.responseText);
        }
    });
};
