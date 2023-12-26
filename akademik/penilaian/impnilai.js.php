<?php
/**[N]**
 * JIBAS Education Community
 * Jaringan Informasi Bersama Antar Sekolah
 *
 * @version: 29.0 (Sept 20, 2023)
 * @notes: JIBAS Education Community will be managed by Yayasan Indonesia Membaca (http://www.indonesiamembaca.net)
 *
 * Copyright (C) 2009 Yayasan Indonesia Membaca (http://www.indonesiamembaca.net)
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 **[N]**/ ?>
function uploadFile()
{
    var fexcel = $("#fexcel").val();
    if (fexcel.length == 0)
    {
        $("#fexcel").focus();
        alert("Tentukan file excel form data nilai!");
        return;
    }

    var formData = new FormData();
    formData.append("fexcel", $("#fexcel")[0].files[0]);

    $("#btProses").attr("disabled", true);
    $.ajax({
        url: "impnilai.upload.php",
        type: 'POST',
        data: formData,
        async: false,
        cache: false,
        contentType: false,
        processData: false,
        success: function (response)
        {
            processExcel(response);
            $("#btProses").attr("disabled", false);
        },
        error: function (xhr, response, error)
        {
            $("#btProses").attr("disabled", false);
            alert(xhr.responseText);
        }
    });
}

function processExcel(fexcel)
{
    $.ajax({
        url: "impnilai.process.php",
        data: "fexcel="+fexcel,
        success: function(html) {
            $("#divReader").empty().html(html);
        },
        error: function(xhr, options, error) {
            alert(xhr.responseText);
        }
    })
}

function validateForm()
{
    if ($("#pelajaran").has('option').length == 0)
    {
        alert("Belum ada data pilihan pelajaran!")
        return false;
    }

    if ($("#aspek").has('option').length == 0)
    {
        alert("Belum ada data pilihan aspek penilaian!")
        return false;
    }

    if ($("#idaturan").has('option').length == 0)
    {
        alert("Belum ada data pilihan jenis ujian!")
        return false;
    }

    return true;
}

function simpanData()
{
    if (!validateForm())
        return;

    if (!confirm("Data sudab benar?"))
        return;

    $.ajax({
        url: 'impnilai.simpan.php',
        type: 'post',
        dataType: 'json',
        data: $('#excelForm').serialize(),
        success: function(html)
        {
            $("#divReader").empty().html(html);
        },
        error: function(xhr, options, error) {
            //alert(xhr.responseText);
            //alert("ERROR");
            $("#divReader").empty().html(xhr.responseText);
        }
    });
}

function urlDecode (str) {
    return decodeURIComponent((str + '').replace(/\+/g, '%20'));
}

function changePelajaran()
{
    if ($("#pelajaran").has('option').length == 0)
        return;

    $("#divAspek").html("memuat ..");
    $("#divJenisUjian").html("memuat ..");
    $("#divRpp").html("memuat ..");

    var idsemester = $("#idsemester").val();
    var idtingkat = $("#idtingkat").val();
    var idkelas = $("#idkelas").val();
    var nipguru = $("#nipguru").val();

    var idaspek = "";
    var idpelajaran = $("#pelajaran").val();
    $.ajax({
        url: 'impnilai.ajax.php',
        data: "op=getselectaspek&idpelajaran="+idpelajaran+"&idtingkat="+idtingkat+"&nip="+nipguru,
        success: function(html)
        {
            var data = $.parseJSON(html);

            idaspek = data.idaspek;
            $("#divAspek").empty().html(urlDecode(data.select));

            $.ajax({
                url: 'impnilai.ajax.php',
                data: "op=getselectjenisujian&idpelajaran="+idpelajaran+"&idaspek="+idaspek+"&idtingkat="+idtingkat+"&idkelas="+idkelas+"&nip="+nipguru,
                success: function(html)
                {
                    $("#divJenisUjian").empty().html(urlDecode(html));
                },
                error: function(xhr, options, error) {
                    alert(xhr.responseText);
                }
            });
        },
        error: function(xhr, options, error) {
            alert(xhr.responseText);
        }
    });

    $.ajax({
        url: 'impnilai.ajax.php',
        data: "op=getselectrpp&idpelajaran="+idpelajaran+"&idtingkat="+idtingkat+"&idsemester="+idsemester,
        success: function(html)
        {
            $("#divRpp").empty().html(urlDecode(html));
        },
        error: function(xhr, options, error) {
            alert(xhr.responseText);
        }
    });
}

function changeAspek()
{
    if ($("#pelajaran").has('option').length == 0)
        return;

    if ($("#aspek").has('option').length == 0)
        return;

    $("#divJenisUjian").html("memuat ..");

    var idtingkat = $("#idtingkat").val();
    var idkelas = $("#idkelas").val();
    var nipguru = $("#nipguru").val();

    var idpelajaran = $("#pelajaran").val();
    var idaspek = $("#aspek").val();
    $.ajax({
        url: 'impnilai.ajax.php',
        data: "op=getselectjenisujian&idpelajaran="+idpelajaran+"&idaspek="+idaspek+"&idtingkat="+idtingkat+"&idkelas="+idkelas+"&nip="+nipguru,
        success: function(html)
        {
            $("#divJenisUjian").empty().html(urlDecode(html));
        },
        error: function(xhr, options, error) {
            alert(xhr.responseText);
        }
    });
}

function addRpp()
{
    var idsemester = $("#idsemester").val();
    var idtingkat = $("#idtingkat").val();
    var idpelajaran = $("#pelajaran").val();

    newWindow('rpp_tampil.php?tingkat='+idtingkat+'&semester='+idsemester+'&pelajaran='+idpelajaran,
        'TambahRPP','750','450','resizable=1,scrollbars=1,status=0,toolbar=0');
}

function refresh_rpp()
{
    var idsemester = $("#idsemester").val();
    var idtingkat = $("#idtingkat").val();
    var idpelajaran = $("#pelajaran").val();

    $.ajax({
        url: 'impnilai.ajax.php',
        data: "op=getselectrpp&idpelajaran="+idpelajaran+"&idtingkat="+idtingkat+"&idsemester="+idsemester,
        success: function(html)
        {
            $("#divRpp").empty().html(urlDecode(html));
        },
        error: function(xhr, options, error) {
            alert(xhr.responseText);
        }
    });
}
