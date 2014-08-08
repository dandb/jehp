<?php

    $params = parse_ini_file('config');
    $allValues = array_values($params);
    $USER = $allValues[0];
    $PASSWORD = $allValues[1];
    $SITE = $allValues[2];

    $URL = "https://$USER:$PASSWORD@$SITE/api/xml";

    if($xml = file_get_contents($URL))
    {
        if(!file_exists('results/status.xml'))
        {
            mkdir('results', 0777, true);
        }
        file_put_contents('results/status.xml' , $xml);
    }    
    else
    {
        exit('Could not get xml');
    }


    $finalPage = 'json/allJobs.json';
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
                } elseif ($color == 'red_anime') {
                    $status = "run_fail";
                    break;
                } elseif ($color == 'aborted_anime') {
                    $status = "run_bail";
                    break;
                } elseif ($color == 'aborted') {
                    $status = "bailed";
                    break;
                } elseif ($color == 'blue_anime') {
                    $status = "run_pass";
                    break;
                } elseif ($color == 'blue') {
                    $status = "passed";
                    break;
                } elseif ($color == 'notbuilt') {
                    $status = "notbuilt";
                    break;
		} elseif ($color == 'disabled') {
                    $status = "disabledbuild";
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

    $groups = "";
    $groups .= "\"groups\": [\n";

    $jobList = "";
    $jobList = "\"jobs\": [\n";

    $curKey = '';
    $pass = false;
    $count = 1;

    foreach($grpList as $key => $block) {
        $mainStat="passed";
        $subcount = 1;
        foreach($block as $index => $value) {
            $valStat = get_job_status($jobs,$value);
            $jobList .= "\t\t{\"name\": \"" . $value . "\", \"status\": \"" . $valStat . "\", \"group\": \"" . $key . "\", \"subtabid\": \"". $count . $subcount .  "\"},\n";
            if ($curKey != $key) {
                $curKey = $key;
            }
            
            switch($valStat){
                case "run_pass":
                    if ($mainStat=="passed") {
                        $mainStat = $valStat;
                    }
                case "notbuilt":
                    if ($mainStat=="passed" || $mainStat=="run_pass") {
                        $mainStat=$valStat;
                    }
                case "disabledbuild":
                    if ($mainStat=="passed" || $mainStat=="run_pass") {
                        $mainStat=$valStat;
                    } 
                case "bailed":
                    if ($mainStat=="passed" || $mainStat=="run_pass" || $mainStat=="notbuilt" || $mainStat=="disabled") {
                        $mainStat=$valStat;
                    }
                case "run_bail":
                    if ($mainStat=="passed" || $mainStat=="run_pass" || $mainStat=="notbuilt" || $mainStat=="disabled" || $mainStat=="bailed") {
                        $mainStat=$valStat;
                    }
                case "unstable":
                    if ($mainStat=="passed" || $mainStat=="run_pass" || $mainStat=="notbuilt" || $mainStat=="disabled" || $mainStat=="bailed" || $mainStat=="run_bail") {
                        $mainStat=$valStat;
                    }
                case "run_fail":
                    if ($mainStat=="passed" || $mainStat=="run_pass" || $mainStat=="notbuilt" || $mainStat=="disabled" || $mainStat=="bailed" || $mainStat=="run_bail" || $mainStat=="unstable") {
                        $mainStat=$valStat;
                    }
                case "failed":
                    $mainStat=$valStat;
                default:
            }

            $subcount = $subcount + 1;
        }

        $groups .= "\t\t{\"name\": \"" . $key . "\", \"status\": \"" . $mainStat . "\", \"count\": \"" . $count . "\"},\n";
        $count = $count + 1;
    }
    $groups = substr($groups, 0, -2); //cut off extra new line and comma at the end
    $jobList = substr($jobList, 0, -2); //cut off extra new line and comma at the end
    $groups .= "\n\t]\n";
    $jobList .= "\n\t]\n";
    $mainJson = "";
    $mainJson .= "{\n\t" . $groups . ",\n\t" . $jobList . "}\n";
    file_put_contents($finalPage,$mainJson);

    //header('Content-Type: application/json');
    //echo $mainJson;


?>
