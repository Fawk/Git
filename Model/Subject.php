<?php

/**
 * Subject short summary.
 *
 * Subject description.
 *
 * @version 1.0
 * @author Fawk
 */

abstract class Subject
{
    /**
    * @var Array<Observer> $observers
    */
	protected $observers;
    
    /**
    * @var string - both below
    */
    protected $success;
    protected $error;
    
    /**
    * Construct function to init the observers array and set error, success to empty strings
    */
    public function __construct() {
        $this->observers = array();
        $this->error = "";
        $this->success = "";
    }
    
    /**
    * Function to attach an observer to the observers list
    * @param Observer $observer
    */
    public function attach($observer) {
        
        $i = array_search($observer, $this->observers);
        if ($i === false) {
            $this->observers[] = $observer;
        }
    }
    
    /**
    * Function to detach an observer from the observers list
    * @param Observer $observer
    */
    public function detach($observer) {
        if (!empty($this->observers)) {
            $i = array_search($observer, $this->observers);
            if ($i !== false) {
                unset($this->observers[$i]);
            }
        }
    }
 
    /**
    * Function to retrieve the error
    * @return string
    */
    public function getError() {
        return $this->error;
    }
    
    /**
    * Function to check if the subject has an error
    * @return bool
    */
    public function hasError() {
    	return $this->error != "";
    }
    
    /**
    * Function to check if the subject has an success
    * @return bool
    */
    public function isSuccess()
    {
        return $this->success != "";
    }
    
    /**
    * Function to retrieve the success
    * @return string
    */
    public function getSuccess()
    {
        return $this->success;
    }
    
    /**
    * Function to set an error
    * @param string $error
    */
    public function setError($error) {
        
        if($this->error == null)
        {
            $this->error = $error;
            $this->notify();
        }
    }
    
    /**
    * Function to set a success
    * @param string $success
    */
    public function setSuccess($success)
    {
        if($this->success == null)
        {
            $this->success = $success;
            $this->notify();
        }
    }
    
    /**
    * Function to notify all observers on the subject
    */
    public function notify() {
        if (!empty($this->observers)) {
            foreach ($this->observers as $observer) {
                $observer->Update($this);
            }
        }
    }
}

