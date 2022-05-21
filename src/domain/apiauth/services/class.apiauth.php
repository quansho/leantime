<?php

namespace leantime\domain\services {

    use leantime\core;

    class apiauth
    {
        public function __construct()
        {
            $this->tpl = new core\template();
        }

        public function getApiRoute()
        {
            var_dump(5);
        }


    }

}
