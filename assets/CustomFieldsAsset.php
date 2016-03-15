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
        'js/ImageCroppedUploadInput.js',
    ];
    
    public $css = [
        'css/ImageCroppedUploadInput.css',
    ];
    
    public $depends = [
    ];
}