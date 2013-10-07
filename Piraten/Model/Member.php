<?php

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
    private $uniqueId;
    private $firstname;
    private $lastname;
    private $ssnumber;
    private $boats = array();
    private $userName;
    private $password;
    private static $fileLocation = "./members.json";
    
    public function __construct($fname, $lname, $snumber)
    {
        $this->uniqueId = $this->AddMemberToFile();
        $this->firstname = $fname;
        $this->lastname = $lname;
        $this->ssnumber = $snumber;
        $this->userName = $this->createUser();
        $this->password = $this->createPassword();
    }
    
    public function getUnique()
    {
        return $this->uniqueId;
    }
    
    public function getFirstName()
    {
        return $this->firstname;
    }
    
    public function getLastName()
    {
        return $this->lastname;
    }
    
    public function getSocialNumber()
    {
        return $this->ssnumber;
    }
    
    public function getBoats()
    {
        return $this->boats;
    }
    
    private function OpenFileStream($type)
    {
        return fopen(self::$fileLocation, $type);
    }
    
    public static function GenerateMemberList()
    {
        $fr = $this->OpenFileStream('r');
        $decoded = json_decode($fr, true);
        fclose($fr);
        
        return $decoded;
    }
    
    public static function DeleteMember($id)
    {
        if($this->DoesMemberExist($id))
        {
            $f = $this->OpenFileStream('r+');
            $list = json_decode($f, true);
            $spliced = array_splice($list, $id, 1);           
            ftruncate($f, 0);
            $encoded = json_encode($spliced);
            fwrite($f, $encoded);
            fclose($f);
            return true;
        }      
        return false;
    }
    
    public static function EditMember($id)
    {
        
    }
    
    private function DoesMemberExist($id)
    {
        $decoded = self::GenerateMemberList();
       
        return array_key_exists($id, $decoded);
    }

    private function ReturnNextAvailableId()
    {
        $decoded = self::GenerateMemberList();
        !empty($decoded) ? $id = count($decoded) : $id = 0;      
        fclose($fr);
        
        return $id;
    }
    
    private function AddMemberToFile()
    {
        $id = $this->ReturnNextAvailableId();
    
        $userArray = array($id, $this->firstname, $this->lastname, $this->ssnumber, $this->userName, $this->password); 
        $encoded = json_encode($userArray);

        $fw = $this->OpenFileStream('r+');
        fwrite($fw, $encoded);
        fclose($fw);
        
        return $id;
    }
    
    public function AddBoat(Boat $boat)
    {
        $this->boats[] = $boat;
    }
    
    private function createUser()
    {
        return substr($this->firstname, 0, 3) + substr($this->lastname, 0, 3);
    }
    
    private function createPassword()
    {
        $rand = new Random(1, 999);
        return substr($this-firstname, -2, 2) + $rand;
    }
}
