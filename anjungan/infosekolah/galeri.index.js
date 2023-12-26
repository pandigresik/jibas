galidx_ShowIndex = function() {
    
    var dept = gal_GetDepartemen();
    var bulan = $("#galidx_CbBulan").val();
    var tahun = $("#galidx_CbTahun").val();
    
    $.ajax({
        url: "galeri.index.ajax.php",
        data: "op=galleryindex&dept="+dept+"&bulan="+bulan+"&tahun="+tahun,
        success: function(html) {
			$("#galidx_GalleryIndexTableList > tbody").empty().append(html);
		},
		error: function(xhr, options, error) {
			alert(xhr.responseText);
		}
    })
}

galidx_ComboChange = function() {
    
    galidx_ShowIndex();
    
}

galidx_RefreshGalleryIndex = function() {
    
    galidx_ShowIndex();
    
}

galidx_BackToGalleryList = function() {
    
	$('#galidx_content').html("");
    gal_ShowGalleryList_TopPos();
    
}

galidx_ViewGallery = function(galleryid) {
    
    $.ajax({
		type: 'POST',
		url: 'galeri.view.php',
		data: 'galleryid='+galleryid,
		success: function(html) {
			
            galvw_SetCaller('galleryindex');
            
            gal_ShowGalleryView();
			$('#galvw_content').html(html);
			
            galvw_InitGalleryView();
            gal_ScrollTopMadingPanel();
            
		},
		error: function(xhr, options, error) {
			alert(xhr.responseText);
		}
	});
    
}