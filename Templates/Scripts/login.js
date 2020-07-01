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

    if(SubmitButton.classList.contains("disabled"))
        return;

    SubmitButton.classList.add("disabled");
    LoginField.setAttribute("disabled", "")
    PasswordField.setAttribute("disabled", "")

    let Login = LoginField.value;
    let Password = PasswordField.value;

    let ErrorField = document.getElementById("login-form-errorMessage");
    let SuccessField = document.getElementById("login-form-successMessage");

    SuccessField.innerText = ErrorField.innerText = "";

    if(Login.length == 0 || Password.length == 0)
    {
        ErrorField.innerText = "Error! Enter login and password!";
        SubmitButton.classList.remove("disabled");
        LoginField.removeAttribute("disabled");
        PasswordField.removeAttribute("disabled");
        return;
    }

    SuccessField.innerText = "Data sent to server. Please, wait for 5 seconds!";

    function success(Answer) {
        SuccessField.innerText = "Welcome!";
    }

    function fail(Answer) {
        SuccessField.innerText = "";
        ErrorField.innerHTML = Answer['Errors'].join('<br>');
        SubmitButton.classList.remove("disabled");
        LoginField.removeAttribute("disabled");
        PasswordField.removeAttribute("disabled");
    }

    setTimeout(function(){
        let Hash = SHA256(SHA256(Login)+SHA256(Password));
    
        AjaxQuery("/Login/Index/hash=" + Hash, "post", {hash : Hash}, false, success, fail);
    }, 5000);
}