var processSub = processFile + "~subscribe/";
$(document).ready(function() {
    $("#addSubButton").attr("disabled", !0), $("[name=sub_screenName]").on("blur", function() {
        "" == $("[name=sub_screenName]").val().trim() ? $("#addSubButton").attr("disabled", !0) : $("#addSubButton").attr("disabled", !1)
    }), $("#addSubButton").on("click", function(t) {
        t.preventDefault(), $(this).attr("disabled", !0), $("[name=sub_screenName]").attr("readonly", !0), $.ajax({
            url: processSub + "addSubscribe",
            type: "POST",
            data: $("#addSubForm").serialize() + "&ajax=true",
            dataType: "json",
            success: function(t) {
                console.log( t );
                $("[name=sub_screenName]").attr("readonly", !1), void 0 !== t.finished_add ? (swal({
                    title: "ØªÙ….",
                    text: t.finished_add,
                    type: "success",
                    confirmButtonText: "Ø§ØºÙ„Ø§Ù‚"
                }), $("#addSubButton").attr("disabled", !1)) : void 0 !== t.webViewError ? (swal({
                    title: "Ø®Ø·Ø§",
                    text: t.webViewError,
                    type: "error",
                    confirmButtonText: "Ø§ØºÙ„Ø§Ù‚"
                }), $("#addSubButton").attr("disabled", !1)) : void 0 !== t.noSelection ? (swal({
                    title: "Ø®Ø·Ø§",
                    text: t.noSelection,
                    type: "error",
                    confirmButtonText: "Ø§ØºÙ„Ø§Ù‚"
                }), $("#addSubButton").attr("disabled", !1)) : void 0 !== t.account_not_found ? (swal({
                    title: "Ø®Ø·Ø§",
                    text: t.account_not_found,
                    type: "error",
                    confirmButtonText: "Ø§ØºÙ„Ø§Ù‚"
                }), $("#addSubButton").attr("disabled", !1)) : void 0 !== t.appError && (swal({
                    title: "Ø®Ø·Ø§",
                    text: t.appError,
                    type: "error",
                    confirmButtonText: "Ø§ØºÙ„Ø§Ù‚"
                }), $("#addSubButton").attr("disabled", !1))
            }
        })
    }), $("[name=deleteSubscriber]").on("click", function(t) {
        t.preventDefault();
        var e = $(this).data("account-delete-id");
        $(this).attr("disabled", !0), $.ajax({
            url: processSub + "deleteAccount",
            type: "POST",
            data: "account_id=" + $(this).data("account-delete-id") + "&ajax=true",
            dataType: "json",
            success: function(t) {
                void 0 !== t.account_delete ? $("#accountContent" + e).remove() : void 0 !== t.appError && (swal({
                    title: "Ø®Ø·Ø§",
                    text: t.appError,
                    type: "error",
                    confirmButtonText: "Ø§ØºÙ„Ø§Ù‚"
                }), $("[name=deleteSubscriber]").attr("disabled", !1))
            }
        })
    })
});