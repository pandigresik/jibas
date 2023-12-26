var $ifse_jQueryParent = parent.jQuery;
var ifse_EmoticonsDialog = undefined;

ifse_ChangeNewLine = function(text)
{
	
	return  text.replace(/\r?\n|\r/g, "[{*@NL#$]}");
	
}

ifse_ShowEmoticons = function()
{
	
	if (ifse_EmoticonsDialog == undefined)
	{
		ifse_EmoticonsDialog = $ifse_jQueryParent("#ifse_Emoticons_Dialog");
		ifse_EmoticonsDialog.dialog({
			autoOpen: false,
			position: 'center',
			height: 500,
			width: 600,
			modal: true,
			buttons: {
				'Tutup': function() {
					$ifse_jQueryParent("#ifse_Emoticons_Dialog").dialog('close');
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
			ifse_EmoticonsDialog.html(html);
			ifse_EmoticonsDialog.dialog('option', 'title', 'Emoticons');
			ifse_EmoticonsDialog.dialog('open');
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(xhr.responseText);
        }
	})
	
	
}