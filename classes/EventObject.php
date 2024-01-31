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
}