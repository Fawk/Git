<?php

/**
 * PageView short summary.
 *
 * PageView description.
 *
 * @version 1.0
 * @author Fawk
 */
class PageView
{
    /**
    * @var string html $header
    */
    private $header;
    /**
    * @var string html $footer
    */
    private $footer;
    
    /**
    * @var Array<string> link - both below
    */
    private $stylesheets = array();
    private $javascripts = array();
    
    /**
    * Function to add stylesheet
    * @param string $link
    */
    public function AddStylesheet($link)
    {
        $this->stylesheets[] = $link;
    }
    
    /**
    * Function to add javascript
    * @param string $link
    */
    public function AddJavascript($link)
    {
        $this->javascripts[] = $link;
    }
    
    /**
    * Function to get header html
    * @param string $title - html
    * @return string html
    */
    public function GetHeader($title = "")
    {
        $str = "<!DOCTYPE html>
                <html> 
                <head> 
                    <title>$title</title> 
                    <meta http-equiv=\"content-type\" content=\"text/html; charset=utf-8\">\n";
                    foreach($this->stylesheets as $key => $value)
                    {
                        $str .= "<link rel='stylesheet/less' href='$value' type='text/css'>\n";
                    }           
       $str .= "</head>
                <body>\n";
           
           return $str;
    }
    
    /**
    * Function to get footer html
    * @return string html
    */
    public function GetFooter()
    {
        $str = "";
        foreach($this->javascripts as $key => $value)
        {
            $str .= "\n<script type='text/javascript' src='$value'></script>";
        }
        $str .= "\n</body>
                </html>"; 
        return $str;
    }
}
