<?php
require_once "ConnDatabase.php";
$sql = new ConnDatabase();

class ImportCSV{
    private $file;
    private $file_type;
    private $file_name;
    private $error;
    private $data;

    public function setInputFile($InputFile, $removeHeader = true)
    {
        $this->file_type = $InputFile['type'];
        $this->file_name = $InputFile['tmp_name'];
        if(!$this->validateFile()){
            return false;
        }
        $this->execute();
        if($removeHeader){
            $this->removeHeader();
        }
        $this->clearData();
        return true;
    }

    /**
     * @return mixed
     */
    public function getResult()
    {
        return $this->data;
    }

    public function save($save_data) {
        echo '<pre>';
        print_r("Lista que está sendo passada para o inserto do banco");
          print_r($save_data);
        echo '</pre>';
        foreach($save_data as $value) {
            $sql->save("insert into pessoa (nome, email, proffisao) values ('" . $value[0] . "', '" . $value[1] . "','" . $value[2] . "');");
        };
    }

    /**
     * @return int
     */
    public function getColumnCount()
    {
        return (!empty($this->data) ? sizeof($this->data) : 0);
    }

    /**
     * @return int
     */
    public function getRowCount()
    {
        return count($this->data);
    }

    /**
     * Remove Header -> File CSV
     */
    private function removeHeader()
    {
        unset($this->data[0]);
        sort($this->data);
    }

    /**
     * Read the file CSV and set data for array $this->data
     */
    private function execute()
    {
        $this->file = fopen($this->file_name, 'r');
        $this->data = [];
        while (!feof($this->file)){
            $row = fgets($this->file, 1024);
            $this->data[] =  array_map('trim', explode(';', $row));
        }
    }

    /**
     * Validate input file -> allowed only CSV
     * @return bool
     */
    private function validateFile()
    {
        if(empty($this->file_name) || $this->file_type != "text/csv"){
            $this->file_name = null;
            $this->error = "Invalid File";
            return false;
        }
        return true;
    }

    /**
     * Removes completely empty lines
     */
    private function clearData()
    {
        $columns = $this->getColumnCount();
        $row = 0;
        foreach($this->data as $value){
           $empty_line = true;
           for($i=0;$i<=$columns;$i++){
               if(!empty($value[$i])){
                   $empty_line = false;
               }else{
                   $empty_line = true;
               }
           }
           
           if($empty_line === true){
               unset($this->data[$row]);
               sort($this->data);
           }
           $row++;
        }
    }
    

}

