/**
 * Image Upload Input Js File
 * @author Eugene Lazarchuk
 * @constructor
 * @param {type} initData
 * @returns {ImageUploadInput}
 * @extends {FileUploadInput}
 */

function ImageUploadInput(initData) {
    FileUploadInput.apply(this, arguments);
}

ImageUploadInput.prototype = Object.create(FileUploadInput.prototype);

ImageUploadInput.prototype.init = function () {
    FileUploadInput.prototype.init.apply(this, arguments);
    if(this.field.value == ''){
        this.preview.style.display = 'none';
    }
};

ImageUploadInput.prototype.setValue = function(value){
    this.field.value = value;
    if (this.preview) {
        this.preview.style.display = (value == '') ? 'none' : '';
        this.preview.src = value;
    }
};
