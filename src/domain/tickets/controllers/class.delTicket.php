<?php

namespace leantime\domain\controllers {

    use leantime\core;
    use leantime\domain\services;
    use leantime\domain\repositories;

    class delTicket
    {

        private $ticketService;
        private $tpl;
        private $language;
        private $projectRepo;

        public function __construct()
        {
            $this->tpl = new core\template();
            $this->language = new core\language();
            $this->ticketService = new services\tickets();
            $this->projectRepo = new repositories\projects();

        }


        public function get()
        {

            //Only admins
            if(core\login::userIsAtLeast("user")) {

                if (isset($_GET['id'])) {
                    $id = (int)($_GET['id']);
                }

                $this->tpl->assign('ticket', $this->ticketService->getTicket($id));
                $this->tpl->display('tickets.delTicket');

            } else {

                $this->tpl->display('general.error');

            }

        }

        public function post($params) {

            $cond = (core\login::userIsAtLeast("user") &&  $this->projectRepo->getProject($_SESSION['currentProject'])['ownerId'] == core\login::getUserId() )
                ||
                core\login::userIsAtLeast("admin");

            if(!$cond)
            {
                $this->tpl->display('general.error');
            }

            if (isset($_GET['id'])) {
                $id = (int)($_GET['id']);
            }

            //Only admins
            if(core\login::userIsAtLeast("user")) {

                if (isset($params['del'])) {

                    $result = $this->ticketService->deleteTicket($id);

                    if($result === true) {
                        $this->tpl->setNotification($this->language->__("notification.todo_deleted"), "success");
                        $this->tpl->redirect($_SESSION['lastPage']);
                    }else{
                        $this->tpl->setNotification($this->language->__($result['msg']), "error");
                        $this->tpl->assign('ticket', $this->ticketService->getTicket($id));
                        $this->tpl->display('tickets.delTicket');
                    }

                }else{
                    $this->tpl->display('general.error');
                }

            }else{
                $this->tpl->display('general.error');
            }
        }

    }

}
