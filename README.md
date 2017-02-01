# css_generator
css_generator - sprite generator for HTML use


 NAME

	css_generator - sprite generator for HTML use

 SYNOPSIS

	css_generator [OPTIONS]... assets_folder

 DESCRIPTION

	Concatenate all PNG images inside a folder in one sprite and write a 
	stylesheet ready to use.
	Mandatory arguments to long options are mandatory for short options 
	too.

 -r, --recursive

	Look for images into the assets_folder passed as arguement and all of 
	its subdirectories.

 -i, --output-image=IMAGE

	Name of the generated image. 
	If blank, the default name is «sprite.png».

 -s, --output-style=STYLE

	Name of the generated stylesheet. 
	If blank, the default name is «style.css».

 -p, --padding=NUMBER

	Add padding between images of NUMBER pixels.

 -o, --override-size=SIZE

	Force each images of the spriteto fit a size of SIZExSIZE pixels.

 -c, --columns_number=NUMBER

	The maximum number of elements to be generated horizontally.

