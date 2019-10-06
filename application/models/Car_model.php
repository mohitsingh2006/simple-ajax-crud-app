<?php

Class Car_model extends CI_model{

    public function create($formArray)
    {
        $this->db->insert('car_models', $formArray);
        return $id = $this->db->insert_id();
    }    

    // This method will return all records from car_models table
    public function all(){
        $result = $this->db
                    ->order_by('id','ASC')
                    ->get('car_models')
                    ->result_array();
        // SELECT * FROM car_models order by id ASC
        return $result;
    }

    function getRow($id){
        $this->db->where('id',$id);
        $row = $this->db->get('car_models')->row_array();
        // SELECT * FROM car_models WHERE id = $id
        return $row;
    }

    function update($id,$formArray) {
        $this->db->where('id',$id);
        $this->db->update('car_models',$formArray);
        return $id;
    }

    function delete($id) {
        $this->db->where('id',$id);
        $this->db->delete('car_models');
    }
}
?>