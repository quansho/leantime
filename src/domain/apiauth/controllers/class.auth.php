<?php

namespace leantime\domain\controllers {

    use leantime\core\session;

    class auth
    {
        /**
         * get - handle get requests
         *
         * @access public
         * @params parameters or body of the request
         */
        public function get($params)
        {
            echo "GET Action triggered";
        }

        /**
         * post - handle post requests
         *
         * @access public
         * @params parameters or body of the request
         */
        public function post($params)
        {
            echo json_encode(headers_list());
        }

        /**
         * patch - handle patch requests
         *
         * @access public
         * @params parameters or body of the request
         */
        public function patch($params)
        {
            echo "PATCH Action triggered";
        }

    }

}
