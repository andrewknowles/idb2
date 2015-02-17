<?php

class Ifx_Model
  {
    /**
     * @param object $db A PDO database connection
     */
//    function __construct($db1)
  	function __construct()
      {
        
        try
          {
          	$this->IfxCon = odbc_connect(IFXODBC, IFXUSER, IFXPASS);
          }
        catch (Exception $e)
          {
            exit('Database connection could not be established.');
          }
      }
    
    /**
     * Get all companies from the DMS databasedatabase
     */
    
    public function getAllCompanies()
      {
        try
          {
            foreach ($_SESSION['cbqueries'] as $x => $x_value)
              {
                if ($x == 1)
                  {
                    $qrycpy = $x_value;
                  }
              }

//using odbc_prepare/odbc_execute
/*           
 			$qrycpy = 'select * from usr where usr_id = ?';
 			$usr = '0000';
			$query1 = odbc_prepare($this->IfxCon, $qrycpy);
           $res = odbc_execute($query1, array($usr));
           while ( $row = odbc_fetch_array ( $query1 ) )
           {
            	print_r($row);
            }*/
//using odbc_exec
//              $qrycpy = 'select cpy_id, cpy_screen from cpy';
            $companies = odbc_exec($this->IfxCon, $qrycpy);
            $_SESSION['companies'] = array();
            while ( $row = odbc_fetch_array ( $companies ) )
            {
            	$_SESSION['companies'] += array(
                    $row['cpy_id'] => $row['cpy_screen']);
            }
            
            if (count($_SESSION['companies']) < 1)
              {
                throw new Exception();
              }
            else
              {
 
                return $_SESSION['companies'];
                
              }
          }
        catch (Exception $e)
          {
            define("ERRMSG", "No companies found - IDB Error No. 20 </br> System halted");
            require APP . 'views/_templates/header.php';
            require APP . 'views/error/error.php';
            require APP . 'views/_templates/footer.php';
            exit;
          }
      }
    
    
    /**
     * Get all branches from the DMS databasedatabase
     */
    public function getAllBranches()
      {
        try
          {
            foreach ($_SESSION['cbqueries'] as $x => $x_value)
              {
                if ($x == 2)
                  {
                    $qrybra = $x_value;
                  }
              }
            
          $branches = odbc_exec($this->IfxCon, $qrybra);
          
            $_SESSION['branches'] = array();
            while ( $row = odbc_fetch_array($branches))
              {
                $_SESSION['branches'] += array(
                    $row['bra_id'] => $row['bra_screen']
                );
              }
            if (count($_SESSION['branches']) < 1)
              {
                throw new Exception();
              }
            else
              {
                return $_SESSION['branches'];
              }
          }
        catch (Exception $e)
          {
            define("ERRMSG", "No branches found - IDB Error No. 21 </br> System halted");
            require APP . 'views/_templates/header.php';
            require APP . 'views/error/error.php';
            require APP . 'views/_templates/footer.php';
            exit;
          }
      }
    
    /**
     * Run quick status queries
     */
    
    public function RunQuickQueries()
      {
        try
          {
            $_SESSION['quick_result']  = array();
            $_SESSION['quick_result1'] = array();
            foreach ($_SESSION['qqueries'] as $query)
              {
                if ($_SESSION['bra'] == 'allbr')
                  { 
                  	$cpy = str_replace("'", "",trim($_SESSION['cpy']));
                    $qryquick   = $query['qry_qry'] .' '. $query['qry_qry3'];
                    $query1 = odbc_prepare($this->IfxCon, $qryquick);
					$res = odbc_execute($query1, array($cpy));
                  }
                else
                  {
                  	$bra = str_replace("'", "",trim($_SESSION['bra']));
                    $qryquick = $query['qry_qry'] . $query['qry_qry2'];
					$query1 = odbc_prepare($this->IfxCon, $qryquick);
					$res = odbc_execute($query1, array($bra));
                  }

                    $title  = $query['qry_title'];
                    while ( $row = odbc_fetch_array ( $query1 ) )
                      {
                        $value                             = $row['cnt'];
                        $_SESSION['quick_result'][$title]  = $value;
                        $_SESSION['quick_result1'][$title] = $query['qry_detail'];
                      }
                  
                
              }
            
            if ((count($_SESSION['quick_result']) < 1 || count($_SESSION['quick_result1']) < 1))
              {
                throw new Exception();
              }
            else
              {
                return $_SESSION['quick_result'];
                return $_SESSION['quick_result1'];
              }
          }
        catch (Exception $e)
          {
            define("ERRMSG", "No quick query results returned - IDB Error No. 22 </br> System halted");
            require APP . 'views/_templates/header.php';
            require APP . 'views/error/error.php';
            require APP . 'views/_templates/footer.php';
            exit;
          }
      }
    
    
    /**
     * Run detail query
     */
    
    public function RunDetailQuery()
      {
        try
          {
            $_SESSION['detail_result'] = array();
            foreach ($_SESSION['qquery'] as $query)
              {
              	$_SESSION['qtitle'] = $query['qry_title'];
                if ($_SESSION['bra'] == 'allbr')
                  {
                  	$cpy = str_replace("'", "",trim($_SESSION['cpy']));
                    $qryquick   = $query['qry_qry'] . ' ' . $query['qry_qry3'];
                   	$query1 = odbc_prepare($this->IfxCon, $qryquick);
					$res = odbc_execute($query1, array($cpy));
                  }
                else
                  {
                  	$bra = str_replace("'", "",trim($_SESSION['bra']));
                    $qryquick = $query['qry_qry'] . ' ' . $query['qry_qry2'];
                   	$query1 = odbc_prepare($this->IfxCon, $qryquick);
					$res = odbc_execute($query1, array($bra));
                  }
                  
                    while ( $row = odbc_fetch_array ( $query1 ) )
                      {
                        array_push($_SESSION['detail_result'], $row);
                      }               
              }
            
            if ((count($_SESSION['detail_result']) < 1))
              {
                throw new Exception();
              }
            else
              {
                return $_SESSION['detail_result'];
              }
          }
        catch (Exception $e)
          {
            define("ERRMSG", "No quick query results returned - IDB Error No. 22 </br> System halted");
            require APP . 'views/_templates/header.php';
            require APP . 'views/error/error.php';
            require APP . 'views/_templates/footer.php';
            exit;
          }
      }
    
    
    
    
  }