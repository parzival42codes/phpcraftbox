<?php

abstract class Event_abstract extends Base
{

    public function getEventCall($call)
    {
        if (
        method_exists($this,
                      $call)
        ) {
            return call_user_func([
                                      $this,
                                      $call
                                  ]);
        }
        else {
            //thrown
        }
    }

}
