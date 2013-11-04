<?php

require_once("./Model/Member.php");

/**
 * MemberView short summary.
 *
 * MemberView description.
 *
 * @version 1.0
 * @author ok222ax
 */
class MemberView
{
    /**
     * @var string - all below
     */
    private static $addMemberKey = "addMember";
    private static $addMemberKeySimple = "add/member";
    private static $editMemberFormKeySimple = "edit";
    private static $editMemberKey = "editMember";
    private static $editBoatKey = "editBoat";
    private static $editBoatKeySimple = "boat/edit/";
    private static $editBoatFormKeySimple = "boat_edit";
    private static $deleteBoatKey = "deleteBoat";
    private static $deleteBoayKeySimple = "/delete/boat/";
    private static $editMemberKeySimple = "edit/member/";
    private static $deleteMemberKey = "deleteMember";
    private static $deleteMemberKeySimple = "delete/member/";
    private static $memberListKey = "listMembers";
    private static $memberListKeySimple = "list/member";
    private static $memberListSmallKey = "small";
    private static $idKey = "id";
    private static $boatIdKey = "boatId";
    private static $firstNameKey = "firstName";
    private static $lastNameKey = "lastName";
    private static $SocialNumberKey = "socialNumber";
    private static $firstNameEditKey = "firstNameEdit";
    private static $lastNameEditKey = "lastNameEdit";
    private static $SocialNumberEditKey = "socialNumberEdit";
    private static $userWantsToAddMemberKey = "userWantsToAddMember";
    private static $userWantsToEditMemberKey = "userWantsToEditMember";
    private static $addMemberCancelKey = "Avbryt";
    private static $addMemberSaveKey = "Spara";
    private static $userIsSearchingKey = "search";
    private static $searchQueryKey = "q";
    private static $addBoatKey = "addBoat";
    private static $addBoatKeySimple = "/add/boat/";
    private static $boatTypeKey = "formBoatType";
    private static $boatLengthKey = "formBoatLength";
    
    public function printMessage($msg)
    {
        echo "<p>" . $msg . "</p>";
    }
    
    public function MemberList($members, $isSimple = false)
    {
        $str = "<form action='/" . self::$addMemberKeySimple . "' method='post'>";
        $str .= "<table class='members'>";
        if($isSimple) $str .= "<tr><td>Medlemsnr:</td><td>Namn:</td><td>Antal båtar:</td></tr>";
        if(!$isSimple) 
        {
            $str .= "<a href='#' id='addmember'>Lägg till medlem</a>";
            $str .= "<tr><td>Medlemsnr:</td><td>Förnamn:</td><td>Efternamn:</td><td>Personnummer:</td><td>Båtar</td></tr>";
            $str .= "<tr class='addmemberfields'>
                            <td></td>
                            <td><input type='text' size='10' name='" . self::$firstNameKey . "'></td>
                            <td><input type='text' size='10' name='" . self::$lastNameKey . "'></td>
                            <td><input type='text' size='10' name='" . self::$SocialNumberKey . "'></td>
                            <td>
                                <a href='#' id='" . self::$addMemberCancelKey . "'>" . self::$addMemberCancelKey . "</a> - 
                                <input type='submit' value='" . self::$addMemberSaveKey . "' />
                            </td>
                        </tr>";
        }
        
        foreach($members as $member)
        {
            if($isSimple)
            {
                $str .= "<tr>
                            <td>" . $member->getId() . "</td>
                            <td><a href='" . $member->getId() . "'>" . $member->getFirstname() . " " . $member->getLastname() . "</a></td>
                            <td>" . count($member->boats) . "</td>
                            <td><a href='/" . self::$deleteMemberKeySimple . $member->getId() . "'>Ta bort medlem</a></td>
                        </tr>";
            }
            else
            {
                $str .= "<tr>
                            <td>" . $member->getId() . "</td>
                            <td>" . $member->getFirstname() . "</td>
                            <td>" . $member->getLastname() . "</td>
                            <td>" . $member->getSocial() . "</td>
                            <td class='boattd'>";
                            if(count($member->boats) != 0)
                            {
                                $str .= "<a href='#' class='showboats'>Visa båtar</a>";
                            }
                            else
                            {
                                $str .= "Inga båtar";
                            }
                                $str .= "<a href='#' class='hideboats'>Göm båtar</a>
                                <div class='boats'>";
                                    foreach($member->boats as $boat)
                                    {
                                        $str .= $boat->getBoatType() . " -> Längd: " . $boat->getLength() . "<br/>";
                                    }
                       $str .= "</div>
                            </td>
                            <td>
                                <a href='/" . $member->getId() . "'>Mer info</a>
                                <a href='/" . self::$deleteMemberKeySimple . $member->getId() . "'>Ta bort medlem</a>
                            </td>";
            }
        }
        
        $str .= "</table>";
        $str .= "</form>";
        
        echo $str;
    }

    public function displaySpecificMember(Member $member, $isEdit = false)
    {
        $str = "<div>";
        if($isEdit) $str .= "<form action='/" . self::$editMemberKeySimple . $member->getId() . "' method='post' id='editmemberform'>";

        $str .= "<div class='values'>";
        $str .= "<p>" . $member->getId() . "</p>";
        if($isEdit) {
            $str .= "<input type='text' name='" . self::$firstNameEditKey . "' value='" . $member->getFirstname() . "'/></p>";
            $str .= "<input type='text' name='" . self::$lastNameEditKey . "' value='" . $member->getLastname() . "'/></p>";
            $str .= "<input type='text' name='" . self::$SocialNumberEditKey . "' value='" . $member->getSocial() . "'/></p>";
        } else {
            $str .= "<p>" . $member->getFirstname() . "</p>";
            $str .= "<p>" . $member->getLastname() . "</p>";
            $str .= "<p>" . $member->getSocial() . "</p>";
        }
        $str .= "</div>";

        if(count($member->boats) > 0) {
            foreach($member->boats as $boat)
            {
                $str .= $boat->getBoatType() . " -> Längd: " . $boat->GetLength() . " - 
                <a href='/" . $member->getId()  . "/" . self::$editBoatKeySimple . $boat->getId() . "'>Ändra båt</a> - 
                <a href='" . self::$deleteBoayKeySimple . $member->getId() . "/" . $boat->getId() . "'>Ta bort båt</a><br/>";
            }
        }
        else
        {
            $str .= "Den här medlemmen har inga båtar!<br/>";
        }
                
        if(!$isEdit) 
        {
            $str .= 
                "<div class='addboatcontainer'>
                    <form action='" . self::$addBoatKeySimple . $member->getId() . "' method='post'>
                        <p>";
                            $ref = new ReflectionClass("BoatType");
                            $constants = $ref->getConstants();
                            $str .= "
                            <label for='boattype'>Båttyp:</label>
                            <select name='" . self::$boatTypeKey . "' id='boattype'>";
                            foreach($constants as $key => $value)
                            {
                                $str .= "<option value='" . $value . "'>" . $key . "</option>";
                            }
                            $str .= 
                            "</select>
                        </p>
                        <p>
                            <label for='boatlength'>Båtlängd:</label>
                            <input type='number' id='boatlength' name='" . self::$boatLengthKey . "' min='1' max='999' />
                        </p>
                        <p>
                            <a href='#' class='abortaddboat'>Avbryt</a> <input type='submit' value='" . self::$addMemberSaveKey . "' />
                        </p>
                    </form>
                </div>";      
        }
                
        if(!$isEdit) $str .= "<p><a href='#' class='addboat'>Lägg till båt</a></p>";
        if(!$isEdit) $str .= "<br/><a href='/" . $member->getId() . '/' . self::$editMemberFormKeySimple . "' class='showeditfields'>Ändra medlemsuppgifter</a><br/>";
        if($isEdit) $str .= "<br/><a href='./'>Tillbaka</a><br/><input type='submit' value='Spara' /><br/>";
        
        $str .= "</div>";
        if($isEdit) $str .= "</form>";
        echo $str;
    }
    
    public function returnLink()
    {
        echo "<a href='?'>Tillbaka</a>";
    }
    
    public function displayBoatEditView(Member $member, Boat $boat)
    {
        $str = "<h3>Redigerar båt " . $boat->getId() ."  för medlemsnummer: " . $member->getId() . "</h3>";
        $str .= "<form action='/" . self::$editBoatKeySimple . $member->getId() . "/" . $boat->getId() . "' method='post'>";
        $ref = new ReflectionClass("BoatType");
        $constants = $ref->getConstants();
        $str .= "
            <label for='boattype'>Båttyp:</label>
            <select name='" . self::$boatTypeKey . "' id='boattype'>";
            foreach($constants as $key => $value)
            {
                $selected = "";
                if($key == $boat->getBoatType())
                {
                    $selected = "selected";
                }
                $str .= "<option value='" . $value . "' $selected>" . $key . "</option>";
            }
            $str .= "</select>
            <p>
                <label for='boatlength'>Båtlängd:</label>
                <input type='number' id='boatlength' name='" . self::$boatLengthKey . "' min='1' max='999' value='" . $boat->getLength() . "' />
            </p>
            <a href='/" . $member->getId() ."'>Tillbaka</a><br/><input type='submit' value='Spara' />
            </form>";

        echo $str;
    }
    
    public function displaySearchResult($result)
    {
        if(count($result) == 0)
        {
            echo "<p>Din sökning retunerade inga medlemmar!</p>";
        }
        else
        {
            echo "<pre><p><p>";
            print_r($result);
        }
    }
    
    public function generateHeader()
    {
        $str = "<div class='header'>";
        $str .= "<a href='/'>Medlemslista</a>";
        $str .= "</div>";
        echo $str;
    }
    
    public function generateListHeader()
    {
        $str = "<div class='listheader'>";
        $str .= "<a href='/'>Fullständig lista</a> - ";
        $str .= "<a href='/" . self::$memberListSmallKey . "'>Enkel lista</a>";
        $str .= "</div>";
        echo $str;
    }
    
    public function generateSavedSearches($searches)
    {
        echo "Sparade sökningar<br/><br/>";
        foreach($searches as $key => $value)
        {
            $str = "";

            if(strpos($value, "_") > -1)
            {
                $value = str_replace("_", " ", $value);
            }
            
            $value = str_replace("=", " -> ", $value);
            
            echo "<a href='/" . self::$userIsSearchingKey . "/" . $key . "'>" . $value . "</a> - ";
        }
        
        echo "<br/><br/>";
    }

    public function addMember()
    {
        return isset($_GET[self::$addMemberKey]);
    }
    
    public function editMember()
    {
        return isset($_GET[self::$editMemberKey]) && 
               isset($_GET[self::$idKey]) && 
               isset($_POST[self::$firstNameEditKey]) &&
               isset($_POST[self::$lastNameEditKey]) &&
               isset($_POST[self::$SocialNumberEditKey]);
    }
    
    public function deleteMember()
    {
        return isset($_GET[self::$deleteMemberKey]) && isset($_GET[self::$idKey]);
    }
    
    public function viewMember()
    {
        return isset($_GET[self::$idKey]);
    }
    
    public function getId()
    {
        $ret = -1;
        isset($_GET[self::$idKey]) ? $ret = $_GET[self::$idKey] : $ret = 0;
        return $ret + 0;
    }
    
    public function getBoatId()
    {
        return $_GET[self::$boatIdKey];
    }
    
    public function getMemberFromForm($isAdd)
    {
        $member = "";
        if($isAdd)
        {
            if(isset($_POST[self::$firstNameKey]) &&
               isset($_POST[self::$lastNameKey]) &&
               isset($_POST[self::$SocialNumberKey]))
            {
                $member = new Member($_POST[self::$firstNameKey],
                                     $_POST[self::$lastNameKey],
                                     $_POST[self::$SocialNumberKey]);
            }
            else
            {
                $member = new Member();
            }
        }
        else
        {
            if(isset($_POST[self::$firstNameEditKey]) &&
               isset($_POST[self::$lastNameEditKey]) &&
               isset($_POST[self::$SocialNumberEditKey]))
            {
                $member = new Member($_POST[self::$firstNameEditKey],
                                     $_POST[self::$lastNameEditKey],
                                     $_POST[self::$SocialNumberEditKey]);
            }
            else
            {
                $member = new Member();
            }
        }
        return $member;
    }
    
    public function getMemberForDelete()
    {
        return new Member("asd", "asd", "11111111-1111");
    }
    
    public function userWantsToEditMember()
    {
        return isset($_GET[self::$editMemberFormKeySimple]);
    }
    
    public function doesUserWantSimpleList()
    {
        return isset($_GET[self::$memberListSmallKey]);
    }
    
    public function userWantsToEditBoat()
    {
        return isset($_GET[self::$editBoatFormKeySimple]) && isset($_GET[self::$boatIdKey]) && isset($_GET[self::$idKey]);
    }
    
    public function isUserSearching()
    {
        return isset($_GET[self::$userIsSearchingKey]) && isset($_GET[self::$searchQueryKey]);
    }
    
    public function fetchSearchParameters()
    {
        return $_GET[self::$searchQueryKey];
    }
    
    public function deleteBoat()
    {
        return isset($_GET[self::$deleteBoatKey]) && isset($_GET[self::$boatIdKey]) && isset($_GET[self::$idKey]);
    }
    
    public function editBoat()
    {
        return isset($_GET[self::$editBoatKey]) && 
               isset($_GET[self::$boatIdKey]) && 
               isset($_POST[self::$boatLengthKey]) &&
               isset($_POST[self::$boatTypeKey]) &&
               isset($_GET[self::$idKey]);
    }
    
    public function getBoatType()
    {
        return $_POST[self::$boatTypeKey];
    }
    
    public function getBoatLength()
    {
        return $_POST[self::$boatLengthKey];
    }
    
    public function addBoat()
    {
        return isset($_GET[self::$addBoatKey]) && 
               isset($_POST[self::$boatTypeKey]) && 
               isset($_POST[self::$boatLengthKey]) &&
               isset($_GET[self::$idKey]);
    }
}
