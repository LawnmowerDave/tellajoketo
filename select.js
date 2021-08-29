
function handleResponse(response) {
    if(response["status"] == 200) {
        alert("Your joke has been sent!")
    } else {
        alert("Something went wrong! " + response["message"]);
    }
}

function submit(object) {

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
            // let response = JSON.parse(JSON.stringify(msg));
            // console.log(response["status_code"]);
        },
        error: function(msg) {
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

function handleKeyPress(object) {

    let value = $("#recipient").val();

    // add carrier option if it's a number
    if (value.length > 5 && $.isNumeric(value)) {
        $("#carrier").parent().removeClass("hidden");
    } else {
        $("#carrier").parent().addClass("hidden");
    }
}