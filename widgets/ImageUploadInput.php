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
        $this->view->registerJs("{$this->javascriptVarName} = new ImageUploadInput($initObject)");
    }

    /**
     * Render current file view
     * @return string
     */
    public function renderView()
    {
        $this->filePreviewOptions['src'] = $this->hasModel() ? $this->model->{$this->attribute} : $this->value;
        return Html::tag(
            'img',
            null,
            $this->filePreviewOptions
        );
    }
    
}
