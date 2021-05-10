    $(document).ready(function() {
        function loadUnseenNotification(view = "") {
            $.ajax({
                url: "/api/pushednotifies",
                method: "GET",
                data: {
                    view: view
                },
                dataType: "json",
                success: function(data) {
                    console.log(data.notification);
                    $("#notify").html(data.notification);
                    if (data.unseenNotification > 0) {
                        // $("audio")[0].muted = false;
                        $("audio")[0].play();
                        $(".badge").html(data.unseenNotification);
                        $(".bx-bell").addClass("bx-tada");
                    }
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    alert('ajaxOptions');
                },
            })
        }

        loadUnseenNotification()
        $(".dropdown.d-inline-block.notifydropdown").on('shown.bs.dropdown', function() {
            $(".badge").html('');
            loadUnseenNotification("yes");
        });
        setInterval(function() {
            loadUnseenNotification();
        }, 1000 * 60 * 2);
    })
