tinyMCE.init({
	mode : "textareas",
	theme : "advanced",
    plugins : "autolink,lists,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,wordcount,advlist,autosave",
    // Theme options
    theme_advanced_buttons1 : "bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,styleselect,formatselect,fontselect,fontsizeselect",
    theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,help,code,|,insertdate,inserttime,preview,|,forecolor,backcolor",
    theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,iespell,advhr,|,print,|,ltr,rtl,|,fullscreen",
    theme_advanced_toolbar_location : "top",
    theme_advanced_toolbar_align : "left",
    theme_advanced_statusbar_location : "bottom",
    theme_advanced_resizing : true,
	relative_urls: false,
	convert_urls: false,
	remove_script_host : false
});

function validate() 
{	
	if (!validateEmptyText('judul', 'Judul Pengantar Surat') || 
		!validateMaxText('judul', 255, 'Judul Pengantar Surat'))
		return false;
	
	var content = tinyMCE.get('pengantar').getContent();
	if ($.trim(content).length == 0)
	{
		alert("Anda harus mengisikan Isi Pengantar Surat!")
		return false;
	}
	
	return confirm("Data sudah benar?");
}