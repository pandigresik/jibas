vididx_ShowIndex = function() {
    
    var dept = vid_GetDepartemen();
    var bulan = $("#vididx_CbBulan").val();
    var tahun = $("#vididx_CbTahun").val();
    
    $.ajax({
        url: "video.index.ajax.php",
        data: "op=videoindex&dept="+dept+"&bulan="+bulan+"&tahun="+tahun,
        success: function(html) {
			$("#vididx_VideoIndexTableList > tbody").empty().append(html);
			vididx_InstallFlowplayer();
		},
		error: function(xhr, options, error) {
			alert(xhr.responseText);
		}
    })
}

vididx_InstallFlowplayer = function() {
	
	var divName = ".vididx_preview";
	if ($(divName).length > 0)
	{
		try
		{
			$(divName).flowplayer({
				disabled: true
			});		
		}
		catch(e)
		{
			//alert(e);
			//vidlst_InstallFlowplayer();
		}
	}
	else
	{
		// ???
	}
}

vididx_ComboChange = function() {
    
    vididx_ShowIndex();
    
}

vididx_RefreshVideoIndex = function() {
    
    vididx_ShowIndex();
    
}

vididx_BackToVideoList = function() {
    
	$('#vididx_content').html("");
    vid_ShowVideoList_TopPos();
    
}

vididx_ViewVideo = function(videoid) {
    
    $.ajax({
		type: 'POST',
		url: 'video.view.php',
		data: 'videoid='+videoid,
		success: function(html) {
			
            vidvw_SetCaller('videoindex');
            
            vid_ShowVideoView();
			$('#vidvw_content').html(html);
			$('#vididx_content').html("");
			
			vidvw_InitVideoView();
            vid_ScrollTopMadingPanel();
		},
		error: function(xhr, options, error) {
			alert(xhr.responseText);
		}
	});
    
}