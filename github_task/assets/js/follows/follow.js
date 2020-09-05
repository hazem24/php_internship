var processFollow = processFile+'~follows/';
$(document).ready(function(){
  


    $("#increaseFollowButton").attr('disabled',true);//By Default .. any button hidden true
    $("[name=screen_name]").on("blur",function(){
        if($("[name=screen_name]").val().trim() == ''){
            $("#increaseFollowButton").attr('disabled',true);
        }else{
                $("#increaseFollowButton").attr('disabled',false);
        }
    });


    $("#increaseFollowButton").on("click",function(event){
        event.preventDefault();
        $('#cmd').modal('show');             
        var formData = $("#increaseFollow").serialize();
        $(this).text("جاري زياده المتابعين ..");
        $("[name=screen_name]").attr("readonly",true);
        $(this).attr('disabled',true);
        $.ajax({
            "url":processFollow+'increaseFollowers',
            "type":"POST",
            "data":formData+"&ajax=true",
            "dataType":"json",
            "success":function(data){ 
                $("[name=screen_name]").attr("readonly",false);
                if(data.noAccounts !== undefined){
                    swal({
                        title: "خطا",
                        text: data.noAccounts,
                        type: "error",
                        confirmButtonText: "اغلاق"
                    });
                    $("#startAddAccounts").text("زياده المتابعين"); 
                }else if (data.finished !== undefined){
                    swal({
                        title: "تم عمل متابعه بنجاح.",
                        text: data.finished,
                        type: "success",
                        confirmButtonText: "اغلاق"
                    });
                    $("#startAddAccounts").text("زياده المتابعين."); 
                }else if (data.no_twitter_account !== undefined){
                    swal({
                        title: "خطا",
                        text: data.no_twitter_account,
                        type: "error",
                        confirmButtonText: "اغلاق"
                    });
                    $("#startAddAccounts").text("زياده المتابعين.");
                    $("#startAddAccounts").attr('disabled',false);
                }
            }
        });
});
//End

});