var vidvw_CmtBoxCnt = 1;
var vidvw_ContentChange = false;
var vidvw_Caller = ""; // videolist OR videoindex

vidvw_SetViewHasChange = function() {
	
	vidvw_ContentChange = true;
	
}

vidvw_RefreshVideoView = function(videoid) {
	
	$.ajax({
		type: 'POST',
		url: 'video.view.php',
		data: 'videoid='+videoid+'&nocount=1',
		success: function(html) {
			$('#vidvw_content').html(html);
			
			vidvw_InitVideoView();
			vid_ScrollTopMadingPanel();
		},
		error: function(xhr, options, error) {
			alert(xhr.responseText);
		}
	});
	
}

vidvw_InitVideoView = function() {
	
	vidvw_InitDialogBox();
	
	// install flowplayer to an element with CSS class "player"
    $("#vidvw_player").flowplayer({
        //disabled: true
		volume: 1
    });
	
}

vidvw_InitDialogBox = function() {
	
	$vid_jQueryParent("#vidvw_DelCmt_Dialog").dialog({
        autoOpen: false,
        position: 'center',
		height: 220,
		width: 480,
		modal: true,
        buttons: {
            'Hapus': function() {
                vidvw_DoDeleteComment();
            },
            'Batal': function() {
                $vid_jQueryParent("#vidvw_DelCmt_Dialog").dialog('close');
            }
        },
        close: function() {
            
        }
    });
	
	$vid_jQueryParent("#vidvw_EditVideo_Dialog").dialog({
        autoOpen: false,
        position: 'center',
		height: 220,
		width: 480,
		modal: true,
        buttons: {
            'Login': function() {
                vidvw_ShowEditVideo();
            },
            'Batal': function() {
                $vid_jQueryParent("#vidvw_EditVideo_Dialog").dialog('close');
            }
        },
        close: function() {
            
        }
    });

	$vid_jQueryParent("#vidvw_DelVideo_Dialog").dialog({
        autoOpen: false,
        position: 'center',
		height: 220,
		width: 480,
		modal: true,
        buttons: {
            'Login': function() {
                vidvw_ValidateDeleteVideo();
            },
            'Batal': function() {
                $vid_jQueryParent("#vidvw_DelVideo_Dialog").dialog('close');
            }
        },
        close: function() {
            
        }
    });
    
}

vidvw_ShowDeleteVideoDialog = function(videoid) {
	
	$.ajax({
		type: "POST",
		url: "video.view.delete.dialog.php",
		data: "videoid="+videoid,
		success: function(html) {
			$vid_jQueryParent("#vidvw_DelVideo_Dialog").html(html);
			$vid_jQueryParent("#vidvw_DelVideo_Dialog").dialog('option', 'title', 'Hapus video');
			$vid_jQueryParent("#vidvw_DelVideo_Dialog").dialog('open');
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(xhr.responseText);
        }
	})
}

vidvw_ValidateDeleteVideo = function() {
	
	var videoid = $vid_jQueryParent("#viddel_VideoId").val();
    var login = $.trim($vid_jQueryParent("#viddel_Login").val());
    var password = $.trim($vid_jQueryParent("#viddel_Password").val());
	
	if (login.length == 0 || password.length == 0)
        return;
	    
    $.ajax({
        url: "video.view.ajax.php",
        type: "POST",
        data: "op=deletevideo&videoid="+videoid+"&login="+login+"&password="+password,
        success: function (html) {
			$("#vidvw_content").html(html);
		
			$vid_jQueryParent("#vidvw_DelVideo_Dialog").dialog('close');
			
			vidvw_ContentChange = true;
			vidvw_BackToCaller(videoid);
        },
        error: function(xhr, ajaxOptions, thrownError) {
            alert(xhr.responseText);
        }
    });
}

vidvw_ShowEditVideoDialog = function(videoid) {
	
	$.ajax({
		type: "POST",
		url: "video.view.edit.dialog.php",
		data: "videoid="+videoid,
		success: function(html) {
			$vid_jQueryParent("#vidvw_EditVideo_Dialog").html(html);
			$vid_jQueryParent("#vidvw_EditVideo_Dialog").dialog('option', 'title', 'Ubah video');
			$vid_jQueryParent("#vidvw_EditVideo_Dialog").dialog('open');
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(xhr.responseText);
        }
	})
}

vidvw_ShowEditVideo = function() {
	
	var videoid = $vid_jQueryParent("#vided_VideoId").val();
    var login = $.trim($vid_jQueryParent("#vided_Login").val());
    var password = $.trim($vid_jQueryParent("#vided_Password").val());
	
	if (login.length == 0 || password.length == 0)
        return;
    
    $.ajax({
        url: "video.view.ajax.php",
        type: "POST",
        data: "op=editvideo&videoid="+videoid+"&login="+login+"&password="+password,
        success: function (html) {

            $vid_jQueryParent("#vidvw_EditVideo_Dialog").dialog('close');
			
			var player = document.getElementById("vidvw_player");
			flowplayer(player).stop();
			
			$.ajax({
				url: "video.edit.php",
				type: "POST",
				data: "videoid="+videoid,
				success: function (html) {
					vid_ShowVideoEdit();
					$("#vided_content").html(html);
					
					vided_InitVideoEdit();
				},
				error: function(xhr, ajaxOptions, thrownError) {
					alert(xhr.responseText);
				}
			});
        },
        error: function(xhr, ajaxOptions, thrownError) {
            alert(xhr.responseText);
        }
    });
}

vidvw_ShowDeleteCommentDialog = function(replid, rowid) {
	
    $.ajax({
		type: "POST",
		url: "video.view.delcmt.dialog.php",
		data: "replid="+replid+"&rowid="+rowid,
		success: function(html) {
			$vid_jQueryParent("#vidvw_DelCmt_Dialog").html(html);
			$vid_jQueryParent("#vidvw_DelCmt_Dialog").dialog('option', 'title', 'Hapus Komentar');
			$vid_jQueryParent("#vidvw_DelCmt_Dialog").dialog('open');
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(xhr.responseText);
        }
	})
}


vidvw_DoDeleteComment = function() {
    
    var replid = $vid_jQueryParent("#vidvw_DelCmt_Replid").val();
    var rowid = $vid_jQueryParent("#vidvw_DelCmt_Rowid").val();
    var login = $.trim($vid_jQueryParent("#vidvw_DelCmt_Login").val());
    var password = $.trim($vid_jQueryParent("#vidvw_DelCmt_Password").val());
    
    if (login.length == 0 || password.length == 0)
        return;
    
    $.ajax({
        url: "video.view.ajax.php",
        type: "POST",
        data: "op=deletecomment&replid="+replid+"&rowid="+rowid+"&login="+login+"&password="+password,
        success: function (html) {
            $("#" + rowid).remove();
            $vid_jQueryParent("#vidvw_DelCmt_Dialog").dialog('close');
        },
        error: function(xhr, ajaxOptions, thrownError) {
            alert(xhr.responseText);
        }
    });
}

vidvw_CheckCommentLength = function(ix) {
    
    var msg = $("#vidvw_comment_" + ix).val();
	var length = $.trim(msg).length;
	var remain = vidinp_MaxCommentLength - length;
	if (remain < 0)
	{
		remain = 0;
		msg = msg.substring(0, vidinp_MaxCommentLength);
	}
	$("#vidvw_comment_" + ix).val(msg);
    
}

vidvw_AddCommentBox = function() {
    
    var lastComment = $("#vidvw_comment_" + vidvw_CmtBoxCnt).val();
    if ($.trim(lastComment).length == 0)
        return;
    
    vidvw_CmtBoxCnt += 1;
    
    var row = "<tr><td align='left' valign='top'>";
    row += "<textarea id='vidvw_comment_" + vidvw_CmtBoxCnt + "' name='vidvw_comment_" + vidvw_CmtBoxCnt + "'";
    row += "    cols='60' rows='2' class='inputbox'";
    row += "    onkeyup='vidvw_CheckCommentLength(" + vidvw_CmtBoxCnt + ")'></textarea>";
    row += "</td></tr>";
    
    $("#vidvw_CmtBoxTable > tbody:last").append(row);
    $("#vidvw_ncommentbox").val(vidvw_CmtBoxCnt);
    
}

vidvw_ShowPrevComment = function(videoid) {
    
    $.ajax({
		type: 'POST',
		url: 'video.view.ajax.php?op=showprevcomment&videoid='+videoid,
		success: function(html) {			
            $("#vidvw_CmtList > thead").empty().append(html);
		},
		error: function(xhr, options, error) {
			$("#vidvw_CmtList > thead").empty().append(xhr.responseText);
		}
	});
    
}

vidvw_SaveComment = function() {
    
    var nEmpty = 0;
    for(var i = 1; i <= vidvw_CmtBoxCnt; i++)
    {
        var cmt = $.trim($("#vidvw_comment_" + i).val());
        if (cmt.length == 0)
            nEmpty += 1;
    }
    
    if (nEmpty == vidvw_CmtBoxCnt)
        return;
    
	var dept = $vid_jQueryParent("#mad_departemen").val();
    var login = $.trim($("#vidvw_Login").val());
    var password = $.trim($("#vidvw_Password").val());
    var videoid = $("#vidvw_videoid").val();
    
    if (login.length == 0 || password.length == 0)
        return;
    
    var formData = new FormData();
	formData.append("dept", dept);
	formData.append("login", login);
	formData.append("password", password);
    formData.append("videoid", videoid);
    
    var ncomment = 0;
    for(var i = 1; i <= vidvw_CmtBoxCnt; i++)
    {
        var comment = $.trim($("#vidvw_comment_" + i).val());
        if (comment.length == 0)
            continue;
        
		comment = mad_ChangeNewLine(comment);
		
        ncomment += 1;
        formData.append("comment_" + ncomment, comment);
    }
    formData.append("ncomment", ncomment);
    
    $.ajax({
        url: "video.view.save.php",
        type: 'POST',
        data: formData,
        async: false,
		cache: false,
        contentType: false,
        processData: false,
        success: function (html) {
            
            $.ajax({
                url: "video.view.ajax.php",
                type: "POST",
                data: "op=reloadcommentbox&videoid="+videoid,
                success: function (html) {
                    vidvw_CmtBoxCnt = 1;
                    $('#vidvw_divAddComment').empty().html(html);
                }
            });
			
			var maxcommentid = $("#vidvw_MaxCommentId").val();
            $.ajax({
                url: "video.view.ajax.php",
                type: "POST",
                data: "op=shownewcomment&videoid="+videoid+"&maxcommentid="+maxcommentid,
                success: function (html) {
                    $("#vidvw_CmtList > tbody:last").append(html);
                    
                    $.ajax({
                        url: "video.view.ajax.php",
                        type: "POST",
                        data: "op=getmaxcommentid&videoid="+videoid,
                        success: function (html) {
                            $("#vidvw_MaxCommentId").val(html);
							vidvw_ContentChange = true; // the content is change
                        }
                    });
                }
            });
        },
		error: function (xhr, response, error)
		{
            $("#vidvw_SaveInfo").text(xhr.responseText);
		}
    });
}

vidvw_BackRefreshVideoList = function(videoid) {
	
	vidlst_RefreshVideoList();
	if (vidvw_ContentChange)
		vid_ShowVideoList_TopPos();
	else
		vid_ShowVideoList_LastPos();

}

vidvw_BackRefreshVideoIndex = function(videoid) {

	vididx_RefreshVideoIndex();	
	if (vidvw_ContentChange)
		vid_ShowVideoIndex_LastPos();
	else
		vid_ShowVideoIndex_TopPos();
	
}

vidvw_BackToCaller = function(videoid) {
	
	if (vidvw_Caller == "videolist")
		vidvw_BackRefreshVideoList(videoid);
	else
		vidvw_BackRefreshVideoIndex(videoid);
		
	var player = document.getElementById("vidvw_player");	
	flowplayer(player).stop();
	//flowplayer(player).mute(true);	
	//flowplayer("*").stop();	
}

vidvw_ShowDebugVideo = function()
{
	vidvw_InitVideoView();
}

vidvw_SetCaller = function(caller) {
	
	vidvw_Caller = caller;
	
}

$( document ).ready(function() {

	vidvw_InitVideoView();
    
});
