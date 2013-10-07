<?php

require_once("./Model/Member.php");
require_once("./View/MemberView.php");

/**
 * MemberController short summary.
 *
 * MemberController description.
 *
 * @version 1.0
 * @author ok222ax
 */
class MemberController
{
    private static $memberView;
    
    public function __construct()
    {
        $this->memberView = new MemberView();
        $this->handleInput();
    }
    
    private function handleInput()
    {
        if($this->memberView->addMember())
        {
            $this->memberView->validateMemberInfo();
        }
        
        if($this->memberView->editMember())
        {
            $id = $this->memberView->getId();
            
        }
        
        if($this->memberView->deleteMember())
        {
            $id = $this->memberView->getId();
            if(Member::DeleteMember($id))
            {
                $this->memberView->handleMessage("SUCCESS_DELETE");
            } 
            else 
            {
                $this->memberView->handleMessage("MEMBER_MISSING", true);
            }
        }
        
        if($this->memberView->listMembers())
        {
            $list = Member::GenerateMemberList();
            $this->memberView->DisplayMemberList($list);
        }
    }
}
