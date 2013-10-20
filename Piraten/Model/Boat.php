<?php

require_once("BoatType.php");

/**
 * Boat short summary.
 *
 * Boat description.
 *
 * @version 1.0
 * @author ok222ax
 */
class Boat
{
    private $boatType;
    private $length;
    private $id;
    
    /**
     * Skapa ett båt objekt
     * Båttyper:
     * 0 : Segelbåt
     * 1 : Motorbåt
     * 2 : Motorseglare
     * 3 : Kajak/Kanot
     * 4 : Övrigt
     * @param $id
     * @param $boattype - integer för båttyp
     * @param $len - i meter
     */
    public function __construct($id, $boattype = 0, $len = 0)
    {
        $this->id = $id + 0;
        $this->boatType = $boattype;
        $this->length = $len;
    }
    
    public function getType()
    {
        return $this->boatType;
    }
    
    public function getBoatType()
    {
        $ref = new ReflectionClass("BoatType");
        $constants = $ref->getConstants();
        foreach($constants as $key => $val)
        {
            if($val == $this->boatType)
            {
                return $key;
            }
        }
    }
    
    public function setBoatType($type)
    {
        $this->boatType = $type;
    }
    
    public function getLength()
    {
        $len = $this->length + 0;
        return $len;
    }
    
    public function getId()
    {
        return $this->id;
    }
    
    public function setLength($length)
    {
        $this->length = $length;
    }
    
    public function isSame(Boat $other)
    {
        return $this->id == $other->getId();
    }
}

