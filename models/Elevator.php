<?php

namespace app\models;

use yii\helpers\VarDumper;

class Elevator extends \yii\base\Object
{
    const MOVE_UP = 1;
    const MOVE_DOWN = 0;

    const MAX_LEVEL = 4;
    const MIN_LEVEL = 1;

    const ALL_PEOPLE = 3;

    /**
     * Куда едем?
     * @var int
     */
    public $move = 1;

    /**
     * Текущий этаж.
     * @var int
     */
    public $current_level = 1;

    /**
     * Количество человек в лифте
     * //TODO: При желании можно сделать отельную модель Human(id, {name}, weight)
     * @var int
     */
    public $people_count = 0;

    /**
     * Люди которые хотят поехать на лифте.
     * @var People
     */
    protected $people;

    /**
     * Люди которые находятся в нутри лифта
     * @var People
     */
    protected $people_in_elevator;

    /**
     * Elevator constructor.
     * @param People $people
     */
    public function __construct($people)
    {
        $this->setPeople($people);
        $this->people_in_elevator = new People();
        parent::__construct();
    }

    /**
     * @param People $people
     */
    protected function setPeople($people)
    {
        $this->people = clone $people;
    }


    /**
     * Лифт начал свою работу
     * Работает до того пока не развезёт всех людей
     * @return bool
     */
    public function start()
    {
        if(!$this->people->getPeople()){
            \Yii::info("Пасажиров нету. Лифт пустой.", 'elevator');
            return true;
        }

        $i = 0 ;
        // Имитируем работу лифта.
        while (true){
            $i++;
            // Дамп для хорошего просмотра действий
            echo '<hr>'.$i.'<Hr>';
            VarDumper::dump($this,10,true);

            // Разгрузим лифт
            $this->unloadPersonFromElevator();

            // Проверяем если все люди успешно были доставлены, то выходим.
            if($this->people_count == self::ALL_PEOPLE){
                \Yii::info("Лифт развёз всех пасажиров.", 'elevator');
                break;
            }

            // Загрузим лифт
            $this->loadPersonInElevator();

            // Двигаемся на этаж.
            if(!$this->changeLevel()){
                $this->changeMove();
            }
        }

        return true;
   }

    /**
     * Люди помещаются в лифт
     */
   protected function loadPersonInElevator()
   {
       foreach ($this->people->getPeople() as $key => $person){
           if($person->current_level == $this->current_level && $person->getButton() == $this->move){
               $this->people_in_elevator->addHuman($person);
               $this->people->removeHuman($key);
               \Yii::info("Человек зашел в лифт!", 'elevator');
           }
       }
   }

    /**
     * Люди выходят из лифта
     * @return bool
     */
    protected function unloadPersonFromElevator()
    {
        if (!$this->people_in_elevator->getPeople()){
            \Yii::error('Нету людей в лифте!', 'elevator');
            return false;
        }

        foreach ($this->people_in_elevator->getPeople() as $key => $person){
            if($person->to_level == $this->current_level){
                $this->people_in_elevator->removeHuman($key);
                $this->people_count++;
                \Yii::info("Человек вышел из лифта!", 'elevator');
            }
       }
       return true;
    }


    /**
     * Передвигаем лифт на этаж више или нижу, в зависимости от его направления.
     * @return bool
     */
   protected function changeLevel()
   {
       if($this->move == self::MOVE_UP){
           $this->current_level++;
           \Yii::info('На этаж в верх!', 'elevator');
           if($this->current_level == self::MAX_LEVEL){
               \Yii::info('Нужно сменить направление', 'elevator');
               return false;
           }
       }
       if($this->move == self::MOVE_DOWN){
           $this->current_level--;
           \Yii::info('На этаж в низ!', 'elevator');
           if($this->current_level == self::MIN_LEVEL){
               \Yii::info('Нужно сменить направление', 'elevator');
               return false;
           }
       }
       return true;
   }

    /**
     * Изменяем направление движения вверх или вниз.
     * @return bool
     */
   protected function changeMove()
   {
       if ($this->move == self::MOVE_UP){
           $this->move = self::MOVE_DOWN;
           \Yii::info('Сменили направление в низ.', 'elevator');
           return true;
       }
       if ($this->move == self::MOVE_DOWN){
           $this->move = self::MOVE_UP;
           \Yii::info('Сменили направление в вверх.', 'elevator');
           return true;
       }
   }

}
