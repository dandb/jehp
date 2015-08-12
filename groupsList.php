<?php
class GroupsList {   
    private $groupRed = "";
    private $groupYellow = "";
    private $groupGreen = "";
    private $groups = "\"groups\": [\n";
    private $groupGreenOnly = "\"groupGreenOnly\": [\n";
    private $groupFailOnly = "\"groupFailOnly\": [\n";
    private $statusArray = array("passed", "run_pass", "notbuilt", "disabledbuild", "bailed", "run_bail", "unstable", "run_unstable", "failed", "run_fail");
    
    public function setJobListings($key, $status, $count) {
        return "\t\t{\"name\": \"" . $key . "\", \"status\": \"" . $status . "\", \"count\": \"" . $count . "\"},\n";
    }

    public function assignWorstJobStatus($worstJobStatus) {
        switch($worstJobStatus) {
            case 2:
            case 3:
            case 4:
                $worstJobStatus=5;
                break;
            case 6:
                $worstJobStatus=7;
                break;
            case 8:
                $worstJobStatus=9;
                break;
        }
    }

    public function groupGreenOnly() {
        $this->groupGreenOnly = substr($this->groupGreenOnly, 0, -2);
        $this->groupGreenOnly .= "\n\t]\n";
        return $this->groupGreenOnly;
    }

    public function groupFailOnly() {
        if(strlen($this->groupYellow)==0) { //To display red group first //changed
            $this->groupRed = substr($this->groupRed, 0, -2);
            $this->groupFailOnly .= $this->groupRed;
        } else {
            $this->groupYellow = substr($this->groupYellow, 0, -2);
            $this->groupFailOnly .= $this->groupRed . $this->groupYellow;
        }
         $this->groupFailOnly .= "\n\t]\n";
         return $this->groupFailOnly;
    }

    public function groupsListings() {
        $count = 1;
        $isAJobRunning = false;
        $worstJobStatus = 0;
        $jobgrp = new JobsGroups;
        $grpList = $jobgrp->getJenkinsGrpLst();
        $jobs = $jobgrp->getStatusXml();
        $culp = new Culprit;

        foreach($grpList as $key => $block) {
        $subcount = 1;
        $isAJobRunning = false;
        $worstJobStatus = 0;
        $curJobStatus = 0;

            foreach($block as $index => $value) {
                $valStat = $jobgrp->getJobStatus($jobs,$value);

            switch($valStat){
                case "passed":
                    $curJobStatus = 0;
                    break;
                case "run_pass":
                    $curJobStatus = 1;
                    $isAJobRunning = true;
                    break;
                case "notbuilt":
                    $curJobStatus = 2;
                    break;
                case "disabledbuild":
                    $curJobStatus = 3;
                    break;
                case "bailed":
                    $curJobStatus = 4;
                    break;
                case "run_bail":
                    $curJobStatus = 5;
                    $isAJobRunning = true;
                    break;
                case "unstable":
                    $curJobStatus = 6;
                    break;
                case "run_unstable":
                    $curJobStatus = 7;
                    $isAJobRunning = true;
                    break;
                case "failed":
                    $curJobStatus = 8;
                    break;
                case "run_fail":
                    $curJobStatus = 9;
                    $isAJobRunning = true;
                    break;
                default:
            }

            if($curJobStatus > $worstJobStatus) {
                $worstJobStatus = $curJobStatus;
            }
            $subcount = $subcount + 1;
        }

        if($isAJobRunning) {
            $this->assignWorstJobStatus($worstJobStatus);
        }
    
        switch($this->statusArray[$worstJobStatus]) {
            case "failed":
            case "run_fail": 
                $this->groupRed .= $this->setJobListings($key, $this->statusArray[$worstJobStatus], $count);
                break;
            case "passed": 
            case "run_pass": 
                $this->groupGreenOnly .= $this->setJobListings($key, $this->statusArray[$worstJobStatus], $count);
                break;
            default: 
                $this->groupYellow .= $this->setJobListings($key, $this->statusArray[$worstJobStatus], $count);
                break;
        } 
        $count = $count + 1;
        } 
    }
}
?>