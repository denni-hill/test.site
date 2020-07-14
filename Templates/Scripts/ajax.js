//Simple jquary based ajax query with integrated server responses handler for this MVC

function AjaxQuery(qurl, qtype, qdata, qreload, success, fail, handle_redirection)
{
    $.ajax({
        url: qurl,
        type: qtype,
        data: qdata,
        success: function (response)
        {
            let Answer = JSON.parse(response);
            document.ServerArguments = Answer['Arguments'];
            if(Answer['Errors'].length > 0 || Answer['ConsoleLogs'].length > 0)
            {
                for(let i = 0; i < Answer['ConsoleLogs'].length; i++)
                    console.log(Answer['ConsoleLogs'][i]);
                if(fail !== undefined)
                {
                    fail(Answer["Errors"]);
                }
            }
            else
            {
                if(Answer['Redirect'] !== "")
                {
                    if(handle_redirection === undefined || handle_redirection === false) window.location = Answer['Redirect'];
                }
                else
                {
                    if(qreload) window.location.reload();
                }
                if(success !== undefined)
                    success(Answer);
            }
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            console.log(textStatus, errorThrown);
        }
    });
}