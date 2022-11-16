<?php

namespace Xiso\InertiaUI\Forms;

class Form{
    public string $method = 'GET';
    public string $action = '/';
    public string $enctype = 'multipart/form-data';

    public function setPOST(): static {
        return $this->setMethod('POST');
    }

    public function setGET(): static {
        return $this->setMethod('GET');
    }

    public function setPUT(): static {
        return $this->setMethod('PUT');
    }

    public function setDELETE(): static {
        return $this->setMethod('DELETE');
    }

    protected function setMethod($method): static
    {
        $allow_methods = ["GET","PUT","DELETE","POST"];
        if(!in_array($method,$allow_methods)) $method = "GET";

        $this->method = $method;
        return $this;
    }
}
