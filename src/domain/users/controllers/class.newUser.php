<?php

namespace leantime\domain\controllers {

    use leantime\core;
	use leantime\domain\repositories;

	class newUser
	{

		/**
		 * run - display template and edit data
		 *
		 * @access public
		 */
		public function run()
		{

			$tpl = new core\template();
			$userRepo = new repositories\users();
			$project = new repositories\projects();
			$language = new core\language();

            $headerAccepts = getallheaders()['Accept'];
            $isApiCall = (isset($headerAccepts) && $headerAccepts == 'application/json');

            if($isApiCall)
            {
                $input = file_get_contents('php://input');
                $postData = json_decode($input);
                $_POST = (array) $postData;
            }

            $values = array(
				'firstname' => "",
				'lastname' => "",
				'user' => "",
				'phone' => "",
				'role' => "",
				'password' => "",
				'clientId' => ""
			);


			//only Admins
//			if (core\login::userIsAtLeast("clientManager")) {

				$projectrelation = array();
				if (isset($_POST['save'])) {

                    $tempPasswordVar = $_POST['password'];
					$values = array(
						'firstname' => ($_POST['firstname']),
						'lastname' => ($_POST['lastname']),
						'user' => ($_POST['user']),
						'phone' => ($_POST['phone']),
						'role' => (core\login::userIsAtLeast("admin")) ? ($_POST['role']) : 15,//TODO MAGIC 15
						'password' => (password_hash($_POST['password'], PASSWORD_DEFAULT)),
						'creatorId' => core\login::getUserId(),
//						'clientId' => ($_POST['client'])
					);

					//Choice is an illusion for client managers
//                if (core\login::userHasRole("clientManager") && !$isApiCall) {
//
//                        $values['clientId'] = core\login::getUserClientId();
//				}

					if ($values['user'] !== '') {

						if ($_POST['password'] == $_POST['password2']) {
							if (filter_var($values['user'], FILTER_VALIDATE_EMAIL)) {
								if (password_verify($_POST['password'], $values['password']) && $_POST['password'] != '') {
									if ($userRepo->usernameExist($values['user']) === false) {
										$userId = $userRepo->addUser($values);

										//Update Project Relationships
										if (isset($_POST['projects'])) {
											if ($_POST['projects'][0] !== '0') {
												$project->editUserProjectRelations($userId, $_POST['projects']);
											} else {
												$project->deleteAllProjectRelations($userId);
											}
										}

										$mailer = new core\mailer();

										$mailer->setSubject($language->__("email_notifications.new_user_subject"));
										$actual_link = BASE_URL;

										$message = sprintf($language->__("email_notifications.new_user_message"), $_SESSION["userdata"]["name"], $actual_link, $values["user"], $tempPasswordVar);
										$mailer->setHtml($message);

										$to = array($values["user"]);

                                        if($isApiCall)
                                        {
                                            echo json_encode(['id'=>$userId]);
                                            exit();
                                        }

                                        $mailer->sendMail($to, $_SESSION["userdata"]["name"]);

										$tpl->setNotification($language->__("notification.user_created"), 'success');

										$tpl->redirect(BASE_URL . "/users/showAll");

									} else {

                                        $messageInfo = $language->__("notification.user_exists");

                                            if($isApiCall)
                                            {
                                                echo json_encode(['message'=>$messageInfo,'type'=>'error','status'=>300]);
                                                exit();
                                            }else{
                                                $tpl->setNotification($messageInfo, 'error');
                                            }

									}
								} else {
                                    $message = $language->__("notification.passwords_dont_match");

                                    if($isApiCall)
                                    {
                                        echo json_encode(['message'=>$message,'type'=>'error','status'=>300]);
                                        exit();
                                    }

                                    $tpl->setNotification($message, 'error');
								}
							} else {
                                $message = $language->__("notification.no_valid_email");

                                if($isApiCall)
                                {
                                    echo json_encode(['message'=>$message,'type'=>'error','status'=>300]);
                                    exit();
                                }

                                $tpl->setNotification($message, 'error');

							}
						} else {

                            $message = $language->__("notification.passwords_dont_match");

                            if($isApiCall)
                            {
                                echo json_encode(['message'=>$message,'type'=>'error','status'=>300]);
                                exit();
                            }

                            $tpl->setNotification($message, 'error');
						}
					} else {
                        $message = $language->__("notification.enter_email");

                        if($isApiCall)
                        {
                            echo json_encode(['message'=>$message,'type'=>'error','status'=>300]);
                            exit();
                        }

                        $tpl->setNotification($message, 'error');
					}
				}

				$tpl->assign('values', $values);
				$clients = new repositories\clients();

				if (core\login::userIsAtLeast("manager")) {
//					$tpl->assign('clients', $clients->getAll());
					$tpl->assign('allProjects', $project->getAll());
					$tpl->assign('roles', core\login::$userRoles);
				} else {

//					$tpl->assign('clients', array($clients->getClient(core\login::getUserClientId())));
					$tpl->assign('allProjects', $project->getClientProjects(core\login::getUserId()));
					$tpl->assign('roles', core\login::$clientManagerRoles);
				}
				$tpl->assign('relations', $projectrelation);


				$tpl->display('users.newUser');

//			}
//            else {
//
//				$tpl->display('general.error');
//
//			}

		}

	}

}
