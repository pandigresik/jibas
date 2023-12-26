// ON STARTUP
var vidlst_StartRow = 0;
var vidlst_GroupPage = 0;

vidlst_GetVideoList = function() {
	
	var dept = vid_GetDepartemen(); //$('#mad_departemen').val();
	$.ajax({
		type: 'POST',
		url: 'video.list.ajax.php?op=getlist&dept='+dept+'&start='+vidlst_StartRow+"&group="+vidlst_GroupPage,
		success: function(html) {
			if (html.indexOf('~~#$@**') >= 0)
			{
				var content = html.split('~~#$@**');
				$("#vidlst_VideoTableList > tbody:last").append(content[0]);
				$("#vidlst_VideoTableList > tfoot").empty().append(content[1]);	
			}
			else
			{
				$("#vidlst_VideoTableList > tbody:last").append(html);
				$("#vidlst_VideoTableList > tfoot").empty();
			}
			
			//setTimeout(function(){vidlst_InstallFlowplayer()}, 1000);
			vidlst_InstallFlowplayer();
			
			vidlst_StartRow += vidlst_RowPerPage;
		},
		error: function(xhr, options, error) {
			alert(xhr.responseText);
		}
	});
}

vidlst_RefreshVideoList = function() {
	
	var dept = vid_GetDepartemen(); //$('#mad_departemen').val();
	$.ajax({
		type: 'POST',
		url: 'video.list.ajax.php?op=refreshlist&dept='+dept+'&currrow='+vidlst_StartRow+"&group="+vidlst_GroupPage,
		success: function(html) {
			if (html.indexOf('~~#$@**') >= 0)
			{
				var content = html.split('~~#$@**');
				$("#vidlst_VideoTableList > tbody").empty().append(content[0]);
				$("#vidlst_VideoTableList > tfoot").empty().append(content[1]);	
			}
			else
			{
				$("#vidlst_VideoTableList > tbody").empty().append(html);
				$("#vidlst_VideoTableList > tfoot").empty();
			}
			
			//setTimeout(function(){vidlst_InstallFlowplayer()}, 100);
			vidlst_InstallFlowplayer();
		},
		error: function(xhr, options, error) {
			alert(xhr.responseText);
		}
	});
}

vidlst_InstallFlowplayer = function() {
	
	//alert("About Install Flowplayer");
	
	var divName = ".vidlst_group_" + vidlst_GroupPage;
	if ($(divName).length > 0)
	{
		//alert(divName + " EXISTS");
		
		try
		{
			$(divName).flowplayer({
				disabled: true
			});
			
			//alert("Flowplayer Installed");
		
			vidlst_GroupPage += 1;
		}
		catch(e)
		{
			//alert(e);
			//vidlst_InstallFlowplayer();
		}
	}
	else
	{
		//alert(divName + " WHEREEEEEE ??????????");
	}
}

vidlst_ViewVideo = function(videoid) {
	
	$.ajax({
		type: 'POST',
		url: 'video.view.php',
		data: 'videoid='+videoid,
		success: function(html) {
			
			vid_ShowVideoView();
			vid_ScrollTopMadingPanel();
			
			vidvw_SetCaller('videolist');			
			$('#vidvw_content').html(html);
		
			vidvw_InitVideoView();
		},
		error: function(xhr, options, error) {
			alert(xhr.responseText);
		}
	});
	
}

vidlst_NewVideoClicked = function() {
	
	$.ajax({
		type: 'POST',
		url: 'video.input.php',
		success: function(html) {
			vid_ShowVideoInput();
			
			$('#vidinp_content').html(html);
			vid_ScrollTopMadingPanel();
		},
		error: function(xhr, options, error) {
			alert(xhr.responseText);
		}
	});
	
}

vidlst_ShowVideoIndexClicked = function() {
	
	$.ajax({
		type: 'POST',
		url: 'video.index.php',
		success: function(html) {
			vid_ShowVideoIndex_TopPos();
			
			$('#vididx_content').html(html);
		},
		error: function(xhr, options, error) {
			alert(xhr.responseText);
		}
	});
	
}