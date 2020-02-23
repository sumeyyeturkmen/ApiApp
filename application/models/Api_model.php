<?php


class Api_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getAll($from ,array $where = array()) {
        return $this->db->where($where)->get($from)->result_array();
    }

    public function getSingleData($from, array $where = array()) {
        return $this->db->where($where)->get($from)->row_array();
    }

    public function getAllMovies(array $where = array(), $columnName = NULL, array $columnParams = array(), array $likeParam = array()) {
        $this->db->select('movies.id');
        $this->db->select('movies.name');
        $this->db->select('types.id as type_id');
        $this->db->select('types.type_name');
        $this->db->from('movies');
        $this->db->join('film_type', 'movies.id = film_type.film_id');
        $this->db->join('types', 'film_type.type_id = types.id');
        $this->db->where($where);
        if($columnName != NULL) {
            $this->db->where_in($columnName, $columnParams);
        }
        $this->db->like($likeParam);
        return $this->db->get()->result_array();
    }
}