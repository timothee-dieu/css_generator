<?php

include_once("sprite.class.php");

class CssGenerator
{
	private $imgsInfo = [];
	private $padding = 0;
	private $output = "";
	private $css = "";
	private $override = 0;
	private $sprite = NULL;
	private $count = 0;

	function __construct(Sprite &$sprite, $padding, $output, $override)
	{
		$this->imgsInfo = $sprite->getImgsInfo();
		$this->padding = $padding;
		$this->output = $output;
		$this->override = $override;
		$this->sprite = $sprite;
	}

	private function add($width, $height, $x, $y)
	{
		$css = "";
		$spriteName = $this->sprite->getName();
		
		$this->count++;
		if ($this->override)
		{
			$width = $this->override;
			$height = $width;
		}
		$css .= ".img$this->count{\n";
		$css .= "\tdisplay: inline-block;\n";
		$css .= "\twidth: ". $width ."px;\n";
		$css .= "\theight: ". $height ."px;\n";
		$css .= "\tbackground-image: url('". $spriteName .".png');\n";
		$css .= "\tbackground-position: -". $x ."px -". $y ."px;\n";
		
		if ($this->padding)
			$css .= "\tmargin: ". $this->padding ."px;\n";
		$css .= "}\n\n";

		$this->css .= $css;

	}

	public function save()
	{
		$file = fopen("$this->output.css", "w");

		foreach ($this->imgsInfo as $imgInfo)
		{
			$width = $imgInfo->width;
			$height = $imgInfo->height;
			$x = $imgInfo->x;
			$y = $imgInfo->y;

			$this->add($width, $height, $x, $y);
		}
		fwrite($file, $this->css);
		fclose($file);
	}
}