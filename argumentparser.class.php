<?php

class ArgumentParser
{
	private $short = [];
	private $long = [];
	private $args = [];
	private $parsed = [];

	function __construct($arguments)
	{
		$this->args = $arguments;
	}

	public function addShortOptions($options)
	{
		if (!preg_match("#^[A-Za-z:]+$#", $options))
		{
			echo "Invalid short option given: $options!\n";
			return;
		}
		$size = strlen($options);
		for ($i = 0; $i < $size; $i++)
		{
			if ($i + 1 < $size && $options[$i + 1] == ":")
			{
				$this->short[] = array($options[$i], true);
				$i++;
			}
			else
			{
				$this->short[] = array($options[$i], false);
			}
		}
	}

	public function addLongOptions(array $options)
	{
		foreach ($options as $option)
		{
			if (!preg_match("#^[A-Za-z:_-]+$#", $option))
			{
				echo "[ArgumentParser]: Invalid long option given: $option\n";
				return;
			}
			if (strpos($option, ":") === strlen($option) - 1)
			{
				$option = trim($option, ":");
				$this->long[] = array($option, true);
			}
			else
			{
				$this->long[] = array($option, false);
			}
		}
	}

	private function getShortOption($option)
	{
		foreach ($this->short as $shortOption)
		{
			if ($shortOption[0] == $option)
				return $shortOption;
		}
		return false;
	}

	private function getLongOption($option)
	{
		foreach ($this->long as $longOption)
		{
			if ($longOption[0] == $option)
				return $longOption;
		}
		return false;
	}

	private function parseLongArgument($arg)
	{
		if (substr_count($arg, "=") > 1)
		{
			echo "[ArgumentParser]: Invalid long option given: $arg\n";
			return;
		}
		$arg = trim($arg, "--");
		$arg = explode("=", $arg);

		$option = $this->getLongOption($arg[0]);
		if ($option && $option[1])
		{
			$arg[1] = trim($arg[1], "\"");
			$this->parsed[$arg[0]] = $arg[1];
		}
		elseif ($option)
		{
			$this->parsed[$arg[0]] = "";
		}
	}

	private function parseShortArgument($arg, &$i)
	{
		if (!preg_match("#^[A-Za-z-]+$#", $arg))
		{
			echo "[ArgumentParser]: Invalid short option given: $arg\n";
			return;
		}
		$arg = trim($arg, "-");
		$option = $this->getShortOption($arg);
		$size = count($this->args);
		if ($option && $option[1] && $i + 1 < $size)
		{
			$value = trim($this->args[$i + 1], "\"");
			$this->parsed[$arg] = $value;
			$i++;
		}
		elseif ($option)
		{
			$this->parsed[$arg] = "";
		}
	}

	public function parse()
	{
		if (!count($this->short) || !count($this->long))
		{
			echo "[ArgumentParser]: No options to parse.\n";
			return [];
		}
		$size = count($this->args);
		$count = 0;
		for ($i = 0; $i < $size; $i++)
		{
			$arg = $this->args[$i];
			if (strpos($arg, "--") === 0)
			{
				$this->parseLongArgument($arg);
			}
			elseif (strpos($arg, "-") === 0)
			{
				$this->parseShortArgument($arg, $i);
			}
			else
			{
				$this->parsed[$count++] = $arg;
			}
		}
		return $this->parsed;
	}
}