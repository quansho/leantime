<?php

/**
 * newClient Class - Add a new client
 *
 */

namespace leantime\domain\controllers {

    use leantime\core;
    use leantime\domain\repositories;

    class newClient
    {

        /**
         * run - display template and edit data
         *
         * @access public
         */
        public function run()
        {

            $tpl = new core\template();
            $clientRepo = new repositories\clients();
            $user = new repositories\users();
            $language = new core\language();

            $headerAccepts = getallheaders()['Accept'];
            $isApiCall = (isset($headerAccepts) && $headerAccepts == 'application/json');
            if($isApiCall)
            {
                $input = file_get_contents('php://input');
                $postData = json_decode($input);
                $_POST = (array) $postData;
            }

            if(core\login::userIsAtLeast("manager")) {

                $values = array(
                    'name' => '',
                    'street' => '',
                    'zip' => '',
                    'city' => '',
                    'state' => '',
                    'country' => '',
                    'phone' => '',
                    'internet' => '',
                    'email' => ''
                );

                if (isset($_POST['save']) === true) {

                    $values = array(
                        'name' => ($_POST['name']),
                        'street' => ($_POST['street']),
                        'zip' => ($_POST['zip']),
                        'city' => ($_POST['city']),
                        'state' => ($_POST['state']),
                        'country' => ($_POST['country']),
                        'phone' => ($_POST['phone']),
                        'internet' => ($_POST['internet']),
                        'email' => ($_POST['email']),
                    );


                    if ($values['name'] !== '') {
                        if ($clientRepo->isClient($values) !== true) {

                            $id = $clientRepo->addClient($values);
                            $tpl->setNotification($language->__('notification.client_added_successfully'), 'success');

                            if($isApiCall)
                            {
                                echo json_encode(['id'=>$id]);exit();

                            }else{
                                $tpl->redirect(BASE_URL."/clients/showClient/".$id);
                            }

                        } else {

                            if($isApiCall)
                            {

                                $values = $clientRepo->getClientByEmail($values['email']);
                                echo json_encode(['id'=>$values['id']]);exit();

                            }else{

                                if($isApiCall)
                                {
                                    echo json_encode(['message'=>$language->__('notification.client_exists_already')]);exit();
                                }else{
                                    $tpl->setNotification($language->__('notification.client_exists_already'), 'error');
                                }
                            }

                        }
                    } else {

                        if($isApiCall)
                        {
                            echo json_encode(['message'=>$language->__('notification.client_exists_already')]);exit();
                        }else{
                            $tpl->setNotification($language->__('notification.client_name_not_specified'), 'error');
                        }
                    }

                }

                if(!$isApiCall)
                {
                    $tpl->assign('values', $values);
                    $tpl->display('clients.newClient');
                }else{
                    echo json_encode(['message'=>$language->__('notification.client_exists_already')]);exit();
                }



            } else {

                if(!$isApiCall)
                {
                    $tpl->display('general.error');
                }else{
                    echo json_encode(['message'=>$language->__('error')]);exit();
                }



            }

        }

    }

}
