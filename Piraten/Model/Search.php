<?php

/**
 * Search short summary.
 *
 * Search description.
 *
 * @version 1.0
 * @author Fawk
 */
class Search
{
    private $validParameters;
    private $memberList;
    private $parameterList;
    private $checks = 0;
    private $savedSearches;
    private static $paramLocation = "./data/validparameters.txt";
    private static $savedSearchesLocation = "./data/savedsearches.txt";
    
    public function __construct(MemberList $list)
    {
        $this->loadValidParameters();
        $this->memberList = $list->GetMemberList();
        $this->parameterList = array();
        $this->savedSearches = $this->loadSavedSearches();
    }
    
    public function getSavedSearches()
    {
        return $this->savedSearches;
    }
    
    private function saveSearchResult($result)
    {
        if(!$this->isSearchSaved($result, false))
        {
            $f = fopen(self::$savedSearchesLocation, 'a+');    
            
            $random_key = rand(100000, 999999);
            
            while($this->isSearchSaved($random_key, true))
            {
                $random_key = rand(100000, 999999);
            }
            
            $data = $random_key . "\040" . $result . "\r\n";
            
            fwrite($f, $data);
            
            $this->savedSearches = $this->loadSavedSearches();
            
            fclose($f);
            return true;
        }
        return false;
    }
    
    private function loadSavedSearches()
    {
        $f = fopen(self::$savedSearchesLocation, 'a+');
        $searches = array();
        if(filesize(self::$savedSearchesLocation) > 0)
        {
            $data = fread($f, filesize(self::$savedSearchesLocation));
            $str = explode("\r\n", $data);
            foreach($str as $key => $value)
            {
                if($value != "")
                {
                    $new = explode("\040", $value);
                    $new[0] = $new[0] + 0;
                    $searches[$new[0]] = $new[1];
                }
            }
        }  
        return $searches;
    }
    
    private function loadValidParameters()
    {
        $f = fopen(self::$paramLocation, "r");
        $data = fread($f, filesize(self::$paramLocation));
        
        $list = explode(",", $data);
        
        foreach($list as $key => $value)
        {        
            $this->validParameters[] = $value;
        }
        
        fclose($f);
    }
    
    private function isValidParameter($param)
    {
        if(!$this->checkForEmpty($param))
        {
            foreach($this->validParameters as $key => $value)
            {
                if($param == $value)
                {
                    return true;
                }
            }
        }
        return false;
    }
    
    // Don't forget to add parameter specific checks inside handleRequest
    public function addValidParameter($param)
    {
        $f = fopen(self::$paramLocation, "w");
        
        $this->validParameters[] = $param;
        
        $str = "";
        foreach($this->validParameters as $key => $value)
        {
            $str .= $value . ",";
        }
        
        fwrite($f, $str);
        fclose($f);
        
        $this->loadValidParameters();
    }
    
    public function isValidList($list)
    {
        return preg_match("/^[a-zåäöA-ZÅÄÖ0-9_=<>]+$/", $list);
    }
    
    public function getResultList($parameters)
    {
        $test = $parameters + 0;
        
        if($this->isSearchSaved($test, true))
        {
            $parameters = $this->getSavedSearchByKey($test);
        }

        if($this->isValidList($parameters))
        {
            if(strpos($parameters, "_") > -1)
            {
                $split = explode("_", $parameters);
                for($j = 0; $j < count($split); $j++)
                {
                    $str = explode("=", $split[$j]);                       
                    for($i = 0; $i < count($str); $i += 2)
                    {
                        if($this->isValidParameter($str[$i]))
                        {
                            $this->parameterList = array_merge($this->parameterList, $this->handleRequest($str[$i], $str[$i + 1]));
                            $this->checks++;
                        }
                    }
                }
            }
            else
            {
                $str = explode("=", $parameters);
                for($i = 0; $i < count($str); $i += 2)
                {
                    if($this->isValidParameter($str[$i]))   
                    {
                        $this->parameterList = array_merge($this->parameterList, $this->handleRequest($str[$i], $str[$i + 1]));
                        $this->checks++;
                    }
                }
            }
            
            if($this->checks != 0)
                $this->saveSearchResult($parameters);
            
            return $this->GenerateResultList($this->parameterList);
        }
    }
  
    private function getSavedSearchByKey($key)
    {
        return $this->savedSearches[$key];
    }
    
    private function isSearchSaved($params, $isKey = false)
    {
        if($isKey)
        {
            return array_key_exists($params, $this->savedSearches);
        }
        else
        {
            foreach($this->savedSearches as $key => $value)
            {
                if($value == $params)
                {
                    return true;
                }
            }
        }
    }
    
    private function checkForEmpty($string)
    {
        return empty($string) || preg_match('/\s/', $string);
    }
    
    private function handleRequest($param, $value)
    {  
        $keys = array();
        
        switch($param)
        {
            case "månad":
                
                $keys = array_merge($keys, $this->handleDate($value, "month"));

                break;
                
            case "dag":
                
                $keys = array_merge($keys, $this->handleDate($value, "day"));
                
                break;
                
            case "år":

                $keys = array_merge($keys, $this->handleDate($value, "year"));

                break;
            
            case "båttyp":
                
                foreach($this->memberList as $member)
                {
                    foreach($member->boats as $boat)
                    {
                        if($boat->getBoatType() == $value)
                        {
                            $keys[] = $member;
                        }
                    }
                }
                 
                break;         
           
            case "längd":
                
                $keys = array_merge($keys, $this->handleRangeValues($value, "length"));

                break;
                
            case "ålder":
                
                $keys = array_merge($keys, $this->handleRangeValues($value, "birthday"));
                
                break;
                
            case "förnamn":
                
                $keys = array_merge($keys, $this->handleString($value, "förnamn"));
                
                break;
                
            case "efternamn":
                
                $keys = array_merge($keys, $this->handleString($value, "efternamn"));
                
                break;   
                
            case "medlemsnummer":
                
                foreach($this->memberList as $member)
                {
                    if($member->getId() == $value)
                    {
                        $keys[] = $member;
                    }
                }
                
                break;
        }

        return $keys;
    }
    
    private function handleDate($value, $which)
    {
        $keys = array();
        
        switch($which)
        {
            case "day":
                $start = 6;
                $lower = 0;
                $length = 2;
                $upper = 32;
                break;
            case "month":
                $start = 4;
                $lower = 0;
                $length = 2;
                $upper = 13;
                break;
            case "year":
                $start = 0;
                $lower = 0;
                $length = 4;
                $upper = 3000;
                break;
        }
        
        $value = $value + 0;
        
        if($value > $lower && $value < $upper) {
            
            if($value < 10) $value = "0" + $value;
            foreach($this->memberList as $member)
            {

                if(substr($member->getSocial(), $start, $length) == "$value")
                {
                    $keys[] = $member;
                }
            }
        }
        return $keys;
    }
    
    private function handleRangeValues($value, $which)
    {
        $type = "";
        $keys = array();
        
        if(strpos($value, ">") !== false)
        {
            $type = "more";
        }
        elseif(strpos($value, "<") !== false)
        {
            $type = "less";  
        }

        $value = substr($value, 1) + 0;
        $compare = 0;

        if($value > 0)     
        {
            foreach($this->memberList as $member)
            {
                switch($which) 
                {
                    case "birthday":
                        
                        $bd = substr($member->getSocial(), 0, 8);
                        $tz = new DateTimeZone("Europe/Stockholm");
                        $compare = DateTime::createFromFormat('Ymd', $bd, $tz)->diff(new DateTime('now', $tz))->y;

                    break;
                    
                    case "length":
                        
                        foreach($member->boats as $boat)
                        { 
                            $compare = $boat->getLength();
                        }
                        
                    break;
                        
                }
                
                if($type == "less") 
                { 
                    if($compare < $value) 
                    { 
                        $keys[] = $member;
                    } 
                }
                elseif($type == "more") 
                { 
                    if($compare > $value) 
                    { 
                        $keys[] = $member;
                    } 
                }
                else
                {
                    if($compare == $value)
                    {
                        $keys[] = $member;
                    }
                }
            }
        }
        return $keys;
    }
    
    private function handleString($value, $which)
    {
        $keys = array();
        foreach($this->memberList as $member)
        {
            if($which == "förnamn")
            {
                if(strpos($member->getFirstname(), $value) !== false)
                {
                    $keys[] = $member;
                }
            }
            else
            {
                if(strpos($member->getLastname(), $value) !== false)
                {
                    $keys[] = $member;
                }
            }
        }
        return $keys;
    }
    
    private function GenerateResultList()
    {
        $keys = array();
        $repeated = array();
        foreach($this->parameterList as $member){
            if (!isset($repeated[$member->getId()])) $repeated[$member->getId()] = 0;
            $repeated[$member->getId()]++;
        }
        
        foreach($repeated as $key => $value)
        {
            if($value == $this->checks)
            {
                $keys[] = $this->memberList[$key - 1];
            }
        }
        
        return $keys;
    }
}
