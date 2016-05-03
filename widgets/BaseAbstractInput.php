<?php
/**
 * @license MIT 
 */

namespace eugenejk\customFields\widgets;

use Yii;
use yii\bootstrap\Progress;
use yii\helpers\Html;

use eugenejk\customFields\assets\CustomFieldsAsset;
use eugenejk\customFields\widgets\buttons\FileUploadButton;

/**
 * Template for Custom Inputs
 *
 * @author Eugene Lazarchuk <shadyjk@yandex.ru>
 */
abstract class BaseAbstractInput extends AbstractInput
{
    /**
     * Form Id is need to pickup _csrf filed for submit verification
     * @var string form id
     */
    public $formId;
    
    /**
     * @var string form id
     */
    public $uploadActionUrl = '';
    
    /**
     * @var string output filename wrapper tag
     */
    public $wrapperViewTag = 'div';

    /**
     * @var string output filename wrapper options
     */
    public $wrapperViewOptions = [];

    /**
     * @var string output filename wrapper tag
     */
    public $wrapperButtonTag = 'div';

    /**
     * @var string output filename wrapper options
     */
    public $wrapperButtonOptions = [];

    /**
     * @var string layout
     */
    public $layout = "{view}{buttons}{input}";

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
     * @var array clear button options
     */
    public $clearButtonOptions = [];
    
    /**
     * @var array reset button options
     */
    public $resetButtonOptions = [];
    
    /**
     * @var string java script variable name which controls forntend behaviour
     */
    public $javascriptVarName;

    /**
     * @var string unique id that used for make names unique
     */
    protected $_uid;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        $this->_uid = uniqid();
        
        if(!isset($this->progressBarOptions['id'])){
            $this->progressBarOptions['id'] = 'upload-file-progress-' . $this->_uid;
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
        if(!isset($this->uploadButtonOptions['onclick'])){
            $this->uploadButtonOptions['onclick'] = 'alert("ADD UPLOAD ACTION")';
        }
        
        if(!isset($this->clearButtonOptions['name'])){
            $this->clearButtonOptions['name'] = 'Clear';
        }
        if(!isset($this->clearButtonOptions['class'])){
            $this->clearButtonOptions['class'] = 'btn btn-warning pull-right';
        }
        if(!isset($this->clearButtonOptions['title'])){
            $this->clearButtonOptions['title'] = 'Clear';
        }
        if(!isset($this->clearButtonOptions['onclick'])){
            $this->clearButtonOptions['onclick'] = 'alert("ADD CLEAR ACTION")';
        }

        if(!isset($this->resetButtonOptions['name'])){
            $this->resetButtonOptions['name'] = 'Restore';
        }
        if(!isset($this->resetButtonOptions['class'])){
            $this->resetButtonOptions['class'] = 'btn btn-default pull-right';
        }
        if(!isset($this->resetButtonOptions['title'])){
            $this->resetButtonOptions['title'] = 'Restore to Original';
        }
        if(!isset($this->resetButtonOptions['onclick'])){
            $this->resetButtonOptions['onclick'] = 'alert("ADD RESET ACTION")';
        }
        
        if (!$this->javascriptVarName) {
            $this->javascriptVarName = 'fileUploadInput_' . $this->_uid;
        }
    }

    /**
     * Renders a section of the specified name.
     * If the named section is not supported, false will be returned.
     * @param string $name the section name, e.g., `{image}`, `{thumbnail}`.
     * @return string|boolean the rendering result of the section, or false if the named section is not supported.
     */
    public function renderSection($name)
    {
        switch ($name) {
            case '{buttons}':
                return Html::tag($this->wrapperButtonTag, $this->renderButtons(), $this->wrapperButtonOptions);
            case '{view}':
                return Html::tag($this->wrapperViewTag, $this->renderView(), $this->wrapperViewOptions);
            case '{input}':
                return $this->renderField();
            default:
                return false;
        }
    }

    /**
     * Renders buttons area
     * @return string
     */
    public function renderButtons()
    {
        $content = preg_replace_callback("/{\\w+}/", function ($matches) {
            $content = $this->renderButton($matches[0]);

            return $content === false ? $matches[0] : $content;
        }, $this->buttonsLayout);

        return $content;
    }

    /**
     * Render view layout
     */
    abstract function renderView();

    /**
     * Render file chouse button
     * @return string
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

    /**
     * Render file input
     * @return string
     */
    public function renderField()
    {
        if ($this->hasModel()) {
            return Html::activeHiddenInput($this->model, $this->attribute, $this->options);
        } else {
            return Html::hiddenInput($this->name, $this->value, $this->options);
        }
    }
    
}
