<?php
/**
 * Custom File Input Field
 * 
 * @author Eugene Lazarchuk <shadyjk@yandex.ru>
 * @license MIT 
 */

namespace eugenejk\customFields\widgets;

use Yii;
use yii\helpers\Html;
/**
 * Custom File Input Field.
 * Uses Ajax uploading
 */
class FileUploadInput extends BaseAbstractInput
{
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
        
        if(array_intersect(['model', 'attribute', 'name'], array_keys($this->fileUploadButtonOptions))){
            throw new Exception('You cannot use model, attribute, name keys in the upload button options ');
        }
        
        if(!isset($this->fileUploadButtonOptions['name'])){
            $this->fileUploadButtonOptions['name'] = 'select-file-button-' . $this->_uid;
        }
        if(!isset($this->fileUploadButtonOptions['id'])){
            $this->fileUploadButtonOptions['id'] = 'select-file-button-' . $this->_uid;
        }
        
        if(!isset($this->filePreviewOptions['id'])){
            $this->filePreviewOptions['id'] = 'file-preview-' . $this->_uid;
        }
        
        $this->uploadButtonOptions['onclick'] = "{$this->javascriptVarName}.upload();";
        $this->clearButtonOptions['onclick'] = "{$this->javascriptVarName}.clear();";
        $this->resetButtonOptions['onclick'] = "{$this->javascriptVarName}.reset();";
    }


    /**
     * @inheritdoc
     */
    public function registerJs()
    {
        parent::registerJs();
       
        $initObject = json_encode([
            'fileInputId' => $this->fileUploadButtonOptions['id'],
            'uploadUrl' => $this->uploadActionUrl,
            'formId' => $this->formId,
            'progressBarId' => $this->progressBarOptions['id'],
            'fieldId' => $this->options['id'],
            'filePreviewId' => $this->filePreviewOptions['id'],
        ]);
        $this->view->registerJs("{$this->javascriptVarName} = new FileUploadInput($initObject)");
    }

    /**
     * Render current file view
     * @return string
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
