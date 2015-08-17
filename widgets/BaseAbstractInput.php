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
 * Template for Custom Inputs
 *
 * @author Eugene Lazarchuk <shadyjk@yandex.ru>
 */
abstract class BaseAbstractInput extends InputWidget
{
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
     * @var string layout
     */
    public $layout = "{view}{buttons}{input}";

    /**
     * Layout for buttons.
     */
    public $buttonsLayout = "{select}\n{upload}\n{clear}\n{reset}";

    /**
     * @var string java script variable name which controls forntend behaviour
     */
    public $javascriptVarName;

    /**
     * @var string unique id that used for make names unique
     */
    protected $_uid;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        $this->_uid = uniqid();

        if (!$this->javascriptVarName) {
            $this->javascriptVarName = 'fileUploadInput_' . $this->_uid;
        }
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
    }

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
     * Render view layout
     */
    abstract function renderView();

    /**
     * Render single button
     */
    abstract function renderButton($buttonName);

    /**
     * Render file input
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

}
