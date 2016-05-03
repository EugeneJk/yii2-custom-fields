<?php
/**
 * @license MIT 
 */

namespace eugenejk\customFields\widgets;

use Yii;
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
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        
        if(!isset($this->filePreviewOptions['id'])){
            $this->filePreviewOptions['id'] = 'file-preview-' . $this->uid;
        }
        
        $this->uploadButtonOptions['onclick'] = "{$this->javascriptVarName}.upload();";
        $this->clearButtonOptions['onclick'] = "{$this->javascriptVarName}.clear();";
        $this->resetButtonOptions['onclick'] = "{$this->javascriptVarName}.reset();";
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
