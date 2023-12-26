simpanPetugas = function () {

    var userReplid = $("#userreplid").val();

    var userId = $.trim($("#userid").val());
    if (userId.length < 3)
    {
        alert("Panjang User Id minimal 3 karater");
        $("#userid").focus();
        return;
    }

    var userName = $.trim($("#username").val());
    if (userName.length < 5)
    {
        alert("Panjang User Name minimal 5 karater");
        $("#username").focus();
        return;
    }

    var password1 = $.trim($("#password1").val());
    if (password1.length < 5)
    {
        alert("Panjang Password minimal 5 karater");
        $("#password1").focus();
        return;
    }

    var password2 = $.trim($("#password2").val());
    if (password2.length < 5)
    {
        alert("Panjang Password minimal 5 karater");
        $("#password2").focus();
        return;
    }

    if (password1 !== password2)
    {
        alert("Password tidak sama");
        $("#password1").focus();
        return;
    }

    var origPass = $.trim($("#origpass").val());
    var keterangan = $.trim($("#keterangan").val());

    var request = new RequestFactory();
    request.add("op", "3276897493284732894");
    request.add("userreplid", userReplid);
    request.add("userid", userId);
    request.add("username", userName);
    request.add("password", password1);
    request.add("keterangan", keterangan);
    request.add("origpass", origPass);
    var qs = request.createQs();

    $.ajax({
        url: "userpos.dialog.ajax.php",
        method: "POST",
        data: qs,
        success: function (json)
        {
            var result = $.parseJSON(json);

            if (parseInt(result[0]) < 0)
            {
                alert(result[1]);
                return;
            }

            opener.location.reload();
            window.close();
        },
        error: function(xhr)
        {
            alert(xhr.responseText);
        }
    })
};



