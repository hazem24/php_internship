var processReplay=processFile+"~replay/";$(document).ready(function(){$("#fastReplayButton").attr("disabled",!0),$("[name=tweet_id]").on("blur",function(){""==$("[name=tweet_id]").val().trim()?$("#fastReplayButton").attr("disabled",!0):$("#fastReplayButton").attr("disabled",!1)}),$("#fastReplayButton").on("click",function(t){t.preventDefault(),$("#cmd").modal("show");var e=$("#fastReplays").serialize();$("[name=fastReplayButton]").attr("readonly",!0),$(this).attr("disabled",!0),$.ajax({url:processReplay+"speedReplays",type:"POST",dataType:"json",data:e+"&ajax=true",success:function(t){$("[name=tweet_id]").attr("readonly",!1),void 0!==t.finished?(swal({title:"تم",text:t.finished,type:"success",confirmButtonText:"اغلاق"}),$("#fastReplayButton").attr("disabled",!1)):void 0!==t.noReplays?(swal({title:"خطا",text:t.noReplays,type:"error",confirmButtonText:"اغلاق"}),$("#fastReplayButton").attr("disabled",!1)):void 0!==t.noAccounts&&(swal({title:"خطا",text:t.noAccounts,type:"error",confirmButtonText:"اغلاق"}),$("#fastReplayButton").attr("disabled",!1))}})})});