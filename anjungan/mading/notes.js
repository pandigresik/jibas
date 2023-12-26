var $not_jQueryParent = parent.jQuery;

var not_DivList = ['not_list_content', 'not_edit_content', 'not_view_content', 'not_input_content', 'not_index_content'];
var not_NotesFrame = $not_jQueryParent('#mad_notes_content');
var not_LastNotesListScrollPos = 0;
var not_LastNotesIndexScrollPos = 0;

not_SetVisible = function(divname) {
	
	for(var i = 0; i < not_DivList.length; i++)
	{
		if (not_DivList[i] == divname)
			$("#" + not_DivList[i]).css('visibility', 'visible');
		else
			$("#" + not_DivList[i]).css('visibility', 'hidden');
	}
}

not_ShowNotesList_LastPos = function() {
	
	not_SetVisible('not_list_content');
	not_NotesFrame.contents().scrollTop(not_LastNotesListScrollPos);
	
}

not_ShowNotesList_TopPos = function() {
	
	not_SetVisible('not_list_content');
	not_NotesFrame.contents().scrollTop(0);
	not_LastNotesListScrollPos = 0;
	
}

not_ShowNotesEdit = function() {
	
	not_SetVisible('not_edit_content');
	
}

not_ShowNotesView = function() {

	not_LastNotesIndexScrollPos = not_NotesFrame.contents().scrollTop();
	not_LastNotesListScrollPos = not_NotesFrame.contents().scrollTop();
	not_SetVisible('not_view_content');
	
}

not_ShowNotesIndex_TopPos = function() {
	
	not_SetVisible('not_index_content');
	not_NotesFrame.contents().scrollTop(0);
	not_LastNotesIndexScrollPos = 0;
	
}

not_ShowNotesIndex_LastPos = function() {
	
	not_SetVisible('not_index_content');
	not_NotesFrame.contents().scrollTop(not_LastNotesListScrollPos);
	
}

not_ScrollTopMadingPanel = function() {
	
	not_NotesFrame.contents().scrollTop(0);
	
}

not_GetDepartemen = function() {
	
	return parent.document.getElementById('mad_departemen').value;
}

$( document ).ready(function() {

	not_GetNotesList();
	
});