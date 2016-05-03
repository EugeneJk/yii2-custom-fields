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
 * Template for Base Custom Inputs
 *
 * @author Eugene Lazarchuk <shadyjk@yandex.ru>
 */
abstract class AbstractInput extends InputWidget
{
    public static $jsClassName = 'JsClass';
    
    /**
     * Form Id is need to pickup _csrf filed for submit verification
     * @var string form id
     */
    public $formId;
    
    /**
     * @var string layout
     */
    public $layout = "{view}{buttons}{input}";
    
    /**
     * @var string output filename wrapper tag
     */
    public $wrapperViewTag = 'div';

    /**
     * @var string output filename wrapper options
     */
    public $wrapperViewOptions = [];

    /**
     * @var string output filename wrapper tag
     */
    public $wrapperButtonTag = 'div';

    /**
     * @var string output filename wrapper options
     */
    public $wrapperButtonOptions = [];

    /**
     * @var string widget buttons layout
     */
    public $buttonsLayout = '';
        
    /**
     * @var string java script variable name which controls forntend behaviour
     */
    public $javascriptVarName;

    /**
     * @var string unique id that used for make names unique
     */
    public $uid;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        
        $this->uid = uniqid();
        
        $this->javascriptVarName = lcfirst(static::$jsClassName) . '_' . $this->uid;
    }
    
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
    
    /**
     * Renders a section of the specified name.
     * If the named section is not supported, false will be returned.
     * @param string $name the section name, e.g., `{image}`, `{thumbnail}`.
     * @return string|boolean the rendering result of the section, or false if the named section is not supported.
     */
    public function renderSection($name)
    {
        switch ($name) {
            case '{buttons}':
                return Html::tag($this->wrapperButtonTag, $this->renderButtons(), $this->wrapperButtonOptions);
            case '{view}':
                return Html::tag($this->wrapperViewTag, $this->renderView(), $this->wrapperViewOptions);
            case '{input}':
                return $this->renderField();
            default:
                return false;
        }
    }

    /**
     * Renders buttons area
     * @return string
     */
    public function renderButtons()
    {
        $content = preg_replace_callback("/{\\w+}/", function ($matches) {
            $content = $this->renderButton($matches[0]);

            return $content === false ? $matches[0] : $content;
        }, $this->buttonsLayout);

        return $content;
    }
    
    /**
     * Render input
     * @return string
     */
    public function renderField()
    {
        if ($this->hasModel()) {
            return Html::activeHiddenInput($this->model, $this->attribute, $this->options);
        } else {
            return Html::hiddenInput($this->name, $this->value, $this->options);
        }
    }
    
    /**
     * Render view layout
     * @return string
     */
    abstract function renderView();
    
    /**
     * Render button
     * @return string
     */
    abstract function renderButton($buttonName);
    
}
