<?php
/**
 * @license MIT 
 */

namespace eugenejk\customFields\widgets;

use Yii;
use yii\helpers\Html;
use yii\widgets\InputWidget;
/**
 * Custom Image Input Field.
 * Uses Ajax uploading
 * 
 * @author Eugene Lazarchuk <shadyjk@yandex.ru>
 */
class ImageCroppedUploadInput extends InputWidget
{
    
    /**
     * Form Id is need to pickup _csrf filed for submit verification
     * @var string form id
     */
    public $formId;
    
    public static $jsClassName = 'ImageCroppedUploadInput';
    
    public $imageCropUrl = '';
    
    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
    }
}
