<?php 

use Firebase\JWT\JWT;

class Controller_Lists extends Controller_Rest
{
    private $key = '53jDgdTf5efGH54efef978';

    
    public function post_create()
    {
        try {
            $token = apache_request_headers()['Authorization'];

            if ($this->authorization($token) == true){
               
                $decoded = JWT::decode($token, $this->key, array('HS256'));
                $id = $decoded->id;

                if ( ! isset($_POST['titulo']) or
                    $_POST['titulo'] == "") 
                {
                $json = $this->response(array(
                    'code' => 401,
                    'message' => 'parametros incorrectos/Los campos no pueden estar vacios'
                ));

                return $json;
                }

                //Validar lista no existe
                $list = Model_lists::find('all', array(
                    'where' => array(
                        array('titulo', $_POST['titulo']),
                    ),
                ));

                if (! empty($list)) {
                   $json = $this->response(array(
                       'code' => 403,
                       'message' => 'Ya existe la lista con este titulo',
                   ));
                  return $json;
                }

                //crear lista
                $input = $_POST;
                $list = new Model_Lists();
                $list->titulo = $input['titulo'];
                $list->id_usuario = $id;
                $list->save();

                $json = $this->response(array(
                    'code' => 201,
                   'message' => 'lista creada'
                ));
                return $json;
            }
            else
            {
                $json = $this->response(array(
                    'code' => 401,
                    'message' => 'Token incorrecto, no tienes permiso'
                ));

                return $json;
            }
        } 
        catch (Exception $e) 
        {
            $json = $this->response(array(
                'code' => 502,
                'message' => $e->getMessage(),
            ));

            return $json;
        }

            
    }

    public function get_listas()
    {
        try {
            $token = apache_request_headers()['Authorization'];

            if ($this->authorization($token) == true){
               
                $decoded = JWT::decode($token, $this->key, array('HS256'));
                $id = $decoded->id;

                $lists = Model_Lists::find('all', array(
                'where' => array(
                    array('id_usuario', $id)
                    ),
                ));

            return $this->response(Arr::reindex($lists));

            }
            else
            {
                $json = $this->response(array(
                    'code' => 401,
                    'message' => 'Token incorrecto, no tienes permiso'
                ));

                return $json;
            }
        } 
        catch (Exception $e) 
        {
            $json = $this->response(array(
                'code' => 502,
                'message' => $e->getMessage(),
            ));

            return $json;
        }
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
        try
        {
            $token = apache_request_headers()['Authorization'];

            if ($this->authorization($token) == true){
               
                $decoded = JWT::decode($token, $this->key, array('HS256'));
                $id = $decoded->id;

                $list = Model_Lists::find('first', array(
                'where' => array(
                    array('id', $_POST['id'])
                    ),
                ));


                if ( ! isset($_POST['id']) or
                 $_POST['id'] == "") 
                {
                   $json = $this->response(array(
                        'code' => 401,
                        'message' => 'parametros incorrectos/Los campos no pueden estar vacios'
                    ));

                    return $json; 
                }
                else
                {
                    if($list->id_usuario == $id){
                        $list->delete();
                        $json = $this->response(array(
                            'code' => 201,
                            'message' => 'Lista borrada'
                        ));
                        return $json;
                    } else {
                        $json = $this->response(array(
                    'code' => 403,
                    'message' => 'No tienes permiso para borrar listas de otros usuarios'
                ));

                return $json;
                    }
                }
            }
            else
            {
                $json = $this->response(array(
                    'code' => 402,
                    'message' => 'Token incorrecto, no tienes permiso'
                ));

                return $json;
            }
        } 
        catch (Exception $e) 
        {
            $json = $this->response(array(
                'code' => 501,
                'message' => $e->getMessage(),
            ));

            return $json;
        }
    }

    public function post_edit()
    {
        try
        {
            $token = apache_request_headers()['Authorization'];

            if ($this->authorization($token) == true){
               
                $decoded = JWT::decode($token, $this->key, array('HS256'));
                $id = $decoded->id;

                $list = Model_Lists::find('first', array(
                'where' => array(
                    array('id', $_POST['id'])
                    ),
                ));


                if ( ! isset($_POST['id']) or
                 ! isset($_POST['titulo']) or
                 $_POST['id'] == "" or
                 $_POST['titulo'] == "") 
                {
                   $json = $this->response(array(
                        'code' => 401,
                        'message' => 'parametros incorrectos/Los campos no pueden estar vacios'
                    ));

                    return $json; 
                }
                else
                {
                    if($list->id_usuario == $id){
                        $list->titulo = $_POST['titulo'];
                        $list->save();
                        $json = $this->response(array(
                            'code' => 201,
                            'message' => 'Titulo cambiado'
                        ));
                        return $json;
                    } else {
                        $json = $this->response(array(
                    'code' => 403,
                    'message' => 'No tienes permiso para modificar listas de otros usuarios'
                ));

                return $json;
                    }
                }
            }
            else
            {
                $json = $this->response(array(
                    'code' => 402,
                    'message' => 'Token incorrecto, no tienes permiso'
                ));

                return $json;
            }
        } 
        catch (Exception $e) 
        {
            $json = $this->response(array(
                'code' => 501,
                'message' => $e->getMessage(),
            ));

            return $json;
        }
    }
}