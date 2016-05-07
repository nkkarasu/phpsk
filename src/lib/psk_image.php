<?php

class PSK_Image
{
	private $_type = PSK_IFT_NONE;
	private $_image;
	private $_extension;
	private $_width = 0;
	private $_height = 0;

	private $_icfFunc = array(
		'jpg' => 'imagecreatefromjpeg',
		'jpeg' => 'imagecreatefromjpeg',
		'gif' => 'imagecreatefromgif',
		'png' => 'imagecreatefrompng'
	);

	private $_types = array (
		'jpg' => PSK_IFT_JPEG,
		'jpeg' => PSK_IFT_JPEG,
		'gif' => PSK_IFT_GIF,
		'png' => PSK_IFT_PNG
	);

	function __destruct()
	{
		if (is_resource($this->_image))
			imagedestroy($this->_image);
	}

	function ImageFromPost($par_Name)
	{
		//PSK_Log::getInstance()->WriteArray($_FILES);
		if (empty($_FILES[$par_Name]['name'])) return false;
		$fileName = $_FILES[$par_Name]['name'];
		$tempFile = $_FILES[$par_Name]['tmp_name'];
		$a = explode('.', $fileName);
		$this->_extension = strtolower($a[count($a) - 1]);

		if (!array_key_exists($this->_extension, $this->_types)) {
			throw new Exception('Desteklenmeyen dosya biçimi!', 5);
		}

		$this->_type = $this->_types[$this->_extension];
		$this->_image = $this->_icfFunc[$this->_extension]($tempFile);
		list($this->_width, $this->_height) = getimagesize($tempFile);

		//var_dump($this->_image);
		return true;
	}

	function ImageFromFile($par_FileName) {
		$a = explode('.', $par_FileName);
		$this->_extension = strtolower($a[count($a) - 1]);
		$this->_type = $this->_types[$this->_extension];
		$this->_image = $this->_icfFunc[$this->_extension]($par_FileName);
		list($this->_width, $this->_height) = getimagesize($par_FileName);
		return true;
	}

	function SaveToFile($par_FileName, $par_Path = '', $par_Quality = 75, $par_UseExt = true)
	{
		$fileName = $par_Path . $par_FileName;
		if ($par_UseExt) $fileName .= '.' . $this->_extension;
		switch ($this->_type) {
			case PSK_IFT_JPEG:
				if (@imagejpeg($this->_image, $fileName, $par_Quality)
				) {
					return true;
				} else {
					throw new Exception('Resim kaydedilemedi!');
				}
				break;
			case PSK_IFT_GIF:
				if (@imagegif($this->_image, $fileName)) {
					return true;
				} else {
					throw new Exception('Resim kaydedilemedi!');
				}
				break;
			case PSK_IFT_PNG:
				if (@imagepng($this->_image, $fileName)) {
					return true;
				} else {
					throw new Exception('Resim kaydedilemedi!');
				}
				break;
		}
	}

	function Resize($par_NewWidth, $par_NewHeigth, $par_KeepRatio = true)
	{
		$ratio = $this->_width / $this->_height;
		if (($this->_width > $par_NewWidth) || ($this->_height > $par_NewHeigth)) {
			if ($ratio > 1) { // Resim yataysa,
				$width = $par_NewWidth;
				$height = $par_KeepRatio ?
					round($par_NewWidth / $ratio) : $par_NewHeigth;
			} else { // Resim dikeyse,
				$width = $par_KeepRatio ?
					round($par_NewHeigth * $ratio) : $par_NewWidth;
				$height = $par_NewHeigth;
			}
			$newImage = imagecreatetruecolor($width, $height);
			imagecopyresampled($newImage, $this->_image, 0, 0, 0, 0,
				$width, $height, $this->_width, $this->_height);
			$this->_width = $width;
			$this->_height = $height;
			$tempImage = & $this->_image;
			$this->_image = & $newImage;
			imagedestroy($tempImage);
		}
	}

	function getExtension()
	{
		return $this->_extension;
	}
}

