function FileUploadInput(initData) {
    var uploader = null;
    var progressBarId = null;
    var field = null;
    var filePreview = null;

    var init = function (initData) {
        uploader = new AjaxFileUploader({
            fileInputId: initData.fileInputId,
            uploadUrl: initData.uploadUrl,
            formId: initData.formId,
            success: success,
            falure: failure,
            progress: progress
        });

        progressBarId = initData.progressBarId;
        field = document.getElementById(initData.fieldId);
        filePreview = document.getElementById(initData.filePreviewId);
    };

    var success = function (result) {
        if (result.success === true) {
            field.value = result.value;
            if (filePreview) {
                filePreview.innerHTML = result.value;
            }
        } else {
            console.log('success', result);
        }
    };

    var failure = function (data) {
        console.log('failure', data);
    };

    var progress = function (done, total, percent) {
        updateProgressBar(percent);
        //console.log('progress', done, total, percent);
    };

    this.upload = function () {
        updateProgressBar(0);
        uploader.run();
    };

    var updateProgressBar = function (percent) {
        $('#' + progressBarId + ' .progress-bar').css({width: percent + '%'});
    };
    
    this.clear = function () {
        field.value = '';
        if (filePreview) {
            filePreview.innerHTML = '';
        }
    };

    init(initData);
}