<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Entity\Utility;

/**
 * Description of ProcessCSV
 * Reads in CSV files and saves the data to database.
 * @author HolmesPlace
 */
class ProcessCSV {
    //put your code here
    

    public function readIn($type=""){
    // Open the file for reading
        if ($type=="Bitcoin") { 
            $path = __DIR__."/bitcoin.csv";
        }
        if ($type=="Etherium") { 
            $path = __DIR__."/etherium.csv";
        }
     //$path = "/path/to/file";
     // echo "Path : $path";
     $rows=0; // Number of rows
     $transactions = Array();
    if (($h = fopen($path, "r")) !== FALSE) 
        {
            // Convert each line into the local $data variable
            while (($data = fgetcsv($h, 1000, ",")) !== FALSE) 
            {	
                if ($rows!=0){
                // echo "<br>".$rows;  
                 $transactions[$rows] = $data;
                }
                $rows+=1;
                
            }
           // Close the file
            fclose($h);
        }
 
       return $transactions; 
}
/*
 * Merges the Bitcoin and Etherium Arrays -- Doesn't work!
 */
    public function combineEthBtxArray($btc,$eth){
        $btcRowCount = sizeof($btc);   // 45
        $ethRowCount = sizeof($eth);   // 26
        $transactions = Array(); // Our new array goint to contain $btcRowCount+$ethRowCount items.
        //The smaller array will get merged with the larger array based on the transaction date.
        
     return array_merge_recursive($btc + $eth);  
    }   
    
}
