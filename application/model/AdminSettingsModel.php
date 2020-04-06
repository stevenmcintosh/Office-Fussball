<?php

/**
 * load the admin settings
 */
class AdminSettingsModel {

    public $id;
    public $var;
    public $name;
    public $description;
    public $area_name;
    public $active;
    public $value;
    public $locked;
    public $last_updated;
    public $updated_by_user_id;
    public $errors = array();

    function __construct($db) {
        try {
            $this->db = $db;
        } catch (PDOException $e) {
            exit('Database connection could not be established.');
        }
    }

    public function load($id) {
        if ($id != 0) {
            $sql = "SELECT AC.id, AC.var, AA.area_name, AC.name, AC.description, AC.active, AC.value, AC.locked, AC.last_updated, AC.updated_by_user_id as updated_by_user_id FROM admin_controls AC JOIN admin_area AA ON AC.area_id = AA.id
	    	WHERE AC.id = :id";
            $query = $this->db->prepare($sql);
            $query->execute(array(':id' => $id));
            $adminSetting = $query->fetchAll();
            $this->loadObjVarsFromSingle($adminSetting[0]);
        } else {
            $this->id = 0;
        }
        return $this;
    }

    private function loadObjVarsFromSingle($adminSetting) {
        foreach ($adminSetting as $var => $value) {
            $var = lcfirst($var);
            $this->$var = $value;
        }
        $userModel = new UserModel($this->db);
        $this->user = $userModel->load($this->updated_by_user_id);
    }

    public function getSettingType() {
        //$type['array'] = '';
        
        switch ($this->var) {
            case 'NUM_TEAMS_PROMOTED':
            case 'NUM_TEAMS_RELEGATED':
                $type['array'] = array('0' => '0', '1' => '1', '2' => '2', '3' => '3', '4' => '4', '5' => '5');
                break;
            case 'HOME_PAGE_RECENT_RESULTS':
            case 'SESSION_TIMEOUT_IN_SECONDS':
            case 'FIRST_TO_GOALS':
            case 'SITE_NAME':
                $type['text'] = '';
                break;
            default: $type['array-active-only'] = Helper::ActiveArray();
                break;
        }

        return $type;
    }

    public function saveAdminSetting() {
        $returnVal = false;
        if ($this->validate()) {

            $sql = "UPDATE admin_controls SET "
                    . "value = :value, "
                    . "active = :active "
                    . "WHERE id = :id";
            $query = $this->db->prepare($sql);
            $sql_array = array(':value' => $this->value, ':active' => $this->active, ':id' => $this->id);
            $query->execute($sql_array);
            $returnVal = true;
        }
        return $returnVal;
    }

    public function define_vars() {
        $sql = "SELECT * FROM admin_controls AC join admin_area AA ON AC.area_id = AA.id ORDER BY AA.area_name ASC";
        $query = $this->db->prepare($sql);
        $query->execute();
        foreach ($query->fetchAll() as $key => $val) {
            if (!defined($val->var)) {
                define($val->var, $val->value);
            }
            if (!defined($val->var. '_ACTIVE')) {
                define($val->var . '_ACTIVE', $val->active);
            }
        }
    }

    public function getAllAdminSettings() {
        $sql = "SELECT AC.id, AC.var, AC.name, AC.description, AA.area_name as area_name, AC.active, AC.value, AC.locked, AC.last_updated, AC.updated_by_user_id as updated_by_user_id FROM admin_controls AC JOIN admin_area AA ON AC.area_id = AA.id";
        $query = $this->db->prepare($sql);
        $query->execute();
        foreach ($query->fetchAll() as $key => $val) {
            $adminSettingsModel = new AdminSettingsModel($this->db);
            $this->adminSettings[$val->area_name][] = $adminSettingsModel->load($val->id);
        }


        //print_r($this->adminSettings);

        return $this->adminSettings;
    }

    private function validate() {
        $returnval = true;

        //print_r($this);
        if ($this->value == '') {
            $this->errors['feedback']['value'] = $this->value . ' can not have an empty value';
        }

        if ($this->var == 'SESSION_TIMEOUT_IN_SECONDS' && !is_numeric($this->value)) {
            $this->errors['feedback']['SESSION_TIMEOUT_IN_SECONDS'] = 'The number of timeout seconds must be a number';
        }

        if ($this->var == 'HOME_PAGE_RECENT_RESULTS' && !is_numeric($this->value)) {
            $this->errors['feedback']['HOME_PAGE_RECENT_RESULTS'] = 'The number of Homepage results must be a number';
        }


        if (count($this->errors) > 0) {
            $returnval = false;
        }
        return $returnval;
    }

}
