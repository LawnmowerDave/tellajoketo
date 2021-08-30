


function shootConfetti() {
    $(document).ready(function () {
        const canvas = document.getElementById("confetti");
    
        canvas.setAttribute('width', window.innerWidth);
        canvas.setAttribute('height', window.innerHeight);
    
        const jsConfetti = new JSConfetti({ canvas });
    
        jsConfetti.addConfetti({
            confettiRadius: 6,
            confettiNumber: 100,
        });
    
    });
}


function handleResponse(response) {
    if (response["status"] == 200) {
        $(".post_send").fadeOut("fast", function () {
            $("#main").fadeIn("fast");
            shootConfetti();
        });
    } else {
        $(".post_send").fadeOut("slow", function () {
            $("#main").fadeIn("slow");
            alert("Something went wrong, very sorry :(");
        });
    }
}

function submit(object) {

    $(".post_send").fadeIn("slow", function () { });
    $("#main").hide();

    let maturity;
    let identity;

    if ($("#clean").hasClass("selected")) {
        maturity = "clean";
    } else {
        maturity = "dirty";
    }

    if ($("#identified").hasClass("selected")) {
        identity = $("#identity").val();
    } else {
        identity = "anonymous";
    }

    $.ajax({
        type: "POST",
        url: "sendJoke.php",
        data: {
            "joke": $("#custom_joke_text").val(),
            "recipient": $("#recipient").val(),
            "carrier": $("#carrier").val(),
            "maturity": maturity,
            "identity": identity
        },
        cache: false,
        success: function (msg) {
            handleResponse(msg);
        },
        error: function (msg) {
            handleResponse(msg);
        }
    });
}


function toggleSelect(object) {
    $(object).parent().children().not(object).removeClass("selected");
    $(object).addClass("selected");

    // custom joke or random joke
    if ($(object).attr("id") == "custom_joke") {
        $("#custom_joke_text").parent().removeClass("hidden");
        $("#dirty").parent().addClass("hidden");
    } else if ($(object).attr("id") == "randomized") {
        $("#custom_joke_text").parent().addClass("hidden");
        $("#dirty").parent().removeClass("hidden");
    }

    if ($(object).attr("id") == "anonymous") {
        $("#identity").parent().addClass("hidden");
    } else if ($(object).attr("id") == "identified") {
        $("#identity").parent().removeClass("hidden");
    }
}
