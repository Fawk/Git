<?php

/**
 * FileSystem short summary.
 *
 * FileSystem description.
 *
 * @version 1.0
 * @author Fawk
 */
class FileSystem
{
    /**
    * @var string - link to various files important to the system
    */
    private static $usersFilePath = "./data/users.php";
    private static $formsFilePath = "./data/forms.php";
    private static $answersFilePath = "./data/answers.php";
    
    /**
     * Returns an array of users
     * @return Array<User>
     */
    public static function loadUsers()
    {
        $data = FileSystem::getDataFromFile(self::$usersFilePath);
        
        if(count($data) != 0)
        {
            $list = array();

            foreach($data as $dkey => $value)
            {
                $user = new User($value["username"], $value["password"]);
                $user->SetId($value["id"] + 0);
                $user->SetCookieTime($value["time"] + 0);
                $user->SetIp($value["ip"]);
                $user->SetAgent($value["agent"]);
                $list[] = $user;
            }
            return $list;
        }
        return $data;
    }
    
    /**
    * Function to delete applied form
    * @param Form $other
    */
    public static function DeleteForm(Form $other)
    {
        $list = FileSystem::loadAllForms();
        
        foreach($list as $key => $form)
        {
            if($form->GetUniqueId() == $other->GetUniqueId())
            {
                unset($list[$key]);
            }
        }
        
        $answers = FileSystem::loadAnswers();
        
        foreach($answers as $key => $answer)
        {
            if($answer->formId == $other->GetId())
            {
                unset($answers[$key]);
            }
        }
        
        FileSystem::saveForms($list);
        FileSystem::saveAnswers($answers);
    }
    
    /**
    * Function to save a new Answer
    * @param Answer $answer
    */
    public static function saveAnswer(Answer $answer)
    {
        $list = FileSystem::loadAnswers();
        $list[] = $answer;
        FileSystem::saveAnswers($list);
    }
    
    /**
    * Function to save all answers
    * @param Array<Answer>
    */
    public static function saveAnswers($list)
    {
        $save = array();
        foreach($list as $answer)
        {
            $inner = array();
            $inner['uid'] = $answer->userId;
            $inner['username'] = $answer->username;
            $inner['fid'] = $answer->formId;
            $inner['formver'] = $answer->formVer;
            $inner['answers'] = array();
            foreach($answer->answers as $key => $value)
            {
                $inner['answers'][] = $value;
            }
            $save[] = $inner;
        }
        self::putDataToFile(self::$answersFilePath, $save);
    }
    
    /**
    * Function to save applied form
    * @param Form $other;
    */
    public static function saveForm(Form $other)
    {
        $list = FileSystem::loadAllForms();
        $done = false;
        
        foreach($list as $form)
        {
            if($form->GetId() == $other->GetId())
            {
                $form->SetTitle($other->GetTitle());
                $form->SetDescription($other->GetDescription());
                $form->SetPrivate($other->GetPrivate());
                $form->inputs = array();
                
                if(count($other->inputs) != 0)
                {
                    foreach($other->inputs as $input)
                    {
                        $form->inputs[] = $input;
                    }
                }
                
                $done = true;
            }
        }

        if(!$done)
        {
            $list[] = $other;
        }

        FileSystem::saveForms($list);
    }
    
    /**
    * Function to save applied list of forms
    * @param Array<Form>
    */
    public static function saveForms($list)
    {
        $returnlist = array();
        
        foreach($list as $form)
        {
            $inner = array();
            $inner['id'] = $form->GetId();
            $inner['uniqueId'] = $form->GetUniqueId();
            $inner['formver'] = $form->GetVersion();
            $inner['private'] = $form->GetPrivate();
            $inner['title'] = $form->GetTitle();
            $inner['description'] = $form->GetDescription();
            $inner['userId'] = $form->GetUserId();
            $inner['inputs'] = array();
            
            if(count($form->inputs) != 0)
            {
                foreach($form->inputs as $input)
                {
                    $arr = array(
                        "id" => $input->GetId(), 
                        "label" => $input->GetLabel(), 
                        "description" => $input->GetDescription(),
                        "type" => $input->GetType(),
                        "required" => $input->GetRequired(),
                        "formId" => $input->GetFormId());
                        
                        if(count($input->radiovalues) != 0)
                        {
                            foreach($input->radiovalues as $key => $val)
                            {
                                $arr["radiovalues"][] = $val;
                            }
                        }
                        else
                        {
                            $arr["radiovalues"] = array();
                        }
                        
                    $inner['inputs'][] = $arr;
                }
            }
            $returnlist[] = $inner;
        }

        self::putDataToFile(self::$formsFilePath, $returnlist);
    }
    
    /**
    * Function to return all forms from the data file or empty array if there were no forms
    * @return Array<Form>
    */
    public static function loadAllForms()
    {
        $data = FileSystem::getDataFromFile(self::$formsFilePath);

        if(count($data) != 0)
        {
            $list = array();
            
            foreach($data as $dkey => $v)
            {
                foreach($v as $key => $dv)
                {
                    $form = new Form($v['title'], $v['description']);
                    $form->SetId($v['id'] + 0);
                    $form->SetUniqueId($v['uniqueId']);
                    $form->SetVersion($v['formver']);
                    $form->SetPrivate($v['private'] + 0);
                    $form->SetUserId($v['userId'] + 0);
                        
                    if(count($v['inputs']) != 0)
                    {
                        for($i = 0; $i < count($v['inputs']); $i++)
                        {
                           $input = new Input(
                                $v['inputs'][$i]['id'] + 0, 
                                $v['inputs'][$i]['label'], 
                                $v['inputs'][$i]['description'], 
                                $v['inputs'][$i]['type'], 
                                $v['inputs'][$i]['required'] + 0);
                           $input->SetFormId($v['inputs'][$i]['formId'] + 0);
                           
                           if(count($v['inputs'][$i]['radiovalues']) != 0)
                           {
                               foreach($v['inputs'][$i]['radiovalues'] as $rk => $rv)
                               {
                                    $input->radiovalues[] = $rv;
                               }
                           }
                           $form->inputs[] = $input;
                        }
                    }         
                }
                $list[] = $form;
            }             
            return $list;
        }
        return $data; 
    }
    
    /**
    * Function to return all answers or empty array if there were none
    * @return Array<Answer>
    */
    public static function loadAnswers()
    {
        $list = array();
        $data = FileSystem::getDataFromFile(self::$answersFilePath);
        if(count($data) != 0)
        {
            foreach($data as $key => $v)
            {
                $answer = new Answer($v['uid'], $v['username'], $v['fid'], $v['formver']);
                foreach($v['answers'] as $key => $value)
                {
                    $answer->answers[$key] = $value;
                }
                $list[] = $answer;
            }
            return $list;
        }
        return $data;
    }
    
    /**
    * Function to get all answers on a certain form
    * @param Form @form
    * @return Array<Answer>
    */
    public static function getFormAnswers(Form $form)
    {
        $answers = FileSystem::loadAnswers();
        $return = array();
        foreach($answers as $answer)
        {
            if($answer->formId == $form->GetId())
            {
                $return[] = $answer;
            }
        }
        return $return;
    }
    
    /**
     * Saves users to file
     * @param Array<User>
     */
    public static function saveUsers($list)
    {
        $returnlist = array();
        
        foreach($list as $user)
        {
            $inner = array();
            $inner['id'] = $user->GetId();
            $inner['username'] = $user->GetUsername();
            $inner['password'] = $user->GetPassword();
            $inner['ip'] = $user->GetIp();
            $inner['agent'] = $user->GetAgent();         
            $inner['time'] = $user->GetCookieTime();
            $returnlist[] = $inner;
        }
        
        self::putDataToFile(self::$usersFilePath, $returnlist);
    }
    
    /**
    * Function to get all forms on applied User
    * @param User $user
    * @return Array<Form>
    */
    public static function getUserForms(User $user)
    {
        $list = FileSystem::loadAllForms();
        $forms = array();
        
        if(count($list) > 0)
        {
            foreach($list as $form)
            {
                if($form->GetUserId() == $user->GetId())
                {
                    $forms[] = $form;
                }
            }
        }
        return $forms;
    }
    
    /**
    * Function to return json encoded data from a file or empty array if file is empty or missing
    * @param string $file
    * @return Array
    */
    private static function getDataFromFile($file)
    {
        if(file_exists($file) && filesize($file) > 0)
        {
            $data = file_get_contents($file);
            
            $data = substr($data, 9);      
            $decoded = json_decode($data, true);
        
            return $decoded;
        }
        return array();
    }
    
    /**
    * Function to write data to a file
    * @param string $file
    * @param array $list
    */
    private static function putDataToFile($file, $list)
    {
        $encoded = "";
        $data = "";
        if(count($list) != 0)
        {
            $encoded = json_encode($list);
            $data = "<?php\r\n//" . $encoded;
        }
        file_put_contents($file, $data);
    }
}
