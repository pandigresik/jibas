var galvw_CmtBoxCnt = 1;
var galvw_ContentChange = false;
var galvw_Caller = ""; // gallerylist OR galleryindex

galvw_SetCaller = function(caller) {
	
	galvw_Caller = caller;
	
}

galvw_SetViewHasChange = function() {
	
	galvw_ContentChange = true;
	
}

galvw_InitDialogBox = function() {
	
	$gal_jQueryParent("#ifse_galvw_EditGallery_Dialog").dialog({
        autoOpen: false,
        position: 'center',
		height: 220,
		width: 480,
		modal: true,
        buttons: {
            'Login': function() {
                galvw_ShowEditGallery();
            },
            'Batal': function() {
                $gal_jQueryParent("#ifse_galvw_EditGallery_Dialog").dialog('close');
            }
        },
        close: function() {
            
        }
    });
    
    $gal_jQueryParent("#ifse_galvw_DelCmt_Dialog").dialog({
        autoOpen: false,
        position: 'center',
		height: 220,
		width: 480,
		modal: true,
        buttons: {
            'Hapus': function() {
                galvw_DoDeleteComment();
            },
            'Batal': function() {
                $gal_jQueryParent("#ifse_galvw_DelCmt_Dialog").dialog('close');
            }
        },
        close: function() {
            
        }
    });
	
	$gal_jQueryParent("#ifse_galvw_DelGallery_Dialog").dialog({
        autoOpen: false,
        position: 'center',
		height: 220,
		width: 480,
		modal: true,
        buttons: {
            'Login': function() {
                galvw_ValidateDeleteGallery();
            },
            'Batal': function() {
                $gal_jQueryParent("#ifse_galvw_DelGallery_Dialog").dialog('close');
            }
        },
        close: function() {
            
        }
    });
	
    
}

galvw_InitGalleryView = function () {
    
    initLytebox();
    
    galvw_InitDialogBox();
    
}

galvw_ShowPrevComment = function(galleryid) {
    
    $.ajax({
		type: 'POST',
		url: 'galeri.view.ajax.php?op=showprevcomment&galleryid='+galleryid,
		success: function(html) {			
            $("#galvw_CmtList > thead").empty().append(html);
		},
		error: function(xhr, options, error) {
			$("#galvw_CmtList > thead").empty().append(xhr.responseText);
		}
	});
    
}

galvw_ShowDeleteGalleryDialog = function(galleryid) {
	
	$.ajax({
		type: "POST",
		url: "galeri.view.delete.dialog.php",
		data: "galleryid="+galleryid,
		success: function(html) {
			$gal_jQueryParent("#ifse_galvw_DelGallery_Dialog").html(html);
			$gal_jQueryParent("#ifse_galvw_DelGallery_Dialog").dialog('option', 'title', 'Hapus galeri');
			$gal_jQueryParent("#ifse_galvw_DelGallery_Dialog").dialog('open');
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(xhr.responseText);
        }
	})
}

galvw_ValidateDeleteGallery = function() {
	
	var galleryid = $gal_jQueryParent("#galdel_GalleryId").val();
    var login = $.trim($gal_jQueryParent("#galdel_Login").val());
    var password = $.trim($gal_jQueryParent("#galdel_Password").val());
	
	if (login.length == 0 || password.length == 0)
        return;
	    
    $.ajax({
        url: "galeri.view.ajax.php",
        type: "POST",
        data: "op=deletegallery&galleryid="+galleryid+"&login="+login+"&password="+password,
        success: function (html) {
			//$("#galvw_content").html(html);
			
			$gal_jQueryParent("#ifse_galvw_DelGallery_Dialog").dialog('close');
			
			galvw_ContentChange = true;
			galvw_BackToCaller(galleryid);
        },
        error: function(xhr, ajaxOptions, thrownError) {
            alert(xhr.responseText);
        }
    });
}

galvw_ShowEditGalleryDialog = function(galleryid) {
	
	$.ajax({
		type: "POST",
		url: "galeri.view.edit.dialog.php",
		data: "galleryid="+galleryid,
		success: function(html) {
			$gal_jQueryParent("#ifse_galvw_EditGallery_Dialog").html(html);
			$gal_jQueryParent("#ifse_galvw_EditGallery_Dialog").dialog('option', 'title', 'Ubah galeri');
			$gal_jQueryParent("#ifse_galvw_EditGallery_Dialog").dialog('open');
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(xhr.responseText);
        }
	})
}

galvw_ShowEditGallery = function() {
	
	var galleryid = $gal_jQueryParent("#galed_GalleryId").val();
    var login = $.trim($gal_jQueryParent("#galed_Login").val());
    var password = $.trim($gal_jQueryParent("#galed_Password").val());
	
	if (login.length == 0 || password.length == 0)
        return;
    
    $.ajax({
        url: "galeri.view.ajax.php",
        type: "POST",
        data: "op=editgallery&galleryid="+galleryid+"&login="+login+"&password="+password,
        success: function (html) {

            $gal_jQueryParent("#ifse_galvw_EditGallery_Dialog").dialog('close');
			
			$.ajax({
				url: "galeri.edit.php",
				type: "POST",
				data: "galleryid="+galleryid,
				success: function (html) {
					gal_ShowGalleryEdit();
					$("#galed_content").html(html);
					
					galed_InitGalleryEdit();
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

galvw_CheckCommentLength = function(ix) {
    
    var msg = $("#galvw_comment_" + ix).val();
	var length = $.trim(msg).length;
	var remain = galinp_MaxCommentLength - length;
	if (remain < 0)
	{
		remain = 0;
		msg = msg.substring(0, galinp_MaxCommentLength);
	}
	$("#galvw_comment_" + ix).val(msg);
    
}

galvw_AddCommentBox = function() {
    
    var lastComment = $("#galvw_comment_" + galvw_CmtBoxCnt).val();
    if ($.trim(lastComment).length == 0)
        return;
    
    galvw_CmtBoxCnt += 1;
    
    var row = "<tr><td align='left' valign='top'>";
    row += "<textarea id='galvw_comment_" + galvw_CmtBoxCnt + "' name='galvw_comment_" + galvw_CmtBoxCnt + "'";
    row += "    cols='60' rows='2' class='inputbox'";
    row += "    onkeyup='galvw_CheckCommentLength(" + galvw_CmtBoxCnt + ")'></textarea>";
    row += "</td></tr>";
    
    $("#galvw_CmtBoxTable > tbody:last").append(row);
    $("#galvw_ncommentbox").val(galvw_CmtBoxCnt);
    
}

galvw_SaveComment = function() {
    
    var nEmpty = 0;
    for(var i = 1; i <= galvw_CmtBoxCnt; i++)
    {
        var cmt = $.trim($("#galvw_comment_" + i).val());
        if (cmt.length == 0)
            nEmpty += 1;
    }
    
    if (nEmpty == galvw_CmtBoxCnt)
        return;
    
	var dept = $gal_jQueryParent("#ifse_departemen").val();
    var login = $.trim($("#galvw_Login").val());
    var password = $.trim($("#galvw_Password").val());
    var galleryid = $("#galvw_galleryid").val();
    
    if (login.length == 0 || password.length == 0)
        return;
    
    var formData = new FormData();
	formData.append("dept", dept);
	formData.append("login", login);
	formData.append("password", password);
    formData.append("galleryid", galleryid);
    
    var ncomment = 0;
    for(var i = 1; i <= galvw_CmtBoxCnt; i++)
    {
        var comment = $.trim($("#galvw_comment_" + i).val());
        if (comment.length == 0)
            continue;
		
		comment = ifse_ChangeNewLine(comment);
        
        ncomment += 1;
        formData.append("comment_" + ncomment, comment);
    }
    formData.append("ncomment", ncomment);
    
    $.ajax({
        url: "galeri.view.save.php",
        type: 'POST',
        data: formData,
        async: false,
		cache: false,
        contentType: false,
        processData: false,
        success: function (html) {
            
            $.ajax({
                url: "galeri.view.ajax.php",
                type: "POST",
                data: "op=reloadcommentbox&galleryid="+galleryid,
                success: function (html) {
                    galvw_CmtBoxCnt = 1;
                    $('#galvw_divAddComment').empty().html(html);
                }
            });
			
			var maxcommentid = $("#galvw_MaxCommentId").val();
            $.ajax({
                url: "galeri.view.ajax.php",
                type: "POST",
                data: "op=shownewcomment&galleryid="+galleryid+"&maxcommentid="+maxcommentid,
                success: function (html) {
                    $("#galvw_CmtList > tbody:last").append(html);
                    
                    $.ajax({
                        url: "galeri.view.ajax.php",
                        type: "POST",
                        data: "op=getmaxcommentid&galleryid="+galleryid,
                        success: function (html) {
                            $("#galvw_MaxCommentId").val(html);
							galvw_ContentChange = true; // the content is change
                        }
                    });
                }
            });
        },
		error: function (xhr, response, error)
		{
            $("#galvw_SaveInfo").text(xhr.responseText);
		}
    });
}

galvw_ShowDeleteCommentDialog = function(replid, rowid) {
	
    $.ajax({
		type: "POST",
		url: "galeri.view.delcmt.dialog.php",
		data: "replid="+replid+"&rowid="+rowid,
		success: function(html) {
			$gal_jQueryParent("#ifse_galvw_DelCmt_Dialog").html(html);
			$gal_jQueryParent("#ifse_galvw_DelCmt_Dialog").dialog('option', 'title', 'Hapus Komentar');
			$gal_jQueryParent("#ifse_galvw_DelCmt_Dialog").dialog('open');
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(xhr.responseText);
        }
	})
}

galvw_DoDeleteComment = function() {
    
    var replid = $gal_jQueryParent("#galvw_DelCmt_Replid").val();
    var rowid = $gal_jQueryParent("#galvw_DelCmt_Rowid").val();
    var login = $.trim($gal_jQueryParent("#galvw_DelCmt_Login").val());
    var password = $.trim($gal_jQueryParent("#galvw_DelCmt_Password").val());
    
    if (login.length == 0 || password.length == 0)
        return;
    
    $.ajax({
        url: "galeri.view.ajax.php",
        type: "POST",
        data: "op=deletecomment&replid="+replid+"&rowid="+rowid+"&login="+login+"&password="+password,
        success: function (html) {
            $("#" + rowid).remove();
            $gal_jQueryParent("#ifse_galvw_DelCmt_Dialog").dialog('close');
        },
        error: function(xhr, ajaxOptions, thrownError) {
            alert(xhr.responseText);
        }
    });
}

galvw_RefreshGalleryView = function(galleryid) {
	
	$.ajax({
		type: 'POST',
		url: 'galeri.view.php',
		data: 'galleryid='+galleryid+'&nocount=1',
		success: function(html) {
			$('#galvw_content').html(html);
			
			galvw_InitGalleryView();
			gal_ScrollTopMadingPanel();
		},
		error: function(xhr, options, error) {
			alert(xhr.responseText);
		}
	});
}


galvw_BackRefreshGalleryList = function(galleryid) {
	
	gallst_RefreshGalleryList();
	if (galvw_ContentChange)
		gal_ShowGalleryList_TopPos();
	else
		gal_ShowGalleryList_LastPos();

}

galvw_BackRefreshGalleryIndex = function(galleryid) {

	galidx_RefreshGalleryIndex();	
	if (galvw_ContentChange)
		gal_ShowGalleryIndex_LastPos();
	else
		gal_ShowGalleryIndex_TopPos();
	
}

galvw_BackToCaller = function(galleryid) {
	
	if (galvw_Caller == "gallerylist")
	{
		galvw_BackRefreshGalleryList(galleryid);
	}
	else
	{
		galvw_BackRefreshGalleryIndex(galleryid);
	}
}

$( document ).ready(function() {

	galvw_InitGalleryView();
    
    
    //var nimage = $("#galvw_nimage").val();
    //alert("ahem " + nimage);
	
});