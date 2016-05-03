<?php
/**
 * @license MIT 
 */

namespace eugenejk\customFields\widgets;

use Yii;
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
     * @var integer crop width
     */
    public $cropWidth = 100;
    
    /**
     * @var integer crop width
     */
    public $cropHeight = 100;
    
    /**
     * @var string mage crop url action
     */
    public $cropUrl = "";
    
    /**
     * Preview id.
     */
    public $previewId;
    
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
            'cropWidth' => $this->cropWidth,
            'cropHeight' => $this->cropHeight,
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
