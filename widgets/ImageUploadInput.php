<?php
/**
 * @license MIT 
 */

namespace eugenejk\customFields\widgets;

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
     *
     * @var array events for js actions
     */
    public $jsEvents = [
        'afterUpload' => null,
    ];

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
            'events' => $this->jsEvents,
        ]);
        $className = static::$jsClassName;
        $this->view->registerJs("{$this->javascriptVarName} = new {$className}($initObject)");
    }
    
    /**
     * @inheritdoc
     */
    public function renderView()
    {
        if(array_key_exists('class', $this->imagePreviewOptions)){
            if(is_array($this->imagePreviewOptions['class'])){
                $this->imagePreviewOptions['class'][] = 'image-preview';
            } else {
                $this->imagePreviewOptions['class'] .= 'image-preview';
            }
        } else {
            $this->imagePreviewOptions['class'] = 'image-preview';
        }
        $this->imagePreviewOptions['src'] = $this->hasModel() ? $this->model->{$this->attribute} : $this->value;
        return Html::tag(
            'img',
            null,
            $this->imagePreviewOptions
        );
    }
    
}
