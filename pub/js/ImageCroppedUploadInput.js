function ImageCroppedUploadInput(initData) {
    var uploader = null;
    var progressBarId = null;
    var field = null;
    var filePreview = null;
    var originalValue = '';

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
        
    };

    var success = function (result) {
        if (result.success === true) {
            makeCroping(result.access_link);
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
    
    var makeCroping = function(imageLink){
        createOverlay(imageLink);
//        setValue(value);
    };
    
    var createOverlay = function(imageLink){
        var overlay = document.createElement('div');
        overlay.classList.add('image-crop-overlay');
        overlay.style.lineHeight = window.innerHeight + 'px';
        document.getElementsByTagName("BODY")[0].appendChild(overlay);
        
        var image = document.createElement('img');
        image.src = imageLink;
        overlay.appendChild(image);

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