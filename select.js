
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


function isNumeric(n) {
    return !isNaN(parseFloat(n)) && isFinite(n);
}


/**
 * handle response from server
 * 
 * @param {JSON} response json response
 */
function handleResponse(response) {
    if (response["status"] == 200) {

        // shoot confetti and reset everything!
        $(".post_send").fadeOut("fast", function () {
            $("#main").fadeIn("fast");
            shootConfetti();
            $("#randomized").addClass("selected");
            $("#clean").addClass("selected");
            $("#anonymous").addClass("selected");
            
            $("#custom_joke").removeClass("selected");
            $("#dirty").removeClass("selected");
            $("#identified").removeClass("selected");
            
            $("#recipient").val("");

            // remove custom joke
            $("#custom_joke_text").parent().addClass("hidden");
            $("#custom_joke_text").val("");
            $("#dirty").parent().removeClass("hidden");

            // remove identity
            $("#identity").parent().addClass("hidden");
            $("#identity").val("");
        });
    } else {
        $(".post_send").fadeOut("slow", function () {
            $("#main").fadeIn("slow");
            // this means a user input error
            if(response.status == 422) {
                alert(response.message);
            } else {
                alert("Something broke, what did ya expect from free software? :P");
            }
        });
    }
}

// on form submit
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

    let recipient = $("#recipient").val();
    let u = $("#u").text();

    if(recipient) {
        recipient = parseRecipient(recipient);
    }

    let data = {
        "joke": $("#custom_joke_text").val(),
        "recipient": recipient,
        "carrier": $("#carrier").val(),
        "maturity": maturity,
        "identity": identity,
    }

    if(u) {
        data["u"] = u;
    }

    $.ajax({
        type: "POST",
        url: "sendJoke.php",
        data: data,
        cache: false,
        success: function (msg) {
            handleResponse(msg);
        },
        error: function (msg) {
            handleResponse(msg);
        }
    });
}

// make sure recipient is email or phone number, also make sure numbers
// are clean
function parseRecipient(recipient) {
    // strip dashes and parentheses from phone number
    if(recipient.indexOf('@') == -1) {
        recipient = recipient.replace(/-/g,'');
        recipient = recipient.replace('(','');
        recipient = recipient.replace(')','');
        recipient = recipient.replace(/ /g,'');
    }

    let exp = new RegExp("[\+][0-9]", 'i');

    // strip out any leading international codes eg. +1
    if(exp.test(recipient)) {
        recipient = recipient.substr(2, recipient.length);
    }

    // someone probably put a 1 in front of the number
    if(recipient.length > 10 && recipient.indexOf('@') == -1) {
        recipient = recipient.substr(1, recipient.length);
    }

    return recipient;
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
