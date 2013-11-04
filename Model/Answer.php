<?php

/**
 * Answer short summary.
 *
 * Answer description.
 *
 * @version 1.0
 * @author Fawk
 */
class Answer
{
    /**
    * @var int $userId
    */
    public $userId;
    
    /**
    * @var string $username
    */
    public $username;
    
    /**
    * @var int $formId
    */
    public $formId;
    
    /**
    * @var int FormVer
    */
    public $formVer;
    
    /**
    * Array<string>
    */
    public $answers = array();
    
    /**
    * Construct function to set UserId, Username, FormId and FormVer to the
    * @param int $uid
    * @param string $username
    * @param int $fid
    * @param int $formver
    */
    public function __construct($uid, $username, $fid, $formver)
    {
        $this->userId = $uid;
        $this->username = $username;
        $this->formId = $fid;
        $this->formVer = $formver;
    }
}
