<?php 

use Firebase\JWT\JWT;

class Controller_Users extends Controller_Rest
{
    private $key = '53jDgdTf5efGH54efef978';

    
    public function post_create()
    {
        
            
    }

    public function get_listas()
    {
    	
    }

    private function authorization($token)
    {

        $decoded = JWT::decode($token, $this->key, array('HS256'));

        $userId = $decoded->id;

        $users = Model_users::find('all', array(
                'where' => array(
                    array('id', $userId)
                ),
        ));

        if ($users != null) {
            return true;
        }
        else 
        {
           return false; 
        }
    }


    public function post_delete()
    {
        
    }

    public function post_edit()
    {
        
    }
}