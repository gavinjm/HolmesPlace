<?php

$yValues = isset($_GET['data']) ? $_GET['data'] : null;
$graphname = isset($_GET['pair']) ? $_GET['pair'] : "Graph";

// Default graph size
    $imgWidth=250;
    $imgHeight=250;
    $scaling = 250;

if ($yValues){
     $imgWidth=350;
     $imgHeight=250;
    // The param value contains graph values seperated with cDate_value_cDate_value
    $data = explode("_",$yValues);
    $count = count($data)-1; // $imgWidth / 25; // count($data);
    $scaling = 250;
    $xscale= (round($imgWidth/$count))-0.5;   // The -1 means graph doesn't end the at the line.
    $yscale=10;
    $bottomGutter=50;
    $topGutter=150;
    $Min=1000000;             // Crypto values can be quite high. 
    $Max=0;
    $oldRange;                // ets set below
    $newRange=$topGutter;
    
    // Copy the readings into $graphValues and get max and min values.
    for ($n=0; $n < $count; $n++){     
        $graphValues[$n] = (int)$data[$n];    
        $Min = ($graphValues[$n] < $Min) ? $graphValues[$n]:$Min;
        $Max = ($graphValues[$n] > $Max) ? $graphValues[$n]:$Max;
    }
         
    //Scale the values as per the height of the viewport
    $oldRange = $Max - $Min;
     for ($n=0; $n<$count; $n++){   
        $graphValues[$n] = ((($graphValues[$n] - $Min)*$newRange) / $oldRange) + $bottomGutter;
     }
    
} else {
 // Add values to the graph
    $graphValues=array(45,80,23,11,130,145,50,80,85,80,55);
    $count = count($graphValues);
    $xscale=round($imgWidth/$count);
}


$graphname = $graphname."  ".$count." Elements.";
// Define .PNG image
    header("Content-type: image/png");

// Create image and define colors
    $image=imagecreate($imgWidth, $imgHeight);
    $colorWhite=imagecolorallocate($image, 255, 255, 255);
    $colorGrey=imagecolorallocate($image, 192, 192, 192);
    $colorBlue=imagecolorallocate($image, 0, 0, 255);
    $colorGreyish=imagecolorallocate($image, 45, 78, 91);
// Create border around image
    imageline($image, 0, 0, 0, $imgHeight, $colorGrey);                             // Left Vert
    imageline($image, 0, 0, $imgWidth, 0, $colorGrey);                              // Top
    imageline($image, $imgWidth-1, 0, $imgWidth-1, $imgHeight-1, $colorGrey);       // Right
    imageline($image, 0, $imgHeight-1, $imgWidth-1,  $imgHeight-1, $colorGrey);     // Bottom
    
// Create grid with 25 unit markers
// Only display 4 Horizontal Lines ImageHeight /  4   
    $yscale = $imgHeight / 4;
    for ($i=1; $i<4; $i++){
        $val = round($Max - ($oldRange / $i),0);
        // imageline($image, $i*$xscale, 0, $i*$xscale, $imgHeight, $colorGrey);        // Vertical Lines
        imageline($image, 35, $i*$yscale, $imgWidth, $i*$yscale, $colorGrey);           // Horizontal Lines
        imagestring($image,2, 2,244-($i*$yscale),$val,$colorGreyish);                   // Add the horizontal line value
        
    }
//Add labels to the Graph.
    $str="Min:".$Min."  Max:".$Max."   Range:".$oldRange;
    imagestring($image,5 ,2, 5,$graphname,$colorBlue);
    imagestring($image,2,2,145,$newMin,$colorBlue);             // Text is written on the bottom line.
    imagestring($image,2, 2,230,$str,$colorBlue);
// Add in graph values
    for ($i=0; $i<$count-1; $i++){
       imageline($image, $i*$xscale, ($scaling - $graphValues[$i]), ($i+1)*$xscale, ($scaling - $graphValues[$i+1]), $colorBlue);
    }
   
    
// Output graph and clear image from memory
imagepng($image);
imagedestroy($image);

