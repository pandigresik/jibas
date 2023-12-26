var $jQueryParent = parent.jQuery;
var emoticonsDialog = undefined;

var cmtBoxCnt = 1;
var maxCommentLength = 255;
var contentChange = false;

$(function() {
    $( "#tabs" ).tabs();
    reArrangeDiv();
	showDetailInfo();
});

// ON WINDOW RESIZED
$(window).resize(function() {
    reArrangeDiv();
});

reArrangeDiv = function()
{
    var offset = $('#divInfo').offset();
    
    var docHeight = $(window).height();
    if (document.body.clientHeight)
        docHeight = document.body.clientHeight; //To get the browser height in IE
        
    var divHeight = docHeight - offset.top - 20;
    
    $('#divInfo').css({height : divHeight});
    $('#divComment').css({height : divHeight});
}

checkCommentLength = function(ix) {
    
    var msg = $("#comment_" + ix).val();
	var length = $.trim(msg).length;
	var remain = maxCommentLength - length;
	if (remain < 0)
	{
		remain = 0;
		msg = msg.substring(0, maxCommentLength);
	}
	$("#comment_" + ix).val(msg);
    
}

addCommentBox = function() {
    
    var lastComment = $("#comment_" + cmtBoxCnt).val();
    if ($.trim(lastComment).length == 0)
        return;
    
    cmtBoxCnt += 1;
    
    var row = "";
    row += "<tr><td align='left' valign='top'>";
    row += "<textarea id='comment_" + cmtBoxCnt + "' name='comment_" + cmtBoxCnt + "'";
    row += "    cols='40' rows='2' class='inputbox'";
    row += "    onkeyup='checkCommentLength(" + cmtBoxCnt + ")'></textarea>";
    row += "</td></tr>";
    
    $("#cmtBoxTable > tbody:last").append(row);
    $("#ncommentbox").val(cmtBoxCnt);
    
}

changeNewLine = function(text)
{
	
	return  text.replace(/\r?\n|\r/g, "[{*@NL#$]}");
	
}


saveComment = function() {
    
    var nEmpty = 0;
    for(var i = 1; i <= cmtBoxCnt; i++)
    {
        var cmt = $.trim($("#comment_" + i).val());
        if (cmt.length == 0)
            nEmpty += 1;
    }
    
    if (nEmpty == cmtBoxCnt)
        return;
    
    var idsurat = $("#idsurat").val();
    
    var formData = new FormData();
    formData.append("idsurat", idsurat);
    
    var ncomment = 0;
    for(var i = 1; i <= cmtBoxCnt; i++)
    {
        var comment = $.trim($("#comment_" + i).val());
        if (comment.length == 0)
            continue;
        
		comment = changeNewLine(comment);
		
        ncomment += 1;
        formData.append("comment_" + ncomment, comment);
    }
    formData.append("ncomment", ncomment);
    
    $.ajax({
        url: "letter.view.comment.save.php",
        type: 'POST',
        data: formData,
        async: false,
		cache: false,
        contentType: false,
        processData: false,
        success: function (html) {
            
            $.ajax({
                url: "letter.view.comment.ajax.php",
                type: "POST",
                data: "op=reloadcommentbox&idsurat="+idsurat,
                success: function (html) {
                    cmtBoxCnt = 1;
                    $('#divAddComment').empty().html(html);
                }
            });
            
            var maxcommentid = $("#maxCommentId").val();
            $.ajax({
                url: "letter.view.comment.ajax.php",
                type: "POST",
                data: "op=shownewcomment&idsurat="+idsurat+"&maxcommentid="+maxcommentid,
                success: function (html) {
                    $("#cmtList > tbody:last").append(html);
                    
                    $.ajax({
                        url: "letter.view.comment.ajax.php",
                        type: "POST",
                        data: "op=getmaxcommentid&idsurat="+idsurat,
                        success: function (html) {
                            $("#maxCommentId").val(html);
							contentChange = true; // the content is change
                        }
                    });
                }
            });
            
        },
		error: function (xhr, response, error)
		{
            $("#saveInfo").text(xhr.responseText);
		}
    });
}

showEmoticons = function()
{
	if (emoticonsDialog == undefined)
	{
		emoticonsDialog = $jQueryParent("#emoticonsDialog");
		emoticonsDialog.dialog({
			autoOpen: false,
			position: 'center',
			height: 500,
			width: 600,
			modal: true,
			buttons: {
				'Tutup': function() {
					$jQueryParent("#emoticonsDialog").dialog('close');
				}
			},
			close: function() {
				
			}
		});
	}
    
    $.ajax({
        type: "POST",
        url: "emoticons.php",
        success: function(html) {
            emoticonsDialog.html(html);
            emoticonsDialog.dialog('option', 'title', 'Emoticons');
            emoticonsDialog.dialog('open');
        },
        error: function(xhr, ajaxOptions, thrownError) {
            alert(xhr.responseText);
        }
	})
	
	
}

showPrevComment = function(idsurat) {
    
    $.ajax({
		type: 'POST',
		url: 'letter.view.comment.ajax.php?op=showprevcomment&idsurat='+idsurat,
		success: function(html) {			
            $("#cmtList > thead").empty().append(html);
		},
		error: function(xhr, options, error) {
			$("#cmtList > thead").empty().append(xhr.responseText);
		}
	});
    
}

showDeleteCommentDialog = function(replid, rowid) {
	
	if (!confirm("Apakah anda akan menghapus komentar ini"))
		return;
	
    $.ajax({
        url: "letter.view.comment.ajax.php",
        type: "POST",
        data: "op=deletecomment&replid="+replid+"&rowid="+rowid,
        success: function (html) {
            $("#" + rowid).remove();
        },
        error: function(xhr, ajaxOptions, thrownError) {
            alert(xhr.responseText);
        }
    });
}

showDetailInfo = function()
{
	var idsurat = $('#idsurat').val();
	
	$.ajax({
        url: "letter.view.info.php",
        type: "POST",
        data: "idsurat="+idsurat,
        success: function (html) {
            $('#divInfo').html(html);
        },
        error: function(xhr, ajaxOptions, thrownError) {
            alert(xhr.responseText);
        }
    });
}