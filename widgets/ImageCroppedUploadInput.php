<?php
/**
 * @license MIT 
 */

namespace eugenejk\customFields\widgets;

use Yii;
use yii\helpers\Html;
/**
 * Custom Image Input Field.
 * Uses Ajax uploading
 * 
 * @author Eugene Lazarchuk <shadyjk@yandex.ru>
 */
class ImageCroppedUploadInput extends ImageUploadInput
{
    
    public static $jsClassName = 'ImageUploadInput';
    
    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
    }


    
}
