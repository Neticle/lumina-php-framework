<?php

namespace application\controller;

class DefaultController extends \system\web\Controller
{
	public function actionIndex()
	{
		$this->display('~index');
	}
}

