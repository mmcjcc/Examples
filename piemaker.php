<?php


/*

The Pie chart generator by Ashish Kasturia (http://www.123ashish.com)
Copyright (C) 2003 Ashish Kasturia (ashish at 123ashish.com)


The Pie chart generator is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, 
USA.

*/
$width = $HTTP_GET_VARS["width"];
$height = $HTTP_GET_VARS["height"];
$values = $HTTP_GET_VARS["values"];
$desc = $HTTP_GET_VARS["desc"];
$title = $HTTP_GET_VARS["title"];

//<img src="piemaker.php?width=400&height=300&values=1,2,3,4,5&desc=A,B,C,D,E&title=My+Pie+Chart">
class Pie
{
	//var $imageWidth = 400;
	//var $imageHeight = 300;
	var $bgR = 255;
	var $bgG = 255;
	var $bgB = 255;
	var $title = "Pie Chart";


	function create($varDesc, $varValues)
	{
		Header("Content-type: image/png");
		$image = ImageCreate($this->imageWidth, $this->imageHeight);


		$bgcolor = ImageColorAllocate($image, 
			$this->bgR, $this->bgG, $this->bgB);

		$white = ImageColorAllocate($image, 255, 255, 255);
		$black = ImageColorAllocate($image, 0, 0, 0);
		ImageFill($image, 0, 0, $bgcolor);

		$num = 0;
		foreach($varDesc as $v)
		{
			$r = mt_rand (0, 255);
			$g = mt_rand (0, 255);
			$b = mt_rand (0, 255);

			$sliceColors[$num] = ImageColorAllocate($image, $r, $g, $b);
			$num++;
			//sleep(.1);
		}

		// now $num has the number of elements

		// draw the box
		ImageLine($image, 0, 0, $this->imageWidth - 1, 0, $black);
		ImageLine($image, $this->imageWidth - 1, 0, $this->imageWidth - 1, $this->imageHeight - 1, $black);
		ImageLine($image, $this->imageWidth - 10, $this->imageHeight - 1, 1, $this->imageHeight - 1, $black);
		ImageLine($image, 0, $this->imageHeight - 10, 0, 0, $black);


		$total = 0;
		for ($x = 0; $x < $num; $x++)
		{
			$total += $varValues[$x];
		}

		// convert each slice into corresponding percentage of 360-degree circle
		for ($x = 0; $x < $num; $x++)
		{
			$angles[$x] = ($varValues[$x] / $total) * 360;
		}


		for($x = 0; $x < $num; $x++)
		{
			// calculate and draw arc corresponding to each slice
			ImageArc($image, 
				$this->imageWidth/4, 
				$this->imageHeight/2, 
				$this->imageWidth/3, 
				$this->imageHeight/3, 
				$angle,
				($angle + $angles[$x]), $sliceColors[$x]);

			$angle = $angle + $angles[$x];

			$x1 = round($this->imageWidth/4 + ($this->imageWidth/3 * cos($angle*pi()/180)) / 2);
			$y1 = round($this->imageHeight/2 + ($this->imageHeight/3 * sin($angle*pi()/180)) / 2);

			// demarcate slice with another line
			ImageLine($image, 
				$this->imageWidth/4,
				$this->imageHeight/2, 
				$x1, $y1, $sliceColors[$x]);

			
		}

		// fill in the arcs
		$angle = 0;
		for($x = 0; $x < $num; $x++)
		{
			$x1 = round($this->imageWidth/4 + 
				($this->imageWidth/3 * cos(($angle + $angles[$x] / 2)*pi()/180)) / 4);
			$y1 = round($this->imageHeight/2 + 
				($this->imageHeight/3 * sin(($angle + $angles[$x] / 2)*pi()/180)) / 4);

			ImageFill($image, $x1, $y1, $sliceColors[$x]);

			$angle = $angle + $angles[$x];
		}


		// put the desc strings
		ImageString($image, 5, $this->imageWidth/2, 60, "Legend", $black);
		for($x = 0; $x < $num; $x++)
		{
			$fl = sprintf("%.2f", $varValues[$x] * 100 / $total);
			$str = $varDesc[$x]." (".$fl."%)";
			ImageString($image, 3	, $this->imageWidth/2, ($x + 5) * 20, $str, $sliceColors[$x]);
		}

		// put the title
		ImageString($image, 5, 20, 20, $this->title, $black);
		

		ImagePng($image);
		ImageDestroy($image);

	}
}

$pie = new Pie;

if(isset($width))
{
	$pie->imageWidth = $width;
}

if(isset($height))
{
	$pie->imageHeight = $height;
}

if(isset($title))
{
	$pie->title = $title;
}

$varDesc = explode(",", $desc);
$varValues = explode(",", $values);

$pie->create($varDesc, $varValues);



?>
