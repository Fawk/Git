<?php

require_once("./Model/Member.php");
require_once("BaseView.php");

/**
 * MemberView short summary.
 *
 * MemberView description.
 *
 * @version 1.0
 * @author ok222ax
 */
class MemberView extends BaseView
{
    private static $addMemberKey = "addMember";
    private static $editMemberKey = "editMember";
    private static $deleteMemberKey = "deleteMember";
    private static $memberListKey = "listMembers";
    private static $idKey = "id";
    private static $firstNameKey = "firstName";
    private static $lastNameKey = "lastName";
    private static $SocialNumberKey = "socialNumber";
    
    public function DisplayMemberList($list)
    {
        $str = "<ul class='members'>";      
        foreach($list as $member)
        {
            $str .= "<li>" 
                       . $member->getUnique() . " "
                       . $member->getFirstName() . " " 
                       . $member->getLastName() .
                    "</li>";

        }        
        $str .= "</ul>";
        
        echo $str;
    }
    
    public function validateMemberInfo()
    {
        $firstname = $_POST[self::$firstNameKey];
        $lastname = $_POST[self::$lastNameKey];
        $socialnumber = $_POST[self::$SocialNumberKey];
        
        if($this->checkForEmpty($firstname))
        {
            $this->handleMessage("FIRST_NAME_ERROR", true);
        }   
        else if($this->checkForEmpty($lastname))
        {
            $this->handleMessage("LAST_NAME_ERROR", true);
        }
        else if(!preg_match('/\d{6}\-\d{4}/', $socialnumber))
        {
            $this->handleMessage("SOCIAL_INVALID", true);
        } 
        else
        {
            new Member($firstname, $lastname, $socialnumber);
            $this->handleMessage("MEMBER_CREATED");
        }        
    }
    
    private function checkForEmpty($string)
    {
        return empty($string) || preg_match('/\s/', $string);
    }
    
    public function addMember()
    {
        return isset($_GET[self::$addMemberKey]);
    }
    
    public function editMember()
    {
        return isset($_GET[self::$editMemberKey]) && isset($_GET[self::$idKey]);
    }
    
    public function deleteMember()
    {
        return isset($_GET[self::$deleteMemberKey]) && isset($_GET[self::$idKey]);
    }
    
    public function listMembers()
    {
        return isset($_GET[self::$memberListKey]);
    }
    
    public function getId()
    {
        return $_GET[self::$idKey];
    }
}
