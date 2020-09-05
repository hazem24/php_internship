var processAccounts = processFile + "~accounts/";
$(document).ready(function() {
    $('#show_accounts_table').DataTable({
        "pagingType": "full_numbers",
        "paging": true,
        "lengthMenu": [10, 25, 50, 75, 100],     
    });
    $("#startAddAccounts").attr("disabled", !0), $("#accountsToAdd").on("blur", function() {
        "" == $("#accountsToAdd").val().trim() ? $("#startAddAccounts").attr("disabled", !0) : $("#startAddAccounts").attr("disabled", !1)
    }), $("#startAddAccounts").on("click", function(t) {
        t.preventDefault(), $("#cmd").modal("show"), $("#accountsToAdd").attr("readonly", !0);
        var e = $("#addAccounts").serialize();
        $(this).text("جاري تهيئه الحسابات"), $(this).attr("disabled", !0), $.ajax({
            url: processAccounts + "addAccountProcess",
            type: "POST",
            data: e + "&ajax=true",
            dataType: "json",
            success: function(t) {
                $("#accountsToAdd").attr("readonly", !1), void 0 !== t.noAccounts ? (swal({
                    title: "خطا",
                    text: t.noAccounts,
                    type: "error",
                    confirmButtonText: "اغلاق"
                }), $("#startAddAccounts").text("تهيئه حسابات.")) : void 0 !== t.finished_add ? (swal({
                    title: "تم تهيئه الحسابات بنجاح.",
                    text: t.finished_add,
                    type: "success",
                    confirmButtonText: "اغلاق"
                }), $("#startAddAccounts").text("تهيئه المزبد.")) : void 0 !== t.appError && (swal({
                    title: "خطا",
                    text: t.appError,
                    type: "error",
                    confirmButtonText: "اغلاق"
                }), $("#startAddAccounts").text("تهيئه حسابات."), $("#startAddAccounts").attr("disabled", !1))
            }
        })
    }), $("[name=deleteAccount]").on("click", function(t) {
        t.preventDefault();
        var e = $(this).data("account-delete-id");
        $(this).attr("disabled", !0), $.ajax({
            url: processAccounts + "deleteAccount",
            type: "POST",
            data: "account_id=" + $(this).data("account-delete-id") + "&ajax=true&account_email" + $(this).data("account-email"),
            dataType: "json",
            success: function(t) {
                void 0 !== t.account_delete ? $("#accountContent" + e).remove() : void 0 !== t.appError && (swal({
                    title: "خطا",
                    text: t.appError,
                    type: "error",
                    confirmButtonText: "اغلاق"
                }), $("[name=deleteAccount]").attr("disabled", !1))
            }
        })
    }), $("#editAccounts").on("click", function(t) {
        t.preventDefault(), $(this).attr("disabled", !0), $.ajax({
            url: processAccounts + "editAccount",
            type: "POST",
            data: $("#accountsSettings").serialize() + "&ajax=true",
            dataType: "json",
            success: function(t) {
                void 0 !== t.edit_done ? (swal({
                    title: "تم تهيئه الحسابات بنجاح.",
                    text: t.edit_done,
                    type: "success",
                    confirmButtonText: "اغلاق"
                }), $("#editAccounts").attr("disabled", !1)) : void 0 !== t.appError && (swal({
                    title: "خطا",
                    text: t.appError,
                    type: "error",
                    confirmButtonText: "اغلاق"
                }), $("#editAccounts").attr("disabled", !1))
            }
        })
    }), !$("#noAccounts").length && $("#table-accounts-content").length
});
var processAccounts = processFile + "~accounts/";
$(document).ready(function() {
    $("#startAddAccounts").attr("disabled", !0), $("#accountsToAdd").on("blur", function() {
        "" == $("#accountsToAdd").val().trim() ? $("#startAddAccounts").attr("disabled", !0) : $("#startAddAccounts").attr("disabled", !1)
    }), $("#startAddAccounts").on("click", function(t) {
        t.preventDefault(), $("#cmd").modal("show"), $("#accountsToAdd").attr("readonly", !0);
        var e = $("#addAccounts").serialize();
        $(this).text("جاري تهيئه الحسابات"), $(this).attr("disabled", !0), $.ajax({
            url: processAccounts + "addAccountProcess",
            type: "POST",
            data: e + "&ajax=true",
            dataType: "json",
            success: function(t) {
                $("#accountsToAdd").attr("readonly", !1), void 0 !== t.noAccounts ? (swal({
                    title: "خطا",
                    text: t.noAccounts,
                    type: "error",
                    confirmButtonText: "اغلاق"
                }), $("#startAddAccounts").text("تهيئه حسابات.")) : void 0 !== t.finished_add ? (swal({
                    title: "تم تهيئه الحسابات بنجاح.",
                    text: t.finished_add,
                    type: "success",
                    confirmButtonText: "اغلاق"
                }), $("#startAddAccounts").text("تهيئه المزبد.")) : void 0 !== t.appError && (swal({
                    title: "خطا",
                    text: t.appError,
                    type: "error",
                    confirmButtonText: "اغلاق"
                }), $("#startAddAccounts").text("تهيئه حسابات."), $("#startAddAccounts").attr("disabled", !1))
            }
        })
    }), $("[name=deleteAccount]").on("click", function(t) {
        t.preventDefault();
        var e = $(this).data("account-delete-id");
        $(this).attr("disabled", !0), $.ajax({
            url: processAccounts + "deleteAccount",
            type: "POST",
            data: "account_id=" + $(this).data("account-delete-id") + "&ajax=true&account_email" + $(this).data("account-email"),
            dataType: "json",
            success: function(t) {
                void 0 !== t.account_delete ? $("#accountContent" + e).remove() : void 0 !== t.appError && (swal({
                    title: "خطا",
                    text: t.appError,
                    type: "error",
                    confirmButtonText: "اغلاق"
                }), $("[name=deleteAccount]").attr("disabled", !1))
            }
        })
    }), $("#editAccounts").on("click", function(t) {
        t.preventDefault(), $(this).attr("disabled", !0), $.ajax({
            url: processAccounts + "editAccount",
            type: "POST",
            data: $("#accountsSettings").serialize() + "&ajax=true",
            dataType: "json",
            success: function(t) {
                void 0 !== t.edit_done ? (swal({
                    title: "تم تهيئه الحسابات بنجاح.",
                    text: t.edit_done,
                    type: "success",
                    confirmButtonText: "اغلاق"
                }), $("#editAccounts").attr("disabled", !1)) : void 0 !== t.appError && (swal({
                    title: "خطا",
                    text: t.appError,
                    type: "error",
                    confirmButtonText: "اغلاق"
                }), $("#editAccounts").attr("disabled", !1))
            }
        })
    }), !$("#noAccounts").length && $("#table-accounts-content").length
});