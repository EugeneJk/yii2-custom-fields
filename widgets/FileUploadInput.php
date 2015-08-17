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
     * @var string file upload button name 
     */
    public $fileUploadButtonOptions = [];
    

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        
        if(array_intersect(['model', 'attribute', 'name'], array_keys($this->fileUploadButtonOptions))){
            throw new Exception('You cannot use model, attribute, name keys in the upload button options ');
        }
        $this->fileUploadButtonOptions['name'] = 'select-file-button_' . $this->_uid;
    }


    /**
     * @inheritdoc
     */
    public function registerJs()
    {
        parent::registerJs();
//        $initObject = json_encode([
//            'uploadButtonId' => $this->buttonOptions['id'],
//            'fileNameAreaId' => $this->fileNameOptions['id'],
//            'fileInputId' => $this->options['id'],
//        ]);
//        FileUploadButtonAsset::register($this->view);
//        $this->view->registerJs("{$this->javascriptVarName} = new FileUploadButton($initObject)");
    }

    
    /**
     * Render file chouse button
     * @return string
     */
    public function renderButton($buttonName)
    {
        switch ($buttonName) {
            case '{select}':
                return FileUploadButton::widget($this->fileUploadButtonOptions);
            case '{upload}':
                $content = '<i class="glyphicon glyphicon-upload"></i>';
                $options = [
                    'class' => 'btn btn-primary',
                    'title' => 'Upload',
                    'onclick' => "{$this->javascriptVarName}.upload();return false;",
                ];
                break;
            case '{clear}':
                $content = '<i class="glyphicon glyphicon glyphicon-trash"></i>';
                $options = [
                    'class' => 'btn btn-warning pull-right',
                    'title' => 'Clear',
                    'onclick' => "{$this->javascriptVarName}.clear(false);return false;",
                ];
                break;
            case '{reset}':
                $content = '<i class="glyphicon glyphicon-picture"></i>';
                $options = [
                    'class' => 'btn btn-default pull-right',
                    'title' => 'Original',
                    'onclick' => "{$this->javascriptVarName}.clear(true);return false;",
                ];
                break;
            default:
                $content = '';
                $options = [];
                break;
        }        
        $options['type'] = 'button';
        return Html::button($content, $options);
    }

    /**
     * Render current file view
     * @return string
     */
    public function renderView()
    {
        return $this->hasModel() ? $this->model->{$this->attribute} : $this->value;
    }
    
}
