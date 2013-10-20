<?php

/**
 * BoatType short summary.
 *
 * BoatType description.
 *
 * @version 1.0
 * @author Fawk
 */
class BoatType
{
    const Segelbåt = 0;
    const Motorbåt = 1;
    const Motorseglare = 2;
    const Kajak_Kanot = 3;
    const Övrigt = 4;
    
    public $type;
    
    public function __construct($int)
    {
        $this->type = $int;
    }
}
