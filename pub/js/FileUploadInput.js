/**
 * @author Eugene Lazarchuk
 */

/**
 * File Upload Input Js File
 * @constructor
 * @returns {FileUploadInput}
 */
function FileUploadInput(initData) {
    this.uploader = null;
    this.progressBarId = null;
    this.field = null;
    this.preview = null;
    this.originalValue = '';
    this.afterUpload = null;
    this.init(initData);
}

FileUploadInput.prototype.cloneArray = function (inArrayLike, inOffset, inStartWith) {
    var arr = inStartWith || [];
    for (var i = inOffset || 0, l = inArrayLike.length; i < l; i++) {
        arr.push(inArrayLike[i]);
    }
    return arr;
};

FileUploadInput.prototype.bind = function (scope, method/*, bound arguments*/) {
    if (!method) {
        method = scope;
        scope = null;
    }
    
    var args = this.cloneArray(arguments, 2);
    if (method.bind) {
        return method.bind.apply(method, [scope].concat(args));
    } else {
        return function () {
            var nargs = this.cloneArray(arguments);
            // invoke with collected args
            return method.apply(scope, args.concat(nargs));
        };
    }
};

FileUploadInput.prototype.init = function (initData) {
    this.uploader = new AjaxFileUploader({
        fileInputId: initData.fileInputId,
        uploadUrl: initData.uploadUrl,
        formId: initData.formId,
        success: this.bind(this,this.success),
        falure: this.bind(this,this.failure),
        progress: this.bind(this,this.progress)
    });

    this.progressBarId = initData.progressBarId;
    this.field = document.getElementById(initData.fieldId);
    this.preview = document.getElementById(initData.filePreviewId);
    this.originalValue = this.field.value;
    this.helpBlock = null;
    for( var i = 0; i < this.field.parentNode.children.length; i++){
        if(this.field.parentNode.children[i].classList.contains('help-block')){
            this.helpBlock = this.field.parentNode.children[i];
            
            break;
        }
    }
    
    if(initData.events.afterUpload){
        this.afterUpload = function(){eval(initData.events.afterUpload);};
    }
};

FileUploadInput.prototype.success = function (result) {
    if(this.helpBlock.parentNode.classList.contains('has-error')){
        this.helpBlock.parentNode.classList.remove('has-error');
    }
    if (result.success === true) {
        setValue(result.access_link);
        if(this.afterUpload){
            this.afterUpload();
        }
    } else {
        this.helpBlock.innerHTML = result.message;
        if(!this.helpBlock.parentNode.classList.contains('has-error')){
            this.helpBlock.parentNode.classList.add('has-error');
        }
    }
    setTimeout(this.bind(this,this.updateProgressBar), 3000, 0);
};

FileUploadInput.prototype.failure = function (data) {
    console.log('failure', data);
};

FileUploadInput.prototype.progress = function (done, total, percent) {
    this.updateProgressBar(percent);
    //console.log('progress', done, total, percent);
};

FileUploadInput.prototype.upload = function () {
    this.updateProgressBar(0);
    this.uploader.run();
};

FileUploadInput.prototype.updateProgressBar = function (percent) {
    $('#' + this.progressBarId + ' .progress-bar').css({width: percent + '%'});
};

FileUploadInput.prototype.clear = function () {
    this.setValue("");
};

FileUploadInput.prototype.reset = function () {
    this.setValue(this.originalValue);
};

FileUploadInput.prototype.setValue = function(value){
    this.field.value = value;
    if (this.preview) {
        this.preview.innerHTML = value;
    }
};



//function FileUploadInput(initData) {
//    var uploader = null;
//    var progressBarId = null;
//    var field = null;
//    var filePreview = null;
//    var originalValue = '';
//    var afterUpload = null;
//
//    var init = function (initData) {
//        uploader = new AjaxFileUploader({
//            fileInputId: initData.fileInputId,
//            uploadUrl: initData.uploadUrl,
//            formId: initData.formId,
//            success: success,
//            falure: failure,
//            progress: progress
//        });
//
//        progressBarId = initData.progressBarId;
//        field = document.getElementById(initData.fieldId);
//        filePreview = document.getElementById(initData.filePreviewId);
//        originalValue = field.value;
//        if(initData.events.afterUpload){
//            afterUpload = function(){eval(initData.events.afterUpload);};
//        }
//    };
//
//    var success = function (result) {
//        if (result.success === true) {
//            setValue(result.access_link);
//            if(afterUpload){
//                afterUpload();
//            }
//        } else {
//            console.log('success', result);
//        }
//        setTimeout(updateProgressBar, 3000, 0);
//    };
//
//    var failure = function (data) {
//        console.log('failure', data);
//    };
//
//    var progress = function (done, total, percent) {
//        updateProgressBar(percent);
//        //console.log('progress', done, total, percent);
//    };
//
//    this.upload = function () {
//        updateProgressBar(0);
//        uploader.run();
//    };
//
//    var updateProgressBar = function (percent) {
//        $('#' + progressBarId + ' .progress-bar').css({width: percent + '%'});
//    };
//    
//    this.clear = function () {
//        setValue("");
//    };
//    
//    this.reset = function () {
//        setValue(originalValue);
//    };
//    
//    var setValue = function(value){
//        field.value = value;
//        if (filePreview) {
//            filePreview.innerHTML = value;
//        }
//    };
//    
//    init(initData);
//}