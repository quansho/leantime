<?php

namespace leantime\domain\controllers {

    use leantime\core;
    use leantime\domain\repositories;

    class showAll
    {

        /**
         * run - display template and edit data
         *
         * @access public
         */

        public function run()
        {

            $tpl = new core\template();

            if(core\login::userIsAtLeast("user")) {

                $projectRepo = new repositories\projects();

                $tpl->assign('role', $_SESSION['userdata']['role']);

                if(core\login::userIsAtLeast("manager")) {
                    $tpl->assign('allProjects', $projectRepo->getAll());
                }else{
                    $tpl->assign('allProjects', $projectRepo->getClientProjects(core\login::getUserId()));
                }

                $tpl->display('projects.showAll');
            }else{

                $tpl->display('general.error');

            }

        }

    }

}
