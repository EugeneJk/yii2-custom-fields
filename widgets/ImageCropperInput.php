<?php
/**
 * @license MIT 
 */

namespace eugenejk\customFields\widgets;

use yii\helpers\Html;
/**
 * Image Cropper Field.
 * Uses Ajax uploading
 * 
 * @author Eugene Lazarchuk <shadyjk@yandex.ru>
 */
class ImageCropperInput extends AbstractInput
{
    public static $jsClassName = 'ImageCropperInput';
    
    /**
     * @inheritdoc
     */
    public $buttonsLayout = "{crop}\n{clear}\n{reset}";

    /**
     * @var array image preview options
     */
    public $imagePreviewOptions = [];
    
    /**
     * @var array crop size [0 => width, 1 => height]
     */
    public $cropSize = [100,100];
    
    /**
     * @var string image crop url action
     */
    public $cropUrl = "";
    
    /**
     * @var string Preview id.
     */
    public $cropImageId;
    
    /**
     *
     * @var array crop button names
     */
    public $cropButtonOptions = [];

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        
        if(!isset($this->imagePreviewOptions['id'])){
            $this->imagePreviewOptions['id'] = 'cropped-image-preview-' . $this->uid;
        }
        
        if(!isset($this->cropButtonOptions['name'])){
            $this->cropButtonOptions['name'] = 'Crop';
        }
        if(!isset($this->cropButtonOptions['class'])){
            $this->cropButtonOptions['class'] = 'btn btn-primary';
        }
        if(!isset($this->cropButtonOptions['title'])){
            $this->cropButtonOptions['title'] = 'Crop';
        }
        $this->cropButtonOptions['onclick'] = "{$this->javascriptVarName}.activateCrop();";
    }

    /**
     * @inheritdoc
     */
    public function registerJsInitCode()
    {
        $initObject = json_encode([
            'cropImageId' => $this->cropImageId,
            'thumbnailId' => $this->options['id'],
            'objectVariableName' => $this->javascriptVarName,
            'url' => $this->cropUrl,
            'thumbnailPreviewId' => $this->imagePreviewOptions['id'],
            'cropWidth' => $this->cropSize[0],
            'cropHeight' => $this->cropSize[1],
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
    
    /**
     * @inheritdoc
     */
    public function renderButton($button)
    {
        switch ($button) {
            case '{crop}':
                $name = $this->cropButtonOptions['name'];
                unset($this->cropButtonOptions['name']);
                return Html::button($name, $this->cropButtonOptions); 
            case '{clear}':
                $name = $this->clearButtonOptions['name'];
                unset($this->clearButtonOptions['name']);
                return Html::button($name, $this->clearButtonOptions); 
            case '{reset}':
                $name = $this->resetButtonOptions['name'];
                unset($this->resetButtonOptions['name']);
                return Html::button($name, $this->resetButtonOptions); 
            default:
                $content = '';
                $options = [];
                break;
        }
        
        return Html::button($content, $options);
    }
}
