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
     * Render current file view
     * @return string
     */
    public function renderView()
    {
        return $this->hasModel() ? $this->model->{$this->attribute} : $this->value;
    }
    
}
