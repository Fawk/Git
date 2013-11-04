<?php

require_once("Member.php");

/**
 * FileSystem short summary.
 *
 * FileSystem description.
 *
 * @version 1.0
 * @author Fawk
 */
class FileSystem
{
    private static $filePath = "./data/members.php";
    
    /**
     * Returns an array of members
     * @return array
     */
    public function loadMembers()
    {
        if(file_exists(self::$filePath) && filesize(self::$filePath) > 10)
        {
            $data = file_get_contents(self::$filePath);
            
            $data = substr($data, 9);      
            $decoded = json_decode($data, true);
            
            $list = array();

            foreach($decoded as $dkey => $value)
            {
                foreach($value as $key => $val)
                {
                    $boats = array();
                    if(count($value["boats"]) > 0)
                    {
                        for($i = 0; $i < count($value["boats"]); $i++)
                        {           
                            $boats[] = new Boat($i + 1, $value["boats"][$i]["båttyp"], $value["boats"][$i]["längd"]);
                        }
                    }
                    $member = new Member($value["firstname"], $value["lastname"], $value["socialnumber"], $boats);
                    $member->setId($value["id"] + 0);
                }
                $list[] = $member;
            }
            return $list;
        }     
        return array();
    }
    
    /**
     * Saves the current MemberList to file
     * @param Array<Member>
     */
    public function saveMembers($list)
    {
        $returnlist = array();
        
        foreach($list as $member)
        {
            $inner = array();
            $inner["id"] = $member->getId();
            $inner["firstname"] = $member->getFirstname();
            $inner["lastname"] = $member->getLastname();
            $inner["socialnumber"] = $member->getSocial();
            $inner["boats"] = array();
            
            foreach($member->boats as $boat)
            {
                $inner["boats"][] = array("båttyp" => $boat->getType(), "längd" => $boat->getLength());
            }           
            $returnlist[] = $inner;
        }
        
        $encoded = json_encode($returnlist);
        $data = "<?php\r\n//" . $encoded;
        file_put_contents(self::$filePath, $data);
    }
}
