$(document).ready(function () {
    $(document).bgStretcher({
        images: b_bgList, imageWidth: 1680, imageHeight: 1050, nextSlideDelay: b_bgDelay
    });
    
    setTimeout(resizeVTab, 10);
    setTimeout(pageLoader, 10);
	
});

// ON STARTED UP
$(function() {
    setUpTabs();
})

// ON WINDOW RESIZED
$(window).resize(function() {
    resizeVTab();
});

setUpTabs = function() {
	$("#vtabs1").jVertTabs();
}

resizeVTab = function() {
	var docHeight = $(document).height();
    //$('#debug1').val(docHeight);
	
	var vTabHeight = docHeight - 160;
	$("#vtabs1").height(vTabHeight);
	
	var vTabDivHeight = docHeight - 170;
	$("#vtabs1>div").height(vTabDivHeight);
	$("#vtabs1>div>div").height(vTabDivHeight);
	
	var vTabPaneHeight = docHeight - 220;
	$("#vtabs1>div>div>div").height(vTabPaneHeight);
	
	//$('#debug2').val($("#vtabs1>div").height());
	//$('#debug3').val($("#vtabs1>div>div").height());
}

pageLoader = function() {
	
    loadBeranda();
    loadInfoSiswa();
    loadPegawai();
	loadPsb();
	loadJadKal();
	loadMading();
	loadInfoSekolah();
	loadPerpustakaan();
	
}

loadBeranda = function() {
    $.ajax({
        url : 'beranda/beranda.php',
        type: 'get',
        success : function(html) {
            $('#content-pane-a').html(html);
        }
    })
}

loadInfoSiswa = function() {
    $.ajax({
        url : 'infosiswa/infosiswa.php',
        type: 'get',
        success : function(html) {
            $('#content-pane-c').html(html);
        }
    })
}

loadPegawai = function() {
    $.ajax({
        url : 'pegawai/struktur.php',
        type: 'get',
        success : function(html) {
            $('#content-pane-g').html(html);
            if (!$.browser.mozilla)
                convertTrees();
            setTimeout(function() {
                expandTree('tree1'); 
            }, 200);
        }
    })
}

loadPsb = function() {
    $.ajax({
        url : 'psb/psb.php',
        type: 'get',
        success : function(html) {
            $('#content-pane-h').html(html);
        }
    })
}

loadJadKal = function() {
    $.ajax({
        url : 'jadkal/jadkal.php',
        type: 'get',
        success : function(html) {
            $('#content-pane-i').html(html);
        }
    })
}

loadMading = function() {
    $.ajax({
        url : 'mading/mading.php',
        type: 'get',
        success : function(html) {
            $('#content-pane-j').html(html);
			mad_InitMading();
        }
    })
}

loadInfoSekolah = function() {
    $.ajax({
        url : 'infosekolah/infosekolah.php',
        type: 'get',
        success : function(html) {
            $('#content-pane-b').html(html);
			ifse_InitInfoSekolah();
        }
    })
}

loadPerpustakaan = function() {
    $.ajax({
        url : 'pustaka/pustaka.php',
        type: 'get',
        success : function(html) {
            $('#content-pane-k').html(html);
			ptka_InitPustaka();
        }
    })
}

