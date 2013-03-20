<?php

class IndexController extends Controller
{
	public function actionIndex()
	{
		$data = array(
			'title' => 'Welcome to Base!'
		);

		return View::make('index', $data);
	}

	public function actionSite($slug)
	{
		return $slug;
	}
}