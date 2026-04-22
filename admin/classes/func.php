<?php

class Func{

	public function get_avg($e1,$e2,$e3,$e4,$e5,$e6)
	{	
		$eall = array($e1,$e2,$e3,$e4,$e5,$e6);    
    
	    $cnt2=0;
	    $subtotal=0;
	    $earray=$eall;
	    //$earray=filter_array($eall);//remove any undefined value i.e if only three judges 
	    $earray = array_filter($eall);
	  
	    $cnt=count($earray); // recalculate array length so that judge can be 3 or 4
	   
	    $min1= min($earray);    
	    $max2= max($earray);
	    
	    for ($i=0;$i<$cnt;$i++)
	    {  
	            if (isset($earray[$i]))
	            { 
	                $subtotal=+$subtotal+$earray[$i]; //total of all the values	              
	            }       
	    }
	    if($cnt==3)
	    {
	        $subtotal=$subtotal-$max2;
	        $cnt2=$cnt-1;  //we will use it to divide 
	    }
	    else if($cnt>3)
	    {
	        $subtotal=$subtotal-$min1-$max2;    
	        $cnt2=$cnt-2;
	    }
	    else
	        {$cnt2=$cnt;}
	   
	    $avgscore=round(($subtotal/$cnt2),2);
	    return $avgscore;
	}

	public static function isGender($gender){
        if(!is_array($gender))
        {
            $gender_array = array('M'=>'MAG', 'F'=>'WAG');
            if(in_array($gender, array_keys($gender_array)))
            	return $gender_array[$gender];
            else
            	return false;
        }
        else
         return false;
    }
  public function qualifierrpt($participant,$eventid,$gender,$apid,$category)
  {
    	$clubarray=array();
      $index=0;
     
      foreach ($participant->qualifierclub($eventid,$gender,$apid,$category) as $clubrow) 
      {
        $clubarray[$clubrow['club']]=0;
      }
      $rank=1;   
      $display=1;
      $cnt=1;     
      //order by total and escore
      foreach ($participant->qualifierrpt($eventid,$gender,$apid,$category) as $row) {             
                  if($clubarray[$row['club']]<2 )
                  {
                      if( $display!=$row['total'])
                      {
                                                  $rank=$cnt;
                      }
                      if($rank=='9')
                      { 
                                          $rank='R1';
                      }
                      if($rank=='10')
                      {
                                        
                                        $rank='R2';
                      }
                      echo '<tr style="height:20 !important; font-size: 12px;font-weight: bold;">'; 
                      echo '<td align="center">'.  $rank  .'</td>';                                             
                                                echo '<td>'. ucfirst($row['name'])  . '</td>';
                                                echo '<td>'. strtoupper($row['club']) . '</td>';
                                                echo '<td align="center">'. $row['total'] . '</td>';                                               
                                    echo '</tr>';
                      $cnt++; 
                      $display=$row['total'];
                      $clubarray[$row['club']]++;
                  }
                  if($rank=='R2')
                  {
                     break;
                  } 
    }                
  }
public function artistic_teamchampionship($comm,$tblname,$gender,$category,$level,$eventid,$rowcount)
  {  
    $club= array();
   
    $sqlcat = "SELECT distinct club FROM `registrations` WHERE Event =$eventid and gender='$gender' and eventtype='$category'";    
    $i=0;
    foreach ($comm->executesql($sqlcat) as $row)
    {
          $club[$i]=$row['club'];
          $i ++;
    } 

    $aname= array();
    $j=0;
    //only vault 1 score included 
    $sql="SELECT * FROM `apparatus` where pid!=0 and (gender='".$gender."' or gender ='b') and apid!=17 and IsActive=1 order by sequence";   
    foreach ($comm->executesql($sql) as $row)
    {
        $aname[$j]=$row['sname'];
        $j ++;
    } 
    $sqlqualifier="SELECT cnt_teamchamp FROM `events` WHERE `Id` = $eventid";
    $qualifiercount =$comm->getvalue($sqlqualifier);//get no. of gymnast to be included from each team for e.g 5 top gymnsts from each team

    $statearray = array();
    for ($i=0;$i<count($club);$i++) {//for all state
        $data = array();
        $data['club']=$club[$i];
        $data['total']=0;    
        for ($j=0;$j<count($aname);$j++) 
        { //for all apparatus

          // if($level=='')
          // {
          //   $sqlclub="SELECT `club`, `name`, `total`, `category`, s.`apid`,r.gender,eventid,gname FROM `gymnast_score` s inner join registrations r on r.id=s.reg_id inner join apparatus a on a.apid=s.apid WHERE `category` LIKE '$category' and r.gender='".$gender."' AND s.`eventid` = $eventid and sname='$aname[$j]' and club='$club[$i]' order by total desc limit $qualifiercount";
          // }
          // else
          // {
          //   $sqlclub="SELECT `club`, `name`, `total`, `category`, s.`apid`,r.gender,eventid,gname FROM `gymnast_score` s inner join registrations r on r.id=s.reg_id inner join apparatus a on a.apid=s.apid WHERE `category` LIKE '$category' and r.gender='".$gender."' and r.level !='Aboveage-B' and r.level='".$level."' AND s.`eventid` = $eventid and sname='$aname[$j]' and club='$club[$i]' order by total desc limit $qualifiercount";
          // }

           $sqlclub = "SELECT `club`, `name`, `total`, `category`, s.`apid`,r.gender,eventid,gname FROM `gymnast_score` s inner join registrations r on r.id=s.reg_id inner join apparatus a on a.apid=s.apid WHERE `category` LIKE '$category' and r.level !='Aboveage-C' and r.gender='" . $gender . "' AND s.`eventid` = $eventid and sname='$aname[$j]' and club='$club[$i]' order by total desc limit $qualifiercount";
  
            $getrecord =$comm->getvalue($sqlclub);
            $data[$aname[$j]]=0;
            if($getrecord !='' ){ //dontinsert duplicate values
                foreach ($comm->executesql($sqlclub) as $row) 
                {//fetch data and save it                   
                   $data[$aname[$j]] +=$row['total'];
                }                  
            }
          $data['total'] +=  $data[$aname[$j]];          
        }

        array_push($statearray, $data);
    }
   
    $i=0;
      $rank=1;
      $display=1;
      $cnt=1;
      $same=0;
      $teampenalty=0;
      $hide=0;
      
      echo'<table border="1" style="border-collapse:collapse;width:90%" cellspacing="0" cellpadding="0">
            <thead>
            <tr>
            <th width="70" class="text-center">Rank</th>
            <th width="200" class="text-center">Club</th>';
      for ($j=0;$j<count($aname);$j++) //print all apparatus as column names
      {
        echo '<th width="60" class="text-center">'.$aname[$j].'</th>';
      } 
      echo '<th width="100" class="text-center">Total</th>';    
      echo '</tr></thead><tbody>';
      //sort on total of all apparatus
      $total = array();
      foreach ($statearray as $key => $row)
      {
          $total[$key] = $row['total'];
      }
      array_multisort($total, SORT_DESC, $statearray);

      foreach ($statearray as $row) 
      {
        
          $teasmsql="SELECT penalty FROM state_penalty WHERE eventid='$eventid' and state = '".$row['club']."' and `gender` = '$gender' AND `category` = '$category'";
          $hidesql="SELECT display FROM state_penalty WHERE eventid='$eventid' and state = '".$row['club']."' and `gender` = '$gender' AND `category` = '$category'";

          $hide =  $comm->getvalue($hidesql);                           
          $teampenalty =  $comm->getvalue($teasmsql); 
          if($hide==0)//will print only if hide is zero
          {      
              if( $display==$row['total'])
              {
                  $same=1;
              }
              else
              {
                    if($cnt !=1)
                    {
                        $rank ++;
                        if($same==1)
                            $rank=$cnt;
                    }
              }
              echo '<tr>'; 
              echo '<td align="center">'. $rank . '</td>';                                      
              echo '<td align="center">'. strtoupper($row['club']) . '</td>';
              for ($j=0;$j<count($aname);$j++) //print all apparatus as column names
              {
                echo '<td width="60" align="center">'.$row[$aname[$j]].'</td>';
              } 
              $finalscore=$row['total']- $teampenalty;                
              echo '<td align="center">'. number_format(round($finalscore,2),2) . '</td>';                             
              $cnt++; 
              $display=$finalscore;
              echo '</tr>';
          }
          
          if($cnt==$rowcount+1)
            break;//to show no. of rows
      } 
      echo'</tbody></table>';


  }//function end
  public function teamchampionship($comm,$gender,$category,$eventid,$rowcount,$showcol)
  {
      $app= array();
      $sname= array();
      $i=0;
      $rank=1;
      $display=1;
      $cnt=1;
      $same=0;
      $teampenalty=0;
      $hide=0;
      $sql="SELECT * FROM `apparatus` where pid!=0 and (gender='".$gender."' or gender ='b') and apid in (SELECT distinct apid FROM `gymnast_score` cs inner join registrations o on o.id=cs.reg_id where eventid='".$eventid."' and apid !=17 and category='".$category."' and gender='".$gender."') order by sequence";

      foreach($comm->executesql($sql) as $row)
      {                              
            $app[$i]=$row['gname'];
            $sname[$i]=$row['sname'];
            $i ++;
      }
      echo'<table border="1" style="border-collapse:collapse;width:90%" cellspacing="0" cellpadding="0">
            <thead>
            <tr>
            <th width="70">Rank</th>
            <th width="200">State/Unit</th>';
            if($showcol!=0)//display apparatus columns
            {
                for ($i=0;$i<count($app);++$i) 
                {
                    echo '<th width="60">'.$sname[$i].'</th>';
                } 
            }
      echo '<th width="60">Score</th></tr></thead><tbody>';
      $sql1="SELECT club,gender";
            for ($i=0;$i<count($app);++$i) {
                               $sql1 .=",ROUND(sum(case when apparatus = '".$app[$i]."' then score else 0 end),2) as '".$sname[$i]."'";
            }
      $sql1.=" ,ROUND(sum(score), 2)score from state_rank r where gender='".$gender."' and category='".$category."' and eventid='".$eventid."' GROUP by club order by score desc"; 
      foreach ($comm->executesql($sql1) as $row) 
      {

          $teasmsql="SELECT penalty FROM state_penalty WHERE eventid='$eventid' and state = '".$row['club']."' and `gender` = '$gender' AND `category` = '$category'";
          $hidesql="SELECT display FROM state_penalty WHERE eventid='$eventid' and state = '".$row['club']."' and `gender` = '$gender' AND `category` = '$category'";

          $hide =  $comm->getvalue($hidesql);                           
          $teampenalty =  $comm->getvalue($teasmsql); 
          if($hide==0)//will print only if hide is zero
          {      
              if( $display==$row['score'])
              {
                  $same=1;
              }
              else
              {
                    if($cnt !=1)
                    {
                        $rank ++;
                        if($same==1)
                            $rank=$cnt;
                    }
              }
              echo '<tr>'; 
              echo '<td align="center">'. $rank . '</td>';                                      
              echo '<td align="center">'. strtoupper($row['club']) . '</td>'; 
              $finalscore=$row['score']- $teampenalty; 
              if($showcol!=0)//display apparatus columns
              {
                for ($i=0;$i<count($app);++$i) {
                                echo '<td width="70">'. $row[$sname[$i]] . '</td>';
                }
              }
              
              echo '<td align="center">'. number_format(round($finalscore,2),2) . '</td>';                             
              $cnt++; 
              $display=$finalscore;
              echo '</tr>';
          }
          if($cnt==$rowcount+1)
            break;//to show no. of rows
      } 
      echo'</tbody></table>';
  }//function end

   public function artistic_aafinal($comm,$participant,$gender,$category,$level,$eventid)
  { 
      $sqlqualifier="SELECT cnt_qualifier FROM `events` WHERE `Id` = $eventid";
      $qualifiercount =$comm->getvalue($sqlqualifier);//get no. of gymnast to be included from each team for e.g 2 gymnsts from 
      $app= array();
      $sname= array();
      $sql="SELECT * FROM `apparatus` where pid!=0 and (gender='".$gender."' or gender ='b') and apid in (SELECT distinct apid FROM `gymnast_score` cs inner join registrations o on o.id=cs.reg_id where eventid='".$eventid."' and apid !=17 and category='".$category."' and gender='".$gender."') order by sequence";

      $k=0;
      foreach($comm->executesql($sql) as $row)
      {
          $app[$k]=$row['apid'];
          $sname[$k]=$row['sname'];
          $k ++;
      }
      echo'<table border="1" style="border-collapse:collapse;width:90%" cellspacing="0" cellpadding="0">
      <thead>
            <tr style="border-bottom: 1px solid #000 !important;">
            <th width="40">Rank</th>
                            <th width="180">Name</th> 
                            <th width="260">Club</th>';
                for ($i=0;$i<count($sname);++$i) {
                                    echo '<th width="60" class="text-center">'.$sname[$i].'</th>';
                    } 
      echo '<th width="80" class="text-center">AA Score</th></tr></thead><tbody>'; 

      $rank=1;
      $display=1;
      $cnt=1;
      $clubarray=array();//all clubs in array
      foreach ($participant->Finalclub($eventid,$gender,$category) as $clubrow) 
      {
              $clubarray[$clubrow['club']]=0;
      }

      if($level=='')
      {
          $sql1="SELECT name,category,Gender,club";
          for ($i=0;$i<count($app);++$i) 
          {
                $sql1 .=",sum(case when apid = '".$app[$i]."' then s.total else 0 end) as '".$app[$i]."'";
          }
          $sql1.=" ,sum(Total) Total FROM `gymnast_score` s inner join registrations o on o.id=s.reg_id where category='".$category."' and s.Eventid='$eventid' and gender='$gender' and apid !=17 GROUP by s.reg_id,name order by Total DESC";
      }
      else
      {
          $sql1="SELECT name,category,Gender,club";
          for ($i=0;$i<count($app);++$i) 
          {
                $sql1 .=",sum(case when apid = '".$app[$i]."' then s.total else 0 end) as '".$app[$i]."'";
          }
          $sql1.=" ,sum(Total) Total FROM `gymnast_score` s inner join registrations o on o.id=s.reg_id where category='".$category."' and s.Eventid='$eventid' and o.level='$level' and gender='$gender' and apid !=17 GROUP by s.reg_id,name order by Total DESC";
      }
      $nume=0;
      $nume =$comm->getvalue($sql1);
                           
      foreach ($comm->executesql($sql1) as $row) 
      {
          if($clubarray[$row['club']] < $qualifiercount)
          {
                  if( $display!=$row['Total'])
                  {
                       $rank=$cnt;
                  }                                     
                  echo '<tr>'; 
                  echo '<td align="center">'.$rank. '</td>';  
                  echo '<td>'. ucfirst($row['name']) . '</td>';
                  echo '<td>'. strtoupper($row['club']) . '</td>'; 
                  for ($i=0;$i<count($app);++$i) {
                        if($row[$app[$i]]>0)
                              echo '<td align="center">'.$row[$app[$i]].'</td>';
                        else
                              echo '<td>&nbsp;</td>';
                  }   
                  echo '<td align="center">'. $row['Total'] . '</td>';                        
                  echo '</tr>';
                  $cnt++; 
                  $display=$row['Total'];
                  $clubarray[$row['club']]++;
          }
          if($rank==50)
          {
              break;
          }
      }                                           
    echo'</tbody></table>';
  }//function end


   public function artistic_aaprize($comm,$participant,$gender,$category,$level,$eventid)
  { 
      $sqlqualifier="SELECT cnt_qualifier FROM `events` WHERE `Id` = $eventid";
      $qualifiercount =$comm->getvalue($sqlqualifier);//get no. of gymnast to be included from each team for e.g 2 gymnsts from 
      $app= array();
      $sname= array();
      $sql="SELECT * FROM `apparatus` where pid!=0 and (gender='".$gender."' or gender ='b') and apid in (SELECT distinct apid FROM `gymnast_score` cs inner join registrations o on o.id=cs.reg_id where eventid='".$eventid."' and apid !=17 and category='".$category."' and gender='".$gender."') order by sequence";

      $k=0;
      foreach($comm->executesql($sql) as $row)
      {
          $app[$k]=$row['apid'];
          $sname[$k]=$row['sname'];
          $k ++;
      }
      echo'<table border="1" style="border-collapse:collapse;width:90%" cellspacing="0" cellpadding="0">
      <thead>
            <tr style="border-bottom: 1px solid #000 !important;">
            <th width="40">Id</th>
                            <th width="180">Name</th> 
                            <th width="200">Club</th><th width="20">School</th>';
                for ($i=0;$i<count($sname);++$i) {
                                    echo '<th width="60" class="text-center">'.$sname[$i].'</th>';
                    } 
      echo '</tr></thead><tbody>'; 

      $rank=1;
      $display=1;
      $cnt=1;
      $clubarray=array();//all clubs in array
      foreach ($participant->Finalclub($eventid,$gender,$category) as $clubrow) 
      {
              $clubarray[$clubrow['club']]=0;
      }

      // if($level=='')
      // {
      //     $sql1="SELECT name,category,Gender,club";
      //     for ($i=0;$i<count($app);++$i) 
      //     {
      //           $sql1 .=",sum(case when apid = '".$app[$i]."' then s.total else 0 end) as '".$app[$i]."'";
      //     }
      //     $sql1.=" ,sum(Total) Total FROM `gymnast_score` s inner join registrations o on o.id=s.reg_id where category='".$category."' and s.Eventid='$eventid' and gender='$gender' and apid !=17 GROUP by s.reg_id,name order by Total DESC";
      // }
      // else
      // {
      //     $sql1="SELECT name,category,Gender,club";
      //     for ($i=0;$i<count($app);++$i) 
      //     {
      //           $sql1 .=",sum(case when apid = '".$app[$i]."' then s.total else 0 end) as '".$app[$i]."'";
      //     }
      //     $sql1.=" ,sum(Total) Total FROM `gymnast_score` s inner join registrations o on o.id=s.reg_id where category='".$category."' and s.Eventid='$eventid' and o.level='$level' and gender='$gender' and apid !=17 GROUP by s.reg_id,name order by Total DESC";
      // }

      $sql1="SELECT reg_id,name,category,Gender,club, contingent,round((vt1+vt2)/2,2)as '6',UB as '7',BB as '8',FX as '9' from(SELECT s.reg_id,name,category,Gender,club,contingent,sum(case when apid = '6' then s.total else 0 end) as 'vt1', sum(case when apid = '17' then s.total else 0 end) as 'vt2', sum(case when apid = '7' then s.total else 0 end) as 'UB',sum(case when apid = '8' then s.total else 0 end) as 'BB',sum(case when apid = '9' then s.total else 0 end) as 'FX' ,sum(Total) Total FROM `gymnast_score` s inner join registrations o on o.id=s.reg_id where category='".$category."' and s.Eventid='$eventid' and gender='$gender' GROUP by s.reg_id,name order by asid asc)aa";

      //print_r($sql1);
      $nume=0;
      $nume =$comm->getvalue($sql1);
                           
      foreach ($comm->executesql($sql1) as $row) 
      {
          if($clubarray[$row['club']] < 200)
          {
                  if( $display!=$row['Total'])
                  {
                       $rank=$cnt;
                  }                                     
                  echo '<tr>'; 
                  echo '<td align="center">'.$row['reg_id']. '</td>';  
                  echo '<td>'. ucfirst($row['name']) . '</td>';
                  echo '<td>'. strtoupper($row['club']) . '</td>';
                   echo '<td>'. strtoupper($row['contingent']) . '</td>'; 
                  for ($i=0;$i<count($app);++$i) {
                        if($row[$app[$i]]>0)
                        {
                          echo '<td align="center">'.$row[$app[$i]];
                          if($row[$app[$i]]>=6 && $row[$app[$i]]<7)
                          {
                              echo '<p>Bronze</p>';
                          }
                          elseif ($row[$app[$i]]>=7 && $row[$app[$i]]<8) 
                          {
                            echo '<p>Silver</p>';
                          }
                          elseif ($row[$app[$i]]>=8 && $row[$app[$i]]<9) 
                          {
                            echo '<p>Gold</p>';
                          }
                          elseif ($row[$app[$i]]>=9) 
                          {
                            echo '<p>Platinum</p>';
                          }
                          echo '</td>';
                        }
                        else
                              echo '<td>&nbsp;</td>';
                  }   
                  //echo '<td align="center">'. $row['Total'] . '</td>';                        
                  echo '</tr>';
                  $cnt++; 
                  $display=$row['Total'];
                  $clubarray[$row['club']]++;
          }
      }                                           
    echo'</tbody></table>';
  }//function end
  
  public function individualallaround($comm,$gender,$category,$eventid,$rowcount)
  {
    $app= array();
    $sname= array();
    $rank=1;
    $display=1;
    $cnt=1;
    $sql="SELECT * FROM `apparatus` where pid!=0 and (gender='".$gender."' or gender ='b') and apid in (SELECT distinct apid FROM `gymnast_score` cs inner join registrations o on o.id=cs.reg_id where eventid='".$eventid."' and apid !=17 and category='".$category."' and gender='".$gender."') order by sequence";

    $k=0;
    foreach($comm->executesql($sql) as $row)
    {
      $app[$k]=$row['apid'];
      $sname[$k]=$row['sname'];
      $k ++;
    }
      echo'<table border="1" style="border-collapse:collapse" cellspacing="0" cellpadding="0">
            <thead>
                  <tr>
                  <th width="70">Rank</th>
                                  <th width="250">Name</th> 
                                  <th width="150">State/Unit</th>';
            // for ($i=0;$i<count($sname);++$i) {
            //                               echo '<th width="60">'.$sname[$i].'</th>';
            //               } 
        echo '<th width="60">Points</th></tr></thead><tbody>'; 
        $sql1="SELECT name,category,Gender,club";
            for ($i=0;$i<count($app);++$i) {
                               $sql1 .=",sum(case when apid = '".$app[$i]."' then s.total else 0 end) as '".$app[$i]."'";
            }
    $sql1.=" ,sum(Total) Total FROM `gymnast_score` s inner join registrations o on o.id=s.reg_id where category='".$category."' and s.Eventid='$eventid' and gender='$gender' and apid !=17 GROUP by s.reg_id,name order by Total DESC";
  
          $nume=0;
    $nume =$comm->getvalue($sql1);
                             
            foreach ($comm->executesql($sql1) as $row) {
             
              if( $display!=$row['Total'])
                                        {
                                           $rank=$cnt;
                                        }
                        echo '<tr>'; 
                         echo '<td align="center">'.$rank. '</td>';  
                        echo '<td>'. ucfirst($row['name']) . '</td>';
                        echo '<td>'. strtoupper($row['club']) . '</td>'; 
                                              
                        // for ($i=0;$i<count($app);++$i) {
                        //         if($row[$app[$i]]>0)
                        //             echo '<td align="center">'.$row[$app[$i]].'</td>';
                        //         else
                        //            echo '<td>&nbsp;</td>';
                        // }   
                        echo '<td align="center">'. $row['Total'] . '</td>';                        
                        echo '</tr>';
                        $cnt++; 
                        $display=$row['Total'];
                        if($cnt==$rowcount+1)
                          break;//to show no. of rows
            }   
                                    
                        echo'</tbody></table>';
  }//end function

  public function createapparatusarray($comm,$gender)
  {
      $appname= array();
      $sql="SELECT * FROM `apparatus` where pid!=0 and (gender='".$gender."' or gender ='b') and apid not in (6,17) and IsActive=1 order by sequence";
      $i=0;
      foreach($comm->executesql($sql) as $row)
      {
          $appname[$i]=$row['apid'];
          $i ++;
      } 
      return $appname;
  }

  public function getallapparatus($comm,$gender)
  {
      $appname= array();
      $sql="SELECT * FROM `apparatus` where pid!=0 and (gender='".$gender."' or gender ='b') and apid not in (17) order by sequence";
      $i=0;
      foreach($comm->executesql($sql) as $row)
      {
          $appname[$i]=$row['apid'];
          $i ++;
      } 
      return $appname;
  }

  public function leaderboard($comm,$apid,$apparatus,$gender,$category,$eventid,$limit)
  {  
      echo ' <div class="col-md-6">
           <div class="feature-3 text-center text-white"><h4>'.$apparatus.'</h4>';
      echo "<div style='overflow-y: scroll;max-height:260px;'>";
      echo'<table border="1" style="border-collapse:collapse" cellspacing="0" cellpadding="0">
                                <thead>
                                <tr>
                                 <th width="70" class="text-center">Rank</th>
                                  <th width="250" class="text-center">Name</th> 
                                  <th width="150" class="text-center">State</th>                           
                                  <th width="70" class="text-center">Final Score</th>                            
                                </tr>
                              </thead>
                              <tbody id="pagination">';

      $rank=1;
      $prevtotal=1;
      $prevescore=1;
      $prevdscore=1;
      $cnt=1;
      $sql1="SELECT reg_id,s.*,Category,eventid, r.gender,dob,name,gname,club FROM `gymnast_score` s INNER join registrations r on s.reg_id=r.id left join apparatus a on a.apid=s.apid where eventid='".$eventid."' and a.apid=".$apid." and r.gender='".$gender."' and category='".$category."' order BY total desc,subtotal DESC,dscore DESC limit $limit"; 
     
      foreach ($comm->executesql($sql1) as $row) 
      {
                                        
                          if( $prevtotal!=$row['total'])
                          {
                              $rank=$cnt;
                          }
                          elseif( $prevescore!=$row['subtotal'])
                          {
                            //tie breaking situation
                             $rank=$cnt;
                          }
                          elseif ($prevdscore!=$row['dscore']) 
                          {
                              $rank=$cnt;                                                                            
                          } 
                          else
                          {}                                                                             
                                        echo '<tr>';
                                        echo '<td align="center" class="text-white">'.$rank. '</td>'; 
                                        echo '<td class="text-white">'. ucfirst($row['name'])  . '</td>';
                                        echo '<td class="text-white">'. strtoupper($row['club']) . '</td>';
                                        echo '<td align="center" class="text-white">'. $row['total'] . '</td>';            
                                        $cnt++; 
                                        $prevtotal=$row['total'];
                                        $prevescore=$row['subtotal'];
                                        $prevdscore=$row['dscore'];
                                        echo '</tr>';
      }                                                           
                                        echo'</tbody>
                                    </table>

                                    </div></div>';
                echo'</div>';          
                                     
  }
  public function tramp_qualifier($comm,$participant,$gender,$category,$eventid,$limit)
  {

    $sqlqualifier="SELECT cnt_qualifier FROM `events` WHERE `Id` = $eventid";
    $qualifiercount =$comm->getvalue($sqlqualifier);//get no. of gymnast to be included from each team for e.g 2 gymnsts from 
    $sqlteams_qualifier="SELECT teams_qualifier FROM `events` WHERE `Id` = $eventid";
    $teams_qualifier =$comm->getvalue($sqlqualifier);//get teams to combine for qualifier
    $clubarray=array();
          
      foreach ($participant->getclub_bytable($eventid,$gender,'trampoline_score',$category) as $clubrow) 
      {
        $clubarray[$clubrow['club']]=0;
      }
      $rank=1;   
      $display=1;
      $cnt=1; 
      echo '
                        <tr>
                            <th width="20">Q/R</th>
                            <th width="50">Rank</th>
                            <th width="200">Name</th>
                            <th width="200">Club</th>                           
                           
                            <th width="70">Total</th>
                        </tr>
                    </thead> <tbody>';
            foreach ($participant->tramp_generatequalifier($eventid,$gender,'trampoline_score',$category) as $row) {             
                  if($clubarray[$row['club']]<$qualifiercount || $row['club'] =='None')
                  {
                      if( $display!=$row['total'])
                      {
                                                  $rank=$cnt;
                      }
                     
                       echo '<tr>';
                      
                       
                        if($cnt<=8)
                        { 
                            echo '<td align="center">Q</td>';
                        }
                        elseif($cnt>8 && $cnt<=10)
                        {
                            echo '<td align="center">R</td>';
                        }
                        else
                            echo '<td align="center">QR</td>';
                            
                        
                        echo '<td align="center">'.$rank. '</td>'; 
                                                
                                                echo '<td>'. ucfirst($row['name'])  . '</td>';
                                                $disclub='';
                                                if($row['club']!='None')
                                                   $disclub= ucwords(strtolower($row['club'])) ;
                                                echo '<td>'.$disclub . '</td>';
                                                echo '<td align="center">'. $row['total'] . '</td>';
                                    echo '</tr>';
                      $cnt++; 
                      $display=$row['total'];
                      $clubarray[$row['club']]++;
                  }
                  if($rank=='R2')
                  {
                     break;
                  } 
            }
            echo '<tbody>' ; 
      //end elseif

  }

  public function tumb_qualifier($comm,$participant,$gender,$category,$eventid,$limit)
  {

    $sqlqualifier="SELECT cnt_qualifier FROM `events` WHERE `Id` = $eventid";
    $qualifiercount =$comm->getvalue($sqlqualifier);//get no. of gymnast to be included from each team for e.g 2 gymnsts from 
   
    $clubarray=array();
          
      foreach ($participant->getclub_bytable($eventid,$gender,'tumbling_score',$category) as $clubrow) 
      {
        $clubarray[$clubrow['club']]=0;
      }
      $rank=1;
      $prevtotal=1;
      $prevescore=1;
      $prevdscore=1;
      $cnt=1;
      //<th width="20">Q/R</th>
      echo '
                        <tr>
                            
                            <th width="50">Rank</th>
                            <th width="200">Name</th>
                            <th width="200">Club</th>                           
                           <th width="70" class="text-center">Twisting</th>                           
                                  <th width="70" class="text-center">Salto</th>                                 
                                  <th width="100" class="text-center">Final Score</th> 
                        </tr>
                    </thead> <tbody>';
            $sql1=" SELECT name,club,reg_id,Category,eventid,sum(CASE WHEN apname ='Twisting' THEN total ELSE 0  END  ) 'Twisting',sum(CASE WHEN apname ='Salto' THEN total ELSE 0  END  ) 'Salto',sum(total) 'Total'  FROM `tumbling_score` s INNER join registrations r on s.reg_id=r.id where eventid='".$eventid."' and r.gender='".$gender."' and category='".$category."'  group by reg_id order BY total DESC  limit $limit";
            foreach ($comm->executesql($sql1) as $row) {             
                  // if($clubarray[$row['club']]<$qualifiercount || $row['club'] =='None')
                  // {
                      if( $prevtotal!=$row['Total'])
                          {
                              $rank=$cnt;
                          }
                     
                       echo '<tr>';
                      
                       
                        // if($cnt<=8)
                        // { 
                        //     echo '<td align="center">Q</td>';
                        // }
                        // elseif($cnt>8 && $cnt<=10)
                        // {
                        //     echo '<td align="center">R</td>';
                        // }
                        // else
                        //     echo '<td align="center">QR</td>';
                            
                        
                        echo '<td align="center">'.$rank. '</td>'; 
                                                
                                                echo '<td>'. ucfirst($row['name'])  . '</td>';
                                                $disclub='';
                    if($row['club']!='None')
                        $disclub= ucwords(substr($row['club'],0,22)) ;
                                                echo '<td>'.$disclub . '</td>';
                                                echo '<td >'. $row['Twisting'] . '</td>'; 
                                        echo '<td >'. $row['Salto'] . '</td>';
                                        echo '<td >'. $row['Total'] . '</td>';  
                                    echo '</tr>';
                      $cnt++; 
                     $prevtotal=$row['Total'];
                      $clubarray[$row['club']]++;
                  // }
                  // if($rank=='R2')
                  // {
                  //    break;
                  // } 
            }
            echo '<tbody>' ; 
      //end elseif

  }

    public function agimleaderboard($comm, $apid, $level, $apparatus, $gender, $category, $eventid, $limit)
    {
        echo ' <div class="col-md-12">
           <div class="feature-3 text-center text-white"><h4>' . $apparatus . '</h4>';

        echo "<div style='overflow-x: scroll;'>";
        echo '<table border="1" style="border-collapse:collapse" cellspacing="0" cellpadding="0">
                                <thead>
                                <tr>';
        if ($eventid == 42) {
            echo '<th width="70" class="text-center">Certificate</th>';
        }
        echo '<th width="70" class="text-center">S. No.</th>
                                  <th width="250" class="text-center">Name</th>
                                  <th width="150" class="text-center">Club</th>
                                  <th width="150" class="text-center">Level</th>
                                  <th width="150" class="text-center">D Score</th>
                                  <th width="150" class="text-center">Penalty</th>
                                  <th width="70" class="text-center">Final Score</th>
                                  <th width="70" class="text-center">Rank</th>
                                </tr>
                              </thead>
                              <tbody id="pagination">';
        $rank = 1;
        $prevtotal = 1;
        $prevescore = 1;
        $prevdscore = 1;
        $cnt = 1;
         $sql1 = "SELECT reg_id,s.*,Category,eventid,r.level, r.gender,dob,name,gname,club FROM `gymnast_score` s INNER join registrations r on s.reg_id=r.id left join apparatus a on a.apid=s.apid where eventid='" . $eventid . "' and a.apid=" . $apid . " and r.gender='" . $gender . "' and category='" . $category . "' order BY total DESC limit $limit";

        
         $sno=1;
        foreach ($comm->executesql($sql1) as $row) {

            if ($prevtotal != $row['total']) {
                $rank = $cnt;
            }  else {
            }
            echo '<tr>';
            if ($eventid == 42) {
                //echo '<td align="center"></td>';
                echo '<td align="center"><a href="certificate.php?regid=' . $row['reg_id'] . '" style="padding:0px !important;"><img src="assets/images/download.png" style="width:30px;height:30px;"/></a></td>';
            }
            echo '<td align="center" class="text-white">' . $sno . '</td>';
            echo '<td class="text-white">' . ucfirst($row['name']) . '</td>';
            echo '<td class="text-white">' . strtoupper($row['club']) . '</td>';
            echo '<td class="text-white">' . strtoupper($row['level']) . '</td>';
            echo '<td class="text-white">' . strtoupper($row['avg']) . '</td>';
            echo '<td class="text-white">' . strtoupper($row['penalty']) . '</td>';
            echo '<td align="center" class="text-white">' . $row['total'] . '</td>';
             echo '<td align="center" class="text-white">' . $rank . '</td>';
            $cnt++;
              $sno ++;
            $prevtotal = $row['total'];
            $prevescore = $row['subtotal'];
            $prevdscore = $row['dscore'];
            echo '</tr>';
        }
        echo '</tbody>
                                    </table>

                                    </div></div>';
        echo '</div>';
    }

    public function agimVaultleaderboard($comm, $apparatus, $gender, $category, $eventid, $limit)
    {
        echo ' <div class="col-md-12">
           <div class="feature-3 text-center"><h4>' . $apparatus . '</h4>';

        echo "<div style='overflow-x: scroll;'>";
        echo '<table border="1" style="border-collapse:collapse" cellspacing="0" cellpadding="0">
                                <thead>
                                <tr>';
        if ($eventid == 42) {
            echo '<th width="70" class="text-center">Certificate</th>';
        }
        echo '<th width="70" class="text-center">S. No.</th>
                                  <th width="250" class="text-center">Name</th>
                                  <th width="150" class="text-center">Club</th>
                                  <th width="150" class="text-center">Level</th>
                                  <th width="150" class="text-center">Vault 1</th>
                                  <th width="150" class="text-center">Vault 2</th>
                                  <th width="70" class="text-center">Final Score</th>
                                  <th width="70" class="text-center">Rank</th>
                                </tr>
                              </thead>
                              <tbody id="pagination">';
        $rank = 1;
        $prevtotal = 1;
        $prevescore = 1;
        $prevdscore = 1;
        $cnt = 1;
         $sql1 = "SELECT s.reg_id,name,r.level,s.total vault1,g.total vault2,s.subtotal evault1,g.subtotal evault2, round((s.total+g.total)/2,2) avg1,GREATEST(s.total,g.total) maxvault,GREATEST(s.subtotal,g.subtotal)  maxe,club 
FROM `gymnast_score` s INNER join registrations r on s.reg_id=r.id left join apparatus a on a.apid=s.apid LEFT join (select * from gymnast_score g where eventid='".$eventid."' and apid='17' and category='".$category."') g on g.reg_id=s.reg_id where s.eventid='".$eventid."' and a.apid='6' and r.gender='".$gender."' and s.category='".$category."' ORDER BY avg1 DESC,maxvault DESC,maxe DESC";
//echo $sql1;
        
        $rank=1;
$display=1;
$cnt=1;
 $sno=1;
        foreach ($comm->executesql($sql1) as $row) {
                           if( $display!=$row['maxe'])
                                        {
                                           $rank=$cnt;
                                        }
                        echo '<tr>';
                        echo '<td align="center" class="text-white">' . $sno . '</td>';
                        
                        echo '<td>'. ucfirst($row['name'])  . '</td>';
                        echo '<td>'. strtoupper($row['club']) . '</td>'; 
                        echo '<td>'. ucwords(strtolower($row['level'])) . '</td>';                       
                        echo '<td align="center">'. $row['vault1'] . '</td>'; 
                        echo '<td align="center">'. $row['vault2'] . '</td>';                                 
                        echo '<td align="center">'. $row['avg1'] . '</td>';
                        echo '<td align="center">'.$rank. '</td>';                                              
                        echo '</tr>';
                       $cnt++; 
                        $sno ++;
                        $display=$row['maxe'];
        }
        echo '</tbody>
                                    </table>

                                    </div></div>';
        echo '</div>';
    }
  public function artistic_qualifier($comm,$participant,$gender,$category,$eventid,$limit,$apid)
  {

    $sqlqualifier="SELECT cnt_qualifier FROM `events` WHERE `Id` = $eventid";
    $qualifiercount =$comm->getvalue($sqlqualifier);//get no. of gymnast to be included from each team for e.g 2 gymnsts from 
    $sqlteams_qualifier="SELECT teams_qualifier FROM `events` WHERE `Id` = $eventid";
    $teams_qualifier =$comm->getvalue($sqlqualifier);//get teams to combine for qualifier
    $clubarray=array();
        
      foreach ($participant->getclub_bytable($eventid,$gender,'gymnast_score',$category) as $clubrow) 
      {
        $clubarray[$clubrow['club']]=0;
      }
      $rank=1;   
      $display=1;
      $cnt=1; 
      echo '
                        <tr>
                            <th width="20">Q/R</th>
                            <th width="50">Rank</th>
                            <th width="200">Name</th>
                            <th width="200">Club</th>
                            <th width="70">Total</th>
                        </tr>
                    </thead> <tbody>';
            foreach ($participant->artistic_generatequalifier($eventid,$gender,'gymnast_score',$category,$apid) as $row) {             
                  if($clubarray[$row['club']]<$qualifiercount)
                  {
                      if( $display!=$row['total'])
                      {
                          $rank=$cnt;
                      }
                     
                       echo '<tr>';
                      
                       
                        if($cnt<=8)
                        { 
                            echo '<td align="center">Q</td>';
                        }
                        elseif($cnt>8 && $cnt<=10)
                        {
                            echo '<td align="center">R</td>';
                        }
                        else
                            echo '<td align="center">QR</td>';
                            
                        
                        echo '<td align="center">'.$rank. '</td>'; 
                                                
                                                echo '<td>'. ucfirst($row['name'])  . '</td>';
                                                $disclub='';
                                                if($row['club']!='None')
                                                   $disclub= ucwords(strtolower($row['club'])) ;
                                                echo '<td>'.$disclub . '</td>';
                                                echo '<td align="center">'. $row['total'] . '</td>';
                                    echo '</tr>';
                      $cnt++; 
                      $display=$row['total'];
                      $clubarray[$row['club']]++;
                  }
                  if($rank=='R2')
                  {
                     break;
                  } 
            }
            echo '<tbody>' ; 
      //end elseif

  }

  public function artistic_topmin($comm,$participant,$gender,$category,$level,$eventid,$limit,$apid)
  {
  
    $sqlqualifier="SELECT cnt_qualifier FROM `events` WHERE `Id` = $eventid";
    $qualifiercount =$comm->getvalue($sqlqualifier);//get no. of gymnast to be included from each team for e.g 2 gymnsts from 
   
    $clubarray=array();
       
      foreach ($participant->getclub_bytable($eventid,$gender,'gymnast_score',$category) as $clubrow) 
      {
        $clubarray[$clubrow['club']]=0;
      }
      //print_r($clubarray);
      $rank=1;   
      $display=1;
      $cnt=1; 
      echo '
                        <tr>';
                          // echo '  <th width="20">Q/R</th>';<th width="240">Club</th>
                           echo '  <th width="50">Rank</th>
                            <th width="300">Name</th>
                            <th width="270">Club</th>
                            <th width="100">Phone</th>
                            <th width="100" class="text-center">Level</th>
                            <th width="100" class="text-center">Category</th>                           
                            <th width="70" class="text-center">Total</th>
                        </tr>
                    </thead> <tbody>';
            foreach ($participant->artistic_generatequalifier($eventid,$gender,'gymnast_score',$category,$level,$apid) as $row) {             
                  if($clubarray[$row['club']]<$qualifiercount)
                  {
                      if( $display!=$row['total'])
                      {
                          $rank=$cnt;
                      }  
                      // if($row['club']!='RnRFit') 
                      // {                 
                          echo '<tr>';       
                                         
                            
                            echo '<td align="center">'.$rank. '</td>';                                                
                            echo '<td>'. ucfirst($row['name'])  . '</td>';
                            $disclub='';
                            if($row['club']!='None')
                                $disclub= ucwords(strtolower($row['club'])) ;
                            echo '<td>'.$disclub . '</td>';
                            echo '<td align="center">'. $row['phone'] . '</td>';
                            echo '<td align="center">'. $level . '</td>';
                            echo '<td align="center">'. $category . '</td>';
                         
                            echo '<td align="center">'. $row['total'] . '</td>';
                            echo '</tr>';
                     // }
                      $cnt++; 
                      $display=$row['total'];
                      $clubarray[$row['club']]++;
                  }
                 
                  $totalnew=bcadd($row['total'], '0.005', 2);
                  $comp=bcadd(5.00, '0.005', 2);
                  // if((round($totalnew, 2) < round(5.00, 2)) || $cnt==$limit)
                  // {
                  //    break;
                  // } 
                  // if( (abs($totalnew-$comp) < PHP_FLOAT_EPSILON) )
                  // {
                  //    break;
                  // } 
                 
                  if($cnt==$limit)
                  {
                     break;
                  } 
            }
            echo '<tbody>' ; 
      //end elseif

  }
  public function artistic_top3($comm,$participant,$gender,$category,$level,$eventid,$limit,$apid)
  {
  
    $sqlqualifier="SELECT cnt_qualifier FROM `events` WHERE `Id` = $eventid";
    $qualifiercount =$comm->getvalue($sqlqualifier);//get no. of gymnast to be included from each team for e.g 2 gymnsts from 
   
    $clubarray=array();
       
      foreach ($participant->getclub_bytable($eventid,$gender,'gymnast_score',$category) as $clubrow) 
      {
        $clubarray[$clubrow['club']]=0;
      }
      //print_r($clubarray);
      $rank=1;   
      $display=1;
      $cnt=1; 
      echo '
                        <tr>';
                          // echo '  <th width="20">Q/R</th>';<th width="240">Club</th>
                           echo '  <th width="50">Rank</th>
                            <th width="300">Name</th>
                            <th width="170">Club</th>
                            <th width="100" class="text-center">Level</th>
                            <th width="100" class="text-center">Category</th>
                            
                            <th width="100" class="text-center">D Score</th>
                           
                            <th width="100" class="text-center">E1</th>
                            <th width="100" class="text-center">E2</th>
                            <th width="100" class="text-center">Avg</th>
                           
                            <th width="70" class="text-center">Total</th>
                        </tr>
                    </thead> <tbody>';
            foreach ($participant->artistic_generatequalifier($eventid,$gender,'gymnast_score',$category,$level,$apid) as $row) {             
                  if($clubarray[$row['club']]<$qualifiercount)
                  {
                      if( $display!=$row['total'])
                      {
                          $rank=$cnt;
                      }  
                      // if($row['club']!='RnRFit') 
                      // {                 
                          echo '<tr>';       
                           
                            // if($cnt<=8)
                            // { 
                            //     echo '<td align="center">Q</td>';
                            // }
                            // elseif($cnt>8 && $cnt<=10)
                            // {
                            //     echo '<td align="center">R</td>';
                            // }
                            // else
                            //     echo '<td align="center">QR</td>';                            
                            
                            echo '<td align="center">'.$rank. '</td>';                                                
                            echo '<td>'. ucfirst($row['name'])  . '</td>';
                            $disclub='';
                            if($row['club']!='None')
                                $disclub= ucwords(strtolower($row['club'])) ;
                            echo '<td>'.$disclub . '</td>';
                            echo '<td align="center">'. $level . '</td>';
                            echo '<td align="center">'. $category . '</td>';
                          //   echo '<td align="center">'. $apid . '</td>';
                            echo '<td align="center">'. $row['dscore'] . '</td>';
                          //  echo '<td align="center">'. $row['de'] . '</td>';
                             echo '<td align="center">'. $row['e1'] . '</td>';
                              echo '<td align="center">'. $row['e2'] . '</td>';
                               echo '<td align="center">'. $row['avg'] . '</td>';
                           // echo '<td align="center">'. $row['penalty'] . '</td>';
                            echo '<td align="center">'. $row['total'] . '</td>';
                            echo '</tr>';
                     // }
                      $cnt++; 
                      $display=$row['total'];
                      $clubarray[$row['club']]++;
                  }
                 
                  $totalnew=bcadd($row['total'], '0.005', 2);
                  $comp=bcadd(5.00, '0.005', 2);
                  // if((round($totalnew, 2) < round(5.00, 2)) || $cnt==$limit)
                  // {
                  //    break;
                  // } 
                  // if( (abs($totalnew-$comp) < PHP_FLOAT_EPSILON) )
                  // {
                  //    break;
                  // } 
                 
                  if($cnt==$limit)
                  {
                     break;
                  } 
            }
            echo '<tbody>' ; 
      //end elseif

  }

  public function vault_qualifier($comm,$participant,$gender,$category,$eventid,$limit)
  {

    $sqlqualifier="SELECT cnt_qualifier FROM `events` WHERE `Id` = $eventid";
    $qualifiercount =$comm->getvalue($sqlqualifier);//get no. of gymnast to be included from each team for e.g 2 gymnsts from 
    $sqlteams_qualifier="SELECT teams_qualifier FROM `events` WHERE `Id` = $eventid";
    $teams_qualifier =$comm->getvalue($sqlqualifier);//get teams to combine for qualifier
    $clubarray=array();
        
    foreach ($participant->getclub_bytable($eventid,$gender,'gymnast_score',$category) as $clubrow) 
    {
        $clubarray[$clubrow['club']]=0;
    }
      $rank=1;   
      $display=1;
      $cnt=1; 
      echo '
                        <tr>
                            <th width="20">Q/R</th>
                            <th width="50">Rank</th>
                            <th width="200">Name</th>
                            <th width="200">Club</th>
                            <th width="70" class="text-center">Vault 1</th>
                            <th width="70" class="text-center">Vault 2</th>
                            <th width="70" class="text-center">Final Score</th>
                        </tr>
                    </thead> <tbody>';
    $sql1="SELECT s.reg_id,name,s.total vault1,g.total vault2,s.subtotal evault1,g.subtotal evault2,s.dscore dvault1,g.dscore dvault2, round((s.total+g.total)/2,2) avg1,GREATEST(s.total,g.total) maxvault,GREATEST(s.subtotal,g.subtotal) maxe,GREATEST(s.dscore,g.dscore) maxd,club FROM `gymnast_score` s INNER join registrations r on s.reg_id=r.id left join apparatus a on a.apid=s.apid LEFT join (select * from gymnast_score g where eventid='".$eventid."' and apid='17' and category='".$category."') g on g.reg_id=s.reg_id where s.eventid='".$eventid."' and a.apid='6' and r.gender='".$gender."' and s.category='".$category."' ORDER BY avg1 DESC,maxvault DESC,maxe DESC";
    
    foreach ($comm->executesql($sql1) as $row) {             
                  if($clubarray[$row['club']]<2)
                  {
                        if( $display!=$row['avg1'])
                        {
                            $rank=$cnt;
                        }
                     
                       echo '<tr>';
                      
                       
                        if($cnt<=8)
                        { 
                            echo '<td align="center">Q</td>';
                        }
                        elseif($cnt>8 && $cnt<=10)
                        {
                            echo '<td align="center">R</td>';
                        }
                        else
                            echo '<td align="center">QR</td>';
                            
                        
                        echo '<td align="center">'.$rank. '</td>'; 
                                                
                                                echo '<td>'. ucfirst($row['name'])  . '</td>';
                                                $disclub='';
                                                if($row['club']!='None')
                                                   $disclub= ucwords(strtolower($row['club'])) ;
                                                echo '<td>'.$disclub . '</td>';
                                                echo '<td align="center">'. $row['vault1'] . '</td>';
                                                echo '<td align="center">'. $row['vault2'] . '</td>';
                                                echo '<td align="center">'. $row['avg1'] . '</td>';
                                    echo '</tr>';
                      $cnt++; 
                      $display=$row['avg1'];
                      $clubarray[$row['club']]++;
                  }
                  if($rank=='R2')
                  {
                     break;
                  } 
            }
            echo '<tbody>' ;
  }

  public function vault_top3($comm,$participant,$gender,$category,$eventid,$limit)
  {

    $sqlqualifier="SELECT cnt_qualifier FROM `events` WHERE `Id` = $eventid";
    $qualifiercount =$comm->getvalue($sqlqualifier);//get no. of gymnast to be included from each team for e.g 2 gymnsts from 
    $sqlteams_qualifier="SELECT teams_qualifier FROM `events` WHERE `Id` = $eventid";
    $teams_qualifier =$comm->getvalue($sqlqualifier);//get teams to combine for qualifier
    $clubarray=array();
        
    foreach ($participant->getclub_bytable($eventid,$gender,'gymnast_score',$category) as $clubrow) 
    {
        $clubarray[$clubrow['club']]=0;
    }
      $rank=1;   
      $display=1;
      $cnt=1; 
      echo '
                        <tr>
                            <th width="20">Q/R</th>
                            <th width="50">Rank</th>
                            <th width="200">Name</th>
                            <th width="200">Club</th>
                            <th width="70" class="text-center">Vault 1</th>
                            <th width="70" class="text-center">Vault 2</th>
                            <th width="70" class="text-center">Final Score</th>
                        </tr>
                    </thead> <tbody>';
    $sql1="SELECT s.reg_id,name,s.total vault1,g.total vault2,s.subtotal evault1,g.subtotal evault2,s.dscore dvault1,g.dscore dvault2, round((s.total+g.total)/2,2) avg1,GREATEST(s.total,g.total) maxvault,GREATEST(s.subtotal,g.subtotal) maxe,GREATEST(s.dscore,g.dscore) maxd,club FROM `gymnast_score` s INNER join registrations r on s.reg_id=r.id left join apparatus a on a.apid=s.apid LEFT join (select * from gymnast_score g where eventid='".$eventid."' and apid='17' and category='".$category."') g on g.reg_id=s.reg_id where s.eventid='".$eventid."' and a.apid='6' and r.gender='".$gender."' and s.category='".$category."' ORDER BY avg1 DESC,maxvault DESC,maxe DESC";
    
    foreach ($comm->executesql($sql1) as $row) {             
                  if($clubarray[$row['club']]<$qualifiercount)
                  {
                        if( $display!=$row['avg1'])
                        {
                            $rank=$cnt;
                        }
                     
                       echo '<tr>';
                      
                       
                        if($cnt<=8)
                        { 
                            echo '<td align="center">Q</td>';
                        }
                        elseif($cnt>8 && $cnt<=10)
                        {
                            echo '<td align="center">R</td>';
                        }
                        else
                            echo '<td align="center">QR</td>';
                            
                        
                        echo '<td align="center">'.$rank. '</td>'; 
                                                
                                                echo '<td>'. ucfirst($row['name'])  . '</td>';
                                                $disclub='';
                                                if($row['club']!='None')
                                                   $disclub= ucwords(strtolower($row['club'])) ;
                                                echo '<td>'.$disclub . '</td>';
                                                echo '<td align="center">'. $row['vault1'] . '</td>';
                                                echo '<td align="center">'. $row['vault2'] . '</td>';
                                                echo '<td align="center">'. $row['avg1'] . '</td>';
                                    echo '</tr>';
                      $cnt++; 
                      $display=$row['avg1'];
                      $clubarray[$row['club']]++;
                  }
                  if($cnt==4)
                  {
                     break;
                  } 
            }
            echo '<tbody>' ;
  }

  public function tramp_result($comm,$participant,$gender,$category,$eventid,$limit)
  {

    $sqlqualifier="SELECT cnt_qualifier FROM `events` WHERE `Id` = $eventid";
    $qualifiercount =$comm->getvalue($sqlqualifier);//get no. of gymnast to be included from each team for e.g 2 gymnsts from 
   
    $clubarray=array();
          
      foreach ($participant->getclub_bytable($eventid,$gender,'trampoline_score',$category) as $clubrow) 
      {
        $clubarray[$clubrow['club']]=0;
      }
      $rank=1;   
      $display=1;
      $cnt=1; 
      echo '
                        <tr>
                           
                            <th width="50">Rank</th>
                            <th width="200">Name</th>
                            <th width="250">Club</th> 
                            <th width="70">DScore</th>
                            <th width="70">EScore</th>
                            <th width="70">Horizontal</th> 
                            <th width="70">TOF</th>                           
                           <th width="70">Penalty</th> 
                            <th width="70">Total</th>
                        </tr>
                    </thead> <tbody>';
            foreach ($participant->tramp_generatequalifier($eventid,$gender,'trampoline_score',$category) as $row) {             
                  // if($clubarray[$row['club']]<$qualifiercount || $row['club'] =='None')
                  // {
                      if( $display!=$row['total'])
                      {
                                                  $rank=$cnt;
                      }
                     
                       echo '<tr>';                        
                        echo '<td align="center">'.$rank. '</td>'; 
                                                
                                                echo '<td>'. ucfirst($row['name'])  . '</td>';
                                                $disclub='';
                                                if($row['club']!='None')
                                                   $disclub= ucwords(strtolower($row['club'])) ;
                                                echo '<td>'.$disclub . '</td>';
                                                echo '<td align="center">'. $row['dscore'] . '</td>';
                                                echo '<td align="center">'. $row['escore'] . '</td>';
                                                echo '<td align="center">'. $row['horizontal'] . '</td>';
                                                echo '<td align="center">'. $row['tof'] . '</td>';
                                                echo '<td align="center">'. $row['penalty'] . '</td>';
                                                echo '<td align="center">'. $row['total'] . '</td>';
                                    echo '</tr>';
                      $cnt++; 
                      $display=$row['total'];
                      $clubarray[$row['club']]++;
                  //}                 
            }
            echo '<tbody>' ; 
      //end elseif

  }
  public function artistic_result($comm,$participant,$gender,$category,$level,$eventid,$limit,$apid)
  {

    $sqlqualifier="SELECT cnt_qualifier FROM `events` WHERE `Id` = $eventid";
    $qualifiercount =$comm->getvalue($sqlqualifier);//get no. of gymnast to be included from each team for e.g 2 gymnsts from 
   
    $clubarray=array();
          
      foreach ($participant->getclub_bytable($eventid,$gender,'gymnast_score',$category) as $clubrow) 
      {
        $clubarray[$clubrow['club']]=0;
      }
      $rank=1;   
      $display=1;
      $cnt=1; 
      echo '
                        <tr>
                           
                            <th width="50">Rank</th>
                            <th width="200">Name</th>
                            <th width="250">Club</th> 
                            <th width="70">D Score</th>';
                           echo' <th width="70">Avg Deduction</th> ';
                          // echo' <th width="70">E Score</th>    ';                    
                          echo ' <th width="70">Penalty</th> 
                            <th width="70">Total</th>
                        </tr>
                    </thead> <tbody>';
            foreach ($participant->artistic_generatequalifier($eventid,$gender,'gymnast_score',$category,$level,$apid) as $row) {             
                  if($clubarray[$row['club']]<$qualifiercount)
                  {
                      if( $display!=$row['total'])
                      {
                                                  $rank=$cnt;
                      }
                     
                       echo '<tr>';                        
                        echo '<td align="center">'.$rank. '</td>'; 
                                                
                                                echo '<td>'. ucfirst($row['name'])  . '</td>';
                                                $disclub='';
                                                if($row['club']!='None')
                                                   $disclub= ucwords(strtolower($row['club'])) ;
                                                echo '<td>'.$disclub . '</td>';
                                                echo '<td align="center">'. $row['dscore'] . '</td>';
                                               
                                                echo '<td align="center">'. $row['avg'] . '</td>';
                                              //   echo '<td align="center">'. $row['subtotal'] . '</td>';
                                                echo '<td align="center">'. $row['penalty'] . '</td>';
                                                echo '<td align="center">'. $row['total'] . '</td>';
                                    echo '</tr>';
                      $cnt++; 
                      $display=$row['total'];
                      $clubarray[$row['club']]++;
                  }                 
            }
            echo '<tbody>' ; 
      //end elseif
  }

  public function artistic_printpdf($comm,$participant,$gender,$category,$level,$eventid,$limit,$apid)
  {

    $sqlqualifier="SELECT cnt_qualifier FROM `events` WHERE `Id` = $eventid";
    $qualifiercount =$comm->getvalue($sqlqualifier);//get no. of gymnast to be included from each team for e.g 2 gymnsts from 
   
    $clubarray=array();
          
      foreach ($participant->getclub_bytable($eventid,$gender,'gymnast_score',$category) as $clubrow) 
      {
        $clubarray[$clubrow['club']]=0;
      }
      $rank=1;   
      $display=1;
      $cnt=1; 
      $dt='<tr >
                  <td width="50" align="center" style="font-weight: bold;border-bottom:1px solid black;">Rank</td>
                  <td width="250" style="font-weight: bold;border-bottom:1px solid black;">Name</td>
                  <td width="200" style="font-weight: bold;border-bottom:1px solid black;">Club</td> ';
          // $dt='        <td width="70" align="center" style="font-weight: bold;border-bottom:1px solid black;">D Score</td>';
          // $dt.=' <td width="70" align="center" style="font-weight: bold;border-bottom:1px solid black;">Avg</td> ';
                                             
          // $dt.=' <td width="70" align="center" style="font-weight: bold;border-bottom:1px solid black;">Penalty</td> ';
          $dt .='        <td width="100" align="center" style="font-weight: bold;border-bottom:1px solid black;">Final Score</td>
                </tr>';
            $scoredata=$participant->artistic_generatequalifier($eventid,$gender,'gymnast_score',$category,$level,$apid);
            $cntscore=count($scoredata);
            if($cntscore>0)
            {
                foreach ($scoredata as $row) {             
                      if($clubarray[$row['club']]<$qualifiercount)
                      {
                          if( $display!=$row['total'])
                          {
                              $rank=$cnt;
                          }                         
                          $dt.= '<tr>';                        
                          $dt.= '<td align="center" width="50">'.$rank. '</td>';                                                 
                          $dt.= '<td width="250">'. ucfirst($row['name'])  . '</td>';
                          $disclub='';
                          if($row['club']!='None')
                              $disclub= ucwords(strtolower($row['club'])) ;
                          $dt.= '<td width="200">'.$disclub.'</td>';
                        //   $dt.= '<td align="center" width="70">'. $row['dscore'] . '</td>';
                        //   $dt.= '<td align="center" width="70">'. $row['avg'] . '</td>';
                        // //echo  '<td align="center">'.$row['subtotal'].'</td>';
                        //   $dt.= '<td align="center" width="70">'. $row['penalty'] . '</td>';
                          $dt.= '<td align="center" width="100">'. $row['total'] . '</td>';
                          $dt.= '</tr>';
                          $cnt++; 
                          $display=$row['total'];
                          $clubarray[$row['club']]++;
                      }  
                      if($cnt==$limit)
                      {
                         break;
                      }                
                }
          }
           
      return $dt;
  }

  public function artistic_printall($comm,$participant,$gender,$category,$level,$eventid,$limit,$apid)
  {   
    $clubarray=array();
          
      foreach ($participant->getclub_bytable($eventid,$gender,'gymnast_score',$category) as $clubrow) 
      {
        $clubarray[$clubrow['club']]=0;
      }    
       
      $dt='<tr >
                  <td width="60" align="center" style="font-weight: bold;border-bottom:1px solid black;"></td>
                  <td width="250" style="font-weight: bold;border-bottom:1px solid black;">Name</td>
                  <td width="250" style="font-weight: bold;border-bottom:1px solid black;">Club</td>'; 
            $dt.=' <td width="65" align="center" style="font-weight: bold;border-bottom:1px solid black;">Start Value</td>';
          //$dt.=' <td width="65" align="center" style="font-weight: bold;border-bottom:1px solid black;">E1</td> ';
          //$dt.=' <td width="65" align="center" style="font-weight: bold;border-bottom:1px solid black;">E2</td> ';
          //$dt.=' <td width="65" align="center" style="font-weight: bold;border-bottom:1px solid black;">Avg</td> ';
          // $dt.=' <td width="65" align="center" style="font-weight: bold;border-bottom:1px solid black;">Penalty</td> '; 
             $dt.='<td width="85" align="center" style="font-weight: bold;border-bottom:1px solid black;">Final Score</td>
                </tr>';
            $rank=1;   
            $display=1;
            $cnt=1;
            $scoredata=$participant->artistic_generatequalifier($eventid,$gender,'gymnast_score',$category,$level,$apid);
            $cntscore=count($scoredata);
            if($cntscore>0)
            {
              $cnt=1;
                foreach ($scoredata as $row) {             
                          if( $display!=$row['total'])
                          {
                              $rank=$cnt;
                          }  
                          // if($row['club']=='RnRFit') {           
                            $dt.= '<tr>';                        
                            $dt.= '<td align="center" width="60">'.$rank. '</td>';                                                 
                            $dt.= '<td width="250">'. ucfirst($row['name'])  . '</td>';
                            $disclub='';
                            if($row['club']!='None')
                                $disclub= ucwords(strtolower($row['club'])) ;
                            $dt.= '<td width="250">'.$disclub.'</td>';
                            $dt.= '<td align="center" width="65">'. $row['dscore'] . '</td>';
                            // $dt.= '<td align="center" width="65">'. $row['e1'] . '</td>';
                            // $dt.= '<td align="center" width="65">'. $row['e2'] . '</td>';
                            // $dt.= '<td align="center" width="65">'. $row['avg'] . '</td>';                       
                            // $dt.= '<td align="center" width="65">'. $row['penalty'] . '</td>';
                            $dt.= '<td align="center" width="85">'. $row['total'] . '</td>';
                            $dt.= '</tr>';  
                          // }                       
                          $cnt++; 
                          $display=$row['total'];
                          $clubarray[$row['club']]++;                                                            
                }
          }           
      return $dt;
  }
 public function artistic_aafinalpdf($comm,$participant,$gender,$category,$level,$eventid)
  { 
      $sqlqualifier="SELECT cnt_qualifier FROM `events` WHERE `Id` = $eventid";
      $qualifiercount =$comm->getvalue($sqlqualifier);//get no. of gymnast to be included from each team for e.g 2 gymnsts from 

      $app= array();
      $sname= array();
      $sql="SELECT * FROM `apparatus` where pid!=0 and (gender='".$gender."' or gender ='b') and apid in (SELECT distinct apid FROM `gymnast_score` cs inner join registrations o on o.id=cs.reg_id where eventid='".$eventid."' and apid !=17 and category='".$category."' and o.level='$level' and gender='".$gender."') order by sequence";
      
      $k=0;
      foreach($comm->executesql($sql) as $row)
      {
          $app[$k]=$row['apid'];
          $sname[$k]=$row['sname'];
          $k ++;
      }
      $dt ='<table cellspacing="0" cellpadding="1" style="border-collapse: collapse;page-break-inside:avoid;">
      <thead>
            <tr style="border-bottom: 1px solid #000 !important;">
            <th width="60" align="center" style="font-weight: bold;border-bottom:1px solid black;">Rank</th>
            <th width="180" style="font-weight: bold;border-bottom:1px solid black;">Name</th> 
            <th width="150" style="font-weight: bold;border-bottom:1px solid black;">Club</th>';
        for ($i=0;$i<count($sname);++$i) {
            $dt.='<th width="50" align="center" style="font-weight: bold;border-bottom:1px solid black;">'.$sname[$i].'</th>';
        } 
      $dt.='<th width="60" align="center" style="font-weight: bold;border-bottom:1px solid black;">AA Score</th>';
      $dt.='</tr></thead><tbody>'; 

      $rank=1;
      $display=1;
      $cnt=1;
      $clubarray=array();//all clubs in array
      foreach ($participant->Finalclub($eventid,$gender,$category) as $clubrow) 
      {
          $clubarray[$clubrow['club']]=0;
      }
      $sql1="SELECT name,category,Gender,club";
      for ($i=0;$i<count($app);++$i) {
          $sql1 .=",sum(case when apid = '".$app[$i]."' then s.total else 0 end) as '".$app[$i]."'";
      }
      $sql1.=" ,sum(Total) Total FROM `gymnast_score` s inner join registrations o on o.id=s.reg_id where category='".$category."' and s.Eventid='$eventid' and o.level='".$level."' and gender='$gender' and apid !=17 GROUP by s.reg_id,name order by Total DESC";
        
      $nume=0;
      $nume =$comm->getvalue($sql1);
                           
      foreach ($comm->executesql($sql1) as $row) 
      {
          // if($clubarray[$row['club']]<7)
          // {
                  if( $display!=$row['Total'])
                  {
                       $rank=$cnt;
                       
                  }
                //  if($row['club']=='RnRFit') {                                        
                  $dt.='<tr>'; 
                  $dt.='<td width="60" align="center">'.$rank. '</td>';  
                  $dt.='<td width="180">'. ucfirst($row['name']) . '</td>';
                  $dt.='<td width="150">'. ucwords(strtolower($row['club'])) . '</td>'; 
                  for ($i=0;$i<count($app);++$i) {
                        if($row[$app[$i]]>0)
                              $dt.='<td width="50" align="center">'.$row[$app[$i]].'</td>';
                        else
                              $dt.='<td width="50">&nbsp;</td>';
                  }   
                  $dt.='<td width="60" align="center">'. $row['Total'] . '</td>';                        
                  $dt.='</tr>';
                //}
                  $cnt++; 
                  $display=$row['Total'];
                  $clubarray[$row['club']]++;
          //}
         
      }                                           
    $dt.='</tbody></table>';
   
    return $dt;
  }  

  public function artistic_apprank($comm,$participant,$gender,$category,$level,$eventid,$limit,$apid)
  {         
      $rank=1;   
      $display=1;
      $cnt=1;
      $scoredata=$participant->artistic_generatequalifier($eventid,$gender,'gymnast_score',$category,$level,$apid);
      $cntscore=count($scoredata);
      if($cntscore>0)
      {
          $cnt=1;
          $apparray=array();
          $data = array();
          foreach ($scoredata as $row) 
          {             
                    if($display!=$row['total'])
                    {
                        $rank=$cnt;
                    } 
                    $data['name']=$row['name']; 
                    $data['total']=$row['total'];
                    $data['rank']=$rank; 
                    $cnt++;                
                    $display=$row['total'];  
                    array_push($apparray, $data);                                                                          
          }          
      }          
      return $apparray;
  }

  public function artistic_aa_array($comm,$participant,$gender,$category,$level,$eventid)
  { 
      $app= array();
      $sql="SELECT * FROM `apparatus` where pid!=0 and (gender='".$gender."' or gender ='b') and apid in (SELECT distinct apid FROM `gymnast_score` cs inner join registrations o on o.id=cs.reg_id where eventid='".$eventid."' and apid !=17 and category='".$category."' and o.level='$level' and gender='".$gender."') order by sequence";
      
      foreach($comm->executesql($sql) as $row)
      {
          $app[$k]=$row['apid'];
      }      

      $rank=1;
      $display=1;
      $cnt=1;
      $apparray=array();
      $data = array();
      $sql1="SELECT name,category,Gender,club";
      for ($i=0;$i<count($app);++$i) {
          $sql1 .=",sum(case when apid = '".$app[$i]."' then s.total else 0 end) as '".$app[$i]."'";
      }
      $sql1.=" ,sum(Total) Total FROM `gymnast_score` s inner join registrations o on o.id=s.reg_id where category='".$category."' and s.Eventid='$eventid' and o.level='".$level."' and gender='$gender' and apid !=17 GROUP by s.reg_id,name order by Total DESC";
          $apparray=array();
          $data = array();              
          foreach ($comm->executesql($sql1) as $row) 
          {
                  if( $display!=$row['Total'])
                  {
                       $rank=$cnt;                       
                  }
                  $data['name']=$row['name'];
                  $data['club']=$row['club']; 
                  $data['total']=$row['Total'];
                  $data['rank']=$rank; 
                  $cnt++;                
                  $display=$row['Total'];  
                  array_push($apparray, $data);                   
          }    
    return $apparray;
  }  

 
}//class
?>