<?php

namespace dogrocker\echarts;

use yii\base\Widget;
use yii\helpers\Html;
use yii\helpers\Json;

class Echarts extends Widget
{
    /** @var array default HTML attribute */
    private $defaultHtmlOptions = [
        'style' => 'height:400px',
        'class' => 'center-block'
    ];

    public $options = [];

    public $htmlOptions = [];

    public $events = [];

    public function init()
    {
        parent::init();
        $this->htmlOptions = array_merge($this->defaultHtmlOptions, $this->htmlOptions);
        if (!isset($this->htmlOptions['id'])) {
            $this->htmlOptions['id'] = $this->getId();
        }
    }

    /**
     * Renders the widget.
     */
    public function run()
    {
        echo Html::tag('div', '', $this->htmlOptions);
        $this->registerClientScript();
    }

    /**
     * Registers the required js files and script to initialize ChartJS plugin
     */
    protected function registerClientScript()
    {
        $view = $this->getView();
        EchartsAsset::register($view);
        $option = !empty($this->options) ? Json::encode($this->options) : '{}';
        $id = $this->htmlOptions['id'];
        $clientId = 'Echarts_' . $id;
        $js = "
        // Initialize after dom ready
        var {$clientId} = echarts.init(document.getElementById('{$id}'));
       
        var option = {$option}

        // use configuration item and data specified to show chart
        {$clientId}.setOption(option);

        window.onresize = {$clientId}.resize;
        ";
        foreach ($this->events as $name => $handlers) {
            foreach ($handlers as $handler) {
                $js .= "{$clientId}.on(" . $this->quote($name) . ", $handler);";
            }
        }
        $view->registerJs($js, $view::POS_LOAD);
    }

    /**
     * Quotes a string for use in JavaScript.
     *
     * @param string $string
     * @return string the quoted string
     */
    private function quote($string) {
        return "'" . addcslashes($string, "'") . "'";
    }
}
