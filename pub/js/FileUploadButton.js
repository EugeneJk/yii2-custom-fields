function FileUploadButton (initData) {
    var uploadButton = null;
    var fileNameArea = null;
    var fileInput = null;
    var fileEmptyFile = null;
    
    var init = function(initData){
        uploadButton = document.getElementById(initData.uploadButtonId);
        fileNameArea = document.getElementById(initData.fileNameAreaId);
        fileInput = document.getElementById(initData.fileInputId);
        fileEmptyFile = fileNameArea.innerHTML;
        
    };
    
    var setActions = function(){
        uploadButton.onclick = function(){
            fileInput.click();
        };
        
        fileInput.onchange = function(){
            if(fileInput.files.length !==0 && fileInput.files[0].name){
                fileNameArea.innerHTML = fileInput.files[0].name;
            } else {
                fileNameArea.innerHTML = fileEmptyFile;
            }
        };
    };
    
    init(initData);
    setActions();
}