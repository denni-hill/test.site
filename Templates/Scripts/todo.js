function TODOList() {
    this.AddTaskWindow = new AddTaskWindow(document.getElementsByClassName("add-task-form")[0], this);
    this.EditTaskWindow = new EditTaskWindow(document.getElementsByClassName("edit-task-form")[0], this);

    this.DOMElement = document.getElementsByClassName("todo-list")[0];
    this.BlackBoardDOMElement = document.getElementsByClassName("blackboard")[0];
    let self = this;

    let headersDOMElements = this.DOMElement.getElementsByClassName("headers")[0].getElementsByClassName("col");
    for(let i = 0; i < headersDOMElements.length; i++)
    {
        headersDOMElements[i].addEventListener("click", function () {
            sort_by_header(headersDOMElements[i]);
        });
    }

    let editButtons = document.getElementsByClassName("edit_row_button");
    for(let i = 0; i < editButtons.length; i++)
    {
        editButtons[i].addEventListener("click", function () {
            editTaskButtonClick(self, editButtons[i]);
        })
    }

    document.getElementsByClassName("add-task-button")[0].addEventListener("click", function () {
        OpenAddTaskWindow(self);
    });

    document.getElementsByClassName("add-task-button")[0].addEventListener("click", function () {
        OpenAddTaskWindow(self);
    });

    function editTaskButtonClick(TODOListObject, editButton) {
        TODOListObject.BlackBoardDOMElement.appendChild(TODOListObject.EditTaskWindow.DOMElement);
        TODOListObject.BlackBoardDOMElement.style.display = "flex";
        $(TODOListObject.BlackBoardDOMElement).animate({ opacity: 1 }, 300);
        TODOListObject.EditTaskWindow.DisableInput();
        AjaxQuery("/Home/api/GetTask", "post", {"task" : editButton.attributes["edit-task-id"].value}, false, function (answer) {
            TODOListObject.EditTaskWindow.SubmitButton.setAttribute("editing-task", editButton.attributes["edit-task-id"].value);
            TODOListObject.EditTaskWindow.InputFields.UserTask.value = answer["Arguments"][0]["task_content"];
            TODOListObject.EditTaskWindow.InputFields.IsCompletedCheckbox.checked = answer["Arguments"][0]["is_completed"] == 1;
            TODOListObject.EditTaskWindow.EnableInput();
        }, function () {
            Popup.ShowErrorMessage("Server error!", "Troubles occurred while receiving data from remote server.\nReloading page...", function () {
                document.location.reload();
            }, 3000);
        });
    }

    function AddTaskWindow(DOMElement, TODOListObject)
    {
        let self = this;
        this.TODOListObject = TODOListObject;
        this.DOMElement = DOMElement;

        document.getElementsByTagName("body")[0].removeChild(this.DOMElement);
        this.DOMElement.style.display = "block";

        this.SubmitButton = this.DOMElement.getElementsByClassName("submit-task-button")[0];
        this.CloseButton = this.DOMElement.getElementsByClassName("close-button")[0];
        this.InputFields = {
            Username : this.DOMElement.getElementsByClassName("username-todo-intput")[0],
            UserEmail : this.DOMElement.getElementsByClassName("useremail-todo-intput")[0],
            UserTask : this.DOMElement.getElementsByClassName("usertask-todo-intput")[0]
        };

        this.SubmitButton.addEventListener("click", function () {
            SubmitButtonClick(self);
        });
        this.CloseButton.addEventListener("click", function () {
            self.TODOListObject.closeWindow();
        });

        function SubmitButtonClick(ATWindow) {
            ATWindow.DisableInput();

            if(!validateFields())
            {
                ATWindow.EnableInput();
                return;
            }

            let data = {
                name : ATWindow.InputFields.Username.value,
                email : ATWindow.InputFields.UserEmail.value,
                task : ATWindow.InputFields.UserTask.value
            };

            AjaxQuery("/Home/api/AddTask", "post", data, false,
                function (Answer) {
                    Popup.ShowSuccessMessage("Success!", "New task added!\nReloading page...", function () {
                        ATWindow.TODOListObject.closeWindow();
                        window.location.reload();
                    }, 5000);
                },
                function (errors) {
                    Popup.ShowErrorMessage("Server error!", errors.join("\n"), undefined, 5000);
                    ATWindow.EnableInput();
                }, true);
        }

        this.DisableInput = function(){
            let elements = [];
            elements.concat(Object.values(this.InputFields));
            elements.push(this.SubmitButton);
            elements.forEach(element => element.setAttribute("disabled", ""));
        };

        this.EnableInput = function(){
            let elements = [];
            elements.concat(Object.values(this.InputFields));
            elements.push(this.SubmitButton);

            elements.forEach(element => element.removeAttribute("disabled"));
        };

        function validateFields() {
            let errors = [];
            if(self.InputFields.Username.value.length < 1) errors.push("Please, enter your name!");
            const re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
            if(!re.test(String(self.InputFields.UserEmail.value).toLowerCase())) errors.push("Given email hasn't valid format!");
            if(self.InputFields.UserTask.value.length < 1) errors.push("Please, enter task!");

            if(errors.length > 0)
            {
                Popup.ShowErrorMessage("Data validation error!", errors.join("\n"), undefined, 5000);
                return false;
            }
            else
                return true;
        }
    }

    function EditTaskWindow(DOMElement, TODOListObject)
    {
        let self = this;
        this.TODOListObject = TODOListObject;
        this.DOMElement = DOMElement;
        document.getElementsByTagName("body")[0].removeChild(this.DOMElement);
        this.DOMElement.style.display = "block";

        this.SubmitButton = this.DOMElement.getElementsByClassName("save-task-changes-button")[0];
        this.CloseButton = this.DOMElement.getElementsByClassName("close-button")[0];
        this.InputFields = {
            UserTask : this.DOMElement.getElementsByClassName("usertask-todo-intput")[0],
            IsCompletedCheckbox : this.DOMElement.getElementsByClassName("task_completed_checkbox")[0]
        };

        this.SubmitButton.addEventListener("click", function () {
            SubmitButtonClick(self);
        });
        this.CloseButton.addEventListener("click", function () {
            self.TODOListObject.closeWindow();
        });

        function SubmitButtonClick(ETWindow) {
            ETWindow.DisableInput();
            if(!validateFields())
            {
                ETWindow.EnableInput();
                return;
            }

            let data = {
                id : ETWindow.SubmitButton.attributes["editing-task"].value,
                task : ETWindow.InputFields.UserTask.value,
                is_completed : ETWindow.InputFields.IsCompletedCheckbox.checked ? 1 : 0
            };

            AjaxQuery("/Home/api/EditTask", "post", data, false,
                function () {
                    Popup.ShowSuccessMessage("Success!", "Task edited!\nReloading page...", function () {
                        ETWindow.TODOListObject.closeWindow(self);
                        window.location.reload();
                    }, 5000);
                },
                function (errors) {
                    Popup.ShowErrorMessage("Server error!", errors.join("\n"), undefined, 5000);
                    ETWindow.EnableInput();
                }, true);
        }

        function validateFields() {
            let errors = [];
            if(self.InputFields.UserTask.value.length < 1) errors.push("Please, enter task!");
            if(errors.length > 0)
            {
                Popup.ShowErrorMessage("Data validation error!", errors.join("\n"), undefined, 5000);
                return false
            }
            else
                return true;
        }

        this.DisableInput = function() {
            let elements = [];
            elements.concat(Object.values(this.InputFields));
            elements.push(this.SubmitButton);
            elements.forEach(element => element.setAttribute("disabled", ""));
        };

        this.EnableInput = function (){
            let elements = [];
            elements.concat(Object.values(this.InputFields));
            elements.push(this.SubmitButton);

            elements.forEach(element => element.removeAttribute("disabled"));
        }
    }


    function OpenAddTaskWindow (TODOListObject) {
        TODOListObject.BlackBoardDOMElement.appendChild(TODOListObject.AddTaskWindow.DOMElement);
        TODOListObject.BlackBoardDOMElement.style.display = "flex";
        $(TODOListObject.BlackBoardDOMElement).animate({ opacity: 1 }, 300);
    }

    function sort_by_header(elem) {
        let sort_by = elem.attributes["sort-option"].value;
        let splitted_pathname = pathname.SplitPathname();
        if(splitted_pathname["args"]["sortby"] !== sort_by)
        {
            splitted_pathname["args"]["sortby"] = sort_by;
            splitted_pathname["args"]["sortdir"] = "ASC";
        }
        else
            splitted_pathname["args"]["sortdir"] = splitted_pathname["args"]["sortdir"].toLowerCase() == "asc" ? "DESC" : "ASC";

        window.location.href = "/" + pathname.ConstructPathname(splitted_pathname)
    }

    this.closeWindow = function () {
        $(this.BlackBoardDOMElement).animate({ opacity: 0 }, 300, function () {
            self.BlackBoardDOMElement.style.display = "none";
        });

        this.BlackBoardDOMElement.innerHTML = "";
    };
}

let TODO = new TODOList();