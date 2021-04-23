let newCount = 0;
let count = 0;
let oldData;
let audioElement = document.getElementsByTagName('audio');
$(document).ready(function(){
    function loadNoti(){
        $.ajax({
            url:"http://project.local/api/pushedNotifies",
            method:"GET",
            dataType: "json",
            success:function(data)
            {
                if(oldData===undefined){
                    oldData=data;
                }else{
                    if(JSON.stringify(oldData) === JSON.stringify(data)){
                        $("#notify > .simplebar-wrapper > .simplebar-mask > .simplebar-offset > .simplebar-content-wrapper > .simplebar-content").empty();
                        
                        data.filter(ele => {
                            if(ele['is_seen'] == 0){
                                oldData.forEach(element => {
                                    displayNotify(element['title'], element['msg'], element['duration']);
                                });
                            }else{
                                console.log("seen");
                            }
                        })
                    }else{
                        oldData=data;
                        newCount = data.length;
                        $(".badge").html(data.length);
                        data.forEach(element => {
                            appendNotify(element['title'], element['msg'], element['duration']);
                        });
                    }
                }
            }
           });
    }
    loadNoti();

    setInterval(function(){
        loadNoti()
    }, 1000);
})

function displayNotify(title, message, duration){
    var now = new Date();
    var newNow = new Date();
    newNow.setHours(duration.slice(11, 13), duration.slice(14, 16))
    var diff = Math.abs(newNow - now) / 1000 / 60;
    if(diff >= 60){
        duration = Math.round(diff / 60);
        if(duration == 1){
            duration += " hour ";
        }else if(duration > 1){
            duration += " hours ";
        }
    }else{
        if(duration == 1){
            duration += " min ";
        }else if(duration > 1){
            duration += " mins ";
        }
    }
    var element = `<a href="#" class="text-reset notification-item">
    <div class="media">
        <div class="avatar-xs me-3">
            <span class="avatar-title bg-primary rounded-circle font-size-16">
                <i class="bx bx-cart"></i>
            </span>
        </div>
        <div class="media-body">
            <h6 class="mt-0 mb-1" key="t-your-order">${title}</h6>
            <div class="font-size-12 text-muted">
                <p class="mb-1" key="t-grammer">${message}</p>
                <p class="mb-0"><i class="mdi mdi-clock-outline"></i> <span key="t-min-ago">${duration} ago</span></p>
            </div>
        </div>
    </div>
</a>`
return $("#notify > .simplebar-wrapper > .simplebar-mask > .simplebar-offset > .simplebar-content-wrapper > .simplebar-content").append(element);

}

function appendNotify(title, message, duration){
    var now = new Date();
    var newNow = new Date();
    newNow.setHours(duration.slice(11, 13), duration.slice(14, 16))
    var diff = Math.abs(newNow - now) / 1000 / 60;
    if(diff >= 60){
        duration = Math.round(diff / 60);
        if(duration == 1){
            duration += " hour ";
        }else if(duration > 1){
            duration += " hours ";
        }
    }else{
        if(duration == 1){
            duration += " min ";
        }else if(duration > 1){
            duration += " mins ";
        }
    }
    var element = `<a href="#" class="text-reset notification-item">
    <div class="media">
        <div class="avatar-xs me-3">
            <span class="avatar-title bg-primary rounded-circle font-size-16">
                <i class="bx bx-cart"></i>
            </span>
        </div>
        <div class="media-body">
            <h6 class="mt-0 mb-1" key="t-your-order">${title}</h6>
            <div class="font-size-12 text-muted">
                <p class="mb-1" key="t-grammer">${message}</p>
                <p class="mb-0"><i class="mdi mdi-clock-outline"></i> <span key="t-min-ago">${duration} ago</span></p>
            </div>
        </div>
    </div>
</a>`
        audioElement[0].muted = false;
        audioElement[0].play();
        $("#notify > .simplebar-wrapper > .simplebar-mask > .simplebar-offset > .simplebar-content-wrapper > .simplebar-content").empty();
        return $("#notify > .simplebar-wrapper > .simplebar-mask > .simplebar-offset > .simplebar-content-wrapper > .simplebar-content").append(element);
}


