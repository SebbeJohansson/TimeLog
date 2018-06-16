/**
 * Created by Sebbans on 2018-04-18.
 */


var clock = null;

$(document).ready(function() {
    $("#loginform").validate({
        rules: {
            username: {
                required: true
            },
            password: {
                required: true
            }
        },
        messages: {
            username: {
                required: "Please enter a username"
            },
            password: {
                required: "please enter a password"
            }
        },
        submitHandler: submitLoginUser
    });

    $("#createform").validate({
        rules: {
            username: {
                required: true
            },
            password: {
                required: true
            }
        },
        messages: {
            username: {
                required: "Please enter a username"
            },
            password: {
                required: "please enter a password"
            }
        },
        submitHandler: submitCreateUser
    });

    console.log("initiate timer");
    clock = $('.your-clock').FlipClock({
        autoStart: false
    });

});

function initiateTimer(){

    $(document).ready(function() {

        $.ajax({
            type: "POST",
            url: "includes/doSomething.php",
            data: "action=inittimer",
            success: function (response) {
                response = JSON.parse(response);
                var errors = response['errors'];
                var successful = response['successful'];
                var statusmessage = response['statusmessage'];
                if (successful) {
                    clock.setTime(response['variables']['timervalue']);
                    clock.start();
                    $('#timerbutton').html("Stop Timer");
                }
            }
        });
    });
}

function submitLoginUser(){
    var data = $("#loginform").serialize();
    $.ajax({
        type: "POST",
        url: "includes/doSomething.php",
        data: data,
        success: function(response){
            response = JSON.parse(response);
            var errors = response['errors'];
            var successful = response['successful'];
            var statusmessage = response['statusmessage'];

            if(successful){
                alert(statusmessage);
                window.location.href = "index.php";
            }else{
                alert(statusmessage);
            }
        }
    });
}

function submitCreateUser(){
    var data = $("#createform").serialize();
    $.ajax({
        type: "POST",
        url: "includes/doSomething.php",
        data: data,
        success: function(response){
            response = JSON.parse(response);
            var errors = response['errors'];
            var successful = response['successful'];
            var statusmessage = response['statusmessage'];

            if(successful){

            }else{
                $('#createform')[0].reset();
                alert("Username Taken.");
            }
        }
    });
}

function logoutUser(){
    $.ajax({
        type: "POST",
        url: "includes/doSomething.php",
        data: "action=logout",
        success: function (response) {
            response = JSON.parse(response);
            var errors = response['errors'];
            var successful = response['successful'];
            var statusmessage = response['statusmessage'];
            alert(statusmessage);
            window.location.href = "index.php";
        }
    });
}

function toggleTimer(){
    $.ajax({
        type: "POST",
        url: "includes/doSomething.php",
        data: "action=toggletimer",
        success: function (response) {
            response = JSON.parse(response);
            var errors = response['errors'];
            var successful = response['successful'];
            var statusmessage = response['statusmessage'];


            if (successful){
                clock.reset();
                clock.start();
                $('#timerbutton').html("Stop Timer");
            } else{
                clock.stop();
                $('#timerbutton').html("Start Timer");
            }
        }
    });
}



function getUserStats(){
    $.ajax({
        type: "POST",
        url: "includes/doSomething.php",
        data: "action=getuserstats",
        success: function (response) {
            console.log(response);
            response = JSON.parse(response);
            var errors = response['errors'];
            var successful = response['successful'];
            var statusmessage = response['statusmessage'];
            if(successful){
                $('#userstats').html(response['variables']['output']);
            }
        }
    });
}

function getLogs(){
    $.ajax({
        type: "POST",
        url: "includes/doSomething.php",
        data: "action=getlogs",
        success: function (response) {
            response = JSON.parse(response);
            var errors = response['errors'];
            var successful = response['successful'];
            var statusmessage = response['statusmessage'];
            if(successful){
                $('#logs').html(response['variables']['output']);
            }
        }
    });
}