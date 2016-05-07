<?php

require_once 'psk_dbecol_base.php';
require_once 'psk_image.php';

class PSK_DBEC_Image extends PSK_DBEC_Base
{

	private $_imageMeta = array();

	/**
	 * Returns the renderend content of the value of the column.
	 *
	 * @param int $par_DataMode
	 *
	 * @param integer $par_ViewMode
	 *
	 * @return string
	 *
	 * @see psk_consts.php for Data Mode constants.
	 *
	 * @see psk_consts.php for View Mode constants.
	 */
	function RenderValue($par_DataMode = PSK_DM_VIEW,
	                     $par_ViewMode = PSK_VM_LIST)
	{
		if (!$this->visible) return;

		$this->__OnRender($this->valueTag, $this->onValueRender, $par_DataMode);

		$mc = count($this->_imageMeta);

		if ($mc == 0) {
			return $this->valueTag->Render();
		}
		$path = $this->_imageMeta[$mc - 1]['path'];

		switch ($par_DataMode) {
			case PSK_DM_INSERT:
				$this->valueTag->inner =
					"<div class=\"wrap\">" .
					"<input type=\"file\" name=\"" . $this->__id .
					"\" id=\"" . $this->__id . "\" class=\"dbfile\" />" .
					"</div>";

				//PSK_Log::getInstance()->WriteDebug($this->valueTag->inner);
				break;
			case PSK_DM_EDIT:
				if (($this->value != '') && ($this->value != '__NOFILE__')) {
					$this->valueTag->inner =
						"<img src=\"" .
						PSK_Uri::getInstance()->IncludePath($path . $this->value) . "\"/>".
						' ' .
						PSK_Uri::getInstance()->PostLink(PSK_STR_DBE_DELETEFILE,
							$this->__owner->getName(), 'deleteImage',
							array($this->__objectName, $this->value));
				} else {
					$con = $this->__owner->KeyColumn()->getName() . '=' .
						$this->__owner->KeyColumn()->value;
					$this->valueTag->inner =
						"<div class=\"wrap\">" .
						"<input type=\"file\" name=\"" . $this->__id .
						"\" id=\"" . $this->__id . "\" class=\"dbfile\" /> " .
						PSK_Uri::getInstance()->PostLink(PSK_STR_DBE_SAVEFILE,
							$this->__owner->getName(), 'uploadImage',
							array($this->__objectName, $con)) .
						"</div>";
				}
				break;
			case PSK_DM_DELETE:
			case PSK_DM_VIEW:
				if (($this->value != '') && ($this->value != '__NOFILE__')) {
					$this->valueTag->inner = "<img src=\"" .
						PSK_Uri::getInstance()->IncludePath($path . $this->value) . "\"/>";
				} else {
					$this->valueTag->inner = 'Resim yok.';
				}
				break;
			default:
				break;
		}
		return $this->valueTag->Render();
	}

	function ReadFromPost()
	{
		if  (isset($_FILES[$this->__id]) &&
				($_FILES[$this->__id] != '') &&
				($_FILES[$this->__id]['error'] == 0)
			) {
			return $_FILES[$this->__id]['name'];
		}
		return '';
	}

	function DefineImageMeta ($par_Path, $par_Width = 0, $par_Height = 0, $par_Quality = 75)
	{
		$this->_imageMeta[] = array (
			'path' => $par_Path,
			'width' => $par_Width,
			'height' => $par_Height,
			'quality' => $par_Quality
		);
	}

	function SaveImage()
	{
		$imageHandler = new PSK_Image();

		if (!$imageHandler->ImageFromPost($this->getId())) {
			return false;
		};

		$fileName = date("YmdHis");

		foreach ($this->_imageMeta as $imageMeta) {
			if ($imageMeta['width'] > 0)
				$imageHandler->Resize($imageMeta['width'], $imageMeta['height']);
			$imageHandler->SaveToFile($fileName, $imageMeta['path'], $imageMeta['quality']);
		}

		$result = $fileName.'.'.$imageHandler->getExtension();

		$imageHandler->__destruct();

		return $result;
	}

	function DeleteImage($par_File)
	{
		foreach ($this->_imageMeta as $imageMeta) {
			//PSK_Log::getInstance()->WriteDebug($imageMeta['path'] . $par_File);
			if (($par_File != '') || ($par_File != '__NOFILE__'))
				@unlink($imageMeta['path'] . $par_File);
		}
	}
}