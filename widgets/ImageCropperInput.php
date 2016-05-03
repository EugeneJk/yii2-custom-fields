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
    public $buttonsLayout = "{init}\n{clear}\n{reset}";

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
     * Preview id.
     */
    public $cropImageId;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        
        if(!isset($this->imagePreviewOptions['id'])){
            $this->imagePreviewOptions['id'] = 'cropped-image-preview-' . $this->uid;
        }
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
            case '{init}':
                $content = '<i class="glyphicon glyphicon-file"></i>';
                $options = [
                    'class' => 'btn btn-primary crop-btn-init',
                    'title' => 'Create',
                    'onclick' => "{$this->javascriptVarName}.activateCrop();return false;",
                ];
                break;
            case '{clear}':
                $content = '<i class="glyphicon glyphicon glyphicon-trash"></i>';
                $options = [
                    'class' => 'btn btn-warning crop-btn-clear',
                    'title' => 'Clear',
                    'onclick' => "{$this->javascriptVarName}.clear(false);return false;",
                ];
                break;
            case '{reset}':
                $content = '<i class="glyphicon glyphicon-picture"></i>';
                $options = [
                    'class' => 'btn btn-default crop-btn-reset',
                    'title' => 'Original',
                    'onclick' => "{$this->javascriptVarName}.clear(true);return false;",
                ];
                break;
            default:
                $content = '';
                $options = [];
                break;
        }
        
        return Html::button($content, $options);
    }
}
