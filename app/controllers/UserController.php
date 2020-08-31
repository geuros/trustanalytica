<?php

use Phalcon\Mvc\Controller;

class UserController extends Controller {
	public function indexAction() {
		$auth = $this->session->get( 'auth', false );
		if ( empty( $auth ) == false ) {
			return $this->response->redirect( 'user/cabinet' );
		}
	}

	public function registerAction() {
		$auth = $this->session->get( 'auth', false );
		if ( empty( $auth ) == false ) {
			return $this->response->redirect( 'user/cabinet' );
		}

		$this->view->data = $this->session->get( 'register_data', [] );
	}

	public function loginAction() {
		$auth = $this->session->get( 'auth', false );
		if ( empty( $auth ) == false ) {
			return $this->response->redirect( 'user/cabinet' );
		}

		$this->view->data = $this->session->get( 'login_data', [] );
	}

	public function logoutAction() {
		$this->session->remove( 'auth' );

		return $this->response->redirect( '/' );
	}

	public function cabinetAction() {
		$this->view->auth = $this->session->get( 'auth', false );

		if ( empty( $this->view->auth ) ) {
			return $this->response->redirect( 'user/login' );
		}
	}

	public function loginSubmitAction() {
		if ( $this->request->isPost() == false ) {
			$this->flashSession->error( 'Empty request data.' );

			return $this->response->redirect( 'user/login' );
		}

		$form_data = $this->request->getPost();
		unset( $form_data[ 'password' ] );

		$this->session->set( 'login_data', $form_data );

		if ( $this->security->checkToken() == false ) {
			$this->flashSession->error( 'Invalid token.' );

			return $this->response->redirect( 'user/login' );
		}

		$user = new Users();
		$userinfo = $user->findFirst( [
			'(driver_licence= :driver_licence:)',
			'bind' => array( 'driver_licence' => $this->request->getPost( 'driver_licence' ) )
		] );

		if ( empty( $userinfo ) == false ) {
			if ( password_verify( $this->request->getPost( 'password' ), $userinfo->password ) ) {
				$this->session->set( 'auth', [
					'id' => $userinfo->id
				] );

				$this->session->remove( 'login_data' );

				$this->flashSession->success( 'Success login.' );

				return $this->response->redirect( 'user/cabinet' );
			} else {
				$this->flashSession->success( 'Invalid Driver Licence/Password.' );

				return $this->response->redirect( 'user/login' );
			}
		}
	}

	public function registerSubmitAction() {
		if ( $this->request->isPost() == false ) {
			$this->flashSession->error( 'Empty request data.' );

			return $this->response->redirect( 'user/register' );
		}

		$form_data = $this->request->getPost();
		unset( $form_data[ 'password' ] );

		$this->session->set( 'register_data', $form_data );

		if ( $this->security->checkToken() == false ) {
			$this->flashSession->error( 'Invalid token.' );

			return $this->response->redirect( 'user/register' );
		}

		$user = new Users();

		// Assign value from the form to $user
		$user->assign(
			$this->request->getPost(),
			[
				'name',
				'surname',
				'age',
				'phone',
				'driver_licence',
				'address',
				'password'
			]
		);

		$user->password = password_hash( $user->password, PASSWORD_DEFAULT );

		// Store and check for errors
		$success = $user->save();

		// passing the result to the view
		$this->view->success = $success;

		if ( $success ) {
			$this->session->remove( 'register_data' );

			$message = 'Thanks for registering!';

			$this->flashSession->success( $message );

			$this->response->redirect( 'user/login' );
		} else {
			$message = 'Sorry, the following problems were generated:<br>'
				. implode( '<br>', $user->getMessages() );

			$this->flashSession->error( $message );

			$this->response->redirect( 'user/register' );
		}

		$this->view->disable();
	}
}

