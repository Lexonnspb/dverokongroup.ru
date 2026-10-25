var uploaders = document.getElementsByClassName('yaDiskUploader');
var Dropzones = [];
if (uploaders.length > 0) {
    for (var i = 0; i < uploaders.length; i++) {
        uploaders[i].classList.add('dropzone_' + (i+1));
        Dropzones[i] = new Dropzone('.dropzone_' + (i+1), {
            url: document.location.href,
            paramName: uploaders[i].dataset.name
        });
        Dropzones[i].on('success', function(e, response) {
            response = JSON.parse(response);
            document.getElementById(response.inputId).setAttribute('value', response.url);
        });
    }
}