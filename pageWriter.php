<?php
    $finalPage = 'quick_health.html';
    $grpList = parse_ini_file('groups.ini');

    if (file_exists('results/status.xml')) {
        $jobs = simplexml_load_file('results/status.xml');
    } else {
        exit('Failed to open results/status.xml');
    }

    function get_job_status($results, $jobName){
        $status = "";
        foreach ($results as $jobInfo):
            $name=$jobInfo->name;
            $color=$jobInfo->color;
            if ($jobName == $name) {
                if ($color == 'red') {
                    $status = "failed";
                    break;
                } elseif ($color == 'red_anim' || $color == 'red_anime') {
                    $status = "run_fail";
                    break;
                } elseif ($color == 'aborted_anim' || $color == 'aborted_anime') {
                    $status = "run_bail";
                    break;
                } elseif ($color == 'aborted') {
                    $status = "bailed";
                    break;
                } elseif ($color == 'blue_anim' || $color == 'blue_anime') {
                    $status = "run_pass";
                    break;
                } elseif ($color == 'blue') {
                    $status = "passed";
                    break;
                } elseif ($color == 'notbuilt') {
                    $status = "notbuilt";
                    break;
		} elseif ($color == 'disabled') {
                    $status = "disabled";
                    break;
		} elseif ($color == 'yellow') {
                    $status = "unstable";
                    break;
                } 
		
            }
        endforeach;
        if ( $status=="" ) {
            $status = "not_found";
        }
        return $status;
    }

    $pageBody = "<html> \n";
    $pageBody .= "<head> \n";
    $pageBody .= "<style type=\"text/css\">\n";
    $pageBody .= "body {background-color:000000;}\n";
    $pageBody .= "td {padding:5px 5px 5px 5px;color:DDDDDD;text-shadow: 1px 1px 2px #000000;}\n";
    $pageBody .= ".passed {background-color:#00FF00;}\n";
    $pageBody .= ".failed {background-color:#FF0000;}\n";
    $pageBody .= ".bailed {background-color:#C0C0C0;}\n";
    $pageBody .= ".notbuilt {background-color:#D3D3D3;}\n";
    $pageBody .= ".disabled {background-color:#D3D3D3;}\n";
    $pageBody .= ".unstable {background-color:#FFFF00;}\n";
    $pageBody .= ".not_found {background-color:#FF7700;}\n";
    $pageBody .= ".run_pass {background-color:#00FF00;animation: blink 1s steps(5, start) infinite;";
    $pageBody .= "-webkit-animation: blink 1s steps(5, start) infinite;}\n";
    $pageBody .= ".run_fail {background-color:#FF0000;animation: blink 1s steps(5, start) infinite;";
    $pageBody .= "-webkit-animation: blink 1s steps(5, start) infinite;}\n";
    $pageBody .= ".run_bail {background-color:#C0C0C0;animation: blink 1s steps(5, start) infinite;";
    $pageBody .= "-webkit-animation: blink 1s steps(5, start) infinite;}\n";
    $pageBody .= "@keyframes blink { to { visibility: hidden; }}\n";
    $pageBody .= "@-webkit-keyframes blink { to { visibility: hidden; }}\n";
    $pageBody .= "</style>\n";
    $pageBody .= "</head>\n";
    $pageBody .= "<body>\n";
    $pageBody .= "<table>\n";

    $curKey = '';
    $pass = false;

    foreach($grpList as $key => $block) {
        $pageBody .= "<tr>\n";
        $mainStat="passed";
        foreach($block as $index => $value) {
            $valStat = get_job_status($jobs,$value);
            if ($curKey != $key) {
                $curKey = $key;
            }
            if ($valStat == "not_found") {
                $pageBody .= "<td id=" . $key . " class=not_found>" . $value . "</td>\n";
            } else {
                switch($valStat){
                    case "run_pass":
                        if ($mainStat=="passed") {
                            $mainStat = $valStat;
                        }
                    case "bailed":
                        if ($mainStat=="passed" || $mainStat=="run_pass") {
                            $mainStat=$valStat;
                        }
                    case "run_bail":
                        if ($mainStat=="passed" || $mainStat=="run_pass" || $mainStat=="bailed") {
                            $mainStat=$valStat;
                        }
                    case "run_fail":
                        if ($mainStat=="passed" || $mainStat=="run_pass" || $mainStat=="bailed" || $mainStat=="run_bail") {
                            $mainStat=$valStat;
                        }
                    case "failed":
                        $mainStat=$valStat;
                    //case "notbuilt":
                    //    if ($mainStat=="passed" || $mainStat=="run_pass" || $mainStat=="bailed" || $mainStat=="run_bail") {
                    //        $mainStat=$valStat;
                    //    }
                    //case "disabled":
                    //    if ($mainStat=="passed" || $mainStat=="run_pass" || $mainStat=="bailed" || $mainStat=="run_bail") {
                    //        $mainStat=$valStat;
                    //    }
                    case "unstable":
                        if ($mainStat=="passed" || $mainStat=="run_pass" || $mainStat=="bailed" || $mainStat=="run_bail") {
                            $mainStat=$valStat;
                        }
                }
            }
        }
        $pageBody .= "</tr>\n";
        $pageBody .= "<td id=" . $key . " class=" . $mainStat . ">" . $key . "</td>\n";
    }

    $pageBody .= "</tr>\n";
    $pageBody .= "</table>\n";
    $pageBody .= "</body>\n";
    $pageBody .= "</html>";
    file_put_contents($finalPage,$pageBody);
?>
