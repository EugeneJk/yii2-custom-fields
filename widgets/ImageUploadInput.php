<?php
/**
 * @license MIT 
 */

namespace eugenejk\customFields\widgets;

use Yii;
use yii\helpers\Html;
/**
 * Custom Image Input Field.
 * Uses Ajax uploading
 * 
 * @author Eugene Lazarchuk <shadyjk@yandex.ru>
 */
class ImageUploadInput extends BaseAbstractInput
{
     
    public static $jsClassName = 'ImageUploadInput';
   
    /**
     * @var string file preview options 
     */
    public $imagePreviewOptions = [];
    
    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        
        if(!isset($this->imagePreviewOptions['id'])){
            $this->imagePreviewOptions['id'] = 'image-preview-' . $this->uid;
        }
    }

    /**
     * @inheritdoc
     */
    public function registerJsInitCode()
    {
        $initObject = json_encode([
            'fileInputId' => $this->fileUploadButtonOptions['id'],
            'uploadUrl' => $this->uploadActionUrl,
            'formId' => $this->formId,
            'progressBarId' => $this->progressBarOptions['id'],
            'fieldId' => $this->options['id'],
            'filePreviewId' => $this->imagePreviewOptions['id'],
        ]);
        $className = static::$jsClassName;
        $this->view->registerJs("{$this->javascriptVarName} = new {$className}($initObject)");
    }
    
    /**
     * @inheritdoc
     */
    public function renderView()
    {
        $this->imagePreviewOptions['src'] = $this->hasModel() ? $this->model->{$this->attribute} : $this->value;
        return Html::tag(
            'img',
            null,
            $this->imagePreviewOptions
        );
    }
    
}
