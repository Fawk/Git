<?php

require_once("FileSystem.php");
require_once("Form.php");

/**
 * FormHandler short summary.
 *
 * FormHandler description.
 *
 * @version 1.0
 * @author Fawk
 */
class FormHandler
{
    /**
    * @var Array<Form> $allForms;
    */
    private $allForms;
    
    /**
    * Function to load all the forms
    */
    public function LoadForms()
    {
        $this->allForms = FileSystem::loadAllForms();
    }
    
    /**
    * Function to get the next available form id
    * @return int
    */
    public function GetNextFormId()
    {
        $id = 0;
        if(count($this->allForms) != 0)
        {
            foreach($this->allForms as $form)
            {
                if($form->GetId() > $id)
                    $id = $form->GetId();
            }
        }
        return $id + 1;
    }
    
    /**
    * Function to the form with the applied UniqueId
    * @param string $unique
    * @return Form
    */
    public function GetForm($unique)
    {
        foreach($this->allForms as $form)
        {
            if($form->GetUniqueId() == $unique)
            {
                return $form;
            }
        }
    }
    
    /**
    * Forwards the answer to the FileSystem for saving
    * @param Answer $answer
    */
    public function SaveAnswer(Answer $answer)
    {
        FileSystem::saveAnswer($answer);
    }
    
    /**
    * Get all answers on applied form
    * @param Form $form
    * @return Array<Answer>
    */
    public function GetFormAnswers(Form $form)
    {
        return FileSystem::getFormAnswers($form);
    }
    
    /**
    * Function to handle the actions on a form
    * @param int $id
    * @param Form $form
    */
    public function HandleForm($id, Form $form)
    {
        if($form->GetId() != null) // Action: Delete
        {
            FileSystem::DeleteForm($form);
        }
        else
        {
            if($form->GetUniqueId() != null) // Action: Edit
            {
                $f = $this->GetForm($form->GetUniqueId());
                $form->SetId($f->GetId());
                $form->SetUserId($f->GetUserId());
                $form->SetVersion($f->GetVersion() + 1);
            }
            elseif($form->GetId() == null) // Action: Create
            {
                $form->GenerateUniqueId();
                while($this->DoesUniqueIdExist($form->GetUniqueId()))
                {
                    $form->GenerateUniqueId();
                }
                $form->SetId($this->GetNextFormId());
                $form->SetUserId($id);
                $form->SetVersion(1);
            }
            
            if(count($form->inputs) != 0)
            {
                foreach($form->inputs as $input)
                {
                    $input->SetFormId($form->GetId());
                }
            }
            
            $this->SaveForm($form);
        }
    } 
    
    /**
    * Function to check if the unique id already exists
    * @return bool
    */
    public function DoesUniqueIdExist($unique)
    {
        foreach($this->allForms as $form)
        {
            if($form->GetUniqueId() == $unique)
                return true;
        }
        return false;
    }
    
    /**
    * Call to the FileSystem function saveForm
    * @param Form $form
    */
    public function SaveForm(Form $form)
    {
        FileSystem::saveForm($form);
    }
    
    /**
    * Get forms on certain user
    * @param User $user
    * @return Array<Form>
    */
    public function GetUserForms(User $user)
    {
        return FileSystem::getUserForms($user);
    }
}
