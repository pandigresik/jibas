// ON STARTUP
var gallst_StartRow = 0;

gallst_GetGalleryList = function() {
	
	var dept = gal_GetDepartemen(); 
	$.ajax({
		type: 'POST',
		url: 'galeri.list.ajax.php?op=getlist&dept='+dept+'&start='+gallst_StartRow,
		success: function(html) {
			if (html.indexOf('~~#$@**') >= 0)
			{
				var content = html.split('~~#$@**');
				$("#gallst_GalleryListTable > tbody:last").append(content[0]);
				$("#gallst_GalleryListTable > tfoot").empty().append(content[1]);	
			}
			else
			{
				$("#gallst_GalleryListTable > tbody:last").append(html);
				$("#gallst_GalleryListTable > tfoot").empty();
			}
			
			gallst_StartRow += gallst_RowPerPage;
		},
		error: function(xhr, options, error) {
			alert(xhr.responseText);
		}
	});
}

gallst_ViewGallery = function(galleryid) {
	
	$.ajax({
		type: 'POST',
		url: 'galeri.view.php',
		data: 'galleryid='+galleryid,
		success: function(html) {
			
			gal_ShowGalleryView();
			gal_ScrollTopMadingPanel();
			
			galvw_SetCaller('gallerylist');
			$('#galvw_content').html(html);
		
			galvw_InitGalleryView();
		},
		error: function(xhr, options, error) {
			alert(xhr.responseText);
		}
	});
	
}

gallst_NewGalleryClicked = function() {
	
	$.ajax({
		type: 'POST',
		url: 'galeri.input.php',
		success: function(html) {
			gal_ShowGalleryInput();
			
			$('#galinp_content').html(html);
			gal_ScrollTopMadingPanel();
		},
		error: function(xhr, options, error) {
			alert(xhr.responseText);
		}
	});
	
}

gallst_ShowGalleryIndexClicked = function() {
	
	$.ajax({
		type: 'POST',
		url: 'galeri.index.php',
		success: function(html) {
			gal_ShowGalleryIndex_TopPos();
			
			$('#galidx_content').html(html);
		},
		error: function(xhr, options, error) {
			alert(xhr.responseText);
		}
	});
	
}

gallst_RefreshGalleryList = function() {
	
	var dept = gal_GetDepartemen(); //$('#ifse_departemen').val();
	$.ajax({
		type: 'POST',
		url: 'galeri.list.ajax.php?op=refreshlist&dept='+dept+'&currrow='+gallst_StartRow,
		success: function(html) {
			if (html.indexOf('~~#$@**') >= 0)
			{
				var content = html.split('~~#$@**');
				$("#gallst_GalleryListTable > tbody").empty().append(content[0]);
				$("#gallst_GalleryListTable > tfoot").empty().append(content[1]);	
			}
			else
			{
				$("#gallst_GalleryListTable > tbody").empty().append(html);
				$("#gallst_GalleryListTable > tfoot").empty();
			}
		},
		error: function(xhr, options, error) {
			alert(xhr.responseText);
		}
	});
}