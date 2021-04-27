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
    $(document).on('click', "#page-header-notifications-dropdown", function(){
        // console.log("something");
        // $(".badge").html('');
        // loadUnseenNotification("yes");
        alert("Hello world");
    })

    // $(document).on("change","button[aria-expanded]", function(){
    //     alert("something");
    // })

    // if($("button[aria-expanded]")[1].ariaExpanded == 'false'){
    //     alert("something");
    // }else{
    //     alert("yes");
    // }
    setInterval(function(){
        loadUnseenNotification();
    }, 1000 * 60 * 2);
})