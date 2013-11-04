<?php

/**
 * Form short summary.
 *
 * Form description.
 *
 * @version 1.0
 * @author Fawk
 */
class Form
{
    /**
    * @var int $id
    */
    private $id;
    
    /**
    * @var string $uniqueId
    */
    private $uniqueId;
    
    /**
    * @var int $private
    */
    private $private;
    
    /**
    * @var string $title
    */
    private $title;
    
    /**
    * @var string $description
    */
    private $description;
    
    /**
    * @var Array<Input>
    */
    public $inputs;
    
    /**
    * @var int $userId
    */
    private $userId;
    
    /**
    * @var int $formVer
    */
    private $formVer;
    
    /**
    * Construct function to init the Form object
    * @param string $title
    * @param string $description
    */
    public function __construct($title, $description)
    {
        $this->title = $title;
        $this->description = $description;
    }
    
    /**
    * Return the form version
    * @return int
    */
    public function GetVersion()
    {
        return $this->formVer;
    }
    
    /**
    * Set form version
    * @param int $v
    */
    public function SetVersion($v)
    {
        $this->formVer = $v;
    }
    
    /**
    * Get the form id
    * @return int
    */
    public function GetId()
    {
        return $this->id;
    }
    
    /**
    * Set the form id
    * @param int $id
    */
    public function SetId($id)
    {
        $this->id = $id;
    }
    
    /**
    * Set the userId
    * @param int $userId
    */
    public function SetUserId($userid)
    {
        $this->userId = $userid;
    }
    
    /**
    * Get the userId
    * @return int
    */
    public function GetUserId()
    {
        return $this->userId;
    }
    
    /**
    * Get the private
    * @return int
    */
    public function GetPrivate()
    {
        return $this->private;
    }
    
    /**
    * Set the private
    * @param int $pri
    */
    public function SetPrivate($pri)
    {   
        $this->private = $pri;
    }
    
    /**
    * Function to generate a uniqueId
    */
    public function GenerateUniqueId()
    {
        $array = range('Aa', 'wW');

        for($j = 26; $j < 32; $j++)
        {
	        unset($array[$j]);
        }
        
        $array = array_values($array);

        $str = '';
        for($i = 0; $i < 39; $i++)
        {
	        $v = $array[rand(0, count($array) - 1)];
	        if($i != 0)
	        {
		        while($v == $array[$i - 1])
		        {
			        $v = $array[rand(0, count($array) - 1)];
		        }
	        }
	        $str .= $v;
        }
        $this->uniqueId = $str;
    }
    
    /**
    * Set the uniqueId
    * @param string $string
    */
    public function SetUniqueId($unique)
    {
        $this->uniqueId = $unique;
    }
    
    /**
    * Get the uniqueId
    * @return string
    */
    public function GetUniqueId()
    {
        return $this->uniqueId;
    }
    
    /**
    * Get title
    * @return string
    */
    public function GetTitle()
    {
        return $this->title;
    }
    
    /**
    * Set title
    * @param string $title
    */
    public function SetTitle($title)
    {
        $this->title = $title;
    }
    
    /**
    * Get description
    * @return string
    */
    public function GetDescription()
    {
        return $this->description;
    }
    
    /**
    * Set description
    * @param string $desc
    */
    public function SetDescription($desc)
    {
        $this->description = $desc;
    }
}
