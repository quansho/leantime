<?php

namespace leantime\domain\controllers {

    use leantime\core;
    use leantime\domain\repositories;
    use leantime\domain\services;

    class duplicateProject
    {


        public function __construct() {


            $this->tpl = new core\template();
            $this->projectRepo = new repositories\projects();
            $this->projectService = new services\projects();
            $this->language = new core\language();
            $this->clientRepo = new repositories\clients();

        }

        public function get()
        {

            //Only admins
            if(core\login::userIsAtLeast("user")) {

                if (isset($_GET['id']) === true) {

                    $id = (int)($_GET['id']);
                    $project = $this->projectService->getProject($id);

                    $this->tpl->assign("project", $project);
                    $this->tpl->displayPartial('projects.duplicateProject');

                }else{

                    $this->tpl->displayPartial('general.error');

                }

            }else{

                $this->tpl->displayPartial('general.error');

            }

        }

        public function post($params) {

            //Only admins
            if(core\login::userIsAtLeast("user")) {

                $id = (int)($_GET['id']);
                $projectName = $params['projectName'];
                $startDate = $this->language->getISODateString($params['startDate']);
                $ownerId = (int) core\login::getUserId();
                $assignSameUsers = false;

                if(isset($params['assignSameUsers'])) {
                    $assignSameUsers = true;
                }

                $result = $this->projectService->duplicateProject($id, $ownerId, $projectName, $startDate, $assignSameUsers );

                $this->tpl->setNotification(sprintf($this->language->__("notifications.project_copied_successfully"), BASE_URL."/projects/changeCurrentProject/".$result), 'success');

                $this->tpl->redirect(BASE_URL."/projects/duplicateProject/". $id);

            }else{

                $this->tpl->displayPartial('general.error');

            }


        }

    }

}
