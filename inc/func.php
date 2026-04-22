<?php

class Func
{

    public function getallapparatus($comm, $gender)
    {
        $appname = array();
        $sql = "SELECT * FROM `apparatus` where pid!=0 and (gender='" . $gender . "' or gender ='b') and apid not in (17) order by sequence";
        $i = 0;

        foreach ($comm->executesql($sql) as $row) {
            $appname[$i] = $row['apid'];
            $i++;
        }
        return $appname;
    }

    public function get_avg($e1, $e2, $e3, $e4, $e5, $e6)
    {
        $eall = array($e1, $e2, $e3, $e4, $e5, $e6);

        $cnt2 = 0;
        $subtotal = 0;
        $earray = $eall;
        //$earray=filter_array($eall);//remove any undefined value i.e if only three judges
        $earray = array_filter($eall);

        $cnt = count($earray); // recalculate array length so that judge can be 3 or 4

        $min1 = min($earray);
        $max2 = max($earray);

        for ($i = 0; $i < $cnt; $i++) {
            if (isset($earray[$i])) {
                $subtotal = +$subtotal + $earray[$i]; //total of all the values
            }
        }
        if ($cnt == 3) {
            $subtotal = $subtotal - $max2;
            $cnt2 = $cnt - 1; //we will use it to divide
        } else if ($cnt > 3) {
            $subtotal = $subtotal - $min1 - $max2;
            $cnt2 = $cnt - 2;
        } else {
            $cnt2 = $cnt;
        }

        $avgscore = round(($subtotal / $cnt2), 2);
        return $avgscore;
    }

    public static function isGender($gender)
    {
        if (!is_array($gender)) {
            $gender_array = array('M' => 'MAG', 'F' => 'WAG');
            if (in_array($gender, array_keys($gender_array))) {
                return $gender_array[$gender];
            } else {
                return false;
            }

        } else {
            return false;
        }

    }
    public function qualifierrpt($participant, $eventid, $gender, $apid, $category)
    {
        $clubarray = array();
        $index = 0;

        foreach ($participant->qualifierclub($eventid, $gender, $apid, $category) as $clubrow) {
            $clubarray[$clubrow['club']] = 0;
        }
        $rank = 1;
        $display = 1;
        $cnt = 1;
        //order by total and escore
        foreach ($participant->qualifierrpt($eventid, $gender, $apid, $category) as $row) {
            if ($clubarray[$row['club']] < 2) {
                if ($display != $row['total']) {
                    $rank = $cnt;
                }
                if ($rank == '9') {
                    $rank = 'R1';
                }
                if ($rank == '10') {

                    $rank = 'R2';
                }
                echo '<tr style="height:20 !important; font-size: 12px;font-weight: bold;">';
                echo '<td align="center">' . $rank . '</td>';
                echo '<td>' . ucfirst($row['name']) . '</td>';
                echo '<td>' . strtoupper($row['club']) . '</td>';
                echo '<td align="center">' . $row['total'] . '</td>';
                echo '</tr>';
                $cnt++;
                $display = $row['total'];
                $clubarray[$row['club']]++;
            }
            if ($rank == 'R2') {
                break;
            }
        }
    }

    public function teamchampionship_old($comm, $gender, $category, $eventid, $rowcount, $showcol)
    {
        $app = array();
        $sname = array();
        $i = 0;
        $rank = 1;
        $display = 1;
        $cnt = 1;
        $same = 0;
        $teampenalty = 0;
        $hide = 0;
        $sql = "SELECT * FROM `apparatus` where pid!=0 and (gender='" . $gender . "' or gender ='b') and apid in (SELECT distinct apid FROM `gymnast_score` cs inner join registrations o on o.id=cs.reg_id where eventid='" . $eventid . "' and apid !=17 and category='" . $category . "' and gender='" . $gender . "') order by sequence";

        foreach ($comm->executesql($sql) as $row) {
            $app[$i] = $row['gname'];
            $sname[$i] = $row['sname'];
            $i++;
        }
        echo '<table border="1" style="border-collapse:collapse;width:90%" cellspacing="0" cellpadding="0">
            <thead>
            <tr>
            <th width="70">Rank</th>
            <th width="150">State/Unit</th>';
        if ($showcol != 0) //display apparatus columns
        {
            for ($i = 0; $i < count($app); ++$i) {
                echo '<th width="60">' . $sname[$i] . '</th>';
            }
        }
        echo '<th width="60">Score</th></tr></thead><tbody>';
        $sql1 = "SELECT club,gender";
        for ($i = 0; $i < count($app); ++$i) {
            $sql1 .= ",ROUND(sum(case when apparatus = '" . $app[$i] . "' then score else 0 end),2) as '" . $sname[$i] . "'";
        }
        $sql1 .= " ,ROUND(sum(score), 2)score from state_rank r where gender='" . $gender . "' and category='" . $category . "' and eventid='" . $eventid . "' GROUP by club order by score desc";
        foreach ($comm->executesql($sql1) as $row) {

            $teasmsql = "SELECT penalty FROM state_penalty WHERE eventid='$eventid' and state = '" . $row['club'] . "' and `gender` = '$gender' AND `category` = '$category'";
            $hidesql = "SELECT display FROM state_penalty WHERE eventid='$eventid' and state = '" . $row['club'] . "' and `gender` = '$gender' AND `category` = '$category'";

            $hide = $comm->getvalue($hidesql);
            $teampenalty = $comm->getvalue($teasmsql);
            if ($hide == 0) //will print only if hide is zero
            {
                if ($display == $row['score']) {
                    $same = 1;
                } else {
                    if ($cnt != 1) {
                        $rank++;
                        if ($same == 1) {
                            $rank = $cnt;
                        }

                    }
                }
                echo '<tr>';
                echo '<td align="center">' . $rank . '</td>';
                echo '<td align="center">' . strtoupper($row['club']) . '</td>';
                $finalscore = $row['score'] - $teampenalty;
                if ($showcol != 0) //display apparatus columns
                {
                    for ($i = 0; $i < count($app); ++$i) {
                        echo '<td width="70">' . $row[$sname[$i]] . '</td>';
                    }
                }

                echo '<td align="center">' . number_format(round($finalscore, 2), 2) . '</td>';
                $cnt++;
                $display = $finalscore;
                echo '</tr>';
            }
            if ($cnt == $rowcount + 1) {
                break;
            }
            //to show no. of rows
        }
        echo '</tbody></table>';
    } //function end

    public function artistic_teamchampionship($comm, $tblname, $gender, $category, $eventid, $rowcount)
    {
        $club = array();

        $sqlcat = "SELECT distinct club FROM `registrations` WHERE Event =$eventid";
        $i = 0;
        foreach ($comm->executesql($sqlcat) as $row) {
            $club[$i] = $row['club'];
            $i++;
        }

        $aname = array();
        $j = 0;
        //only vault 1 score included
        $sql = "SELECT * FROM `apparatus` where pid!=0 and (gender='" . $gender . "' or gender ='b') and apid!=17 order by sequence";
        foreach ($comm->executesql($sql) as $row) {
            $aname[$j] = $row['sname'];
            $j++;
        }
        $sqlqualifier = "SELECT cnt_teamchamp FROM `events` WHERE `Id` = $eventid";
        $qualifiercount = $comm->getvalue($sqlqualifier); //get no. of gymnast to be included from each team for e.g 5 top gymnsts from each team

        $statearray = array();
        for ($i = 0; $i < count($club); $i++) { //for all state
            $data = array();
            $data['club'] = $club[$i];
            $data['total'] = 0;
            for ($j = 0; $j < count($aname); $j++) { //for all apparatus

                $sqlclub = "SELECT `club`, `name`, `total`, `category`, s.`apid`,r.gender,eventid,gname FROM `gymnast_score` s inner join registrations r on r.id=s.reg_id inner join apparatus a on a.apid=s.apid WHERE `category` LIKE '$category' and r.gender='" . $gender . "' AND s.`eventid` = $eventid and sname='$aname[$j]' and club='$club[$i]' order by total desc limit $qualifiercount";

                $getrecord = $comm->getvalue($sqlclub);
                $data[$aname[$j]] = 0;
                if ($getrecord != '') { //dontinsert duplicate values
                    foreach ($comm->executesql($sqlclub) as $row) { //fetch data and save it
                        $data[$aname[$j]] += $row['total'];
                    }
                }
                $data['total'] += $data[$aname[$j]];
            }

            array_push($statearray, $data);
        }

        $i = 0;
        $rank = 1;
        $display = 1;
        $cnt = 1;
        $same = 0;
        $teampenalty = 0;
        $hide = 0;

        echo '<table border="1" style="border-collapse:collapse;width:90%" cellspacing="0" cellpadding="0">
            <thead>
            <tr>
            <th width="70" align="center">Rank</th>
            <th width="150" align="center">State/Unit</th>';
        for ($j = 0; $j < count($aname); $j++) //print all apparatus as column names
        {
            echo '<th width="60" align="center">' . $aname[$j] . '</th>';
        }
        echo '<th width="150" align="center">Total</th>';
        echo '</tr></thead><tbody>';
        //sort on total of all apparatus
        $total = array();
        foreach ($statearray as $key => $row) {
            $total[$key] = $row['total'];
        }
        array_multisort($total, SORT_DESC, $statearray);

        foreach ($statearray as $row) {

            $teasmsql = "SELECT penalty FROM state_penalty WHERE eventid='$eventid' and state = '" . $row['club'] . "' and `gender` = '$gender' AND `category` = '$category'";
            $hidesql = "SELECT display FROM state_penalty WHERE eventid='$eventid' and state = '" . $row['club'] . "' and `gender` = '$gender' AND `category` = '$category'";

            $hide = $comm->getvalue($hidesql);
            $teampenalty = $comm->getvalue($teasmsql);
            if ($hide == 0) //will print only if hide is zero
            {
                if ($display == $row['total']) {
                    $same = 1;
                } else {
                    if ($cnt != 1) {
                        $rank++;
                        if ($same == 1) {
                            $rank = $cnt;
                        }

                    }
                }
                echo '<tr>';
                echo '<td align="center">' . $rank . '</td>';
                echo '<td align="center">' . strtoupper($row['club']) . '</td>';
                for ($j = 0; $j < count($aname); $j++) //print all apparatus as column names
                {
                    echo '<td width="60" align="center">' . $row[$aname[$j]] . '</td>';
                }
                $finalscore = $row['total'] - $teampenalty;
                echo '<td align="center">' . number_format(round($finalscore, 2), 2) . '</td>';
                $cnt++;
                $display = $finalscore;
                echo '</tr>';
            }

            if ($cnt == $rowcount + 1) {
                break;
            }
            //to show no. of rows
        }
        echo '</tbody></table>';
    } //function end

    public function leaderboard($comm, $apid, $level, $apparatus, $gender, $category, $eventid, $limit)
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
        echo '<th width="70" class="text-center">Rank</th>
                                  <th width="250" class="text-center">Name</th>
                                  <th width="150" class="text-center">Club</th>
                                  <th width="150" class="text-center">D</th>
                                  <th width="150" class="text-center">E1</th>
                                  <th width="150" class="text-center">E2</th>
                                  <th width="150" class="text-center">Avg</th>
                                  <th width="70" class="text-center">Final Score</th>
                                </tr>
                              </thead>
                              <tbody id="pagination">';

        $rank = 1;
        $prevtotal = 1;
        $prevescore = 1;
        $prevdscore = 1;
        $cnt = 1;
        if ($level != '') {
            $sql1 = "SELECT reg_id,s.*,Category,eventid, r.gender,dob,name,gname,club FROM `gymnast_score` s INNER join registrations r on s.reg_id=r.id left join apparatus a on a.apid=s.apid where eventid='" . $eventid . "' and s.level='" . $level . "' and a.apid=" . $apid . " and r.gender='" . $gender . "' and category='" . $category . "' order BY total desc,subtotal DESC,dscore DESC limit $limit";
        } else {
            $sql1 = "SELECT reg_id,s.*,Category,eventid, r.gender,dob,name,gname,club FROM `gymnast_score` s INNER join registrations r on s.reg_id=r.id left join apparatus a on a.apid=s.apid where eventid='" . $eventid . "' and a.apid=" . $apid . " and r.gender='" . $gender . "' and category='" . $category . "' order BY total desc,subtotal DESC,dscore DESC limit $limit";
        }

        //print_r($sql1);
        foreach ($comm->executesql($sql1) as $row) {

            if ($prevtotal != $row['total']) {
                $rank = $cnt;
            } elseif ($prevescore != $row['subtotal']) {
                //tie breaking situation
                $rank = $cnt;
            } elseif ($prevdscore != $row['dscore']) {
                $rank = $cnt;
            } else {
            }
            echo '<tr>';
            if ($eventid == 42) {
                //echo '<td align="center"></td>';
                echo '<td align="center"><a href="certificate.php?regid=' . $row['reg_id'] . '" style="padding:0px !important;"><img src="assets/images/download.png" style="width:30px;height:30px;"/></a></td>';
            }
            echo '<td align="center" class="text-white">' . $rank . '</td>';
            echo '<td class="text-white">' . ucfirst($row['name']) . '</td>';
            echo '<td class="text-white">' . strtoupper($row['club']) . '</td>';
            echo '<td class="text-white">' . strtoupper($row['dscore']) . '</td>';
            echo '<td class="text-white">' . strtoupper($row['e1']) . '</td>';
            echo '<td class="text-white">' . strtoupper($row['e2']) . '</td>';
            echo '<td class="text-white">' . strtoupper($row['avg']) . '</td>';
            echo '<td align="center" class="text-white">' . $row['total'] . '</td>';
            $cnt++;
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
                                 
                                </tr>
                              </thead>
                              <tbody id="pagination">';
        $rank = 1;
        $prevtotal = 1;
        $prevescore = 1;
        $prevdscore = 1;
        $cnt = 1;
         $sql1 = "SELECT reg_id,s.*,Category,eventid,r.level, r.gender,dob,name,gname,club FROM `gymnast_score` s INNER join registrations r on s.reg_id=r.id left join apparatus a on a.apid=s.apid where eventid='" . $eventid . "' and a.apid=" . $apid . " and r.gender='" . $gender . "' and category='" . $category . "' order BY total DESC limit $limit";

        //print_r($sql1);
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
            //echo '<td align="center" class="text-white">' . $rank . '</td>';
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

     public function agim_aafinal($comm, $participant, $gender, $category, $eventid)
    {
        $app = array();
        $sname = array();
        $sql = "SELECT * FROM `apparatus` where pid!=0 and (gender='" . $gender . "' or gender ='b') and apid in (SELECT distinct apid FROM `gymnast_score` cs inner join registrations o on o.id=cs.reg_id where eventid='" . $eventid . "' and apid !=17 and category='" . $category . "' and gender='" . $gender . "') order by sequence";

        $k = 0;
        foreach ($comm->executesql($sql) as $row) {
            $app[$k] = $row['apid'];
            $sname[$k] = $row['sname'];
            $k++;
        }
        
        echo '<table border="1" style="border-collapse:collapse;width:90%" cellspacing="0" cellpadding="0">
            <thead>
            <tr style="border-bottom: 1px solid #000 !important;">
            <th width="70">S. No.</th>
                            <th width="250">Name</th>
                            <th width="150">Club</th>';
        for ($i = 0; $i < count($sname); ++$i) {
            echo '<th width="60" class="text-center">' . $sname[$i] . '</th>';
        }
        echo '</tr></thead><tbody>';

        $rank = 1;
        $display = 1;
        $cnt = 1;
        $clubarray = array(); //all clubs in array
        foreach ($participant->Finalclub($eventid, $gender, $category) as $clubrow) {
            $clubarray[$clubrow['club']] = 0;
        }
        // $sql1 = "SELECT name,category,Gender,club";
        // for ($i = 0; $i < count($app); ++$i) {
        //     $sql1 .= ",sum(case when apid = '" . $app[$i] . "' then s.total else 0 end) as '" . $app[$i] . "'";
        // }
        // $sql1 .= " ,sum(Total) Total FROM `gymnast_score` s inner join registrations o on o.id=s.reg_id where category='" . $category . "' and s.Eventid='$eventid' and gender='$gender' and apid !=17 GROUP by s.reg_id,name order by Total DESC";


$sql1="SELECT reg_id,name,category,Gender,club, contingent,round((vt1+vt2)/2,2)as '6',UB as '7',BB as '8',FX as '9' from(SELECT s.reg_id,name,category,Gender,club,contingent,sum(case when apid = '6' then s.total else 0 end) as 'vt1', sum(case when apid = '17' then s.total else 0 end) as 'vt2', sum(case when apid = '7' then s.total else 0 end) as 'UB',sum(case when apid = '8' then s.total else 0 end) as 'BB',sum(case when apid = '9' then s.total else 0 end) as 'FX' ,sum(Total) Total FROM `gymnast_score` s inner join registrations o on o.id=s.reg_id where category='".$category."' and s.Eventid='$eventid' and gender='$gender' GROUP by s.reg_id,name order by asid asc)aa";
        $nume = 0;
        $nume = $comm->getvalue($sql1);
$sno=1;
        foreach ($comm->executesql($sql1) as $row) {
            // if($clubarray[$row['club']]<2 )
            // {
            if ($display != $row['Total']) {
                $rank = $cnt;
            }
            echo '<tr>';
            echo '<td align="center">' . $sno . '</td>';
            echo '<td>' . ucfirst($row['name']) . '</td>';
            echo '<td>' . strtoupper($row['club']) . '</td>';
            for ($i = 0; $i < count($app); ++$i) {
                if ($row[$app[$i]] > 0) {
                    // if($row[$app[$i]]>0)
                    //     {fa fa-facebook fb1
                          echo '<td align="center">'.$row[$app[$i]];
                          if($row[$app[$i]]>=6 && $row[$app[$i]]<7)
                          {
                              echo '<p><font style="background-color:#CD7F32;" color="black"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-award" viewBox="0 0 16 16">
  <path d="M9.669.864 8 0 6.331.864l-1.858.282-.842 1.68-1.337 1.32L2.6 6l-.306 1.854 1.337 1.32.842 1.68 1.858.282L8 12l1.669-.864 1.858-.282.842-1.68 1.337-1.32L13.4 6l.306-1.854-1.337-1.32-.842-1.68L9.669.864zm1.196 1.193.684 1.365 1.086 1.072L12.387 6l.248 1.506-1.086 1.072-.684 1.365-1.51.229L8 10.874l-1.355-.702-1.51-.229-.684-1.365-1.086-1.072L3.614 6l-.25-1.506 1.087-1.072.684-1.365 1.51-.229L8 1.126l1.356.702 1.509.229z"/>
  <path d="M4 11.794V16l4-1 4 1v-4.206l-2.018.306L8 13.126 6.018 12.1 4 11.794z"/>
</svg></font>Bronze</p>';
                          }
                          elseif ($row[$app[$i]]>=7 && $row[$app[$i]]<8) 
                          {
                            echo '<p><font style="background-color:#C0C0C0;" color="black"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-award" viewBox="0 0 16 16">
  <path d="M9.669.864 8 0 6.331.864l-1.858.282-.842 1.68-1.337 1.32L2.6 6l-.306 1.854 1.337 1.32.842 1.68 1.858.282L8 12l1.669-.864 1.858-.282.842-1.68 1.337-1.32L13.4 6l.306-1.854-1.337-1.32-.842-1.68L9.669.864zm1.196 1.193.684 1.365 1.086 1.072L12.387 6l.248 1.506-1.086 1.072-.684 1.365-1.51.229L8 10.874l-1.355-.702-1.51-.229-.684-1.365-1.086-1.072L3.614 6l-.25-1.506 1.087-1.072.684-1.365 1.51-.229L8 1.126l1.356.702 1.509.229z"/>
  <path d="M4 11.794V16l4-1 4 1v-4.206l-2.018.306L8 13.126 6.018 12.1 4 11.794z"/>
</svg></font>Silver</p>';
                          }
                          elseif ($row[$app[$i]]>=8 && $row[$app[$i]]<9) 
                          {
                            echo '<p><font style="background-color:#CFB53B;" color="black"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-award" viewBox="0 0 16 16">
  <path d="M9.669.864 8 0 6.331.864l-1.858.282-.842 1.68-1.337 1.32L2.6 6l-.306 1.854 1.337 1.32.842 1.68 1.858.282L8 12l1.669-.864 1.858-.282.842-1.68 1.337-1.32L13.4 6l.306-1.854-1.337-1.32-.842-1.68L9.669.864zm1.196 1.193.684 1.365 1.086 1.072L12.387 6l.248 1.506-1.086 1.072-.684 1.365-1.51.229L8 10.874l-1.355-.702-1.51-.229-.684-1.365-1.086-1.072L3.614 6l-.25-1.506 1.087-1.072.684-1.365 1.51-.229L8 1.126l1.356.702 1.509.229z"/>
  <path d="M4 11.794V16l4-1 4 1v-4.206l-2.018.306L8 13.126 6.018 12.1 4 11.794z"/>
</svg></font>Gold</p>';
                          }
                          elseif ($row[$app[$i]]>=9) 
                          {
                            echo '<p><font style="background-color:#FFDF00;" color="black"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-award" viewBox="0 0 16 16">
  <path d="M9.669.864 8 0 6.331.864l-1.858.282-.842 1.68-1.337 1.32L2.6 6l-.306 1.854 1.337 1.32.842 1.68 1.858.282L8 12l1.669-.864 1.858-.282.842-1.68 1.337-1.32L13.4 6l.306-1.854-1.337-1.32-.842-1.68L9.669.864zm1.196 1.193.684 1.365 1.086 1.072L12.387 6l.248 1.506-1.086 1.072-.684 1.365-1.51.229L8 10.874l-1.355-.702-1.51-.229-.684-1.365-1.086-1.072L3.614 6l-.25-1.506 1.087-1.072.684-1.365 1.51-.229L8 1.126l1.356.702 1.509.229z"/>
  <path d="M4 11.794V16l4-1 4 1v-4.206l-2.018.306L8 13.126 6.018 12.1 4 11.794z"/>
</svg></font>Platinum</p>';
                          }
                          echo '</td>';
                    //echo '<td align="center">' . $row[$app[$i]] . '</td>';
                } else {
                    echo '<td>&nbsp;</td>';
                }

            }
            //echo '<td align="center">' . $row['Total'] . '</td>';
            echo '</tr>';
            $cnt++;
            $sno ++;
            $display = $row['Total'];
            $clubarray[$row['club']]++;
            // }
            // if($rank==18)
            // {
            //     break;
            // }
        }
        echo '</tbody></table>';
    } //function end

     public function agim_teamchampionship($comm, $tblname, $gender, $category, $eventid, $rowcount)
    {
        $club = array();

        $sqlcat = "SELECT distinct club FROM `registrations` WHERE Event =$eventid";
        $i = 0;
        foreach ($comm->executesql($sqlcat) as $row) {
            $club[$i] = $row['club'];
            $i++;
        }

        $aname = array();
        $j = 0;
        //only vault 1 score included
        $sql = "SELECT * FROM `apparatus` where pid!=0 and (gender='" . $gender . "' or gender ='b') and apid!=17 and IsActive=1 order by sequence";
        foreach ($comm->executesql($sql) as $row) {
            $aname[$j] = $row['sname'];
            $j++;
        }
        $sqlqualifier = "SELECT cnt_teamchamp FROM `events` WHERE `Id` = $eventid";
        $qualifiercount = $comm->getvalue($sqlqualifier); //get no. of gymnast to be included from each team for e.g 5 top gymnsts from each team

        $statearray = array();
        for ($i = 0; $i < count($club); $i++) { //for all state
            $data = array();
            $data['club'] = $club[$i];
            $data['total'] = 0;
            for ($j = 0; $j < count($aname); $j++) { //for all apparatus

                $sqlclub = "SELECT `club`, `name`, `total`, `category`, s.`apid`,r.gender,eventid,gname FROM `gymnast_score` s inner join registrations r on r.id=s.reg_id inner join apparatus a on a.apid=s.apid WHERE `category` LIKE '$category' and r.level !='Aboveage-C' and r.gender='" . $gender . "' AND s.`eventid` = $eventid and sname='$aname[$j]' and club='$club[$i]' order by total desc limit $qualifiercount";
//print_r($sqlclub); 
                $getrecord = $comm->getvalue($sqlclub);
                $data[$aname[$j]] = 0;
                if ($getrecord != '') { //dontinsert duplicate values
                    foreach ($comm->executesql($sqlclub) as $row) { //fetch data and save it
                        $data[$aname[$j]] += $row['total'];
                    }
                }
                $data['total'] += $data[$aname[$j]];
            }

            array_push($statearray, $data);
        }

        $i = 0;
        $rank = 1;
        $display = 1;
        $cnt = 1;
        $same = 0;
        $teampenalty = 0;
        $hide = 0;

        echo '<table border="1" style="border-collapse:collapse;width:90%" cellspacing="0" cellpadding="0">
            <thead>
            <tr>
             <th width="70" class="text-center">Rank</th>
            <th width="200" class="text-center">Club</th>';
        for ($j = 0; $j < count($aname); $j++) //print all apparatus as column names
        {
            echo '<th width="60" class="text-center">' . $aname[$j] . '</th>';
        }
        echo '<th width="150" class="text-center">Total</th>';
        echo '</tr></thead><tbody>';
        //sort on total of all apparatus
        $total = array();
        foreach ($statearray as $key => $row) {
            $total[$key] = $row['total'];
        }
        array_multisort($total, SORT_DESC, $statearray);

        foreach ($statearray as $row) {           
            
                if ($display == $row['total']) {
                    $same = 1;
                } else {
                    if ($cnt != 1) {
                        $rank++;
                        if ($same == 1) {
                            $rank = $cnt;
                        }
                    }
                }
                echo '<tr>';
                echo '<td align="center">' . $rank . '</td>';
                echo '<td align="center">' . strtoupper($row['club']) . '</td>';
                for ($j = 0; $j < count($aname); $j++) //print all apparatus as column names
                {
                    echo '<td width="60" align="center">' . $row[$aname[$j]] . '</td>';
                }
                $finalscore = $row['total'] ;
                echo '<td align="center">' . number_format(round($finalscore, 2), 2) . '</td>';
                $cnt++;
                $display = $finalscore;
                echo '</tr>';
            

            if ($cnt == $rowcount + 1) {
                break;
            }
            //to show no. of rows
        }
        echo '</tbody></table>';
    } //function end

    public function artistic_aafinal($comm, $participant, $gender, $category, $eventid)
    {
        $app = array();
        $sname = array();
        $sql = "SELECT * FROM `apparatus` where pid!=0 and (gender='" . $gender . "' or gender ='b') and apid in (SELECT distinct apid FROM `gymnast_score` cs inner join registrations o on o.id=cs.reg_id where eventid='" . $eventid . "' and apid !=17 and category='" . $category . "' and gender='" . $gender . "') order by sequence";

        $k = 0;
        foreach ($comm->executesql($sql) as $row) {
            $app[$k] = $row['apid'];
            $sname[$k] = $row['sname'];
            $k++;
        }
        echo '<table border="1" style="border-collapse:collapse;width:90%" cellspacing="0" cellpadding="0">
      <thead>
            <tr style="border-bottom: 1px solid #000 !important;">
            <th width="70">Rank</th>
                            <th width="250">Name</th>
                            <th width="150">Club</th>';
        for ($i = 0; $i < count($sname); ++$i) {
            echo '<th width="60">' . $sname[$i] . '</th>';
        }
        echo '<th width="60">AA Score</th></tr></thead><tbody>';

        $rank = 1;
        $display = 1;
        $cnt = 1;
        $clubarray = array(); //all clubs in array
        foreach ($participant->Finalclub($eventid, $gender, $category) as $clubrow) {
            $clubarray[$clubrow['club']] = 0;
        }
        $sql1 = "SELECT name,category,Gender,club";
        for ($i = 0; $i < count($app); ++$i) {
            $sql1 .= ",sum(case when apid = '" . $app[$i] . "' then s.total else 0 end) as '" . $app[$i] . "'";
        }
        $sql1 .= " ,sum(Total) Total FROM `gymnast_score` s inner join registrations o on o.id=s.reg_id where category='" . $category . "' and s.Eventid='$eventid' and gender='$gender' and apid !=17 GROUP by s.reg_id,name order by Total DESC";

        $nume = 0;
        $nume = $comm->getvalue($sql1);

        foreach ($comm->executesql($sql1) as $row) {
            // if($clubarray[$row['club']]<2 )
            // {
            if ($display != $row['Total']) {
                $rank = $cnt;
            }
            echo '<tr>';
            echo '<td align="center">' . $rank . '</td>';
            echo '<td>' . ucfirst($row['name']) . '</td>';
            echo '<td>' . strtoupper($row['club']) . '</td>';
            for ($i = 0; $i < count($app); ++$i) {
                if ($row[$app[$i]] > 0) {
                    echo '<td align="center">' . $row[$app[$i]] . '</td>';
                } else {
                    echo '<td>&nbsp;</td>';
                }

            }
            echo '<td align="center">' . $row['Total'] . '</td>';
            echo '</tr>';
            $cnt++;
            $display = $row['Total'];
            $clubarray[$row['club']]++;
            // }
            // if($rank==18)
            // {
            //     break;
            // }
        }
        echo '</tbody></table>';
    } //function end

    public function artistic_aaleaderboard($comm, $participant, $gender, $category, $level, $eventid, $rowcount)
    {
        $app = array();
        $sname = array();
        $sql = "SELECT * FROM `apparatus` where pid!=0 and (gender='" . $gender . "' or gender ='b') and apid in (SELECT distinct apid FROM `gymnast_score` cs inner join registrations o on o.id=cs.reg_id where eventid='" . $eventid . "' and apid !=17 and category='" . $category . "' and gender='" . $gender . "') order by sequence";

        $k = 0;
        foreach ($comm->executesql($sql) as $row) {
            $app[$k] = $row['apid'];
            $sname[$k] = $row['sname'];
            $k++;
        }
        echo '<table border="1" style="border-collapse:collapse;width:90%" cellspacing="0" cellpadding="0">
      <thead>
           <tr style="border-bottom: 1px solid #000 !important;">
            <th width="70" class="text-center">Rank</th>
                            <th width="250">Name</th>
                            <th width="150">Club</th>';
        for ($i = 0; $i < count($sname); ++$i) {
            echo '<th width="60" class="text-center">' . $sname[$i] . '</th>';
        }
        echo '<th width="60" class="text-center">AA Score</th></tr></thead><tbody>';

        $rank = 1;
        $display = 1;
        $cnt = 1;
        $clubarray = array(); //all clubs in array
        foreach ($participant->Finalclub($eventid, $gender, $category) as $clubrow) {
            $clubarray[$clubrow['club']] = 0;
        }
        $sql1 = "SELECT name,category,Gender,club";
        for ($i = 0; $i < count($app); ++$i) {
            $sql1 .= ",sum(case when apid = '" . $app[$i] . "' then s.total else 0 end) as '" . $app[$i] . "'";
        }
        if ($level == '') {
            $sql1 .= " ,sum(Total) Total FROM `gymnast_score` s inner join registrations o on o.id=s.reg_id where category='" . $category . "' and s.Eventid='$eventid' and gender='$gender' and apid !=17 GROUP by s.reg_id,name order by Total DESC limit $rowcount";
        } else {
            $sql1 .= " ,sum(Total) Total FROM `gymnast_score` s inner join registrations o on o.id=s.reg_id where category='" . $category . "' and s.Eventid='$eventid' and o.level='$level' and gender='$gender' and apid !=17 GROUP by s.reg_id,name order by Total DESC limit $rowcount";
        }

        $nume = 0;
        $nume = $comm->getvalue($sql1);

        foreach ($comm->executesql($sql1) as $row) {

            if ($display != $row['Total']) {
                $rank = $cnt;
            }
            echo '<tr>';
            echo '<td class="text-center">' . $rank . '</td>';
            echo '<td>' . ucfirst($row['name']) . '</td>';
            echo '<td>' . strtoupper($row['club']) . '</td>';
            for ($i = 0; $i < count($app); ++$i) {
                if ($row[$app[$i]] > 0) {
                    echo '<td class="text-center">' . $row[$app[$i]] . '</td>';
                } else {
                    echo '<td>&nbsp;</td>';
                }

            }
            echo '<td class="text-center">' . $row['Total'] . '</td>';
            echo '</tr>';
            $cnt++;
            $display = $row['Total'];
            $clubarray[$row['club']]++;
        }
        echo '</tbody></table>';
    } //function end

    public function Tramp_teamchampionship($comm, $tblname, $gender, $category, $eventid, $rowcount, $showcol)
    {
        $club = array();
        $sqlcat = "SELECT distinct club FROM `registrations` WHERE Event =$eventid";
        $i = 0;
        foreach ($comm->executesql($sqlcat) as $row) {
            $club[$i] = $row['club'];
            $i++;
        }

        $sqlqualifier = "SELECT cnt_teamchamp FROM `events` WHERE `Id` = $eventid";
        $qualifiercount = $comm->getvalue($sqlqualifier); //get no. of gymnast to be included from each team for e.g 5 top gymnsts from each team
        $clubname = '';
        $prevclub = '';
        $scoretotal = 0;
        $statearray = array();
        for ($i = 0; $i < count($club); $i++) { //for all state

            $sqlclub = "SELECT `club`, `name`, `total`, `category`,r.gender,eventid FROM `$tblname` s inner join registrations r on r.id=s.reg_id WHERE `category` LIKE '$category' and r.gender='" . $gender . "' AND s.`eventid` = $eventid and club='$club[$i]' order by total desc,club limit $qualifiercount";

            $getrecord = $comm->getvalue($sqlclub);
            $data = array();
            $data['club'] = $club[$i];
            $data['score'] = 0;
            if ($getrecord != '') { //dontinsert duplicate values
                $score = 0;
                foreach ($comm->executesql($sqlclub) as $row) { //fetch data and save it

                    $data['score'] += $row['total'];
                }
                array_push($statearray, $data);
            }
        }

        $i = 0;
        $rank = 1;
        $display = 1;
        $cnt = 1;
        $same = 0;
        $teampenalty = 0;
        $hide = 0;

        echo '<table border="1" style="border-collapse:collapse;width:90%" cellspacing="0" cellpadding="0">
            <thead>
            <tr>
            <th width="70" align="center">Rank</th>
            <th width="150" align="center">State/Unit</th>';

        echo '<th width="60" align="center">Score</th></tr></thead><tbody>';
        //sort on highest score
        $total = array();
        foreach ($statearray as $key => $row) {
            $total[$key] = $row['score'];
        }
        array_multisort($total, SORT_DESC, $statearray);

        foreach ($statearray as $row) {

            $teasmsql = "SELECT penalty FROM state_penalty WHERE eventid='$eventid' and state = '" . $row['club'] . "' and `gender` = '$gender' AND `category` = '$category'";
            $hidesql = "SELECT display FROM state_penalty WHERE eventid='$eventid' and state = '" . $row['club'] . "' and `gender` = '$gender' AND `category` = '$category'";

            $hide = $comm->getvalue($hidesql);
            $teampenalty = $comm->getvalue($teasmsql);
            if ($hide == 0) //will print only if hide is zero
            {
                if ($display == $row['score']) {
                    $same = 1;
                } else {
                    if ($cnt != 1) {
                        $rank++;
                        if ($same == 1) {
                            $rank = $cnt;
                        }

                    }
                }
                echo '<tr>';
                echo '<td align="center">' . $rank . '</td>';
                echo '<td align="center">' . strtoupper($row['club']) . '</td>';
                $finalscore = $row['score'] - $teampenalty;

                echo '<td align="center">' . number_format(round($finalscore, 2), 2) . '</td>';
                $cnt++;
                $display = $finalscore;
                echo '</tr>';
            }

            if ($cnt == $rowcount + 1) {
                break;
            }
            //to show no. of rows
        }
        echo '</tbody></table>';
    } //function end

    public function Tramp_teamchampionship_above16($comm, $tblname, $gender, $category, $eventid, $rowcount, $showcol)
    {
        $club = array();
        $sqlcat = "SELECT distinct club FROM `registrations` WHERE Event =$eventid";
        $i = 0;
        foreach ($comm->executesql($sqlcat) as $row) {
            $club[$i] = $row['club'];
            $i++;
        }

        $sqlqualifier = "SELECT cnt_teamchamp FROM `events` WHERE `Id` = $eventid";
        $qualifiercount = $comm->getvalue($sqlqualifier); //get no. of gymnast to be included from each team for e.g 5 top gymnsts from each team
        $clubname = '';
        $prevclub = '';
        $scoretotal = 0;
        $statearray = array();
        for ($i = 0; $i < count($club); $i++) { //for all state

            $sqlclub = "SELECT reg_id, `name`, `Gender`, `Event`,eventtype,eventid, `club`,sum(`total`)total FROM `$tblname` s INNER join registrations r on s.reg_id=r.id where eventid='" . $eventid . "' and r.gender='" . $gender . "' and category='" . $category . "' and club='$club[$i]' group by reg_id order BY total desc,escore desc,dscore DESC,tof DESC,horizontal DESC limit $qualifiercount";

            $getrecord = $comm->getvalue($sqlclub);
            $data = array();
            $data['club'] = $club[$i];
            $data['score'] = 0;
            if ($getrecord != '') { //dontinsert duplicate values
                $score = 0;
                foreach ($comm->executesql($sqlclub) as $row) { //fetch data and save it

                    $data['score'] += $row['total'];
                }
                array_push($statearray, $data);
            }
        }

        $i = 0;
        $rank = 1;
        $display = 1;
        $cnt = 1;
        $same = 0;
        $teampenalty = 0;
        $hide = 0;

        echo '<table border="1" style="border-collapse:collapse;width:90%" cellspacing="0" cellpadding="0">
            <thead>
            <tr>
            <th width="70" align="center">Rank</th>
            <th width="150" align="center">State/Unit</th>';

        echo '<th width="60" align="center">Score</th></tr></thead><tbody>';
        //sort on highest score
        $total = array();
        foreach ($statearray as $key => $row) {
            $total[$key] = $row['score'];
        }
        array_multisort($total, SORT_DESC, $statearray);

        foreach ($statearray as $row) {

            $teasmsql = "SELECT penalty FROM state_penalty WHERE eventid='$eventid' and state = '" . $row['club'] . "' and `gender` = '$gender' AND `category` = '$category'";
            $hidesql = "SELECT display FROM state_penalty WHERE eventid='$eventid' and state = '" . $row['club'] . "' and `gender` = '$gender' AND `category` = '$category'";

            $hide = $comm->getvalue($hidesql);
            $teampenalty = $comm->getvalue($teasmsql);
            if ($hide == 0) //will print only if hide is zero
            {
                if ($display == $row['score']) {
                    $same = 1;
                } else {
                    if ($cnt != 1) {
                        $rank++;
                        if ($same == 1) {
                            $rank = $cnt;
                        }

                    }
                }
                echo '<tr>';
                echo '<td align="center">' . $rank . '</td>';
                echo '<td align="center">' . strtoupper($row['club']) . '</td>';
                $finalscore = $row['score'] - $teampenalty;

                echo '<td align="center">' . number_format(round($finalscore, 2), 2) . '</td>';
                $cnt++;
                $display = $finalscore;
                echo '</tr>';
            }

            if ($cnt == $rowcount + 1) {
                break;
            }
            //to show no. of rows
        }
        echo '</tbody></table>';
    } //f
    public function individualallaround($comm, $gender, $category, $eventid, $rowcount)
    {
        $app = array();
        $sname = array();
        $rank = 1;
        $display = 1;
        $cnt = 1;
        $sql = "SELECT * FROM `apparatus` where pid!=0 and (gender='" . $gender . "' or gender ='b') and apid in (SELECT distinct apid FROM `gymnast_score` cs inner join registrations o on o.id=cs.reg_id where eventid='" . $eventid . "' and apid !=17 and category='" . $category . "' and gender='" . $gender . "') order by sequence";

        $k = 0;
        foreach ($comm->executesql($sql) as $row) {
            $app[$k] = $row['apid'];
            $sname[$k] = $row['sname'];
            $k++;
        }
        echo '<table border="1" style="border-collapse:collapse" cellspacing="0" cellpadding="0">
            <thead>
                  <tr>
                  <th width="70">Rank</th>
                                  <th width="250">Name</th>
                                  <th width="150">State/Unit</th>';
        // for ($i=0;$i<count($sname);++$i) {
        //                               echo '<th width="60">'.$sname[$i].'</th>';
        //               }
        echo '<th width="60">Points</th></tr></thead><tbody>';
        $sql1 = "SELECT name,category,Gender,club";
        for ($i = 0; $i < count($app); ++$i) {
            $sql1 .= ",sum(case when apid = '" . $app[$i] . "' then s.total else 0 end) as '" . $app[$i] . "'";
        }
        $sql1 .= " ,sum(Total) Total FROM `gymnast_score` s inner join registrations o on o.id=s.reg_id where category='" . $category . "' and s.Eventid='$eventid' and gender='$gender' and apid !=17 GROUP by s.reg_id,name order by Total DESC";

        $nume = 0;
        $nume = $comm->getvalue($sql1);

        foreach ($comm->executesql($sql1) as $row) {

            if ($display != $row['Total']) {
                $rank = $cnt;
            }
            echo '<tr>';
            echo '<td align="center">' . $rank . '</td>';
            echo '<td>' . ucfirst($row['name']) . '</td>';
            echo '<td>' . strtoupper($row['club']) . '</td>';

            // for ($i=0;$i<count($app);++$i) {
            //         if($row[$app[$i]]>0)
            //             echo '<td align="center">'.$row[$app[$i]].'</td>';
            //         else
            //            echo '<td>&nbsp;</td>';
            // }
            echo '<td align="center">' . $row['Total'] . '</td>';
            echo '</tr>';
            $cnt++;
            $display = $row['Total'];
            if ($cnt == $rowcount + 1) {
                break;
            }
            //to show no. of rows
        }

        echo '</tbody></table>';
    } //end function

    public function createapparatusarray($comm, $gender)
    {
        $appname = array();
        $sql = "SELECT * FROM `apparatus` where pid!=0 and (gender='" . $gender . "' or gender ='b') and apid not in (6,17) and IsActive=1 order by sequence";
        $i = 0;
        foreach ($comm->executesql($sql) as $row) {
            $appname[$i] = $row['apid'];
            $i++;
        }
        return $appname;
    }
    //for all around medals page
    public function leaderboard_medalsperstate($participant, $comm, $apid, $apparatus, $gender, $category, $eventid, $limit)
    {
        echo ' <div class="col-md-6">
           <div class="feature-3 text-center text-white"><h4>' . $apparatus . '</h4>';
        echo "<div style='overflow-y: scroll;max-height:260px;'>";
        echo '<table border="1" style="border-bottom: 1px solid #000 !important;" cellspacing="0" cellpadding="0">
                                <thead>
                                <tr style="border-bottom: 1px solid #000 !important;">
                                 <th width="70" class="text-center">Rank</th>
                                  <th width="250" class="text-center">Name</th>
                                  <th width="150" class="text-center">State</th>
                                  <th width="70" class="text-center">Final Score</th>
                                </tr>
                              </thead>
                              <tbody id="pagination">';

        $rank = 1;
        $prevtotal = 1;
        $prevescore = 1;
        $prevdscore = 1;
        $cnt = 1;
        $clubarray = array(); //all clubs in array
        foreach ($participant->Finalclub($eventid, $gender, $category) as $clubrow) {
            $clubarray[$clubrow['club']] = 0;
        }

        $sql1 = "SELECT reg_id,s.*,Category,eventid, r.gender,dob,name,gname,club FROM `gymnast_score` s INNER join registrations r on s.reg_id=r.id left join apparatus a on a.apid=s.apid where eventid='" . $eventid . "' and a.apid=" . $apid . " and r.gender='" . $gender . "' and category='" . $category . "' order BY total desc,subtotal DESC,dscore DESC limit $limit";

        foreach ($comm->executesql($sql1) as $row) {
            if ($clubarray[$row['club']] < 2) {
                if ($prevtotal != $row['total']) {
                    $rank = $cnt;
                } elseif ($prevescore != $row['subtotal']) {
                    //tie breaking situation
                    $rank = $cnt;
                } elseif ($prevdscore != $row['dscore']) {
                    $rank = $cnt;
                } else {
                }
                echo '<tr>';
                echo '<td align="center" class="text-white">' . $rank . '</td>';
                echo '<td class="text-white">' . ucfirst($row['name']) . '</td>';
                echo '<td class="text-white">' . strtoupper($row['club']) . '</td>';
                echo '<td align="center" class="text-white">' . $row['total'] . '</td>';
                $cnt++;
                $prevtotal = $row['total'];
                $prevescore = $row['subtotal'];
                $prevdscore = $row['dscore'];
                echo '</tr>';
                $clubarray[$row['club']]++;
            }
        }

        echo '</tbody>
                                    </table>

                                    </div></div>';
        echo '</div>';
    }
    public function artistic_result($comm, $participant, $gender, $category, $eventid, $limit, $apid)
    {

        $sqlqualifier = "SELECT cnt_qualifier FROM `events` WHERE `Id` = $eventid";
        $qualifiercount = $comm->getvalue($sqlqualifier); //get no. of gymnast to be included from each team for e.g 2 gymnsts from

        $clubarray = array();

        foreach ($participant->getclub_bytable($eventid, $gender, 'gymnast_score', $category) as $clubrow) {
            $clubarray[$clubrow['club']] = 0;
        }
        $rank = 1;
        $display = 1;
        $cnt = 1;
        echo '
                        <tr>

                            <th width="50">Rank</th>
                            <th width="200">Name</th>
                            <th width="250">Club</th>
                            <th width="70">DScore</th>
                            <th width="70">Avg Deduction</th>
                            <th width="70">EScore</th>
                           <th width="70">Penalty</th>
                            <th width="70">Total</th>
                        </tr>
                    </thead> <tbody>';
        foreach ($participant->artistic_generatequalifier($eventid, $gender, 'gymnast_score', $category, $apid) as $row) {
            if ($clubarray[$row['club']] < $qualifiercount) {
                if ($display != $row['total']) {
                    $rank = $cnt;
                }

                echo '<tr>';
                echo '<td align="center">' . $rank . '</td>';

                echo '<td>' . ucfirst($row['name']) . '</td>';
                $disclub = '';
                if ($row['club'] != 'None') {
                    $disclub = ucwords(strtolower($row['club']));
                }

                echo '<td>' . $disclub . '</td>';
                echo '<td align="center">' . $row['dscore'] . '</td>';

                echo '<td align="center">' . $row['avg'] . '</td>';
                echo '<td align="center">' . $row['subtotal'] . '</td>';
                echo '<td align="center">' . $row['penalty'] . '</td>';
                echo '<td align="center">' . $row['total'] . '</td>';
                echo '</tr>';
                $cnt++;
                $display = $row['total'];
                $clubarray[$row['club']]++;
            }
        }
        echo '<tbody>';
        //end elseif
    }

    public function usaigc_leaderboard($comm, $apid, $apparatus, $gender, $category, $level, $eventid, $limit)
    {
        echo ' <div class="col-md-6">
           <div class="feature-3 text-center text-white"><h4>' . $apparatus . '</h4>';
        echo "<div style='overflow-y: scroll;max-height:260px;'>";
        echo '<table border="1" style="border-bottom: 1px solid #000 !important;" cellspacing="0" cellpadding="0">
                                <thead>
                                <tr style="border-bottom: 1px solid #000 !important;">
                                 <th width="70" class="text-center">Rank</th>
                                  <th width="250" class="text-center">Name</th>
                                  <th width="150" class="text-center">Club</th>
                                  <th width="70" class="text-center">Final Score</th>
                                </tr>
                              </thead>
                              <tbody id="pagination">';

        $rank = 1;
        $prevtotal = 1;
        $prevescore = 1;
        $prevdscore = 1;
        $cnt = 1;
        $sql1 = "SELECT reg_id,s.*,Category,eventid, r.gender,dob,name,gname,club FROM `gymnast_score` s INNER join registrations r on s.reg_id=r.id left join apparatus a on a.apid=s.apid where eventid='" . $eventid . "' and a.apid=" . $apid . " and r.gender='" . $gender . "' and r.level='" . $level . "' and category='" . $category . "' order BY total desc,subtotal DESC,dscore DESC limit $limit";

        foreach ($comm->executesql($sql1) as $row) {

            if ($prevtotal != $row['total']) {
                $rank = $cnt;
            } elseif ($prevescore != $row['subtotal']) {
                //tie breaking situation
                $rank = $cnt;
            } elseif ($prevdscore != $row['dscore']) {
                $rank = $cnt;
            } else {
            }
            echo '<tr>';
            echo '<td align="center" class="text-white">' . $rank . '</td>';
            echo '<td class="text-white">' . ucfirst($row['name']) . '</td>';
            echo '<td class="text-white">' . strtoupper($row['club']) . '</td>';
            echo '<td align="center" class="text-white">' . $row['total'] . '</td>';
            $cnt++;
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
    public function tramp_leaderboard($comm, $gender, $category, $eventid, $limit)
    { //max-height:260px;
        echo ' <div class="col-md-12">
           <div class="feature-3 text-center text-white">';
        echo "<div>";
        echo '<table border="1" style="border-collapse:collapse" cellspacing="0" cellpadding="0">
                                <thead>
                                <tr>
                                 <th width="70" class="text-center">Rank</th>
                                  <th width="250" class="text-center">Name</th>
                                  <th width="120" class="text-center">Unit</th>
                                  <th width="120" class="text-center">D Score</th>
                                  <th width="120" class="text-center">E Score</th>
                                  <th width="120" class="text-center">Horizontal</th>
                                  <th width="120" class="text-center">TOF</th>
                                  <th width="120" class="text-center">Penalty</th>
                                  <th width="100" class="text-center">Final Score</th>
                                </tr>
                              </thead>
                              <tbody id="pagination">';

        $rank = 1;
        $prevtotal = 1;
        $prevescore = 1;
        $prevdscore = 1;
        $cnt = 1;
        $sql1 = "SELECT reg_id,s.*,Category,eventid, r.gender,dob,name,club FROM `trampoline_score` s INNER join registrations r on s.reg_id=r.id where eventid='" . $eventid . "' and r.gender='" . $gender . "' and category='" . $category . "' order BY total desc,escore desc,dscore DESC,tof DESC,horizontal DESC limit $limit";

        foreach ($comm->executesql($sql1) as $row) {

            if ($prevtotal != $row['total']) {
                $rank = $cnt;
            } elseif ($prevescore != $row['escore']) {
                //tie breaking situation
                $rank = $cnt;
            } elseif ($prevdscore != $row['horizontal']) {
                $rank = $cnt;
            } else {
            }
            echo '<tr>';
            echo '<td align="center" class="text-white">' . $rank . '</td>';
            echo '<td class="text-white">' . ucfirst($row['name']) . '</td>';
            $disclub = '';
            if ($row['club'] != 'None') {
                $disclub = ucwords(substr($row['club'], 0, 22));
            }

            echo '<td class="text-white">' . $disclub . '</td>';
            echo '<td align="center" class="text-white">' . $row['dscore'] . '</td>';
            echo '<td align="center" class="text-white">' . $row['escore'] . '</td>';
            echo '<td align="center" class="text-white">' . $row['horizontal'] . '</td>';
            echo '<td align="center" class="text-white">' . $row['tof'] . '</td>';
            echo '<td align="center" class="text-white">' . $row['penalty'] . '</td>';
            echo '<td align="center" class="text-white">' . $row['total'] . '</td>';
            $cnt++;
            $prevtotal = $row['total'];
            $prevescore = $row['escore'];
            $prevdscore = $row['horizontal'];
            echo '</tr>';
        }
        echo '</tbody>
                                    </table>

                                    </div></div>'; //
        echo '</div>';
    }

    public function tramp_leaderboard_Above16($comm, $gender, $category, $eventid, $limit)
    {
        echo ' <div class="col-md-12">
           <div class="feature-3 text-center text-white">';
        echo "<div style='overflow-y: scroll;'>";
        echo '<table border="1" style="border-collapse:collapse" cellspacing="0" cellpadding="0">
                                <thead>
                                <tr>
                                 <th width="70" class="text-center">Rank</th>
                                  <th width="250" class="text-center">Name</th>
                                  <th width="120" class="text-center">Unit</th>
                                  <th width="120" class="text-center">D Score</th>
                                  <th width="120" class="text-center">E Score</th>
                                  <th width="120" class="text-center">Horizontal</th>
                                  <th width="120" class="text-center">TOF</th>
                                  <th width="120" class="text-center">Penalty</th>
                                  <th width="100" class="text-center">Final Score</th>
                                </tr>
                              </thead>
                              <tbody id="pagination">';

        $rank = 1;
        $prevtotal = 1;
        $prevescore = 1;
        $prevdscore = 1;
        $cnt = 1;
        $sql1 = "SELECT reg_id, `name`, `Gender`, `Event`,eventtype, `club`,sum(s.dscore)dscore,sum(s.escore)escore,sum(`horizontal`)horizontal,sum(`tof`)tof,sum(`penalty`)penalty,sum(`total`)total FROM `trampoline_score` s INNER join registrations r on s.reg_id=r.id where eventid='" . $eventid . "' and r.gender='" . $gender . "' and category='" . $category . "' group by reg_id order BY total desc,escore desc,dscore DESC,tof DESC,horizontal DESC limit $limit";

        foreach ($comm->executesql($sql1) as $row) {

            if ($prevtotal != $row['total']) {
                $rank = $cnt;
            } elseif ($prevescore != $row['escore']) {
                //tie breaking situation
                $rank = $cnt;
            } elseif ($prevdscore != $row['horizontal']) {
                $rank = $cnt;
            } else {
            }
            echo '<tr>';
            echo '<td align="center" class="text-white">' . $rank . '</td>';
            echo '<td class="text-white">' . ucfirst($row['name']) . '</td>';
            echo '<td class="text-white">' . strtoupper($row['club']) . '</td>';
            echo '<td align="center" class="text-white">' . $row['dscore'] . '</td>';
            echo '<td align="center" class="text-white">' . $row['escore'] . '</td>';
            echo '<td align="center" class="text-white">' . $row['horizontal'] . '</td>';
            echo '<td align="center" class="text-white">' . $row['tof'] . '</td>';
            echo '<td align="center" class="text-white">' . $row['penalty'] . '</td>';
            echo '<td align="center" class="text-white">' . $row['total'] . '</td>';
            $cnt++;
            $prevtotal = $row['total'];
            $prevescore = $row['escore'];
            $prevdscore = $row['horizontal'];
            echo '</tr>';
        }
        echo '</tbody>
                                    </table>

                                    </div></div>'; //
        echo '</div>';
    }

    public function tump_leaderboard($comm, $gender, $category, $eventid, $limit)
    {
        echo ' <div class="col-md-12">
           <div class="feature-3 text-center text-white">';
        // echo "<div style='overflow-y: scroll;max-height:260px;'>";
        echo '<table border="1" style="border-collapse:collapse" cellspacing="0" cellpadding="0">
                                <thead>
                                <tr>
                                 <th width="70" class="text-center">Rank</th>
                                  <th width="250" class="text-center">Name</th>
                                  <th width="120" class="text-center">Unit</th>
                                  <th width="120" class="text-center">Twisting</th>
                                  <th width="120" class="text-center">Salto</th>
                                  <th width="100" class="text-center">Final Score</th>
                                </tr>
                              </thead>
                              <tbody id="pagination">';

        $rank = 1;
        $prevtotal = 1;
        $prevescore = 1;
        $prevdscore = 1;
        $cnt = 1;
        // $sql1="SELECT reg_id,s.*,Category,eventid, r.gender,dob,name,club FROM `tumbling_score` s INNER join registrations r on s.reg_id=r.id where eventid='".$eventid."' and r.gender='".$gender."' and category='".$category."' order BY total desc,escore desc,dscore DESC limit $limit";
        $sql1 = " SELECT name,club,reg_id,Category,eventid,sum(CASE WHEN apname ='Twisting' THEN total ELSE 0  END  ) 'Twisting',sum(CASE WHEN apname ='Salto' THEN total ELSE 0  END  ) 'Salto',sum(total) 'Total'  FROM `tumbling_score` s INNER join registrations r on s.reg_id=r.id where eventid='" . $eventid . "' and r.gender='" . $gender . "' and category='" . $category . "'  group by reg_id order BY total DESC  limit $limit";

        foreach ($comm->executesql($sql1) as $row) {

            if ($prevtotal != $row['Total']) {
                $rank = $cnt;
            }
            // elseif( $prevescore!=$row['escore'])
            // {
            //   //tie breaking situation
            //    $rank=$cnt;
            // }
            else {
            }
            echo '<tr>';
            echo '<td align="center" class="text-white">' . $rank . '</td>';
            echo '<td class="text-white">' . ucfirst($row['name']) . '</td>';
            echo '<td class="text-white">' . strtoupper($row['club']) . '</td>';
            // echo '<td align="center" class="text-white">'. $row['dscore'] . '</td>';
            // echo '<td align="center" class="text-white">'. $row['escore'] . '</td>';
            // echo '<td align="center" class="text-white">'. $row['penalty'] . '</td>';
            echo '<td align="center" class="text-white">' . $row['Twisting'] . '</td>';
            echo '<td align="center" class="text-white">' . $row['Salto'] . '</td>';
            echo '<td align="center" class="text-white">' . $row['Total'] . '</td>';
            $cnt++;
            $prevtotal = $row['Total'];
            //$prevescore=$row['escore'];
            echo '</tr>';
        }
        echo '</tbody>
                                    </table>

                                    </div>'; //</div>
        echo '</div>';
    }
    public function Tumb_teamchampionship($comm, $tblname, $gender, $category, $eventid, $rowcount, $showcol)
    {
        $club = array();
        $sqlcat = "SELECT distinct club FROM `registrations` WHERE Event =$eventid";
        $i = 0;
        foreach ($comm->executesql($sqlcat) as $row) {
            $club[$i] = $row['club'];
            $i++;
        }

        $sqlqualifier = "SELECT cnt_teamchamp FROM `events` WHERE `Id` = $eventid";
        $qualifiercount = $comm->getvalue($sqlqualifier); //get no. of gymnast to be included from each team for e.g 5 top gymnsts from each team
        $clubname = '';
        $prevclub = '';
        $scoretotal = 0;
        $statearray = array();
        for ($i = 0; $i < count($club); $i++) { //for all state

            //$sqlclub="SELECT `club`, `name`, `total`, `category`,r.gender,eventid FROM `$tblname` s inner join registrations r on r.id=s.reg_id WHERE `category` LIKE '$category' and r.gender='".$gender."' AND s.`eventid` = $eventid and club='$club[$i]' order by total desc,club limit $qualifiercount";

            $sqlclub = "Select club,sum(total) Total,category,gender,eventid from (Select * from (SELECT `club`, `name`, `total`, `category`,r.gender,eventid FROM `tumbling_score` s inner join registrations r on r.id=s.reg_id WHERE  `category` LIKE '$category' and r.gender='" . $gender . "' AND s.`eventid` = $eventid and club='$club[$i]' and apname='Twisting' order by total desc,club limit $qualifiercount) a Union
Select * from (SELECT `club`, `name`, `total`, `category`,r.gender,eventid FROM `tumbling_score` s inner join registrations r on r.id=s.reg_id WHERE  `category` LIKE '$category' and r.gender='" . $gender . "' AND s.`eventid` = $eventid and club='$club[$i]' and apname='Salto' order by total desc,club limit $qualifiercount)b) c group by club";
            //print_r($sqlclub);
            $getrecord = $comm->getvalue($sqlclub);
            $data = array();
            $data['club'] = $club[$i];
            $data['score'] = 0;
            if ($getrecord != '') { //dontinsert duplicate values
                $score = 0;
                foreach ($comm->executesql($sqlclub) as $row) { //fetch data and save it

                    $data['score'] = $row['Total'];
                }
                array_push($statearray, $data);
            }
        }

        $i = 0;
        $rank = 1;
        $display = 1;
        $cnt = 1;
        $same = 0;
        $teampenalty = 0;
        $hide = 0;

        echo '<table border="1" style="border-collapse:collapse;width:90%" cellspacing="0" cellpadding="0">
            <thead>
            <tr>
            <th width="70"  align="center">Rank</th>
            <th width="150"  align="center">State/Unit</th>';

        echo '<th width="60"  align="center">Score</th></tr></thead><tbody>';
        //sort on highest score
        $total = array();
        foreach ($statearray as $key => $row) {
            $total[$key] = $row['score'];
        }
        array_multisort($total, SORT_DESC, $statearray);
        foreach ($statearray as $row) {

            $teasmsql = "SELECT penalty FROM state_penalty WHERE eventid='$eventid' and state = '" . $row['club'] . "' and `gender` = '$gender' AND `category` = '$category'";
            $hidesql = "SELECT display FROM state_penalty WHERE eventid='$eventid' and state = '" . $row['club'] . "' and `gender` = '$gender' AND `category` = '$category'";

            $hide = $comm->getvalue($hidesql);
            $teampenalty = $comm->getvalue($teasmsql);
            if ($hide == 0) //will print only if hide is zero
            {
                if ($display == $row['score']) {
                    $same = 1;
                } else {
                    if ($cnt != 1) {
                        $rank++;
                        if ($same == 1) {
                            $rank = $cnt;
                        }

                    }
                }
                echo '<tr>';
                echo '<td align="center">' . $rank . '</td>';
                echo '<td align="center">' . strtoupper($row['club']) . '</td>';
                $finalscore = $row['score'] - $teampenalty;

                echo '<td align="center">' . number_format(round($finalscore, 2), 2) . '</td>';
                $cnt++;
                $display = $finalscore;
                echo '</tr>';
            }

            if ($cnt == $rowcount + 1) {
                break;
            }
            //to show no. of rows
        }
        echo '</tbody></table>';
    } //function end

}
?>