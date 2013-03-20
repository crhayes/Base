<?php

function extend($name)
{
	View::getInstance()->extend($name);
}

function section($name)
{
	View::getInstance()->section($name);
}

function close()
{
	echo View::getInstance()->close();
}

function show($name)
{
	echo View::getInstance()->show($name);
}

function partial($name)
{
	echo View::getInstance()->load($name);
}