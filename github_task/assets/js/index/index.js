var indexProccess = processFile + "~index/";
$(document).ready(function() {
    $("#logMeIn").on("click", function(t) {
        t.preventDefault(), $(this).attr("disabled", !0), $(this).text("برجاء الانتظار");
        var e = $("#login_form").serialize();
        $.ajax({
            type: "POST",
            url: indexProccess + "loginAccess",
            dataType: "json",
            data: e + "&ajax=true",
            success: function(t) {
                void 0 !== t.redirect ? location.href = t.redirect : void 0 !== t.wrongData ? (swal({
                    title: "خطا",
                    text: t.wrongData,
                    type: "error",
                    confirmButtonText: "اغلاق"
                }), $("#logMeIn").text("تسجيل دخول"), $("#logMeIn").attr("disabled", !1)) : void 0 !== t.webViewError ? (swal({
                    title: "خطا",
                    text: t.webViewError,
                    type: "error",
                    confirmButtonText: "اغلاق"
                }), $("#logMeIn").text("تسجيل دخول"), $("#logMeIn").attr("disabled", !1)) : void 0 !== t.notActive && (swal({
                    title: "خطا",
                    text: t.notActive,
                    type: "error",
                    confirmButtonText: "اغلاق"
                }), $("#logMeIn").text("تسجيل دخول"), $("#logMeIn").attr("disabled", !1))
            }
        })
    }), $("#signupNow").on("click", function(t) {
        ip = $("#regIp").text(), t.preventDefault(), $(this).attr("disabled", !0), $(this).text("برجاء الانتظار"), $.ajax({
            url: indexProccess + "sign",
            type: "POST",
            data: "ajax=true&regIp=" + ip,
            dataType: "json",
            success: function(t) {
                void 0 !== t.appError ? (swal({
                    title: "خطا",
                    text: t.appError,
                    type: "error",
                    confirmButtonText: "اغلاق"
                }), $("#signupNow").text("تسجيل"), $("#signupNow").attr("disabled", !1)) : void 0 !== t.code ? (swal({
                    title: "برجاء حفظ هذه البيانات للاهميه",
                    text: "كود الدخول الخاص بك : " + t.code,
                    type: "success",
                    confirmButtonText: "اغلاق"
                }), $("#signupNow").text("تم تسجيلك بنجاح")) : void 0 !== t.userFound ? (swal({
                    title: "خطا",
                    text: t.userFound,
                    type: "error",
                    confirmButtonText: "اغلاق"
                }), $("#signupNow").text("تسجيل"), $("#signupNow").attr("disabled", !1)) : void 0 !== t.user_gen && (swal({
                    title: "خطا",
                    text: t.user_gen,
                    type: "error",
                    confirmButtonText: "اغلاق"
                }), $("#signupNow").text("تسجيل"), $("#signupNow").attr("disabled", !1))
            }
        })
    })
});