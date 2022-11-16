<?php

namespace Xiso\InertiaUI\Forms;

class Form{
    public string $method = 'GET';
    public string $action = '/';
    public string $enctype = 'multipart/form-data';


    public function setPOST(){
        $this->setMethod('POST');
    }
    public function setGET(){
        $this->setMethod('GET');
    }
    public function setPUT(){
        $this->setMethod('PUT');
    }
    public function setDELETE(){
        $this->setMethod('DELETE');
    }

    private function setMethod($method): static
    {
        $allow_methods = ["GET","PUT","DELETE","POST"];
        if(!in_array($method,$allow_methods)) $method = "GET";

        $this->method = $method;
        return $this;
    }
}
