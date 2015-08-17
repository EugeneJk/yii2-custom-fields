function AjaxFileUploader (initData) {
    var success = null;
    var failure = null;
    
    var uploader;
    var uploadUrl = '';
    var input = null;
    var form = null;
    
    
    var init = function(initData){
        input = document.getElementById(initData.uploadInputId);
        if (initData.formId !== undefined || initData.formId !== ''){
            form = document.getElementById(initData.formId);
        }
        if (initData.uploadUrl !== undefined || initData.uploadUrl !== ''){
            uploadUrl = initData.uploadUrl;
        }
    };

    var initUploader = function(){
        uploader = new XMLHttpRequest();
        uploader.onreadystatechange = function(e){
            if (uploader.readyState === 4){
                if(uploader.status === 200){
                    if(success){
                        success(JSON.parse(uploader.responseText));
                    } else {
                        console.log(JSON.parse(uploader.responseText));
                    }
                } else {
                    if(failure){
                        failure(uploader);
                    } else {
                        console.log(JSON.parse(uploader.responseText));
                    }
                }
            }
        };
        
        uploader.addEventListener('progress', function(e) {
            var done = e.position || e.loaded, total = e.totalSize || e.total;
            console.log('xhr progress: ' + (Math.floor(done/total*1000)/10) + '%');
        }, false);
        if ( uploader.upload ) {
            uploader.upload.onprogress = function(e) {
                var done = e.position || e.loaded, total = e.totalSize || e.total;
                console.log('xhr.upload progress: ' + done + ' / ' + total + ' = ' + (Math.floor(done/total*1000)/10) + '%');
            };
        }

    };
    
    this.upload = function(){
        if(input.files.length === 0){
            return;
        }
        initUploader();

        /* Create a FormData instance */
        var formData = new FormData();
        /* Add the file */ 
        formData.append("file", input.files[0]);
        if(form){
            for(var i in form.elements){
                var element = form.elements[i];
                if(element.name === '_csrf'){
                    formData.append(element.name,element.value);
                }
            }
        }

        uploader.open("post", uploadUrl, true);
        uploader.send(formData);  /* Send to server */ 
    };
    
    var successUpload = function(data){
        self.success(data);
    };
    
    var failureUpload = function(){
        self.failure(uploader);
    };
    // http://code.tutsplus.com/tutorials/uploading-files-with-ajax--net-21077
    
    
    init(initData);
}

