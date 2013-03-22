<?php

class IndexController extends Controller
{
	public function actionIndex()
	{
		//Session::set('user', 'Chris');
//Session::forget('user');
		echo Session::get('user');
		$title = 'Welcome to Base!';

		return View::make('index', compact('title'));
	}
}