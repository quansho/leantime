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
            $clientsRepo = new repositories\clients();
            echo json_encode([$clientsRepo->getClientByEmail($_GET['email'])]);
        }

	}

}
