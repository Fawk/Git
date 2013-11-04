<?php

require_once("FileSystem.php");

/**
 * MemberList short summary.
 *
 * MemberList description.
 *
 * @version 1.0
 * @author Fawk
 */
class MemberList
{
    private $allMembers;
    private $fileSystem;
    
    public function __construct()
    {
        $this->fileSystem = new FileSystem();
    }
    
    public function LoadMembers()
    {
        $this->allMembers = $this->fileSystem->loadMembers();
    }
    
    public function GetMemberList()
    {
        return $this->allMembers;
    }
    
    public function AddMember(Member $member)
    {
        $member->setId($this->getNextId());
        $this->allMembers[] = $member;
        $this->fileSystem->saveMembers($this->allMembers);
    }
    
    public function getNextId()
    {
        $id = 0;
        if(count($this->allMembers) != 0)
        {
            foreach($this->allMembers as $member)
            {
                if($member->getId() > $id)
                    $id = $member->getId();
            }
        }
        return $id + 1;
    }
    
    public function GetMember($id)
    {
        foreach($this->allMembers as $member)
        {
            if($member->getId() == $id)
            {
                return $member;
            }
        }
        $e = new Exception("4");
        throw $e;
    }
    
    public function EditMember(Member $other, $id)
    {
        $m = $this->GetMember($id);
        $other->setId($m->getId());
        foreach($this->allMembers as $member)
        {
            if($member->isSame($other))
            {
                $member->Edit($other);
                $this->fileSystem->saveMembers($this->allMembers);
            }
        }
    }
    
    public function DeleteMember($id)
    {
        $other = $this->GetMember($id);
        foreach($this->allMembers as $key => $member)
        {
            if($member->isSame($other))
            {
                unset($this->allMembers[$key]);
                $this->fileSystem->saveMembers($this->allMembers);
            }
        }
    }
    
    public function AddBoat(Member $member, Boat $boat)
    {
        $member->boats[] = $boat;
        $this->fileSystem->saveMembers($this->allMembers);
    }
    
    public function EditBoat(Member $other, Boat $boat)
    {
        $other->EditBoat($boat);
        $this->fileSystem->saveMembers($this->allMembers);
    }
    
    public function DeleteBoat(Member $member, Boat $boat)
    {
        $member->DeleteBoat($boat);
        $this->fileSystem->saveMembers($this->allMembers);        
    }
}
