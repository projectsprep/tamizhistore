$(document).ready(function(){
    function loadUnseenNotification(view=""){
        $.ajax({
            url: "http://project.local/api/pushednotifies",
            method: "GET",
            data:{view:view},
            dataType:"json",
            success: function(data){
                 $("#notify").html(data.notification);
                 if(data.unseenNotification > 0){
                     $("audio")[0].muted = false;
                     $("audio")[0].play();
                    $(".badge").html(data.unseenNotification);
                 }
            }
        })
    }

    loadUnseenNotification()
    $(document).on('click', "#notifydropdown", function(){
        console.log("something");
        $(".badge").html('');
        loadUnseenNotification("yes");
    })

    setInterval(function(){
        loadUnseenNotification();
    }, 10000);
})