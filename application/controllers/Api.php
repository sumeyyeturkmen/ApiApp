<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Api extends CI_Controller {

	private $result= [];

	public function __construct()
    {
        parent::__construct();
        $this->load->model('Api_model');
        header('Content-Type:application/json;charset:utf8');
        $this->output->set_header("Access-Control-Allow-Origin: *");
        $this->result['status'] = false;
        error_reporting(E_ALL);
    }

    public function index()
    {
        $year = $this->input->get('year', TRUE);
        $year = (int)$year;

        $types = $this->input->get('types', TRUE);

        if($year <= 0) {
            $this->result['message'] = 'Yıl(year) parametresi zorunludur!';
            print_r(json_encode($this->result));
            return;
        } else if($types == NULL || empty($types)) {
            $this->result['message'] = 'Tür(types) parametresi zorunludur!';
            print_r(json_encode($this->result));
            return;
        }else {
            $allType = $this->Api_model->getAll('types', []);
            $types = explode(',', $types);
            $paramType = [];
            $i = 1;
            for ($c = 0; $c < count($types); $c++) {
                $paramType[$i] = $types[$i-1];
                $i++;
            }

            $dbType = [];
            foreach ($allType as $allType) {
                $dbType[$allType['id']] = $allType['type_name'];
            }
            $likeParam = [
                'movies.name' => $year
            ];
            $typeIds = [];
            foreach ($dbType as $key => $value) {
                for ($j = 1; $j <= count($paramType); $j++) {
                    if ($value == $paramType[$j]) {
                        array_push($typeIds, $key);
                    }
                }
            }

            if (empty($typeIds) || $typeIds == NULL || count($typeIds) <= 0) {
                $this->result['message'] = 'Tür(types) parametresi geçersiz bir türü ifade ediyor!';
                print_r(json_encode($this->result));
                return;
            } else {
                $columnName = 'type_id';
                $data = $this->Api_model->getAllMovies([],$columnName,array_values($typeIds), $likeParam);
                $this->result['status'] = true;
                $this->result['data'] = $data;
            }
        }
        print_r(json_encode($this->result['data']));
    }
}
