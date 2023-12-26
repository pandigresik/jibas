notidx_ShowNotesIndex = function() {
    
    var dept = not_GetDepartemen();
    var bulan = $("#notidx_CbBulan").val();
    var tahun = $("#notidx_CbTahun").val();
    
    $.ajax({
        url: "notes.index.ajax.php",
        data: "op=notesindex&dept="+dept+"&bulan="+bulan+"&tahun="+tahun,
        success: function(html) {
			$("#not_NotesIndexTableList > tbody").empty().append(html);
		},
		error: function(xhr, options, error) {
			alert(xhr.responseText);
		}
    })
}

notidx_BtShowClicked = function() {
    
    notidx_ShowNotesIndex();
    
}

notidx_ComboChange = function() {
    
    notidx_ShowNotesIndex();
    
}

notidx_ViewNotes = function(notesid) {
	
	$.ajax({
		type: 'POST',
		url: 'notes.view.php',
		data: 'notesid='+notesid,
		success: function(html) {
			
			notview_SetCaller('notesindex');
            
			not_ShowNotesView();
			$('#not_view_content').html(html);
			
			not_InitNotesView();
			not_ScrollTopMadingPanel();
			
		},
		error: function(xhr, options, error) {
			alert(xhr.responseText);
		}
	});
}

notidx_RefreshNotesIndex = function() {
    
    notidx_ShowNotesIndex();
    
}

notidx_BtBackClicked = function() {
    
	$('#not_index_content').html("");
    not_ShowNotesList_TopPos();
    
}