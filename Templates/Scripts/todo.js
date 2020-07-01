function TODOList()
{
    this.TODO_DOM_element = document.getElementsByClassName("todo-list")[0];
    this.blackboard_DOM_element = document.getElementsByClassName("blackboard")[0];
    this.add_task_form_DOM_element = document.getElementsByClassName("add-task-form")[0];
    this.edit_task_form_DOM_element = document.getElementsByClassName("edit-task-form")[0];

    document.getElementsByTagName("body")[0].removeChild(this.add_task_form_DOM_element);
    document.getElementsByTagName("body")[0].removeChild(this.edit_task_form_DOM_element);

    let success_message_DOM_element = document.getElementsByClassName("data-sent-alert")[0];
    let error_message_DOM_element = document.getElementsByClassName("bad-input-alert")[0];

    let add_window_add_task_button = this.TODO_DOM_element.getElementsByClassName("add-task-button")[0];
    let add_window_submit_task_button = this.add_task_form_DOM_element.getElementsByClassName("submit-task-button")[0];
    let add_window_close_button = this.add_task_form_DOM_element.getElementsByClassName("close-button")[0];

    let add_window_username_field = this.add_task_form_DOM_element.getElementsByClassName("username-todo-intput")[0];
    let add_window_useremail_field = this.add_task_form_DOM_element.getElementsByClassName("useremail-todo-intput")[0];
    let add_window_usertask_field = this.add_task_form_DOM_element.getElementsByClassName("usertask-todo-intput")[0];
    let self = this;

    let edit_window_close_button = this.edit_task_form_DOM_element.getElementsByClassName("close-button")[0];

    let edit_window_usertask_field = this.edit_task_form_DOM_element.getElementsByClassName("usertask-todo-intput")[0];
    let edit_window_is_completed_field = this.edit_task_form_DOM_element.getElementsByClassName("task_completed_checkbox")[0];
    let edit_window_save_changes_button = this.edit_task_form_DOM_element.getElementsByClassName("save-task-changes-button")[0];

    let headers_DOM_elements = this.TODO_DOM_element.getElementsByClassName("headers")[0].getElementsByClassName("col");
    for(let i = 0; i < headers_DOM_elements.length; i++)
    {
        headers_DOM_elements[i].addEventListener("click", function () {
            sort_by_header(headers_DOM_elements[i]);
        });
    }

    let edit_buttons = document.getElementsByClassName("edit_row_button");
    for(let i = 0; i < edit_buttons.length; i++)
    {
        edit_buttons[i].addEventListener("click", function () {
            edit_task_button_click(self, edit_buttons[i]);
        })
    }

    add_window_add_task_button.addEventListener("click", function () {
        add_window_add_task_button_click(self);
    });

    add_window_submit_task_button.addEventListener("click", function () {
        add_window_submit_task_button_click();
    });

    add_window_close_button.addEventListener("click", function () {
        edit_window_close_button_click(self);
    });

    edit_window_save_changes_button.addEventListener("click", function () {
        edit_window_save_changes_button_click(edit_window_save_changes_button.attributes["editing-task"].value)
    });

    edit_window_close_button.addEventListener("click", function () {
        edit_window_close_button_click(self)
    });

    function add_window_add_task_button_click (todo) {
        todo.blackboard_DOM_element.appendChild(todo.add_task_form_DOM_element);
        todo.blackboard_DOM_element.style.display = "flex";
        $(todo.blackboard_DOM_element).animate({ opacity: 1 }, 300);
    }
    function add_window_submit_task_button_click() {
        if(!validate_fields()) return;

        let data = {
            name : add_window_username_field.value,
            email : add_window_useremail_field.value,
            task : add_window_usertask_field.value
        };

        add_window_send_data_to_server(data)
    }
    
    function edit_task_button_click(todo, edit_button) {
        todo.blackboard_DOM_element.appendChild(todo.edit_task_form_DOM_element);
        todo.blackboard_DOM_element.style.display = "flex";
        $(todo.blackboard_DOM_element).animate({ opacity: 1 }, 300);
        AjaxQuery("/Home/Index/GetTask/task=" + edit_button.attributes["edit-task-id"].value, "get", {}, false, function (answer) {
            edit_window_save_changes_button.setAttribute("editing-task", edit_button.attributes["edit-task-id"].value);
            edit_window_usertask_field.value = answer["Arguments"][0]["task_content"];
            edit_window_is_completed_field.checked = answer["Arguments"][0]["is_completed"] == 1;
        }, function () {
            error_message_DOM_element.getElementsByTagName("p")[0].innerText = "Troubles occurred when receiving data from remote server. Please, reload page!";
            $(error_message_DOM_element).animate({ opacity: 1 }, 300, function () {
                setTimeout(function () {
                    $(error_message_DOM_element).animate({ opacity: 0 }, 300);
                }, 5000);
            });
        });
    }

    function edit_window_save_changes_button_click(id)
    {
        if(!validate_editor_fields()) return;

        let data = {
            id : id,
            task : edit_window_usertask_field.value,
            is_completed : edit_window_is_completed_field.checked ? 1 : 0
        };

        send_editor_data_to_server(data);
    }

    function validate_editor_fields()
    {
        let errors = [];
        if(edit_window_usertask_field.value.length < 1) errors.push("Please, enter task!");
        if(errors.length > 0)
        {
            error_message_DOM_element.getElementsByTagName("p")[0].innerText = errors.join("\n");
            $(error_message_DOM_element).animate({ opacity: 1 }, 300, function () {
                setTimeout(function () {
                    $(error_message_DOM_element).animate({ opacity: 0 }, 300);
                }, 5000);
            });

            return false
        }
        else
            return true;
    }

    function validate_fields() {
        let errors = [];
        if(add_window_username_field.value.length < 1) errors.push("Please, enter your name!");
        const re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        if(!re.test(String(add_window_useremail_field.value).toLowerCase())) errors.push("Given email hasn't valid format!");
        if(add_window_usertask_field.value.length < 1) errors.push("Please, enter task!");

        if(errors.length > 0)
        {
            error_message_DOM_element.getElementsByTagName("p")[0].innerText = errors.join("\n");
            $(error_message_DOM_element).animate({ opacity: 1 }, 300, function () {
                setTimeout(function () {
                    $(error_message_DOM_element).animate({ opacity: 0 }, 300);
                }, 5000);
            });

            return false;
        }
        else
            return true;
    }
    
    function add_window_send_data_to_server(data) {
        AjaxQuery("/Home/Index/AddTask", "post", data, true,
function () {
            edit_window_close_button_click(self);
            $(success_message_DOM_element).animate({ opacity: 1 }, 300, function () {
                setTimeout(function () {
                    $(success_message_DOM_element).animate({ opacity: 0 }, 300);
                }, 5000);
            });
        },
    function (errors) {
            error_message_DOM_element.getElementsByTagName("p")[0].innerText = errors.join("\n");
            $(error_message_DOM_element).animate({ opacity: 1 }, 300, function () {
                setTimeout(function () {
                    $(error_message_DOM_element).animate({ opacity: 0 }, 300);
                }, 5000);
            });
        });
    }

    function send_editor_data_to_server(data)
    {
        AjaxQuery("/Home/Index/EditTask", "post", data, true,
            function () {
                edit_window_close_button_click(self);
                $(success_message_DOM_element).animate({ opacity: 1 }, 300, function () {
                    setTimeout(function () {
                        $(success_message_DOM_element).animate({ opacity: 0 }, 300);
                    }, 5000);
                });
            },
            function (errors) {
                error_message_DOM_element.getElementsByTagName("p")[0].innerText = errors.join("\n");
                $(error_message_DOM_element).animate({ opacity: 1 }, 300, function () {
                    setTimeout(function () {
                        $(error_message_DOM_element).animate({ opacity: 0 }, 300);
                    }, 5000);
                });
            });
    }
    
    function edit_window_close_button_click(todo) {
        $(todo.blackboard_DOM_element).animate({ opacity: 0 }, 300, function () {
            todo.blackboard_DOM_element.style.display = "none";
        });

        todo.blackboard_DOM_element.innerHTML = "";
    }

    function sort_by_header(elem) {
        let sort_by = elem.attributes["sort-option"].value;
        let splitted_pathname = split_pathname();
        if(splitted_pathname["args"]["sortby"] !== sort_by)
        {
            splitted_pathname["args"]["sortby"] = sort_by;
            splitted_pathname["args"]["sortdir"] = "ASC";
        }
        else
            splitted_pathname["args"]["sortdir"] = splitted_pathname["args"]["sortdir"].toLowerCase() == "asc" ? "DESC" : "ASC";

        window.location.href = "/" + construct_pathname(splitted_pathname)
    }

    function split_pathname() {
        let uri_params = {"parts" : [], "args" : {}};
        let splitted_pathname = window.location.pathname.split("/");
        for(let i = 0; i < splitted_pathname.length; i++)
            if(splitted_pathname[i] === "")
                splitted_pathname.splice(i, 1);

        let first_parameter_found = false;
        for(let i = 0; i < splitted_pathname.length; i++)
        {
            if(splitted_pathname[i].indexOf("=") !== -1 || first_parameter_found)
            {
                let key_value = splitted_pathname[i].split("=");
                if(key_value.length > 1)
                {
                    uri_params["args"][key_value[0]] = key_value.splice(1).join("=");
                }
                else
                    uri_params["args"][key_value[0]] = 1;
                first_parameter_found = true;
            }
            else uri_params["parts"].push(splitted_pathname[i])
        }

        return uri_params;
    }
    
    function construct_pathname(uri_params) {
        let parts_part = uri_params["parts"].join("/");
        let args_part = "";
        for(let i = 0; i < Object.keys(uri_params["args"]).length; i++)
        {
            args_part += "/" + Object.keys(uri_params["args"])[i] + "=" + uri_params["args"][Object.keys(uri_params["args"])[i]];
        }
        return parts_part + args_part;
    }
}

let TODO = new TODOList();