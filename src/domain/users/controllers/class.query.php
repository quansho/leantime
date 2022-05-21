<?php

namespace leantime\domain\controllers {

    use leantime\domain\repositories;


	class query
	{

		/**
		 * run - display template and edit data
		 *
		 * @access public
		 */
		public function run()
        {
            $userRepo = new repositories\users();
            $userRepo->getUserByEmail($_GET['email']);
            echo json_encode($userRepo->getUserByEmail($_GET['email']));
        }

	}

}
