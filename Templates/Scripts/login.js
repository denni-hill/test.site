document.addEventListener("keyup", function(event)
{
    if(event.keyCode == 13)
        SubmitLoginForm();
});
document.getElementById("submit-button").addEventListener("click", SubmitLoginForm);

function SubmitLoginForm()
{
    let SubmitButton = document.getElementById("submit-button");
    let LoginField = document.getElementById("login-field");
    let PasswordField = document.getElementById("password-field");

    if(SubmitButton.hasAttribute("disabled"))
        return;

    DisableInput();

    let Login = LoginField.value;
    let Password = PasswordField.value;

    if(Login.length == 0 || Password.length == 0)
    {
        Popup.ShowErrorMessage("Input error!", "Please, enter login and password!", undefined, 5000);
        EnableInput();
        return;
    }

    Popup.ShowSuccessMessage("Wait a moment!", "Data was sent to server!", undefined, 5000);

    function success(Answer) {
        Popup.ShowSuccessMessage("Welcome!", "Authentication succeed!", function () {
            window.location = Answer["Redirect"];
        }, 2000);
    }

    function fail(Answer) {
        Popup.ShowErrorMessage("Errors occurred!", Answer.join('<br>'), undefined, 5000);
        EnableInput();
    }

    setTimeout(function(){
        let Hash = SHA256(SHA256(Login)+SHA256(Password));
    
        AjaxQuery("/Auth/api/Index", "post", {hash : Hash}, false, success, fail, true);
    }, 5000);

    function EnableInput() {
        SubmitButton.removeAttribute("disabled");
        LoginField.removeAttribute("disabled");
        PasswordField.removeAttribute("disabled");
    }

    function DisableInput() {
        SubmitButton.setAttribute("disabled", "");
        LoginField.setAttribute("disabled", "");
        PasswordField.setAttribute("disabled", "");
    }
}