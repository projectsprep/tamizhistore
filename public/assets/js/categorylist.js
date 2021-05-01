import $ from "jquery";
$(document).ready(function(){
    console.log("yes");
    loadCategoryList()
    function loadCategoryList(){
        $.ajax({
            url: "http://project.local/api/getcategory",
            method:"GET",
            success: function(data){
                // $("#categorylists").html("something");
                console.log(data);
            }
        })
    }
});

console.log("Hello");