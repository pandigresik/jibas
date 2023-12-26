// ON STARTUP
var startRow = 0;

not_GetNotesList = function() {
	
	var dept = not_GetDepartemen(); //$('#ifse_departemen').val();
	$.ajax({
		type: 'POST',
		url: 'notes.list.ajax.php?op=getlist&dept='+dept+'&start='+startRow,
		success: function(html) {
			if (html.indexOf('~~#$@**') >= 0)
			{
				var content = html.split('~~#$@**');
				$("#not_NotesTableList > tbody:last").append(content[0]);
				$("#not_NotesTableList > tfoot").empty().append(content[1]);	
			}
			else
			{
				$("#not_NotesTableList > tbody:last").append(html);
				$("#not_NotesTableList > tfoot").empty();
			}
			
			startRow += not_RowPerPage;
		},
		error: function(xhr, options, error) {
			alert(xhr.responseText);
		}
	});
}

not_RefreshNotesList = function() {
	
	var dept = not_GetDepartemen(); //$('#ifse_departemen').val();
	$.ajax({
		type: 'POST',
		url: 'notes.list.ajax.php?op=refreshlist&dept='+dept+'&currrow='+startRow,
		success: function(html) {
			if (html.indexOf('~~#$@**') >= 0)
			{
				var content = html.split('~~#$@**');
				$("#not_NotesTableList > tbody").empty().append(content[0]);
				$("#not_NotesTableList > tfoot").empty().append(content[1]);	
			}
			else
			{
				$("#not_NotesTableList > tbody").empty().append(html);
				$("#not_NotesTableList > tfoot").empty();
			}
		},
		error: function(xhr, options, error) {
			alert(xhr.responseText);
		}
	});
}

not_NewNotesClicked = function() {
	
	$.ajax({
		type: 'POST',
		url: 'notes.input.php',
		success: function(html) {
			not_SetVisible('not_input_content');
			$('#not_input_content').html(html);
			not_ScrollTopMadingPanel();
		},
		error: function(xhr, options, error) {
			alert(xhr.responseText);
		}
	});
}

not_ViewNotes = function(notesid) {
	
	$.ajax({
		type: 'POST',
		url: 'notes.view.php',
		data: 'notesid='+notesid,
		success: function(html) {
			
			notview_SetCaller('noteslist');
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

not_ReloadNotesRow = function(notesid) {
	
	$.ajax({
		type: 'POST',
		url: 'notes.list.ajax.php',
		data: 'op=reloadnotesrow&notesid='+notesid,
		success: function(html) {
			$("#not_list_row_" + notesid).replaceWith(html);
		},
		error: function(xhr, options, error) {
			alert(xhr.responseText);
		}
	});
	
}

not_ShowNotesIndexClicked = function() {
	
	$.ajax({
		type: 'POST',
		url: 'notes.index.php',
		data: 'op=load',
		success: function(html) {
			
			not_ShowNotesIndex_TopPos();
			$('#not_index_content').html(html);
			
			notidx_ShowNotesIndex();
			
		},
		error: function(xhr, options, error) {
			alert(xhr.responseText);
		}
	});
	
}