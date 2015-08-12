<?php
class JenkinsConfig {
    public function getValues() {
        return array_values(parse_ini_file('config'));
    }

    public function getUser() {
        return $this->getValues()[0];
    }

    public function getPass() {
        return $this->getValues()[1];
    }

    public function getSite() {
        return $this->getValues()[2];
    }

    public function getUrl() {
        return "https://".$this->getUser().":".$this->getPass()."@".$this->getSite()."/api/xml";
    }

    public function writeToStatusXml() {
        $url = $this->getUrl();
        if($xml = file_get_contents($this->getUrl())) {
            if(!file_exists('results/status.xml')) {
                mkdir('results', 0664, true);
            }
            file_put_contents('results/status.xml' , $xml);
        } else {
            exit('Could not get xml');
        }
    }
}
?>