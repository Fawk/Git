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
    private $header;
    private $footer;
    private $stylesheets = array();
    private $javascripts = array();
    
    public function AddStylesheet($link)
    {
        $this->stylesheets[] = $link;
    }
    
    public function AddJavascript($link)
    {
        $this->javascripts[] = $link;
    }
    
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
