<?php
class SportsbookModel
{
    public $csvFile = "../public/import/sportsbook.csv";
    public $maxCells = 0;

    public $errors = array();

    public function getDataFromCsv()
    {
        $data = array();
        $f = fopen($this->csvFile, "r");
        $row = 0;
        while (($line = fgetcsv($f)) !== false) {
            $row++;
            foreach ($line as $cell) {
                $data[$row][] = htmlspecialchars($cell);
            }
        }
        fclose($f);
        return $data;
    }

    /**
     * sets the maximum number of cells for any line
     * in a csv file
     */
    public function setMaxCellsForCsv() {
            $data = array();
            $f = fopen($this->csvFile, "r");
            $row = 0;
            while (($line = fgetcsv($f)) !== false) {
                $row++;
                foreach ($line as $cell) {
                    $data[$row][] = '';

                    if (count($data[$row]) > $this->maxCells) {
                        $this->maxCells = count($data[$row]);
                    }
                }
            }
            $this->maxCells--;
            fclose($f);
    }

    private function doesCsvFileExist() {
        $returnVal = true;
        if (!file_exists($this->csvFile)) {
            $returnVal = false;
        }
        return $returnVal;
    }

    public function  validate() {
        $returnVal = true;

        if(!$this->doesCsvFileExist())
        {
            $this->errors['feedback']['fileExist'] = "The CSV file '".$this->csvFile ."'' does not exist";
        }

        if(count($this->errors) > 0) {
            $returnVal = false;
        }
        return $returnVal;

    }
}
