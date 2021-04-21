$(document).ready(function(){
    function loadNoti(){
        $.ajax({
            url:"http://project.local/api/notifications",
            method:"GET",
            dataType: "json",
            success:function(data)
            {
                console.log(data["0"]);
            }
           });
    }
    loadNoti();

    setInterval(function(){
        loadNoti()
    }, 1000 * 60 * 2);
})

// function appendNotify(){
//     var a = $('<a href="#" class="text-reset notification-item"></a>').text("Hello");
//     var div = $('<div class="media"></div>')
//     $("#notify").append(a);
// }
// appendNotify();

