<?php

include_once("imginfo.class.php");


class Sprite
{
	private $width = 0;
	private $height = 0;
	private $override = 0;
	private $imgs = [];
	private $output = "sprite";
	private $maxCol = 0;
	private $lineHeight = 0;
	private $xCursor = 0;
	private $yCursor = 0;
	private $count = 0;

	function __construct($output, $override, $maxCol)
	{
		$this->output = $output;
		$this->override = $override;
		$this->maxCol = $maxCol;
	}

	private function addImgInfo($file)
	{
		$this->imgs[] = new ImgInfo;
		end($this->imgs)->img = imagecreatefrompng($file);
		end($this->imgs)->name = pathinfo($file,  PATHINFO_FILENAME);
		end($this->imgs)->width = getimagesize($file)[0];
		end($this->imgs)->height = getimagesize($file)[1];
		end($this->imgs)->x = 0;
		end($this->imgs)->y = 0;
	}

	public function getName()
	{
		return $this->output;
	}

	public function add($file)
	{
		$this->addImgInfo($file);
		$imgInfo = end($this->imgs);
		$imgInfo->x = $this->xCursor;
		$imgInfo->y = $this->yCursor;

		$width = ($this->override > 0) ? $this->override : $imgInfo->width;
		$height = ($this->override > 0) ? $this->override : $imgInfo->height;
		
		$this->count++;
		$this->xCursor += $width;
		if ($this->lineHeight < $height)
			$this->lineHeight = $height;
		if ($this->height < $this->lineHeight)
			$this->height = $this->lineHeight;
		if ($this->width < $this->xCursor)
			$this->width = $this->xCursor;
		if ($this->yCursor == $this->height)
			$this->height += $this->lineHeight;
		if ($this->maxCol !== 0 && $this->count % $this->maxCol == 0){
			$this->yCursor += $this->lineHeight;
			$this->height = $this->yCursor;
			$this->xCursor = 0;
			$this->lineHeight = 0;
		}
	}
	
	private function allocSprite($width, $height)
	{
		if ($width * $height > 16000000){
			echo "[err.]: Created sprite is too big (maximum: 16 000 000px).\n";
			return false;
		}
		$sprite = imagecreatetruecolor($width, $height);
		imagesavealpha($sprite, true);
		$background = imagecolorallocatealpha($sprite, 0, 0, 0, 127);
		imagefill($sprite, 0, 0, $background);
		return $sprite;
	}

	public function getImgsInfo()
	{
		return $this->imgs;
	}

	public function save()
	{
		$sprite = $this->allocSprite($this->width, $this->height);
		if ($sprite === false){
			return false;
		}
		foreach ($this->imgs as $img)
		{
			$override = $this->override;
			$width = ($override > 0) ? $override : $img->width;
			$height = ($override > 0) ? $override : $img->height;
			imagecopyresized($sprite, $img->img, $img->x, $img->y,
			 0, 0, $width, $height, $img->width, $img->height);
		}
		imagepng($sprite, "$this->output.png");
		imagedestroy($sprite);
	}
}

