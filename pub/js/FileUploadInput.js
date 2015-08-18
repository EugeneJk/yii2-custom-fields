function FileUploadInput (initData) {
    var _uploader = null;
    var init = function(initData){
        _uploader = new AjaxFileUploader({
            uploadInputId: initData.uploadInputId,
            uploadUrl: initData.uploadUrl,
            formId: initData.formId,
            success: success,
            falure: failure,
            progress: progress
        });
        
    };
    
    var success = function(result){
        console.log('success', result);
    };
    
    var failure = function(data){
        console.log('faiilure', data);
    };
    
    var progress = function(done, total, percent){
        console.log('progress', done, total, percent);
    };
    
    init(initData);
}