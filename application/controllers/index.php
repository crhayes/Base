<?php

class IndexController extends Controller
{
	public function actionIndex()
	{
		return Redirect::to('site')->with('name', 'Chris');
		$title = 'Welcome to Base!';

		$users = Database::query('SELECT * FROM users');

		return View::make('index', compact('title', 'users'));
	}

	public function actionSite()
	{
		echo Session::get('name');
	}
}