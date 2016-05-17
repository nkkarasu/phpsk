<?php

class indexController extends PSK_Controller
{
	function indexAction()
	{
		$this->log->WriteLog("Hi.", PSK_ET_APPINFORMATION);
		$this->RenderView();
	}
}