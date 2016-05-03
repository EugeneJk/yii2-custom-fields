function ImageCroppedUploadInput(initData) {
    var uploader = null;
    var progressBarId = null;
    var field = null;
    var filePreview = null;
    var originalValue = '';
    
    
    
    
    var self = this;
    this.iWidth = 0;
    this.iHeight = 0;
    this.cropWidth = 100;//options.cropWidth;
    this.cropHeight = 100;//options.cropHeight;
    this.cropX = 0;
    this.cropY = 0;
//    var imageToCrop = document.getElementById(options.cropImageId);
//    var thumnailField = document.getElementById(options.thumbnailId);
//    var thumnailPreview = document.getElementById(options.thumbnailPreviewId);
//    var notificationArea = document.getElementById(options.notificationAreaId);
//    var originalThumb = null;

//    var cropperOverlayId = 'cropper-overlay';
//    var cropperImageId = 'cropper-image';
//    var cropperOverlayBodyId = 'cropper-overlay-body';
//    var cropperOverlayFooterId = 'cropper-overlay-footer';
   
    

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
        var croppingImageId = showCroppingLayout(imageLink);
        activateCrop(croppingImageId);
        
//        setValue(value);
    };
    
    var activateCrop = function(croppingImageId){
        cropresizer.getObject(croppingImageId).init({
            cropWidth : 100,
            cropHeight : 100,
            onUpdate : function() {
                console.log(this.iWidth,this.iHeight,this.cropWidth, this.cropHeight,this.cropLeft - this.iLeft,this.cropTop - this.iTop);
//                self.iWidth = this.iWidth;
//                self.iHeight = this.iHeight;
//                self.cropWidth = this.cropWidth;
//                self.cropHeight = this.cropHeight;
//                self.cropX = this.cropLeft - this.iLeft;
//                self.cropY = this.cropTop - this.iTop;
            }
        },1);
    };
    
    
    var showCroppingLayout = function(imageLink){
        var overlay = crearteOverlay();
        document.getElementsByTagName("BODY")[0].appendChild(overlay);
        
        var overlayBody = createOverlayBody();
        overlay.appendChild(overlayBody);
        
        var image = createCroppedImage(imageLink);
        overlayBody.appendChild(image);

//        drawFooter();
        return image.id;
    };
    
    var crearteOverlay = function(){
        var item = document.createElement('div');
        item.classList.add('image-crop-overlay');
        item.style.lineHeight = window.innerHeight + 'px';
        return item;
    };
    
    var createOverlayBody = function(){
        var item = document.createElement('div');
        item.classList.add('image-crop-overlay-body');
        return item;
    };
    
    var createCroppedImage = function(imageLink){
        var item = document.createElement('img');
        item.id = 'image-for-crop';
        item.src = imageLink;
        return item;
    };
    
//    var drawFooter = function(){
//        $("<div />",{
//            id: cropperOverlayFooterId
//        }).appendTo($("#" + cropperOverlayId));
//        
//        $("<button/>",{
//            class:"cropper-button-close btn btn-default pull-right",
//            onclick: options.objectVariableName + '.close();return false;'
//        }).html('Close <i class="glyphicon glyphicon-remove"></i>').appendTo($("#" + cropperOverlayFooterId));
//        
//        $("<button/>",{
//            class:"cropper-button-close btn btn-success pull-right",
//            onclick: options.objectVariableName + '.crop();return false;'
//        }).html('Apply <i class="glyphicon glyphicon-ok"></i>').appendTo($("#" + cropperOverlayFooterId));
//    };
    
//    this.close = function(){
//        $('#' + cropperOverlayId).remove();
//        $('#resizeDivId_' + cropperImageId).remove();
//        $('#cropDivId_' + cropperImageId).remove();
//    };
    
    
    
    var setValue = function(value){
        field.value = value;
        if (filePreview) {
            filePreview.style.display = (value == '') ? 'none' : '';
            filePreview.src = value;
        }
    };
    
    init(initData);
}