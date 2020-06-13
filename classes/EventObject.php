<?php

class EventObject
{
    public $id;
    public $title;
    public $company;
    public $date;
    public $hours;

    public function __construct($id, $title = null, $company = null, $date = null, $hours = null)
    {
        $this->id = $id;
        $this->title = $title;
        $this->company = $company;
        $this->date = $date;
        $this->hours = $hours;
    }

    public function getDay()
    {
        return date('d', strtotime($this->date));
    }

    public function getYear()
    {
        return date('Y', strtotime($this->date));
    }

    public function getMonth()
    {
        return date('m', strtotime($this->date));
    }

// --Commented out by Inspection START (5/2/20, 9:44 AM):
//    public function getDate()
//    {
//        return $this->getMonth() . "/" . $this->getDay() . "/" . $this->getYear();
//    }
// --Commented out by Inspection STOP (5/2/20, 9:44 AM)

}