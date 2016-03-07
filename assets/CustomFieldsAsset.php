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
    public $sourcePath = '@vendor/eugenejk/yii2-custom-fields/pub/js';
    
    public $js = [
        'FileUploadButton.js',
        'FileUploadInput.js',
        'AjaxFileUploader.js',
        'ImageUploadInput.js',
    ];
    
    public $css = [
    ];
    
    public $depends = [
    ];
}