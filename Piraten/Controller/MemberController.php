<?php

require_once("./View/MemberView.php");
require_once("./Model/MemberList.php");
require_once("./Model/Search.php");

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
    
    public function __construct()
    {
        $this->view = new MemberView();
        $this->model = new MemberList($this->view);
        $this->model->LoadMembers();
        $this->search = new Search($this->model);
        $this->message = new Message();
        $this->handleInput();
    }
    
    private function handleInput()
    {   
        try
        {
            if($this->view->viewMember())
            {
                $this->view->generateHeader();
            }
            if($this->view->addMember())
            {
                $this->model->AddMember();
                $this->view->printMessage($this->message->fetchMessage(8));
            }
            if($this->view->deleteMember())
            {
                $this->model->DeleteMember();
                $this->view->printMessage($this->message->fetchMessage(3));
            }
            if($this->view->editMember())
            {
                $this->model->EditMember();
                $this->view->printMessage($this->message->fetchMessage(9));
            }
            if($this->view->addBoat())
            {
                $this->model->AddBoat();
                $this->view->printMessage($this->message->fetchMessage(10));
            }
            if($this->view->editBoat())
            {
                $this->model->EditBoat();
                $this->view->printMessage($this->message->fetchMessage(13));
            }
            if($this->view->deleteBoat())
            {
                $this->model->DeleteBoat();
                $this->view->printMessage($this->message->fetchMessage(11));
            }
            if($this->view->viewMember())
            {
                $this->model->ViewMember();
            }
        }
        catch(Exception $e)
        {
            $this->view->printMessage($this->message->fetchMessage($e->getMessage() + 0));
        }
        
        if(!$this->view->viewMember())
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
    }
}
