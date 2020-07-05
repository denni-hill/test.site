function PopupMessage() {
    document.getElementsByTagName("body")[0].insertAdjacentHTML("beforeend", "<div class=\"alert alert-danger bad-input-alert\" role=\"alert\" id=\"popup-message-error-message\">\n" +
        "        <h4 class=\"alert-heading\">Something went wrong...</h4>\n" +
        "        <p></p>\n" +
        "    </div>");

    document.getElementsByTagName("body")[0].insertAdjacentHTML("beforeend", "<div class=\"alert alert-success data-sent-alert\" role=\"alert\" id=\"popup-message-success-message\">\n" +
        "        <h4 class=\"alert-heading\">Wait a moment!</h4>\n" +
        "        <p>Data successfully sent to server!</p>\n" +
        "    </div>");

    this.ErrorField = document.getElementById("popup-message-error-message");
    this.SuccessField = document.getElementById("popup-message-success-message");

    let style = "position: absolute;\n" +
        "  bottom: 0px;\n" +
        "  margin-bottom: 0px;\n" +
        "  width: 100%;\n" +
        "  opacity: 0;\n" +
        "  z-index: 150;";

    this.ErrorField.setAttribute("style", style);
    this.SuccessField.setAttribute("style", style);

    this.ShowErrorMessage = function(header, message, callback, duration) {
        this.ErrorField.getElementsByTagName("h4")[0].innerText = header;
        this.ErrorField.getElementsByTagName("p")[0].innerText = message;
        ShowMessage(this.ErrorField, callback, duration);
    };

    this.ShowSuccessMessage = function(header, message, callback, duration) {
        this.SuccessField.getElementsByTagName("h4")[0].innerText = header;
        this.SuccessField.getElementsByTagName("p")[0].innerText = message;
        ShowMessage(this.SuccessField, callback, duration);
    };

    function ShowMessage(DOM_object, callback, duration)
    {
        $(DOM_object).animate({ opacity: 1 }, 300, function () {
            if(duration !== undefined && !isNaN(duration)) duration = 2000;
            setTimeout(function () {
                $(DOM_object).animate({ opacity: 0 }, 300);
                if(callback !== undefined)
                    callback();
            }, duration);
        });
    }
}

let Popup = new PopupMessage();