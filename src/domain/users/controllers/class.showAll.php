<?php

namespace leantime\domain\controllers {

    use leantime\core;
    use leantime\domain\repositories;
    use leantime\domain\services;

    class showAll
    {


        public function get()
        {

            $tpl = new core\template();
            $userRepo =  new repositories\users();
            $ldapService = new services\ldap();

            //Only Admins

            $tpl->assign('allUsers', $userRepo->getAll());



            if(core\login::userIsAtLeast("user")) {

                if(core\login::userIsAtLeast("admin")) {
                    $tpl->assign('admin', true);

                    $tpl->assign('allUsers', $userRepo->getAll());

                }else{
                    $tpl->assign('allUsers', $userRepo->getAllClientUsers(core\login::getUserId()));
                }

                $tpl->assign('roles', core\login::$userRoles);

                $tpl->display('users.showAll');

            }else{

                $tpl->display('general.error');

            }

        }

        public function post($params) {

        }

    }

}
