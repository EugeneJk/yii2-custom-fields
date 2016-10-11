function ImageUploadInput(initData) {
    var uploader = null;
    var progressBarId = null;
    var field = null;
    var filePreview = null;
    var originalValue = '';
    var afterUpload = null;

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
        originalValue = field.value;
        if(field.value == ''){
            filePreview.style.display = 'none';
        }
        if(initData.events.afterUpload){
            afterUpload = function(){eval(initData.events.afterUpload);};
        }
    };

    var success = function (result) {
        if (result.success === true) {
            setValue(result.access_link);
            if(afterUpload){
                afterUpload();
            }            
        } else {
            console.log('success', result);
        }
        setTimeout(updateProgressBar, 3000, 0);
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
        setValue("");
        updateProgressBar(0);
    };
    
    this.reset = function () {
        setValue(originalValue);
        updateProgressBar(0);
    };
    
    var setValue = function(value){
        field.value = value;
        if (filePreview) {
            filePreview.style.display = (value == '') ? 'none' : '';
            filePreview.src = value;
        }
    };
    
    init(initData);
}