<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Utility\StatisticConsolidator;
use App\Entity\CryptoPrices;
use DateTime;


 /**
 * Description of CryptoController
 *
 * @author HolmesPlace
 */
class CryptoController extends AbstractController {
    
    
    public function c_index(){
        
        $cp = $this->runSqlLQuery("select date,btc_price,eth_price from crypto_prices order by id desc limit 1");    
        $cc = $this->runSqlLQuery("select name, balance from crypto_currency");
        
        $totBtc = $cp[0]['btc_price'] * $cc[0]['balance'];
        $totEth = $cp[0]['eth_price'] * $cc[1]['balance'];
        $totEthSav = $cp[0]['eth_price'] * $cc[3]['balance']; 
        $total['value'] = $totBtc + $totEth+ $totEthSav + $cc[2]['balance'];
        $total['ether_sum'] = $cc[1]['balance'] + $cc[3]['balance'];
             
        return $this->render('CryptoCurrencies/c_index.html.twig',[
            'page' => 'Crypto Currency Index Page',
            'cryptoPrices' => $cp,
            'cryptoCurrencies' => $cc,
            'totals' => $total,
             ]);
    }    
    
    /** showWallets
     * Fetches Wallet details from MySQL.
     * Table: crypto_currency
     * Fields: id, name, balance, available
     */
    public function showWallets(){
       $wallets =  $this->runSqlLQuery("select id,name,balance,available from  crypto_currency");   
        return $this->render('CryptoCurrencies/Wallets.html.twig', [
            'page' => 'Wallets','data' => $wallets,
             ]);
    }
    
    /** 
     * getTickerPrices
     *  Returns latest ticker prices
     */
    public function showTickerData(Request $request, string $pair){
        $startDate = new DateTime('now'); 
        $period=$request->get('period');
        if ($period==""){ $period="P1W";}
                
        $startDate->sub(new \DateInterval($period));
       
     $sql="SELECT"         
           ." FROM_UNIXTIME(timestamp/1000) AS date,pair,ask,bid,last_trade,rolling_24_hour_volume"
           ." FROM ticker WHERE pair='".$pair."' AND FROM_UNIXTIME(timestamp/1000) > '".$startDate->format('Y-m-d H:m:s')."'"
           ." ORDER BY timestamp ASC";
       
       $tickers =  $this->runSqlLQuery($sql);
       // Tickers table values: date, pair, ask, bid, last lrade, volume   
       $graphString = $this->convertToString($tickers);
        
     return $this->render('CryptoCurrencies/showTickerTable.html.twig',[
           'data' => $tickers,
           'graphstring' => $graphString,
           'pair' => $pair,
           'period' => $period,
            ]);   
}
    
    private function convertToString($tickerArray){
        $arrString = "";
        foreach ($tickerArray as $row=>$tick){
            $arrString = $arrString.$tickerArray[$row]['last_trade']."_";
        }
        return $arrString;
    }
    
    /** getTickers
     ** Get latest Ticker values from Luno API
     * the true value in the json_decode ensures an array is returned from json_decode
     * 
     */
    public function getTickers(){
        $opts = array('http'=>array('header' => "User-Agent:MyAgent/1.0\r\n")); 
    //Basically adding headers to the request
        $context = stream_context_create($opts);
        //$html = file_get_contents($url,false,$context);
       // $html = htmlspecialchars($html);
        
        $jsonurl = "https://api.mybitx.com/api/1/tickers";
        $json = file_get_contents($jsonurl,false,$context);
        // the true param to json_decode ensures an associative array is returned.
        return (json_decode($json,true));
         //return (json_decode($json,true));
    }
        
    /** 
     * updateCryptoPrices
     * Updates the database with the latest Luno prices
     * calls getTickers() to return the latest data from Luno api site.
     */
    public function updateCryptoPrices(){
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');     
        $this->SC = new StatisticConsolidator();
        $data = $this->getTickers();
       // $data = $this->curlTickers();
        
       // Get the Bitcoin XBTZAR price from the data array as a Ticker 
         $xbtzar = $this->SC->getXBTZAR($data);
         $ethzar = $this->SC->getETHZAR($data);
         
         $cryptoPrices = new CryptoPrices();
           
           $cryptoPrices->setBtcPrice($xbtzar->getLastTrade());
           $cryptoPrices->setEthPrice($ethzar->getLastTrade());
           //Sets the update time to now.
           $cryptoPrices->setDate(new \DateTime());
           
                   
      // Save the Ticker XBTZAR and ETHZAR to the database.
         $entityManager = $this->getDoctrine()->getManager();
         $entityManager->persist($xbtzar);
         $entityManager->persist($ethzar);
         $entityManager->persist($cryptoPrices);
         $entityManager->flush(); 
         return $this->redirectToRoute('index');
         
    } 
    
    public function findByDescription($description){
        $query = "select count(*) as count         
           from transaction
           where description like '".$description."%'";
        $stmnt = $this->getDoctrine()->getConnection()->prepare($query);
        $stmnt->execute();
        $res = $stmnt->fetchAll();
        return $res[0]['count'];
        
    }
    
    public function findByDate($date){
        $query = "select count(*) as count         
           from transaction
           where FROM_UNIXTIME(timestamp) like '".$date."%'";
        $stmnt = $this->getDoctrine()->getConnection()->prepare($query);
        $stmnt->execute();
        $res = $stmnt->fetchAll();
        return $res[0]['count'];
    }
    
    public function findByBalances($balArray){
        $query = "select count(*) as count
            from transaction
            where (balance = ".$balArray[0].") AND
                (balance_delta = ".$balArray[1].") AND 
                (available_bal_delta = ".$balArray[2].")";
        $stmnt = $this->getDoctrine()->getConnection()->prepare($query);
        $stmnt->execute();
        $res = $stmnt->fetchAll();
        return $res[0]['count'];
        
        
    }
    
    public function findByTransaction($transaction){
        $query= " select count(*) as count from transaction "
                ." where "
                ."( FROM_UNIXTIME(timestamp) like '%".$transaction['timestamp']."%'"
                ." and "
                ." description like '%".$transaction['description']."%'"
                ." and "
                ." currency like '%".$transaction['currency']."%'"
                ." and "
                ." balance_delta = ".$transaction['balance_delta']
                ." and "
                ." available_bal_delta = ".$transaction['available_bal_delta']
                ." and balance = ".$transaction['balance']
                ." and value like '%".$transaction['value']."%'"
                .")";
        
        $stmnt = $this->getDoctrine()->getConnection()->prepare($query);
        $stmnt->execute();
        $res = $stmnt->fetchAll();
        return $res[0]['count'];
    }
    
    public function saveTransactions($transactions){
      $recordesAdded=0;        
        foreach ($transactions as $item){
         // $descriptionCount=0; $balanceCount=0;
         if ($item['wallet_id']!="Wallet ID"){
           $duplicate = $this->findByTransaction($item);
           if ($duplicate == 0){
           $sqlInsert = "insert into transaction values(0,";
           $sqlInsert .= "'".$item['wallet_id']."' , UNIX_TIMESTAMP('".$item['timestamp']."'),'".$item['description']."',";
           $sqlInsert .= "'".$item['currency']."',";
           $sqlInsert .= $item['balance_delta'].",".$item['available_bal_delta'].",".$item['balance']." , ";
           $sqlInsert .= $item['available_balance'].",'".$item['cc_transaction_id']."',". 0 .",'".$item['value']."')";
             //  echo "<br>".$sqlInsert;     
            $update=$this->getDoctrine()->getConnection()->prepare($sqlInsert);
            $update->execute();
            $recordesAdded +=1;
           } else {
               echo "<br>  DUPLICATE: Date: ".$item['timestamp']  
                   ."  Description: ".$item['description']."  Balance: ".$item['balance'];
           }
          }
        }       
      // $entityManager->flush(); 
        return $recordesAdded;
    }
    
    /** readInTransactions from file
     *  Luno transactions in .csv format are received from Luno
     *  and stored in web root /public/asset/crypto, read into the database
     * Database:
     * Table:
     * Duplicates are handled.
     */
    public function readInTransactions(){
        // Open the file for reading
       $filename = "transactions_1.csv";
       $path = $this->getParameter('kernel.project_dir');
       $path = $path."/public/asset/uploads/".$filename;
    
     $transactions = Array();
    
     $row=0;
     $headings=array('wallet_id','id','timestamp','description','currency','balance_delta','available_bal_delta',
                    'balance','available_balance','cc_transaction_id',"cc_address","value");
             
    if (($h = fopen($path, "r")) !== FALSE) 
        {
            // Convert each line into the local $data variable
            while (($data = fgetcsv($h, 1000, ",")) !== FALSE) 
            {	
                $num = count($data);
              for ($c=0; $c < $num; $c++) {    
                  $transactions[$row][$headings[$c]] = $data[$c]; 
                 }   
              $row++;
            }
           // Close the file
            fclose($h);
        } 
        
       $recordCount = $this->saveTransactions($transactions);
       echo "<p> Number of records added: ".$recordCount."</p>";
       return $this->redirectToRoute('crypto_transactions');
       // $transactions;
} 
               
    public function runSqlLQuery(string $sql){  
        $stmnt = $this->getDoctrine()->getConnection()->prepare($sql);
        $stmnt->execute();
        $result =  $stmnt->fetchAll();
      return $result;
    }
    
    public function queryTransactions(Request $request){
        $sdate = $request->get('startdate');
        $edate = $request->get('enddate');
        $curr =  $request->get('currency');
        $descr =  $request->get('description');
        $sql = "select  id,wallet_id, FROM_UNIXTIME(timestamp) AS timestamp, description, currency, balance_delta,
           available_bal_delta, balance, available_balance, cc_transaction_id, cc_address, value from transaction ";
        $sqlNumeric = "select sum(balance) from transactions ";
        $period="";
        $bought=0.0;
        $sold=0.0;
        $interest=0.0;
        $charged=0.0;
        
        if ($descr != ""){
            $sql .= " WHERE (description LIKE '%".$descr."%' )" ;
        } 
        
        if (($sdate != "") && ($descr !="")){
             $sql .= " AND FROM_UNIXTIME(timestamp) > '".$sdate."'";
             $period .= $sdate;
         } 
         if (($sdate != "") && ($descr =="")){
             $sql .= " WHERE FROM_UNIXTIME(timestamp) > '".$sdate."'";
             $period .= $sdate;
           }
          
         if ($edate != ""){
            $sql .= " AND FROM_UNIXTIME(timestamp) < '".$edate."'";
            $period .= "  to ".$edate;
            }
            
       $sql .= " order by timestamp desc";
       $transactions = $this->runSqlLQuery($sql); 
       
         return $this->render('CryptoCurrencies/queryTransactions.html.twig', [
            'page' => 'Transactions',
            'transactions' => $transactions,
            'query' => $sql,
            'period' => $period,
             
             ]);
    }
       
    /**
     * showTransactions
     * Displays historical transactions on the Etherium and Bitcoin Wallets
     * 
     */
    public function showTransactions(Request $request){
       //$sqlQuery = $request->get('query');
       $sql="select id,wallet_id, FROM_UNIXTIME(timestamp) AS timestamp, description, currency, balance_delta,
           available_bal_delta, balance, available_balance, cc_transaction_id, cc_address, value from transaction 
           ORDER BY timestamp desc";    
          $transactions = $this->runSqlLQuery($sql);   
        return $this->render('CryptoCurrencies/showTransactions.html.twig', [
            'page' => 'Transactions','transactions' => $transactions,
             ]);
        
    }
    
}
