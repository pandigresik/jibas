ptka_InitPustaka = function()
{
	
}

pustaka_browse = function()
{
    $.ajax({
        url: 'pustaka/pustaka.daftar.php',
        type: 'get',
        success : function(html) {
            $('#pustaka_content').html(html);
        },
		error: function(xhr, options, error) {
			alert(xhr.responseText);
		}
    })
}

pustaka_search = function()
{
    $.ajax({
        url: 'pustaka/pustaka.cari.php',
        type: 'get',
        success : function(html) {
            $('#pustaka_content').html(html);
        },
		error: function(xhr, options, error) {
			alert(xhr.responseText);
		}
    })
}

pustaka_stat = function()
{
    $.ajax({
        url: 'pustaka/pustaka.stat.php',
        type: 'get',
        success : function(html) {
            $('#pustaka_content').html(html);
        },
		error: function(xhr, options, error) {
			alert(xhr.responseText);
		}
    })
}