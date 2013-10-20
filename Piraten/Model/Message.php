<?php

/**
 * Class which holds messages displayed in the system.
 *
 * @version 1.0
 * @author Fawk
 */
class Message
{
    private $messageList = 
    array("Inloggad.",
        "Utloggad",
        "Fel användarnamn eller lösenord!",
        "Medlemmen blev borttagen utan problem.",
        "Medlemmen som angavs finns inte!",
        "Personnummer är inte angett i rätt format!",
        "Förnamn måste vara mellan 2 och 15 tecken!",
        "Efternamn måste vara mellan 2 och 25 tecken!",
        "Medlemmen har blivit tillagd utan problem.",
        "Medlemmen har ändrats utan problem.",
        "Båten har blivit tillagd utan problem.",
        "Båten blev borttagen utan problem!",
        "Båten som angavs fanns inte!",
        "Båten har blivit ändrad utan problem.");
    
    /**
     * Fetches a message with applied id
     * 
     * 0  : Inloggad.
     * 1  : Utloggad.
     * 2  : Fel användarnamn eller lösenord!
     * 3  : Medlemmen blev borttagen utan problem.
     * 4  : Medlemmen som angavs finns inte!
     * 5  : Personnummer är inte angett i rätt format!
     * 6  : Förnamn måste vara mellan 2 och 15 tecken!
     * 7  : Efternamn måste vara mellan 2 och 25 tecken!
     * 8  : Medlemmen har blivit tillagd utan problem.
     * 9  : Medlemmen har ändrats utan problem.
     * 10 : Båten har blivit tillagd utan problem.
     * 11 : Båten blev borttagen utan problem!
     * 12 : Båten som angavs fanns inte!
     * 13 : Båten har blivit ändrad utan problem!
     * @param $int - the id for the message
     */
    public function fetchMessage($int)
    {
        return $this->messageList[$int];
    }
}
