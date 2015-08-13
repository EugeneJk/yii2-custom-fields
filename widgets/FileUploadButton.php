<?php
namespace eugenejk\customFields\widgets;
use Yii;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\InputWidget;
use eugenejk\customFields\assets\FileUploadButtonAsset;
/*
 * Ajax Image Uploader
 */
/**
 * Description of ImageUploader
 *
 * @author Eugene Lazarchuk
 */
class FileUploadButton extends InputWidget
{
    /**
     * @var string Button display name
     */
    public $title = 'browse...';
    
    /**
     * @var array Button tag options
     */
    public $options = [];
    
    /**
     * @var array Upoad Input Button tag options
     */
    public $inputOptions = [];
    
    /**
     * @var string layout
     */
    public $layout = "{button}{selected_file_name}";
    
    /**
     * @var string empty file name string
     */
    public $fileName = "No file chosen";
    
    /**
     * @var string empty file name tag
     */
    public $fileNameTag = "div";
    
    /**
     * @var string empty file name tag
     */
    public $fileNameOptions = [];
    
    /**
     * @var string empty file name tag
     */
    public $javascriptVarName;
    
    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        $uid = uniqid();
        if(!array_key_exists('id',$this->options)){
            $this->options['id'] = 'upload-button-' . $uid;
        }
        if(!array_key_exists('class',$this->options)){
            $this->options['class'] = ['btn', 'btn-default'];
        }
        
        if(!array_key_exists('style',$this->inputOptions)){
            $this->inputOptions['style'] = ['display' => 'none'];
        } else {
            $this->inputOptions['style']['display'] = 'none';
        }
        if(!array_key_exists('id',$this->inputOptions)){
            $this->inputOptions['id'] = 'file-input-' . $uid;
        }
        
        if(!array_key_exists('id',$this->fileNameOptions)){
            $this->fileNameOptions['id'] = 'file-name-' . $uid;
        }
        if(!array_key_exists('style',$this->fileNameOptions)){
            $this->fileNameOptions['style'] = ['display' => 'inline'];
        }
        
        if(!$this->javascriptVarName){
            $this->javascriptVarName = 'fileUploadButton_' . $uid;
        }
    }
    
    /**
     * @inheritdoc
     */
    public function run()
    {
        $this->registerJs();
        return $this->render();
    }
    
    /**
     * Registers scripts
     */
    public function registerJs(){
        $initObject = json_encode([
            'uploadButtonId' => $this->options['id'],
            'fileNameAreaId' => $this->fileNameOptions['id'],
            'fileInputId' => $this->inputOptions['id'],
        ]);
        FileUploadButtonAsset::register($this->view);
        $this->view->registerJs("{$this->javascriptVarName} = new FileUploadButton($initObject)");
    }
    
    /**
     * Renders widget
     * @return string
     */
    public function render(){
        $content = preg_replace_callback("/{\\w+}/", function ($matches) {
            $content = $this->renderSection($matches[0]);
            return $content === false ? $matches[0] : $content;
        }, $this->layout);
        
        return $content;
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
            case '{selected_file_name}':
                return $this->renderFileName();
            case '{button}':
                return $this->renderButton();
            default:
                return false;
        }
    }
    
    /**
     * Render Image section
     */
    public function renderFileName(){
        return Html::tag($this->fileNameTag, $this->fileName, $this->fileNameOptions);
    }
    
    /**
     * Render Button
     */
    public function renderButton()
    {
        
        return $this->renderField()
            . Html::button($this->title, $this->options);
    }
    
    public function renderField(){
        if ($this->hasModel()) {
            return Html::activeFileInput($this->model, $this->attribute, $this->inputOptions);
        } else {
            return Html::fileInput($this->name, $this->value, $this->inputOptions);
        }
    }
    
}