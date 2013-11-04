<?php

/**
 * Input short summary.
 *
 * Input description.
 *
 * @version 1.0
 * @author Fawk
 */
class Input
{
    /**
    * @var int $id
    */
    private $id;
    
    /**
    * @var string $label
    */ 
    private $label;
    
    /**
    * @var string $description
    */
    private $description;
    
    /**
    * @var string $type
    */
    private $type;
    
    /**
    * @var int $required
    */
    private $required;
    
    /**
    * @var int $formId
    */
    private $formId;
    
    /**
    * @var array<string>
    */
    public $radiovalues = array();
    
    /**
    * Construct function to init an Input object
    * @param int $id;
    * @param string $label
    * @param string $description
    * @param string $type
    * @param int $required
    */
    public function __construct($id, $label, $description, $type, $required)
    {
        $this->id = $id;
        $this->label = $label;
        $this->description = $description;
        $this->type = $type;
        $this->required = $required;
    }
    
    /**
    * Function to set formId
    * @param int $id
    */
    public function SetFormId($id)
    {
        $this->formId = $id;
    }
    
    /**
    * Function to get formId
    * @return int
    */
    public function GetFormId()
    {
        return $this->formId;
    }
    
    /**
    * Function to get Id
    * @return int
    */
    public function GetId()
    {
        return $this->id;
    }
    
    /**
    * Function to get label
    * @return string
    */
    public function GetLabel()
    {
        return $this->label;
    }
    
    /**
    * Function to get description
    * @return string
    */
    public function GetDescription()
    {
        return $this->description;
    }
    
    /**
    * Function to get type
    * @return string
    */
    public function GetType()
    {
        return $this->type;
    }
    
    /**
    * Function to get required
    * @return int
    */
    public function GetRequired()
    {
        return $this->required;
    }
}
