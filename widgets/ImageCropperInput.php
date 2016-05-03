<?php
/**
 * @license MIT 
 */

namespace eugenejk\customFields\widgets;

use Yii;
use yii\helpers\Html;
use yii\widgets\InputWidget;
use eugenejk\customFields\assets\CustomFieldsAsset;
/**
 * Custom Image Input Field.
 * Uses Ajax uploading
 * 
 * @author Eugene Lazarchuk <shadyjk@yandex.ru>
 */
class ImageCropperInput extends InputWidget
{
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
     * @var string hiddenInputId
     */
    public $hiddenInputId;

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
    public $javascriptVariableName;
    
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
        if(empty($this->javascriptVariableName)){
            $this->javascriptVariableName = 'cropper_' . $uid;
        }
        if(empty($this->previewId)){
            $this->previewId = 'image-preview_' . $uid;
        }
        $this->notificationId = 'notification_' . $uid;
        if(empty($this->hiddenInputId)){
            $this->hiddenInputId  = 'crop-hidden-field_' . $uid;
        }
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        $this->registerClientScript();
        return $this->renderArea();
    }
    
    /**
     * Renders widget
     * @return string
     */
    private function renderArea(){
        $content = preg_replace_callback("/{\\w+}/", function ($matches) {
            $content = $this->renderSection($matches[0]);

            return $content === false ? $matches[0] : $content;
        }, $this->layout);
        
        return $content;
    }
    
    /**
     * Registers javascript and css
     */
    private function registerClientScript(){
//        $originalImage = $this->model->{$this->attribute};
        $view = $this->getView();
        $view->registerJs(<<<JS
            {$this->javascriptVariableName} = new ImageCropperInput({
                cropImageId : '{$this->cropImageId}',
                thumbnailId : '{$this->hiddenInputId}',
                objectVariableName: '{$this->javascriptVariableName}',
                url: '{$this->url}',
                thumbnailPreviewId: '{$this->previewId}',
                notificationAreaId: '{$this->notificationId}',
                cropWidth : {$this->cropWidth},
                cropHeight : {$this->cropHeight}
            });
JS
        );
        CustomFieldsAsset::register($view);
    }

    public function renderSection($name)
    {
        switch ($name) {
            case '{field}':
                return $this->renderField();
            case '{image}':
                return $this->renderImage();
            case '{fileInput}':
                return $this->renderFileInput();
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
            return Html::activeHiddenInput($this->model, $this->attribute, ['id' => $this->hiddenInputId]);
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
                    'onclick' => "{$this->javascriptVariableName}.activateCrop();return false;",
                ];
                break;
            case '{clear}':
                $content = '<i class="glyphicon glyphicon glyphicon-trash"></i>';
                $options = [
                    'class' => 'btn btn-warning crop-btn-clear',
                    'title' => 'Clear',
                    'onclick' => "{$this->javascriptVariableName}.clear(false);return false;",
                ];
                break;
            case '{reset}':
                $content = '<i class="glyphicon glyphicon-picture"></i>';
                $options = [
                    'class' => 'btn btn-default crop-btn-reset',
                    'title' => 'Original',
                    'onclick' => "{$this->javascriptVariableName}.clear(true);return false;",
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
