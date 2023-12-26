// ON STARTUP
var not_CmtBoxCnt = 1;
var not_view_ContentChange = false;
var notview_Caller = ""; // noteslist OR notesindex

notview_SetCaller = function(caller) {
	
	notview_Caller = caller;
	
}

not_NotesView_InitDialogComment = function() {
    
	$not_jQueryParent("#not_EditNotes_Dialog").dialog({
        autoOpen: false,
        position: 'center',
		height: 220,
		width: 480,
		modal: true,
        buttons: {
            'Login': function() {
                not_ShowEditNotes();
            },
            'Batal': function() {
                $not_jQueryParent("#not_EditNotes_Dialog").dialog('close');
            }
        },
        close: function() {
            
        }
    });
		
	$not_jQueryParent("#not_DelNotes_Dialog").dialog({
        autoOpen: false,
        position: 'center',
		height: 220,
		width: 480,
		modal: true,
        buttons: {
            'Login': function() {
                not_view_ValidateDeleteNotes();
            },
            'Batal': function() {
                $not_jQueryParent("#not_DelNotes_Dialog").dialog('close');
            }
        },
        close: function() {
            
        }
    });
	
    $not_jQueryParent("#not_DelCmt_Dialog").dialog({
        autoOpen: false,
        position: 'center',
		height: 220,
		width: 480,
		modal: true,
        buttons: {
            'Hapus': function() {
                not_DoDeleteComment();
            },
            'Batal': function() {
                $not_jQueryParent("#not_DelCmt_Dialog").dialog('close');
            }
        },
        close: function() {
            
        }
    });
}

not_InitNotesView = function () {
    
    initLytebox();
    
    not_NotesView_InitDialogComment();
	not_view_ContentChange = false;
    
}

not_DownloadDoc = function(replid, furl, name) {
    
	//newWindow(mypage,myname,w,h,features) {
	var title = 'NotesFile' + replid;
	var page = 'notes.view.forcedown.php?furl='+furl+'&name='+name;
	newWindow(page, title, 500, 500, "");
    //document.location.href = 'notes.view.forcedown.php?url='+url+'&name='+name;
}

not_ShowPrevComment = function(notesid) {
    
    $.ajax({
		type: 'POST',
		url: 'notes.view.ajax.php?op=showprevcomment&notesid='+notesid,
		success: function(html) {			
            $("#not_CmtList > thead").empty().append(html);
		},
		error: function(xhr, options, error) {
			$("#not_CmtList > thead").empty().append(xhr.responseText);
		}
	});
    
}

not_CheckCommentLength = function(ix) {
    
    var msg = $("#not_comment_" + ix).val();
	var length = $.trim(msg).length;
	var remain = not_MaxCommentLength - length;
	if (remain < 0)
	{
		remain = 0;
		msg = msg.substring(0, not_MaxCommentLength);
	}
	$("#not_comment_" + ix).val(msg);
    
}

not_AddCommentBox = function() {
    
    var lastComment = $("#not_comment_" + not_CmtBoxCnt).val();
    if ($.trim(lastComment).length == 0)
        return;
    
    not_CmtBoxCnt += 1;
    
    var row = "<tr><td align='left' valign='top'>";
    row += "<textarea id='not_comment_" + not_CmtBoxCnt + "' name='not_comment_" + not_CmtBoxCnt + "'";
    row += "    cols='60' rows='2' class='inputbox'";
    row += "    onkeyup='not_CheckCommentLength(" + not_CmtBoxCnt + ")'></textarea>";
    row += "</td></tr>";
    
    $("#not_CmtBoxTable > tbody:last").append(row);
    $("#not_ncommentbox").val(not_CmtBoxCnt);
    
}

not_SaveComment = function() {
    
    var nEmpty = 0;
    for(var i = 1; i <= not_CmtBoxCnt; i++)
    {
        var cmt = $.trim($("#not_comment_" + i).val());
        if (cmt.length == 0)
            nEmpty += 1;
    }
    
    if (nEmpty == not_CmtBoxCnt)
        return;
    
	var dept = $not_jQueryParent("#mad_departemen").val();
    var login = $.trim($("#not_view_Login").val());
    var password = $.trim($("#not_view_Password").val());
    var notesid = $("#not_notesid").val();
    
    if (login.length == 0 || password.length == 0)
        return;
    
    var formData = new FormData();
	formData.append("dept", dept);
	formData.append("login", login);
	formData.append("password", password);
    formData.append("notesid", notesid);
    
    var ncomment = 0;
    for(var i = 1; i <= not_CmtBoxCnt; i++)
    {
        var comment = $.trim($("#not_comment_" + i).val());
        if (comment.length == 0)
            continue;
        
		comment = mad_ChangeNewLine(comment);
		
        ncomment += 1;
        formData.append("comment_" + ncomment, comment);
    }
    formData.append("ncomment", ncomment);
    
    $.ajax({
        url: "notes.view.save.php",
        type: 'POST',
        data: formData,
        async: false,
		cache: false,
        contentType: false,
        processData: false,
        success: function (html) {
            
            $.ajax({
                url: "notes.view.ajax.php",
                type: "POST",
                data: "op=reloadcommentbox&notesid="+notesid,
                success: function (html) {
                    not_CmtBoxCnt = 1;
                    $('#not_divAddComment').empty().html(html);
                }
            });
            
            var maxcommentid = $("#not_MaxCommentId").val();
            $.ajax({
                url: "notes.view.ajax.php",
                type: "POST",
                data: "op=shownewcomment&notesid="+notesid+"&maxcommentid="+maxcommentid,
                success: function (html) {
                    $("#not_CmtList > tbody:last").append(html);
                    
                    $.ajax({
                        url: "notes.view.ajax.php",
                        type: "POST",
                        data: "op=getmaxcommentid&notesid="+notesid,
                        success: function (html) {
                            $("#not_MaxCommentId").val(html);
							not_view_ContentChange = true; // the content is change
                        }
                    });
                }
            });
            
        },
		error: function (xhr, response, error)
		{
            $("#not_SaveInfo").text(xhr.responseText);
		}
    });
}

not_ShowDeleteCommentDialog = function(replid, rowid) {
	
    $.ajax({
		type: "POST",
		url: "notes.view.delcmt.dialog.php",
		data: "replid="+replid+"&rowid="+rowid,
		success: function(html) {
			$not_jQueryParent("#not_DelCmt_Dialog").html(html);
			$not_jQueryParent("#not_DelCmt_Dialog").dialog('option', 'title', 'Hapus Komentar');
			$not_jQueryParent("#not_DelCmt_Dialog").dialog('open');
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(xhr.responseText);
        }
	})
}

not_view_ValidateDeleteNotes = function() {
	
	var notesid = $not_jQueryParent("#not_DelNotes_Notesid").val();
    var login = $.trim($not_jQueryParent("#not_DelNotes_Login").val());
    var password = $.trim($not_jQueryParent("#not_DelNotes_Password").val());
	
	if (login.length == 0 || password.length == 0)
        return;
    
    $.ajax({
        url: "notes.view.ajax.php",
        type: "POST",
        data: "op=deletenotes&notesid="+notesid+"&login="+login+"&password="+password,
        success: function (html) {
			$not_jQueryParent("#not_DelNotes_Dialog").dialog('close');
			
			not_view_ContentChange = true;
			//not_BackRefreshNotesList();
			notview_BackToCaller(notesid);
        },
        error: function(xhr, ajaxOptions, thrownError) {
            alert(xhr.responseText);
        }
    });
}

not_ShowEditNotes = function() {
	
	var notesid = $not_jQueryParent("#not_EditNotes_Notesid").val();
    var login = $.trim($not_jQueryParent("#not_EditNotes_Login").val());
    var password = $.trim($not_jQueryParent("#not_EditNotes_Password").val());
	
	if (login.length == 0 || password.length == 0)
        return;
    
    $.ajax({
        url: "notes.view.ajax.php",
        type: "POST",
        data: "op=editnotes&notesid="+notesid+"&login="+login+"&password="+password,
        success: function (html) {
			
            $not_jQueryParent("#not_EditNotes_Dialog").dialog('close');		
			$.ajax({
				url: "notes.edit.php",
				type: "POST",
				data: "notesid="+notesid,
				success: function (html) {
					not_ShowNotesEdit();
					$("#not_edit_content").html(html);
					
					not_edit_InitNotesEdit();
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

not_ShowEditNotesDialog = function(notesid) {
	
	$.ajax({
		type: "POST",
		url: "notes.view.edit.dialog.php",
		data: "notesid="+notesid,
		success: function(html) {
			$not_jQueryParent("#not_EditNotes_Dialog").html(html);
			$not_jQueryParent("#not_EditNotes_Dialog").dialog('option', 'title', 'Ubah notes');
			$not_jQueryParent("#not_EditNotes_Dialog").dialog('open');
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(xhr.responseText);
        }
	})
}

not_ShowDeleteNotesDialog = function(notesid) {
	
	$.ajax({
		type: "POST",
		url: "notes.view.delete.dialog.php",
		data: "notesid="+notesid,
		success: function(html) {
			$not_jQueryParent("#not_DelNotes_Dialog").html(html);
			$not_jQueryParent("#not_DelNotes_Dialog").dialog('option', 'title', 'Hapus notes');
			$not_jQueryParent("#not_DelNotes_Dialog").dialog('open');
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(xhr.responseText);
        }
	})
}

not_DoDeleteComment = function() {
    
    var replid = $not_jQueryParent("#not_DelCmt_Replid").val();
    var rowid = $not_jQueryParent("#not_DelCmt_Rowid").val();
    var login = $.trim($not_jQueryParent("#not_DelCmt_Login").val());
    var password = $.trim($not_jQueryParent("#not_DelCmt_Password").val());
    
    if (login.length == 0 || password.length == 0)
        return;
    
    $.ajax({
        url: "notes.view.ajax.php",
        type: "POST",
        data: "op=deletecomment&replid="+replid+"&rowid="+rowid+"&login="+login+"&password="+password,
        success: function (html) {
            $("#" + rowid).remove();
            $not_jQueryParent("#not_DelCmt_Dialog").dialog('close');
        },
        error: function(xhr, ajaxOptions, thrownError) {
            alert(xhr.responseText);
        }
    });
}

not_SetViewHasChange = function() {
	
	not_view_ContentChange = true;
	
}

not_RefreshNotesView = function(notesid) {
	
	$.ajax({
		type: 'POST',
		url: 'notes.view.php',
		data: 'notesid='+notesid,
		success: function(html) {
			$('#not_view_content').html(html);
			
			not_InitNotesView();
			not_ScrollTopMadingPanel();
		},
		error: function(xhr, options, error) {
			alert(xhr.responseText);
		}
	});
}

not_BackRefreshNotesList = function(notesid) {
	
	if (not_view_ContentChange)
	{
		not_RefreshNotesList();
		not_ShowNotesList_TopPos();
	}
	else
	{
		not_RefreshNotesList();
		not_ShowNotesList_LastPos();
	}
}

notview_BackRefreshNotesIndex = function(notesid) {
	
	notidx_RefreshNotesIndex();
	not_ShowNotesIndex_LastPos();
	
}

notview_BackToCaller = function(notesid) {
	
	if (notview_Caller == "noteslist")
	{
		not_BackRefreshNotesList(notesid);
	}
	else
	{
		notview_BackRefreshNotesIndex(notesid);
	}
}