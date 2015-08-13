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
class FileUploadButtonAsset extends AssetBundle
{
    public $basePath = '@vendor/eugenejk/image-uploader/';
    public $sourcePath = '@vendor/eugenejk/image-uploader/pub/file-upload-button';
    
    public $js = [
        'FileUploadButton.js',
    ];
    
    public $css = [
    ];
    
    public $depends = [
    ];
}