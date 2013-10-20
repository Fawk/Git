<?php

require_once("FileSystem.php");
require_once("Message.php");

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
    private $view;
    
    public function __construct(MemberView $view)
    {
        $this->fileSystem = new FileSystem();
        $this->view = $view;
    }
    
    public function LoadMembers()
    {
        $this->allMembers = $this->fileSystem->loadMembers();
    }
    
    public function GetMemberList()
    {
        return $this->allMembers;
    }
    
    public function AddMember()
    {
        $member = $this->view->getMemberFromForm(true);
        $member->setId($this->getNextId());
        $this->allMembers[] = $member;
        $this->fileSystem->saveMembers($this);
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
    
    public function ViewMember()
    {
        $member = $this->GetMember($this->view->getId());
        $this->view->displaySpecificMember($member);
    }
    
    public function EditMember()
    {
        $other = $this->view->getMemberFromForm(false);
        $m = $this->GetMember($this->view->getId());
        $other->setId($m->getId());
        foreach($this->allMembers as $member)
        {
            if($member->isSame($other))
            {
                $member->Edit($other);
                $this->fileSystem->saveMembers($this);
            }
        }
    }
    
    public function DeleteMember()
    {
        $other = $this->GetMember($this->view->getId());
        foreach($this->allMembers as $key => $member)
        {
            if($member->isSame($other))
            {
                unset($this->allMembers[$key]);
                $this->fileSystem->saveMembers($this);
            }
        }
    }
    
    public function AddBoat()
    {
        $member = $this->GetMember($this->view->getId());
        $boat = new Boat($member->getNextBoatId(), $this->view->getBoatType(), $this->view->getBoatLength());
        $member->boats[] = $boat;
        $this->fileSystem->saveMembers($this);
    }
    
    public function EditBoat()
    {
        $other = $this->GetMember($this->view->getId());
        $boat = new Boat($this->view->getBoatId(), $this->view->getBoatType(), $this->view->getBoatLength());
        foreach($this->allMembers as $member)
        {
            if($member->isSame($other))
            {
                $member->EditBoat($boat);
                $this->fileSystem->saveMembers($this);
            }
        }
    }
    
    public function DeleteBoat()
    {
        $other = $this->GetMember($this->view->getId());
        $boat = new Boat($this->view->getBoatId(), 0, 0);
        foreach($this->allMembers as $mkey => $member)
        {
            if($member->isSame($other))
            {
                $result = $member->DeleteBoat($boat);
                $this->fileSystem->saveMembers($this);   
            }
        }
    }
}
