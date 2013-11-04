<?php

abstract class Subject
{
	protected $observers;
    protected $state;
    protected $error;
 
    public function __construct() {
        $this->observers = array();
        $this->state = null;
        $this->error = false;
    }
 
    public function attach($observer) {
        $i = array_search($observer, $this->observers);
        if ($i === false) {
            $this->observers[] = $observer;
        }
    }
 
    public function detach($observer) {
        if (!empty($this->observers)) {
            $i = array_search($observer, $this->observers);
            if ($i !== false) {
                unset($this->observers[$i]);
            }
        }
    }
 
    public function getState() {
        return $this->state;
    }

    public function isError() {
    	return $this->error;
    }
 
    public function setState($state, $isError = false) {
        $this->state = $state;
        $this->error = $isError;
        $this->notify();
    }
 
    public function notify() {
        if (!empty($this->observers)) {
            foreach ($this->observers as $observer) {
                $observer->update($this);
            }
        }
    }
 
 
    public function getObservers() {
        return $this->observers;
    }
}