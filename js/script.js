/* ...Enable buttons... */
function enableButton(name) {
    document.querySelector(name).disabled = false;
}

/* ...Disable buttons... */
function disableButton(name) {
    document.querySelector(name).disabled = true;
}

/* ...Response parser... */
function responseParser(name, data) {
    document.querySelector(name).innerHTML = data;
}

/* ...Control expanding of details... */
const details = document.querySelectorAll("details");
details.forEach((targetDetail) => {
    targetDetail.addEventListener("click", () => {
        // Close all the details that are not targetDetail.
        details.forEach((detail) => {
            if (detail !== targetDetail) {
                detail.removeAttribute("open");
            }
        });
    });
});

/* ...Load last modified log on page load... */
document.addEventListener('DOMContentLoaded', function () {
    updateLog()
}, false);

/* ...Load last modified log... */
function updateLog() {
    fetch("logs/log.json")
        .then(response => response.json())
        .then(data => {
            responseParser('.maintenance-log', data.requestMaintenance);
            responseParser('.upload-log', data.storeFile);
            responseParser('.check-log', data.requestCheck);
            responseParser('.update-log', data.requestUpdate);
        });
    fetch("logs/log.txt")
        .then(x => x.text())
        .then(text => {
            let lines = text.split(/\n|\s\n/).reverse();
            responseParser('.eventLogger', lines.join("<br>"));
        });
}

/* ...Upload field reset... */
const inputFile = document.querySelector('#upload-file');
let selectedFile;
inputFile.addEventListener('change', function () {
    if (inputFile.files.length === 0) {
        inputFile.files = selectedFile;
    } else {
        selectedFile = inputFile.files;
    }
    document.querySelector('.text').textContent = inputFile.value.replace(/.*[\/\\]/, '');
    responseParser('#store-file-button', "Upload");
    document.querySelector('.progress').setAttribute("style", "width: 0%");
    enableButton('#store-file-button');
});

/* ...Send request... */
function sendRequest(request) {
    disableButton('#' + request);
    fetch("php/script.php", {
        method: "POST",
        body: "data" + '=' + request,
        headers:
            {
                "Content-Type": "application/x-www-form-urlencoded"
            }
    }).then(function (response) {
        return response.text();
    }).then(function (text) {
        if (request === "requestCheck") {
            responseParser('.remote-directory', text);
            document.querySelector('.remote-directory-container').style.display = 'block';
            let mainContent = document.querySelector('.remote-directory');
            mainContent.style.maxHeight = mainContent.scrollHeight + "px";
            enableButton('#requestUpdate');
        } else {
            responseParser('#' + request, text);
        }
        setTimeout(updateLog, 2500);
    })
}

/* ...Upload selected file... */
function storeFile() {
    const inputFile = document.querySelector('input[type="file"]');
    const data = new FormData();
    data.append('data', inputFile.files[0]);
    const connection = new XMLHttpRequest();
    connection.onloadend = function () {
        document.querySelector('.select-file-button').classList.toggle('disabled');
        responseParser('#store-file-button', this.responseText);
        setTimeout(updateLog, 2500);
    };
    connection.upload.onprogress = function (event) {
        document.querySelector('.progress').style.width = ((event.loaded / event.total) * 100) + "%";
    };
    connection.open("POST", "php/script.php");
    connection.send(data);
    /* ...Disable buttons... */
    disableButton('#store-file-button');
    document.querySelector('.select-file-button').classList.toggle('disabled');
    /* ...Clear input file... */
    document.querySelector('input[type="file"]').value = "";
}