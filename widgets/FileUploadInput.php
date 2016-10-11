<?php
/**
 * @license MIT 
 */

namespace eugenejk\customFields\widgets;

use yii\helpers\Html;
/**
 * Custom File Input Field.
 * Uses Ajax uploading
 * 
 * @author Eugene Lazarchuk <shadyjk@yandex.ru>
 */
class FileUploadInput extends BaseAbstractInput
{
    public static $jsClassName = 'FileUploadInput';
    
    /**
     * @var string file preview tag 
     */
    public $filePreviewTag = 'div';
    
    /**
     * @var string file preview options 
     */
    public $filePreviewOptions = [];
    
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
        
        if(!isset($this->filePreviewOptions['id'])){
            $this->filePreviewOptions['id'] = 'file-preview-' . $this->uid;
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
            'filePreviewId' => $this->filePreviewOptions['id'],
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
        return Html::tag(
            $this->filePreviewTag,
            $this->hasModel() ? $this->model->{$this->attribute} : $this->value,
            $this->filePreviewOptions
        );
    }
    
}
