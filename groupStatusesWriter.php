<?php
include ("jenkinsConfig.php");
include ("culprit.php");
include ("jobsList.php");
include ("groupsList.php");
include ("jobsGroups.php");
include ("ArduinoConnection.php");

$conf = new JenkinsConfig;
$conf->writeToStatusXml();

$jobLst = new JobsList; 
$jobLst->jobListings();

$groupsLst = new GroupsList;
$groupsLst->groupsListings();

$different = false;
$oldAllJobs = json_decode(file_get_contents('json/arduinoOldJobs.json',true), true);//gets the info from file
$allJobs = json_decode(file_get_contents('json/allJobs.json',true), true);//gets the info from the file
$jobs = $allJobs['jobs'];
$oldJobs = $oldAllJobs['jobs'];
for($newJobPos = 0; $newJobPos < count($jobs); $newJobPos++){//figures out any differences
    $newJ = $jobs[$newJobPos]; //what it is
    $newJobSubtabid = $newJ['subtabid'];
    $oldJobPos = 0;
    for($oldJobPos = 0; $oldJobPos < count($oldJobs); $oldJobPos++){//figures out which line has the same subtabid
        $oj = $oldJobs[$oldJobPos];
        if($oj['subtabid'] == $newJobSubtabid){
            break;
        }
    }

    $oldJ = $oldJobs[$oldJobPos]; //what it was
    if($oldJ['group'] != $newJ['group'] || $oldJ['name'] != $newJ['name'] || $oldJ['culprit'] != $newJ['culprit'] || $oldJ['status'] != $newJ['status']){ //if they are different
        $ac = new ArduinoConnection; //connection to arduino
        $sendMess = $ac->constructMessage($jobs[$newJobPos]);
        $ac->sendMessage($sendMess);//sends the message
        $different = true;
    }
}
if($different){//there is a difference between the two
    file_put_contents('json/arduinoOldJobs.json',json_encode($allJobs)); //replace the old with the new
}

$mainJson = "";
$mainJson .= "{\n\t" . $groupsLst->groupFailOnly() . ",\n\t" . $groupsLst->groupGreenOnly() . ",\n\t" . $jobLst->jobConcat() . "}\n";
file_put_contents('json/allJobs.json',$mainJson);
?>

