var processLike=processFile+"~like/";$(document).ready(function(){$("#fastLikeButton").attr("disabled",!0),$("[name=retweet_like_id]").on("blur",function(){""==$("[name=retweet_like_id]").val().trim()?$("#fastLikeButton").attr("disabled",!0):$("#fastLikeButton").attr("disabled",!1)}),$("#fastLikeButton").on("click",function(t){t.preventDefault(),$("#cmd").modal("show");var e=$("#fastLike").serialize();$("[name=retweet_like_id]").attr("readonly",!0),$(this).attr("disabled",!0),$.ajax({url:processLike+"speedLike",type:"POST",data:e+"&ajax=true",dataType:"json",success:function(t){$("[name=retweet_like_id]").attr("readonly",!1),void 0!==t.noAccounts?swal({title:"خطا",text:t.noAccounts,type:"error",confirmButtonText:"اغلاق"}):void 0!==t.finished&&swal({title:"تم عمل مفضله بنجاح.",text:t.finished,type:"success",confirmButtonText:"اغلاق"})}})})});