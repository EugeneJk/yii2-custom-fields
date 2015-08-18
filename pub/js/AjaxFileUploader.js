/**
 * use <b>run()</b> for start uploading
 * 
 * @param {object} initData: <ul>
 * <li>uploadInputId - id of file input</li>
 * <li><i>formId</i> - id of upload for to pickup _csrf param</li>
 * <li><i>uploadUrl</i> - upload action url</li>
 * <li><i>success</i> - on success function(result){}</li>
 * <li><i>falure</i> - on failure function(data){}</li>
 * <li><i>progress</i> - on progress function(done, total, percent){}</li>
 * </ul>
 * @returns {AjaxFileUploader}
 */
function AjaxFileUploader(initData) {
    var success = null;
    var failure = null;
    var progress = null;

    var uploader;
    var uploadUrl = '';
    var input = null;
    var form = null;


    var init = function (initData) {
        input = document.getElementById(initData.uploadInputId);
        if (initData.formId !== undefined || initData.formId !== '') {
            form = document.getElementById(initData.formId);
        }
        if (initData.uploadUrl !== undefined || initData.uploadUrl !== '') {
            uploadUrl = initData.uploadUrl;
        }

        if (initData.success) {
            success = initData.success;
        }

        if (initData.failure) {
            success = initData.failure;
        }

        if (initData.progress) {
            progress = initData.progress;
        }
    };

    var initUploader = function () {
        uploader = new XMLHttpRequest();
        uploader.onreadystatechange = function (e) {
            if (uploader.readyState === 4) {
                if (uploader.status === 200) {
                    if (success) {
                        success(JSON.parse(uploader.responseText));
                    } else {
                        console.log(JSON.parse(uploader.responseText));
                    }
                } else {
                    if (failure) {
                        failure(uploader);
                    } else {
                        console.log(JSON.parse(uploader.responseText));
                    }
                }
            }
        };

// http://code.tutsplus.com/tutorials/uploading-files-with-ajax--net-21077
//        uploader.addEventListener('progress', function(e) {
//            var done = e.position || e.loaded, total = e.totalSize || e.total;
//            console.log('xhr progress: ' + (Math.floor(done/total*1000)/10) + '%');
//        }, false);
        if (uploader.upload) {
            uploader.upload.onprogress = function (e) {
                var done = e.position || e.loaded,
                        total = e.totalSize || e.total,
                        percent = (Math.floor(done / total * 1000) / 10);
                if (progress) {
                    progress(done, total, percent);
                } else {
                    console.log('done: ' + done + ' byte of ' + total + ' byte (' + percent + '%)');
                }
            };
        }

    };

    this.run = function () {
        if (input.files.length === 0) {
            return;
        }
        initUploader();

        /* Create a FormData instance */
        var formData = new FormData();
        /* Add the file */
        formData.append("file", input.files[0]);
        if (form) {
            for (var i in form.elements) {
                var element = form.elements[i];
                if (element.name === '_csrf') {
                    formData.append(element.name, element.value);
                }
            }
        }

        uploader.open("post", uploadUrl, true);
        uploader.send(formData);  /* Send to server */
    };

    init(initData);
}

