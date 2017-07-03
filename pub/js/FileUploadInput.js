/**
 * File Upload Input Js File
 * @author Eugene Lazarchuk
 * @constructor
 * @param {type} initData
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
        failure: this.bind(this,this.failure),
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
    this.helpBlock.innerHTML = '';
    if(this.helpBlock.parentNode.classList.contains('has-error')){
        this.helpBlock.parentNode.classList.remove('has-error');
    }
    if (result.success === true) {
        this.setValue(result.access_link);
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
    this.helpBlock.innerHTML = 'Unknown error';
    if(!this.helpBlock.parentNode.classList.contains('has-error')){
        this.helpBlock.parentNode.classList.add('has-error');
    }
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
    this.updateProgressBar(0);
    this.setValue("");
};

FileUploadInput.prototype.reset = function () {
    this.updateProgressBar(0);
    this.setValue(this.originalValue);
};

FileUploadInput.prototype.setValue = function(value){
    this.field.value = value;
    if (this.preview) {
        this.preview.innerHTML = value;
    }
};
