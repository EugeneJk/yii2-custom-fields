<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
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
    ];
    
    public $css = [
    ];
    
    public $depends = [
    ];
}