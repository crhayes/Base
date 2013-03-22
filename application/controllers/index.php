<?php

class IndexController extends Controller
{
	public function actionIndex()
	{
		$title = 'Welcome to Base!';

		return View::make('index', compact('title'));
	}
}