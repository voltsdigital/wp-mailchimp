<?php

class ControllerMailChimpOptions{
    protected $options;

    public function __construct() {
        $this->options = get_option( 'mailchimp_options' );
        add_action( 'init' , array( $this ,'init' ), 15 );
        add_filter( 'mco_get_api_key' , array( $this ,'getApiKey' ) );
        add_filter( 'mco_get_list_id' , array( $this ,'getListId' ) );
    }

    public function init(){
        $this->createMailChimpOptions();
    }

    public function getApiKey(){
        if( isset( $this->options[ 'mailchimp_api_key' ] ) && $this->options[ 'mailchimp_api_key' ] != '' ){
            return $this->options[ 'mailchimp_api_key' ];
        }
        return '';
    }

    public function getListId(){
        if( isset( $this->options[ 'mailchimp_list' ] ) && $this->options[ 'mailchimp_list' ] != '' ){
            return $this->options[ 'mailchimp_list' ];
        }
        return '';
    }

    public function createMailChimpOptions(){
        $options = new Odin_Theme_Options(
            'mailchimp_options', // Slug/ID da página (Obrigatório)
            'Opções do MailChimp', // Titulo da página (Obrigatório
            'read'
        );

        $options->set_tabs(
            array(
                array(
                    'id'    => 'mailchimp_options',
                    'title' => 'Opções',
                ),
            )
        );

        $lists = apply_filters( 'mc_get_lists' , '' );
        $lists = apply_filters( 'array_to_select' , $lists, 'id' , 'name' );

        $options->set_fields(
            array(
                'options' => array(
                    'tab'   => 'mailchimp_options',
                    'title' => 'Opções do MailChimp',
                    'fields' => array(
                        // Future
                        // array(
                        //     'id' => 'mailchimp_is_enable',
                        //     'label' => 'Habilitar',
                        //     'type' => 'checkbox',
                        //     'default'     => '',
                        //     'description' => ''
                        // ),
                        array(
                            'id' => 'mailchimp_api_key',
                            'label' => 'Api Key',
                            'type' => 'text',
                        ),
                        array(
                            'id' => 'mailchimp_list',
                            'label' => 'Lista',
                            'type' => 'select',
                            'options' => $lists
                        )
                    )
                ),
            )
        );
    }
}

new ControllerMailChimpOptions;
?>