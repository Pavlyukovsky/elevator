<?php

namespace app\models;

class Human extends \yii\base\Object
{
    /**
     * Имя пассажира.
     * @var string
     */
    public $name;

    /**
     * На каком этаже пассажир находится.
     * @var int
     */
    public $current_level;

    /**
     * На какой этаж нужно пасажиру.
     * @var int
     */
    public $to_level;

    /**
     * Мы можем это найти сами.
     * @var
     */
    protected $button;

    /**
     * Human constructor.
     * @param $name string
     * @param $current_level int
     * @param $to_level int
     * @param $button null
     */
    public function __construct($name, $current_level, $to_level, $button = null)
    {
        $this->name = $name;
        $this->current_level = $current_level;
        $this->to_level = $to_level;
        $this->setButton($button);
        parent::__construct();
    }

    /**
     * @param $button int or null
     */
    public function setButton($button)
    {
        if($button){
            $this->button = $button;
        } else {
            $this->button = (($this->to_level - $this->current_level) > 0) ? Elevator::MOVE_UP : Elevator::MOVE_DOWN;
        }
    }

    /**
     * @return mixed
     */
    public function getButton()
    {
        return $this->button;
    }

}