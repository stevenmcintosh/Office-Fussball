<?php 

    class DemoMode {
        /** This will reset the database to the demo mode. 
     * Typically run by a cron job at regual intervals. 
     * Should never be run on production
     */
        public function resetDatabaseToDemoMode() {
            
           
            if (file_exists('../application/config/database_connection.php')) {
                include '../application/config/database_connection.php';
            } else {
                exit('You must manually create a database_connection.php file. See the instructions on the GIT. <br />
                https://github.com/stevenmcintosh/Office-Fussball');
            }

            // Open DB Connection
            $options = array(PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ, PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING);


            try {
                $db = new PDO('mysql:host=' . $hostname . ';
                dbname=' . $dbname . ';
                charset=' . $dbcharset, $dbuser , $dbpass, $options);
            } catch (PDOException $e) {
                exit('Database connection could not be established. Error message = ' . $e);
            }

            if (!file_exists('../demoMode/empty_database.sql')) {
                exit('Missing File : ../demoMode/empty_database.sql');
            }

            $sqlFileToExecute = '../demoMode/empty_database.sql';

            // read the sql file
            $f = fopen($sqlFileToExecute,"r+");
            $sqlFile = fread($f, filesize($sqlFileToExecute));
            $sqlArray = explode(';',$sqlFile);
            foreach ($sqlArray as $stmt) {
                
                if (strlen($stmt)>3 && substr(ltrim($stmt),0,2)!='/*') {
                    
                    $query = $db->prepare($stmt);
                    $query->execute();
                }
            }
            
            $stmt = file_get_contents('../sql/fsbl.sql');
            $query = $db->prepare($stmt);

            if ($query->execute()) {
                echo "DB tables re-created and populated - Success";
            } else { 
                echo "Oh crap. DB tables we're not re-created and populated - Something went wrong. Check file " . __FILE__  . ".";
            }
        }
    }


    $reset = new DemoMode();
    $reset->resetDatabaseToDemoMode();