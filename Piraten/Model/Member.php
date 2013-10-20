<?php

require_once("Boat.php");

/**
 * Member short summary.
 *
 * Member description.
 *
 * @version 1.0
 * @author ok222ax
 */
class Member
{
    private $id;
    private $firstname;
    private $lastname;
    private $ssnumber;
    public $boats = array();
    
    public function __construct($fname = "", $lname = "", $snumber = "", $boats = array())
    {
        if(strlen($fname) < 2 || strlen($fname) > 15)
        {
            $e = new Exception("6");
            throw $e;
        }
        elseif(strlen($lname) < 2 || strlen($lname) > 25)
        {
            $e = new Exception("7");
            throw $e;
        }
        elseif(!preg_match("/^\d{8,8}-\d{4,4}$/", $snumber))
        {
            $e = new Exception("5");
            throw $e;
        }
        
        $this->firstname = $fname;
        $this->lastname = $lname;
        $this->ssnumber = $snumber;
        $this->boats = $boats;
    }
    
    public function setId($id)
    {
        $this->id = $id;
    }
    
    public function getId()
    {
        return $this->id;
    }
    
    public function getFirstname()
    {
        return $this->firstname;
    }
    
    public function getLastname()
    {
        return $this->lastname;
    }
    
    public function getSocial()
    {
        return $this->ssnumber;
    }

    public function isSame(Member $other)
    {
        return $this->id == $other->getId();
    }
    
    public function getNextBoatId()
    {
        $id = 0;
        if(count($this->boats) != 0)
        {
            foreach($this->boats as $boat)
            {
                if($boat->getId() > $id)
                    $id = $boat->getId();
            }
        }
        return $id + 1;
    }
    
    public function Edit(Member $other)
    {
        $this->firstname = $other->getFirstname();
        $this->lastname = $other->getLastname();
        $this->ssnumber = $other->getSocial();
    }
    
    public function GetBoat($id)
    {
        foreach($this->boats as $boat)
        {
            if($boat->getId() == $id)
                return $boat;
        }
        $e = new Exception("12");
        throw $e;
    }
    
    public function EditBoat(Boat $otherboat)
    {
        $boat = $this->GetBoat($otherboat->getId());        
        $boat->setBoatType($otherboat->getType());
        $boat->setLength($otherboat->getLength());
    }
    
    public function DeleteBoat(Boat $otherboat)
    {
        foreach($this->boats as $key => $boat)
        {
            if($boat->isSame($otherboat))
            {
                unset($this->boats[$key]);
                return;
            }   
        }
        $e = new Exception("12");
        throw $e;
    }
}
