<?php 
use \Firebase\JWT\JWT;

class Controller_Listas extends Controller_Base 
{   
    public function post_create()
    {
        try {
            if ( ! isset($_POST['nameList'])) 
            {
                $json = $this->response(array(
                    'code' => 400,
                    'message' => 'parametro incorrecto, se necesita que el parametro nombre de lista'
                ));

                return $json;
            }

            $input = $_POST;
            $idUsuarioEnTKN = self::checkToken();
            $list = new Model_List();
            $list->nameList = $input['nameList'];
            $list->id_user = $idUsuarioEnTKN;
            $list->save();

            $json = $this->response(array(
                'code' => 201,
                'message' => 'Cancion creada',
                'name' => $input['nameList'],
                'idCreador' => $idUsuarioEnTKN
            ));

            return $json;

        } 
        catch (Exception $e) 
        {
            $json = $this->response(array(
                'code' => 500,
                'message' => 'error interno del servidor',
            ));

            return $json;
        }        
    }

    public function post_modify()
    {
        //try {
            if ( ! isset($_POST['nameSong'])) 
            {
                $json = $this->response(array(
                    'code' => 400,
                    'message' => 'parametro incorrecto, se necesita que el parametro se llame name'
                ));

                return $json;
            }

            if ( ! isset($_POST['urlSong'])) 
            {
                $json = $this->response(array(
                    'code' => 400,
                    'message' => 'parametro incorrecto, se necesita que el parametro url'
                ));

                return $json;
            }

            if ( ! isset($_POST['idsong'])) 
            {
                $json = $this->response(array(
                    'code' => 400,
                    'message' => 'parametro incorrecto, se necesita que el parametro id'
                ));

                return $json;
            }

            $input = $_POST;
            $song = Model_Cancion::find($input['idsong']);
            $song->nameSong = $input['nameSong'];
            $song->urlSong = $input['urlSong'];
            $song->save();

            $json = $this->response(array(
                'code' => 200,
                'message' => 'cancion modificada',
                'name' => $song->nameSong
            ));

            return $json;      
    }

    public function get_lists()
    {
        $idUser = self::checkToken();
        $list = Model_List::find('all', ['where' => ['id_user' =>$idUser]]);

        $json = $this->response(array(
            $list,
        ));

        return $json;  
    }

    public function post_delete()
    {
        $list = Model_List::find($_POST['id']);
        $list->delete();

        $json = $this->response(array(
            'code' => 200,
            'message' => 'lista borrada',
            'name' => $list
        ));

        return $json;
    }
}
