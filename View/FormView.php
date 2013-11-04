<?php

require_once("Message.php");
require_once("./Model/Form.php");
require_once("./Model/Input.php");
require_once("./Model/Answer.php");

/**
 * FormView short summary.
 *
 * FormView description.
 *
 * @version 1.0
 * @author Fawk
 */
class FormView
{
    /**
    * @var string - all below - keys used by the view
    */
    private static $createFormKey = "createForm";
    private static $creatingFormKey = "creatingForm";
    private static $idKey = "id";
    private static $formTitleKey = "formtitle";
    private static $formDescriptionKey = "formdescription";
    private static $formKey = "form";
    private static $formPrivateKey = "private";
    private static $editKey = "edit";
    private static $editingKey = "editing";
    private static $deleteKey = "delete";
    private static $inputTitle = "title";
    private static $inputDesc = "desc";
    private static $inputType = "type";
    private static $inputReq = "required";
    private static $inputRadio = "radiovalues";
    private static $answerField = "answer";
    
    /**
    * @var array<string> - used by the view to determine the allowed types of inputs
    */
    private $options = array("text", "number", "checkbox", "radio", "textarea", "date", "datetime-local", "time", "email");
    private $values = array("Textfält", "Nummer", "Kryssruta", "Radioknappar", "Stycketext", "Datum", "Datum/Tid", "Tid", "Epost");
    
    /**
    * @var const int - used in length rulings
    */
    const FORM_TITLE_MIN = 5;
    const FORM_TITLE_MAX = 50;
    const FORM_DESC_MIN = 50;
    const FORM_DESC_MAX = 500;
    
    /**
    * @var string - used in the view to store actual error message
    */
    private $errorMessage = "";
    
    /**
    * Construct function - inits the message class
    */
    public function __construct()
    {
        $this->message = new Message();
    }
    
    /**
    * Function to create the html for view a form
    * @param Form $form
    * @param User $user
    * @param User $viewer
    * @param Array<Answer> $answers
    */
    public function ViewForm(Form $form, User $user, User $viewer, $answers = array())
    {
        if($form->GetPrivate() != 1 || $user->GetId() == $viewer->GetId())
        {
            $str = "<div class='formview'>
                    <h4>" . $form->GetDescription() . "</h4>
                    <form name='answerform' action='?" . self::$formKey . "=" . $form->GetUniqueId() . "&" . self::$answerField . "' method='post'>
                        <div class='inputs'>";
            
            if(count($form->inputs) != 0)
            {
                foreach($form->inputs as $input)
                {
                    $id = $form->GetId() . "_" . $input->GetId();
                    $req = "";
                    $input->GetRequired() == 1 ? $req = "required" : $req = "";
                    $str .= "<label for='" . $id . "'>" . $input->GetLabel() . "</label>";
                    $str .= "<p>" . $input->GetDescription() . "</p>";
                    $idd = $input->GetId() - 1;
                    switch($input->GetType())
                    {
                        case "radio":
                            $str .= "<table class='radioinputs'>";
                            foreach($input->radiovalues as $k => $v)
                            {
                                $str .= "<tr><td><input type='radio' name='" . $form->GetUniqueId() . "[$idd]' id='" . $id . "_radio" . $k ."' value='$v' $req /></td><td>$v</td></tr>";
                            }
                            $str .= "</table>";
                            break;
                            
                        case "textarea":
                            $str .= "<textarea name='" . $form->GetUniqueId() . "[$idd]' id='$id' $req></textarea>";
                            break;
                            
                        default:
                            $str .= "<input type='" . $input->GetType() . "' name='" . $form->GetUniqueId() . "[$idd]' id='$id' $req />";
                            break;
                    }                  
                }        
            }
            
            $str .= "</div>
                         <div class='buttons'>
                            <button type='button' data-href='?" . self::$idKey . "=" . $user->GetId() . "'>Tillbaka</button>
                            <input type='submit' value='Svara' />
                        </div>
                     </form>";
            
            if($form->GetUserId() == $viewer->GetId())
            {
                $str .= "<div class='buttons'>
                            <button type='button' data-href='?" . self::$formKey . "=" . $form->GetUniqueId() . "&" . self::$editKey ."'>Ändra formulär</button>
                            <button type='button' data-href='?" . self::$formKey . "=" . $form->GetUniqueId() . "&" . self::$deleteKey ."'>Ta bort formulär</button>
                        </div>
                        <button type='button' class='showanswers'>Visa svar</button>";
                if(count($answers) != 0)
                {
                    $str .= "<div class='answers'>
                                <table>
                                    <tr><td>Användare:</td><td>Fver</td>";
                        foreach($form->inputs as $input)
                        {
                            $str .= "<td>" . $input->GetLabel() . "</td>";
                        }
                            $str .= "</tr>";
                        
                        foreach($answers as $answer)
                        {
                            if($answer->formVer != $form->GetVersion())
                            {
                                $str .= "<tr>
                                            <td><a href='?" . self::$idKey . "=$answer->userId'>$answer->username</a></td>
                                            <td><b>$answer->formVer</b> - Utdaterad svar</td>";
                                            for($a = 0; $a < count($form->inputs); $a++)
                                            {
                                                $str .= "<td> - </td>";
                                            }
                                $str .= "</tr>";
                            }
                            else
                            {
                            $str .= "<tr>
                                        <td><a href='?" . self::$idKey . "=$answer->userId'>$answer->username</a></td>
                                        <td>$answer->formVer</td>";
                                foreach($answer->answers as $key => $val)
                                {
                                    $str .= "<td>$val</td>";
                                }
                                $str .= "</tr>";      
                            }
                        }
                    }
                    
                $str .= "</table></div>";
            }
            else
            {
                $str .= "<h3>Ingen har svarat på detta formulär!</h3>";
            }
        }
            
        $str .= "</div>";
            
        $this->Container($form->GetTitle() . "<br/> 
            <span>Ett formulär av <a href='?" . self::$idKey . "=" . $user->GetId() . "'>" . $user->GetUsername() . "</a></span>", $str);
    }
    
    /**
    * Container function used for encapsling html and presenting it
    * @param string $title - html
    * @param string $html - html
    */
    public function Container($title, $html = "")
    {
        $str = "<div class='container'>
                    <article>
                        <header>
                            <h1>$title</h1>
                        </header>
                        <section>
                            $html
                        </section>
                    </article>
                </div>";
        
        echo $str;
    }
    
    /**
    * Function to return the appropriate success message for the applied action
    * @param string $which
    * @return string - html
    */
    public function Success($which)
    {
        $msg = "";
        switch($which)
        {
            case "create":
                $msg = $this->message->get(20);
                break;
                
            case "edit":
                $msg = $this->message->get(21);
                break;
            
            case "delete":
                $msg = $this->message->get(22);
                break;
                
            case "answer":
                $msg = $this->message->get(23);
                break;
        }
        
        return "<div class='success'>
                    <h3>$msg</h3>
                </div>";
    }
    
    /**
    * Error message whenever the user was not found
    */
    public function NoUser()
    {
        $this->Container($this->message->get(1));
    }
    
    /**
    * Error message whenver the form was not found
    */
    public function NoForm()
    {
        $this->Container($this->message->get(19));
    }
    
    /**
    * Function to list all the forms on a certain user
    * @param Array<Form> $list
    * @param User $user
    * @param User $viewer
    * @param string $html - html
    */
    public function ListUserForms($list, $user, $viewer, $html)
    {   
        $same = $user->GetId() == $viewer->GetId();
        $str = "";
        $title = "";

        if(count($list) != 0)
        {
            foreach($list as $form)
            {
                if($form->GetPrivate() != 1 || $same)
                {
                    $desc = substr($form->GetDescription(), 0, 40) . "...";
                    $str .= "<div class='userform'>
                                <a href='?" . self::$formKey . "=" . $form->getUniqueId() . "'>" . $form->GetTitle() . "</a>
                                <span class='formdesc'>$desc</span>
                            </div>";
                }
            }
            $title = "Listar formulär för användare: <b>" . $user->GetUsername() . "</b>";
        }
        else
        {
            if($same)
            {
                $title = "Du har inga formulär!";
            }
            else
            {
                $title = sprintf($this->message->get(12), "<b>" . $user->GetUsername() . "</b>");
            }
        }
        
        if($same) $str .= "<a class='cform' href='?" . self::$creatingFormKey . "'>Skapa nytt formulär</a>";
        $this->Container($title, $str . $html);
    }
    
    /**
    * Function to write out appropriate html to the action applied when working on a form
    * @param User $user
    * @param string $which - the action
    * @param Form $form
    * @return string - html
    */
    public function FormAction(User $user, $which, $form = null)
    {
        switch($which)
        {
            case "create":
                $action = self::$createFormKey;
                $id = "formadd";
                $formtitle = $this->GetFormTitle();
                $formdesc = $this->GetFormDesc();
                $class = "createform";
                $text = "Skapar nytt formulär";
                $inputs = "";
                $private = "";
                break;
                
            case "edit":
                $action = self::$formKey . "=" . $form->GetUniqueId() . "&" . self::$editingKey;
                $title = "";
                $id = "formedit";
                $private = "";
                $form->GetPrivate() == 1 ? $private = "checked" : "";
                if(isset($_POST['submit']))
                {
                    $formtitle = $this->GetFormTitle();
                    $formdesc = $this->GetFormDesc();
                    $title = $formtitle;
                }
                else
                {
                    $formtitle = $form->getTitle();
                    $formdesc = $form->getDescription();
                    $title = $formtitle;
                }
                
                $inputs = "";
                
                if(count($form->inputs) != 0)
                {
                    foreach($form->inputs as $inputkey => $input)
                    {
                        $checked = "";
                        $input->GetRequired() == 1 ? $checked = "checked" : "";
                        $k = array_search($input->GetType(), $this->options);
                        $type = $this->values[$k];
                        $inputs .= "<div class='ui-state-default'>
                                <div class='fieldhead'>
                                    <span class='typeof'>$type</span>
                                    <span title='Ta bort fält' class='glyphicon glyphicon-remove'></span>
                                    <span title='Redigera fält' class='glyphicon glyphicon-wrench'></span>
                                </div>
                                <div class='field'>
                                    <select name='" . self::$inputType . "[]' class='selectpicker'>";
                                    foreach($this->options as $key => $value)
                                    {
                                        $sel = "";
                                        $this->options[$key] == $input->GetType() ? $sel = "selected" : $sel = "";
                                        $inputs .= "<option value='" . $value . "' $sel>" . $this->values[$key] . "</option>";
                                    }
                        $inputs .= "</select>";  
                        if($input->GetType() == "radio")
                        {
                            $inputs .= "<div class='radios'>";
                            if(count($input->radiovalues) > 0)
                            {
                                foreach($input->radiovalues as $rkey => $rval)
                                {
                                    $radio = $rkey + 1;
                                    $inputs .= "<div class='radioinput'><input type='text' class='input' title='Radioknappp " . $radio . " värde' placeholder='Radioknapp " . $radio . "' värde' name='" . self::$inputRadio . "[$inputkey][]' value='$rval' data-id='$radio' /><span title='Ta bort radioknapp' class='glyphicon glyphicon-remove'></span></div>";
                                }
                            }
                            $inputs .= "<a href='#' class='addradio'>Lägg till ny radioknapp</a>
                                       </div>";
                        }
                        $inputs .= "<input type='text' placeholder='Titel på fält' title='Titel på fält' name='" . self::$inputTitle . "[]' value='" . $input->GetLabel() . "' class='input' />
                                    <input type='text' name='" . self::$inputDesc . "[]' placeholder='Beskrivning av fältet' title='Beskrivning av fältet' value='" . $input->GetDescription() . "' class='input' />
                                    <input type='checkbox' value='1' class='required' name='" . self::$inputReq . "[]' $checked />
                                    <span class='required'>Är fältet obligatoriskt?</span>
                                </div>
                            </div>";
                    }
                }
                
                $class = "editform";
                $text = "Redigerar formulär '" . $title . "' för användare: <b>" . $user->GetUsername() ."</b>";
                break;
        }
        
        $str = "<div class='$class'>
                    <div class='heading'>
                        <h3>$text</h3>
                    </div>
                    <div class='formbody'>
                    <div class='successmessage'></div>
                    <div class='formerror'>$this->errorMessage</div>
                        <form action='?" . $action . "' method='post' id='$id' class='form'>
                            <label for='title'>
                                Titel för formuläret:
                                <input type='text' id='title' name='" . self::$formTitleKey . "' placeholder='Skriv titel här' size='60' title='Var god fyll i titel' value='" . $formtitle . "' />
                            </label><br/>
                            <label for='desc'>Beskrivning av formuläret:</label><br/>
                            <textarea id='desc' name='" . self::$formDescriptionKey . "' placeholder='Skriv beskrivning här' title='Var god fyll i beskrivning'>" . $formdesc . "</textarea><br/>
                            <label for='private'>Ska formuläret vara privat? (Bara du kommer att se det på din profil):</label>
                            <input type='checkbox' id='private' value='true' name='" . self::$formPrivateKey . "' $private />
                            <br/>
                            <button type='button' class='addinput'>Lägg till fält</button>
                            <span class='small'>Du kan dra och släppa de olika fälten i den ordningen du vill ha.</span>
                            <div id='sortable'>$inputs</div>
                            <div class='buttons'>
                                <button type='button' data-href='?' class='cancelcreateform'>Avbryt</button>
                                <input type='submit' value='Spara' />
                            </div>
                        </form>
                    </div>
                </div>";
        
        return $str;
    }
    
    /**
    * Is the user answering a form?
    * @return bool
    */
    public function AnswerForm()
    {
        return isset($_GET[self::$answerField]);
    }
    
    /**
    * Function to get answer object from $_POST
    * @param User $user
    * @param Form $form
    * @return Answer
    */
    public function GetAnswers(User $user, Form $form)
    {
        if(isset($_GET[self::$answerField]) &&
           isset($_GET[self::$formKey]) &&
           isset($_POST[$_GET[self::$formKey]]))
        {
            $answer = new Answer($user->GetId(), $user->GetUsername(), $form->GetId(), $form->GetVersion());

            for($i = 0; $i < count($form->inputs); $i++)
            {
                if(isset($_POST[$form->GetUniqueId()][$i]))
                {
                    $answer->answers[] = $_POST[$form->GetUniqueId()][$i];
                }
                else
                {
                    $answer->answers[] = null;
                }
            }
            return $answer;
        }
    }
    
    /**
    * Does the user want to edit a form?
    * @return bool
    */
    public function EditForm()
    {
        return isset($_GET[self::$editKey]);
    }
    
    /**
    * Is the user editing a form?
    * @return bool
    */
    public function Editing()
    {
        return isset($_GET[self::$editingKey]);
    }
    
    /**
    * Does the user want delete a form?
    * @return bool
    */
    public function DeleteForm()
    {
        return isset($_GET[self::$deleteKey]);
    }
    
    /**
    * Function to return a form object from $_POST or set error message
    * @return Form
    */
    public function GetFormInfo()
    { 
        if(isset($_GET[self::$createFormKey]) ||
           isset($_GET[self::$editKey]) ||
           isset($_GET[self::$editingKey]) &&
           isset($_POST[self::$formTitleKey]) &&
           isset($_POST[self::$formDescriptionKey]))
        {
            $form = new Form("","");
            $t = $_POST[self::$formTitleKey];
            $d = $_POST[self::$formDescriptionKey];
            
            if(array_key_exists(self::$inputTitle, $_POST))
            {
                for($i = 0; $i < count($_POST[self::$inputTitle]); $i++)
                {
                    if(isset($_POST[self::$inputTitle][$i]) && isset($_POST[self::$inputDesc][$i]))
                    {   
                        $required = 0;
                        isset($_POST[self::$inputReq][$i]) ? $required = 1 : "";
                        $input = new Input($i + 1, strip_tags($_POST[self::$inputTitle][$i]), strip_tags($_POST[self::$inputDesc][$i]), strip_tags($_POST[self::$inputType][$i]), $required);
                        
                        if(isset($_POST[self::$inputRadio][$i]) && $_POST[self::$inputRadio][$i] > 0)
                        {
                            for($j = 0; $j < count($_POST[self::$inputRadio][$i]); $j++)
                            {
                                $input->radiovalues[] = strip_tags($_POST[self::$inputRadio][$i][$j]);
                            }
                        }

                        $form->inputs[] = $input;
                    }
                }
            }

            $t = $this->TrimValue($t);
            $d = $this->TrimValue($d);
            
		    $t = strip_tags($t);
            $d = strip_tags($d);
            
            if($this->CheckValue($t, "title") && $this->CheckValue($d, "desc"))
            {
                $d = nl2br($d);
                
                $form->SetTitle($t);
                $form->SetDescription($d);
                $private = 0;
                isset($_POST[self::$formPrivateKey]) ? $private = 1 : $private = 0;
                $form->SetPrivate($private);
                return $form;
            }
        }
    }
    
    /**
    * Function to trim applied string
    * @param string $val
    * @return string
    */
    private function TrimValue($val)
    {
        return ltrim(rtrim($val));
    }
    
    /**
    * Function to check the input of a $_POST value
    * @param string $value
    * @param string $which
    */
    private function CheckValue($value, $which)
    {   
        if($which == "title")
        {
            if(strlen($value) < self::FORM_TITLE_MIN)
            {
                $this->errorMessage = sprintf($this->message->get(13), self::FORM_TITLE_MIN);
                return false;
            }
            elseif(strlen($value) > self::FORM_TITLE_MAX)
            {
                $this->errorMessage = sprintf($this->message->get(14), self::FORM_TITLE_MAX);
                return false;
            }
        }
        else
        {
            if(strlen($value) < self::FORM_DESC_MIN)
            {
                $this->errorMessage = sprintf($this->message->get(15), self::FORM_DESC_MIN);
                return false;
            }
            elseif(strlen($value) > self::FORM_DESC_MAX)
            {
                $this->errorMessage = sprintf($this->message->get(16), self::FORM_DESC_MAX);
                return false;
            }
        }
        return true;
    }
    
    /**
    * Get form title from $_POST
    * @return string
    */
    private function GetFormTitle()
    {
        if(isset($_POST[self::$formTitleKey]))
            return $_POST[self::$formTitleKey];
    }
    
    /**
    * Get form description from $_POST
    * @return string
    */
    private function GetFormDesc()
    {
        if(isset($_POST[self::$formDescriptionKey]))
            return $_POST[self::$formDescriptionKey];
    }
    
    /**
    * Does the user want create a form?
    * @return bool
    */
    public function CreateForm()
    {
        return isset($_GET[self::$createFormKey]);
    }
    
    /**
    * Is the user creating a form?
    * @return bool
    */
    public function CreatingForm()
    {
        return isset($_GET[self::$creatingFormKey]);
    }
    
    /**
    * Get id from $_GET
    * @return int
    */
    public function GetId()
    {
        return $_GET[self::$idKey];
    }
    
    /**
    * Does user want to view forms?
    * @return bool
    */
    public function ViewFormsOnUser()
    {
        return isset($_GET[self::$idKey]);
    }
    
    /**
    * Is the user viewing a form?
    * @return bool
    */
    public function IsViewingForm()
    {
        return isset($_GET[self::$formKey]);
    }
    
    /**
    * Get formKey from $_GET
    * @return string
    */
    public function GetFormKey()
    {
        return $_GET[self::$formKey];
    }
}