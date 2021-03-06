<?php
/**
 * @license MIT 
 */

namespace eugenejk\customFields\widgets;

use yii\bootstrap\Progress;
use yii\helpers\Html;

use eugenejk\customFields\widgets\buttons\FileUploadButton;

/**
 * Template for Custom Inputs
 *
 * @author Eugene Lazarchuk <shadyjk@yandex.ru>
 */
abstract class BaseAbstractInput extends AbstractInput
{
    /**
     * @var string form id
     */
    public $uploadActionUrl = '';
    
    /**
     * Layout for buttons.
     */
    public $buttonsLayout = "{select}\n{progress}\n{upload}\n{clear}\n{reset}";
    
    /**
     * @var array file upload button options
     */
    public $fileUploadButtonOptions = [];
    
    /**
     * @var array progress bar options 
     */
    public $progressBarOptions = [];
    
    /**
     * @var array upload button options
     */
    public $uploadButtonOptions = [];

    /**
     * @var string unique id that used for make names unique
     */
    public $uid;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        
        if(array_intersect(['model', 'attribute', 'name'], array_keys($this->fileUploadButtonOptions))){
            throw new Exception('You cannot use model, attribute, name keys in the upload button options ');
        }
        
        if(!isset($this->progressBarOptions['id'])){
            $this->progressBarOptions['id'] = 'upload-file-progress-' . $this->uid;
        }
        
        if(!isset($this->fileUploadButtonOptions['name'])){
            $this->fileUploadButtonOptions['name'] = 'select-file-button-' . $this->uid;
        }
        
        if(!isset($this->fileUploadButtonOptions['id'])){
            $this->fileUploadButtonOptions['id'] = 'select-file-button-' . $this->uid;
        }
        
        if(!isset($this->uploadButtonOptions['name'])){
            $this->uploadButtonOptions['name'] = 'Upload';
        }
        if(!isset($this->uploadButtonOptions['class'])){
            $this->uploadButtonOptions['class'] = 'btn btn-primary';
        }
        if(!isset($this->uploadButtonOptions['title'])){
            $this->uploadButtonOptions['title'] = 'Upload';
        }
        $this->uploadButtonOptions['onclick'] = "{$this->javascriptVarName}.upload();";
    }

    /**
     * @inheritdoc
     */
    public function renderButton($buttonName)
    {
        switch ($buttonName) {
            case '{select}':
                return FileUploadButton::widget($this->fileUploadButtonOptions);
            case '{progress}':
                return Progress::widget($this->progressBarOptions);
            case '{upload}':
                $name = $this->uploadButtonOptions['name'];
                unset($this->uploadButtonOptions['name']);
                return Html::button($name, $this->uploadButtonOptions); 
            case '{clear}':
                $name = $this->clearButtonOptions['name'];
                unset($this->clearButtonOptions['name']);
                return Html::button($name, $this->clearButtonOptions); 
            case '{reset}':
                $name = $this->resetButtonOptions['name'];
                unset($this->resetButtonOptions['name']);
                return Html::button($name, $this->resetButtonOptions); 
        }        
        return false;
    }
}
