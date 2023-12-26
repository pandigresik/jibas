var $mad_jQueryParent = parent.jQuery;
var mad_EmoticonsDialog = undefined;

mad_ChangeNewLine = function(text)
{
	
	return  text.replace(/\r?\n|\r/g, "[{*@NL#$]}");
	
}

mad_ShowEmoticons = function()
{
	
	if (mad_EmoticonsDialog == undefined)
	{
		mad_EmoticonsDialog = $mad_jQueryParent("#mad_Emoticons_Dialog");
		mad_EmoticonsDialog.dialog({
			autoOpen: false,
			position: 'center',
			height: 500,
			width: 600,
			modal: true,
			buttons: {
				'Tutup': function() {
					$mad_jQueryParent("#mad_Emoticons_Dialog").dialog('close');
				}
			},
			close: function() {
				
			}
		});
	}
	
	 $.ajax({
		type: "POST",
		url: "emoticons.php",
		success: function(html) {
			mad_EmoticonsDialog.html(html);
			mad_EmoticonsDialog.dialog('option', 'title', 'Emoticons');
			mad_EmoticonsDialog.dialog('open');
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(xhr.responseText);
        }
	})
	
	
}