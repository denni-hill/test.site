//Converting URL to object and object to URL

function Pathname() {
    this.SplitPathname = function() {
        let uriParams = {"parts" : [], "args" : {}};
        let splittedPathname = window.location.pathname.split("/");
        for(let i = 0; i < splittedPathname.length; i++)
            if(splittedPathname[i] === "")
                splittedPathname.splice(i, 1);

        let firstParameterFound = false;
        for(let i = 0; i < splittedPathname.length; i++)
        {
            if(splittedPathname[i].indexOf("=") !== -1 || firstParameterFound)
            {
                let keyValuePair = splittedPathname[i].split("=");
                if(keyValuePair.length > 1)
                {
                    uriParams["args"][keyValuePair[0]] = keyValuePair.splice(1).join("=");
                }
                else
                    uriParams["args"][keyValuePair[0]] = 1;
                firstParameterFound = true;
            }
            else uriParams["parts"].push(splittedPathname[i])
        }

        return uriParams;
    };

    this.ConstructPathname = function (uriParams) {
        let partsPart = uriParams["parts"].join("/");
        let argsPart = "";
        for(let i = 0; i < Object.keys(uriParams["args"]).length; i++)
        {
            argsPart += "/" + Object.keys(uriParams["args"])[i] + "=" + uriParams["args"][Object.keys(uriParams["args"])[i]];
        }
        return partsPart + argsPart;
    };
}

let pathname = new Pathname();