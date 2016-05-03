<?php
/**
 * @license MIT 
 */

namespace eugenejk\customFields\widgets;

use Yii;
use yii\helpers\Html;
use eugenejk\customFields\assets\CustomFieldsAsset;
/**
 * Custom Image Input Field.
 * Uses Ajax uploading
 * 
 * @author Eugene Lazarchuk <shadyjk@yandex.ru>
 */
class ImageCropperInput extends AbstractInput
{
    public static $jsClassName = 'ImageUploadInput';
    
    /**
     * @var string widget layout
     */
    public $layout = "{field}\n{image}\n{buttons}\n{notification}";
    
    /**
     * @var string widget layout
     */
    public $buttonsLayout = "{init}\n{clear}\n{reset}";

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
    public $url = "";
    
    /**
     * Preview id.
     */
    public $previewId;
    
    /**
     * Preview id.
     */
    public $cropImageId;

    /**
     * javascriptVariableName
     */
    public $javascriptVarName;
    
    /**
     * Notification section id.
     */
    private $notificationId;
    
    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        $uid = uniqid();
        if(empty($this->javascriptVarName)){
            $this->javascriptVarName = 'cropper_' . $uid;
        }
        if(empty($this->previewId)){
            $this->previewId = 'image-preview_' . $uid;
        }
        $this->notificationId = 'notification_' . $uid;
    }

    /**
     * @inheritdoc
     */
    public function registerJsInitCode()
    {
        $initObject = json_encode([
            cropImageId => $this->cropImageId,
            thumbnailId => $this->options['id'],
            objectVariableName => $this->javascriptVarName,
            url => $this->url,
            thumbnailPreviewId => $this->previewId,
            notificationAreaId => $this->notificationId,
            cropWidth => $this->cropWidth,
            cropHeight => $this->cropHeight,
        ]);
        $className = static::$jsClassName;
        $this->view->registerJs("{$this->javascriptVarName} = new {$className}($initObject)");
    }

    public function renderSection($name)
    {
        switch ($name) {
            case '{field}':
                return $this->renderField();
            case '{image}':
                return $this->renderImage();
            case '{buttons}':
                return $this->renderButtons();
            case '{notification}':
                return $this->renderNotifications();
            default:
                return false;
        }
    }

    public function renderField(){
        if ($this->hasModel()) {
            return Html::activeHiddenInput($this->model, $this->attribute, $this->options);
        } else {
            return Html::hiddenInput($this->name, $this->value, $this->options);
        }
    }
    
    public function renderImage(){
        return Html::tag(
            'div',
            Html::img(
                empty($this->model->{$this->attribute}) ?  null : $this->model->{$this->attribute},
                ['id' => $this->previewId]
            ),
            ['class' => 'project-upload-element text-center']
        );
    }
    
    public function renderNotifications(){
        return Html::tag(
            'div',
            '',
            ['id' => $this->notificationId,'class' => 'help-block']
        );
    }
    
    public function renderButtons(){
        $content = preg_replace_callback("/{\\w+}/", function ($matches) {
            $content = $this->renderButton($matches[0]);

            return $content === false ? $matches[0] : $content;
        }, $this->buttonsLayout);
        
        return Html::tag('div', $content,[ 'class' => 'project-upload-element']);
    }
    
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
