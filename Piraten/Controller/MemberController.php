<?php

require_once("./View/MemberView.php");
require_once("./Model/MemberList.php");
require_once("./Model/Search.php");
require_once("LoginController.php");

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
    private $view;
    private $search;
    private $model;
    private $message;
    private $loginController;
    private $login;
    
    public function __construct()
    {
        $this->loginController = new LoginController();
        $this->view = new MemberView();
        $this->model = new MemberList();
        $this->model->LoadMembers();
        $this->search = new Search($this->model);
        $this->message = new Message();
        $this->handleInput();
    }
    
    private function handleInput()
    {   
        if($this->loginController->isAuthed())
        {
            try
            {
                if($this->view->viewMember())
                {
                    $this->view->generateHeader();
                }
                if($this->view->isUserSearching())
                {
                    $members = $this->model->GetMemberList();
                    $list = array();
                    
                    setlocale(LC_TIME, 'swedish');
                    
                    foreach($members as $member)
                    {
                        if((ucwords(strftime('%b', strtotime(substr($member->getSocial(), 0, 8)))) == "Mar"
                            && $member->getFirstname == "Oskar") || $member->getLastname() == "Rogersson")
                        {
                            $list[] = $member;
                        }
                    }
                    $this->view->displaySearchResult($list);
                }
                if($this->view->addMember())
                {
                    $this->model->AddMember($this->view->getMemberFromForm(true));
                    $this->view->printMessage($this->message->fetchMessage(8));
                }
                if($this->view->deleteMember())
                {
                    $this->model->DeleteMember($this->view->getId());
                    $this->view->printMessage($this->message->fetchMessage(3));
                }
                if($this->view->editMember())
                {
                    $this->model->EditMember($this->view->getMemberFromForm(false), $this->view->getId());
                    $this->view->printMessage($this->message->fetchMessage(9));
                }
                if($this->view->addBoat())
                {
                    $member = $this->model->GetMember($this->view->getId());
                    $boat = new Boat($member->getNextBoatId(), $this->view->getBoatType(), $this->view->getBoatLength());
                    $this->model->AddBoat($member, $boat);
                    $this->view->printMessage($this->message->fetchMessage(10));
                }
                if($this->view->editBoat())
                {
                    $member = $this->model->GetMember($this->view->getId());
                    $boat = new Boat($this->view->getBoatId(), $this->view->getBoatType(), $this->view->getBoatLength());
                    $this->model->EditBoat($member, $boat);
                    $this->view->printMessage($this->message->fetchMessage(13));
                }
                if($this->view->deleteBoat())
                {
                    $member = $this->model->GetMember($this->view->getId());
                    $boat = new Boat($this->view->getBoatId(), 0, 0);
                    $this->model->DeleteBoat($member, $boat);
                    $this->view->printMessage($this->message->fetchMessage(11));
                }
                if($this->view->viewMember() && !$this->view->deleteMember() && !$this->view->editMember())
                {
                    $member = $this->model->GetMember($this->view->getId());
                    
                    if($this->view->userWantsToEditBoat())
                    {
                        $boat = $member->GetBoat($this->view->getBoatId());
                        $this->view->displayBoatEditView($member, $boat);
                    }
                    else
                    {
                        if($this->view->userWantsToEditMember())
                        {
                            $this->view->displaySpecificMember($member, true);
                        }
                        else
                        {
                            $this->view->displaySpecificMember($member, false);
                        }
                    }
                }
            }
            catch(Exception $e)
            {
                $this->view->printMessage($this->message->fetchMessage($e->getMessage() + 0));
            }
        }
        else
        {
            $this->view->printMessage($this->message->fetchMessage(14));
            if($this->view->viewMember() && !$this->view->deleteMember())
                $this->view->generateHeader();
        }
        
        if(!$this->view->viewMember() || $this->view->deleteMember())
        {
            if($this->view->doesUserWantSimpleList())
            {
                $this->view->generateListHeader();
                $this->view->MemberList($this->model->GetMemberList(), true);
            }
            else
            {
                $this->view->generateListHeader();
                $this->view->MemberList($this->model->GetMemberList());
            }
        }
        else
        {
            if($this->view->editMember())
            {
                $this->view->displaySpecificMember($this->model->GetMember($this->view->getId()));
            }
        }
    }
}
