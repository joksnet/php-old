<?php

class Personas_Ingresar extends Wax_Document_Body_Form
{
    public function __construct( Standard8 $Standard8, $username = '' )
    {
        parent::__construct();

        $SID = Standard8_Session::getSID();

        if ( !( empty($SID) ) )
        {
            throw new Wax_Exception(
                'User already sign in.'
            );
        }

        $messages = Wax_Messages::getInstance();

        foreach ( $messages as $message )
            $this->appendMessage(null, $message, true);

        $messages->clear();

        $this->showMessages()
             ->appendMessage('username', __('UsuarioEsObligatorio') )
             ->appendMessage('password', __('ClaveEsObligatorio') )
             ->appendText('username', __('Nombre'), $username, 32, true)
             ->appendPassword('password', __('Clave'), 32, true)
             ->appendSubmit('submit', __('Ingresar') )
             ->setId('login');

        $Standard8->appendLocation( __('Ingresar'), $this )
                  ->appendLocation( __('RecuperarClave'), 'Personas_Recuperar', 'Standard8', true )
                  ->appendLocation( __('SolicitarInvitacion'), 'Personas_Invitacion', 'Standard8', true );
                //->appendSidebarNav( __('RecuperarClave'), 'Personas_Recuperar', 'Standard8');
    }

    public function __onSubmit( $username, $password )
    {
        if ( !( empty($username) || empty($password) ) )
        {
            $userData = Wax_Db::select()
                ->from( TABLE_PERSONAS, array(
                    'id_personas'
                  , 'usuario'
                  , 'clave')
                )
                ->where('usuario', $username)
                ->query()->fetchRow();

            if ( sizeof($userData) == 0 )
            {
                Wax_Messages::getInstance()
                    ->add( __('UsuarioInexistente', Standard8_Uri::createUri('Personas_Invitacion', 'Standard8') ) );

                return array(false, 'Personas_Ingresar_' . $username);
            }

            if ( md5($password) != $userData['clave'] )
            {
                Wax_Messages::getInstance()
                    ->add( __('ClaveNoValida', Standard8_Uri::createUri('Personas_Recuperar', 'Standard8') ) );

                return array(false, 'Personas_Ingresar_' . $username);
            }

            $idSessiones = Standard8_Session::begin( $userData['id_personas'] );
        }
        else
        {
            Wax_Messages::getInstance()
                ->add( __('CamposIncompletos') );

            return false;
        }

        return true;
    }
}