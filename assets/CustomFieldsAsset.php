<?php
/*
 * @license MIT 
 */
namespace eugenejk\customFields\assets;
use yii\web\AssetBundle;
/**
 * Description of ImageUploaderAsset
 *
 * @author eugene
 */
class CustomFieldsAsset extends AssetBundle
{
    public $basePath = '@vendor/eugenejk/yii2-custom-fields/';
    public $sourcePath = '@vendor/eugenejk/yii2-custom-fields/pub';
    
    public $js = [
        'js/FileUploadButton.js',
        'js/FileUploadInput.js',
        'js/AjaxFileUploader.js',
        'js/ImageUploadInput.js',
        'js/ImageCropperInput.js',
        'js/fc-cropresizer/fc-cropresizer.js',
    ];
    
    public $css = [
        'css/ImageCropperInput.css',
        'js/fc-cropresizer/fc-cropresizer.css',
    ];
    
    public $depends = [
    ];
}