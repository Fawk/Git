<?php

require_once("./View/FormView.php");
require_once("./Model/FormHandler.php");

/**
 * FormController short summary.
 *
 * FormController description.
 *
 * @version 1.0
 * @author Fawk
 */
class FormController
{
    /**
    * @var FormView $view
    * @var FormHandler $model
    */
    private $view;
    private $model;
    
    /**
    * Construct function that takes FormView as param and inits the model as well as loading all forms
    * @param FormView $view
    */
    public function __construct(FormView $view)
    {
        $this->view = $view;
        $this->model = new FormHandler();
        $this->model->LoadForms();
    }
    
    /**
    * Main function for the FormController to determine what the user wants to do
    * Takes two user object, first one being the Authed user, second one being user fetched from id
    * @param User $user
    * @param User $otheruser
    */
    public function toggleFormAction(User $user, $otheruser)
    {
        if($otheruser != null)
        {
            $html = "";
            $exists = true;
            
            if($otheruser->GetId() == $user->GetId())
            {
                if($this->view->CreatingForm())
                {
                    $html = $this->view->FormAction($otheruser, "create");
                }
                elseif($this->view->CreateForm())
                {
                    $form = $this->view->GetFormInfo();
                    if($form != null)
                    {        
                        $this->model->HandleForm($user->GetId(), $form);
                        $html = $this->view->Success("create");
                    }
                    else
                    {
                        $html = $this->view->FormAction($otheruser, "create");
                    }
                }
            }
            
            if($this->view->IsViewingForm())
            {   
                $rform = $this->model->GetForm($this->view->GetFormKey());
                
                $same = $otheruser->GetId() == $user->GetId();
                
                if($rform != null)
                {
                    if($this->view->EditForm() && $same)
                    {
                        $html = $this->view->FormAction($user, "edit", $rform);
                    }
                    elseif($this->view->Editing() && $same)
                    {
                        $form = $this->view->GetFormInfo();
                        if($form != null)
                        {
                            $form->SetUniqueId($rform->GetUniqueId());
                            $this->model->HandleForm($user->GetId(), $form);
                            $html = $this->view->Success("edit");
                        }
                        else
                        {
                            $html = $this->view->FormAction($user, "edit", $rform);
                        }
                    }
                    elseif($this->view->DeleteForm() && $same)
                    {
                        $this->model->HandleForm($user->GetId(), $rform);
                        $html = $this->view->Success("delete");
                    }
                    elseif($this->view->AnswerForm() && $rform != null)
                    {
                        $answer = $this->view->GetAnswers($user, $rform);
                        if($answer != null)
                        {
                            $this->model->SaveAnswer($answer);
                            $html = $this->view->Success("answer");
                        }
                    }
                    else
                    {
                        $this->view->ViewForm($rform, $otheruser, $user, $this->model->GetFormAnswers($rform));
                        $exists = false;
                    }
                }
                else
                {   
                    $this->view->NoForm();
                    $exists = false;
                }                     
            }
            
            if($exists)
            {
                $this->view->ListUserForms(
                    $this->model->GetUserForms($otheruser), 
                    $otheruser, 
                    $user, 
                    $html);
            }
        }
        else
        {
            $this->view->NoUser();
        }
    }
    
    /**
    * Return UserId from the currently viewed form
    * @return int
    */
    public function GetUserIdFromForm()
    {
        if($this->view->IsViewingForm())
        {
            $form = $this->model->GetForm($this->view->GetFormKey());
            if($form != null)
                return $form->GetUserId();  
        }
    }
}
