sp_Refresh = function() {
    $.ajax({
        url : 'pegawai/struktur.php',
        type: 'get',
        success : function(html) {
            $('#content-pane-g').html(html);
            convertTrees();
            setTimeout(function() {
                expandTree('tree1'); 
            }, 200);
        }
    })
}
