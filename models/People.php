<?php

namespace app\models;


class People extends \yii\base\Object
{

    /**
     * @var Human[]
     */
    public $people;

    /**
     * Добавляем человека.
     * Создаём клонированыый объект, что бы была возможность его очистить.
     * @param Human $human
     */
    public function addHuman(Human $human)
    {
        $this->people[] = clone $human;
        unset($human);
    }

    /**
     * @return Human[]
     */
    public function getPeople()
    {
        return $this->people;
    }

    /**
     * @param $id
     * @return Human|null
     */
    public function getHuman($id)
    {
        if($id){
            return $this->people[$id];
        }
        return null;
    }

    public function removeHuman($id)
    {
        if($id){
            unset($this->people[$id]);
            return true;
        }
        return null;
    }
}