<?php

class ControllerMailChimp{


    protected $mc;
    protected $error;
    protected $apiKey;

    public function __construct(){
        require_once FUNCTIONS_DIR . '/lib/mailchimp/Mailchimp.php';
        //add_action( 'init' , array( $this, 'init' ) , 10 );
        add_action( 'mc_subscribe_member' , array( $this, 'subscribeMember' ) , 10, 2 );
        add_filter( 'mc_get_lists' , array( $this , 'getLists' ) );
    }

    public function connect(){

        $apiKey = apply_filters( 'mco_get_api_key' , '' );

        if( $apiKey == '' ){
            return;
        }


        $this->mc = new Mailchimp( $apiKey ); //your api key here

        try {
            $lists = $this->mc->lists->getList();
        }
        catch( \Mailchimp_Invalid_ApiKey $e ) {
            if( !isset( $lists) || $lists[ 'status' ] == 'error' ){
                $this->mc = null;
                $this->error = 'Erro de conexão ou Chave da Api inválida';
            }
        }
    }

    public function getLists(){
        $this->connect();

        if( !$this->mc ){
            return array( array( 'id' => 0 , 'name' => $this->error ) );
        }

        $result = $this->mc->lists->getList();
        if( $result[ 'total'] > 0 ){
            return $result[ 'data' ];
        }

        return null;
    }

    public function getMembers( $list_id ){
        $this->connect();
        return $this->mc->lists->members( $list_id );
    }

    public function subscribeMember( $email, $listaId = null ){
        $this->connect();

        if( !$listaId ){
            $listaId = apply_filters( 'mco_get_list_id' , '' );
        }

        $emails = array( 'email' => $email );

        try{
            $result = $this->mc->lists->subscribe( $listaId , $emails );
            return $result;
        }
        catch( Exception $e ){
            return false;
        }
    }
}

new ControllerMailChimp();