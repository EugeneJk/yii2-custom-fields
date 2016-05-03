<?php
/**
 * @license MIT 
 */

namespace eugenejk\customFields\widgets;

use Yii;
use yii\widgets\InputWidget;
use eugenejk\customFields\assets\CustomFieldsAsset;

/**
 * Template for Base Custom Inputs
 *
 * @author Eugene Lazarchuk <shadyjk@yandex.ru>
 */
abstract class AbstractInput extends InputWidget
{

    /**
     * @inheritdoc
     */
    public function run()
    {
        $this->registerJs();
        return $this->renderWidget();
    }

    /**
     * Registers scripts
     */
    public function registerJs()
    {
        CustomFieldsAsset::register($this->view);
        $this->registerJsInitCode();
    }
    
    /**
     * Registers Javascript initialization code
     */
    abstract function registerJsInitCode();
    
    /**
     * Renders widget
     * @return string
     */
    public function renderWidget()
    {
        $content = preg_replace_callback("/{\\w+}/", function ($matches) {
            $content = $this->renderSection($matches[0]);
            return $content === false ? $matches[0] : $content;
        }, $this->layout);

        return $content;
    }
    
    abstract function renderSection($name);
    
}
