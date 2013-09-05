<?php

class Personas_Invitacion extends Wax_Document_Body_Element
{
    public function __construct( Standard8 $Standard8 )
    {
        parent::__construct('div');

        $SID = Standard8_Session::getSID();

        if ( !( empty($SID) ) )
        {
            throw new Wax_Exception(
                'User already sign in.'
            );
        }

        $form = Wax_Document::$body->createForm('/');
        $messages = Wax_Messages::getInstance();

        foreach ( $messages as $message )
            $form->appendMessage('a', $message);

        $messages->clear();

        $this->appendChild( $Standard8->createTitleNav( __('SolicitarInvitacion') ) )
             ->appendChild( $form );

        $Standard8->appendLocation( __('Ingresar'), 'Personas_Ingresar', 'Standard8' )
                  ->appendLocation( __('RecuperarClave'), 'Personas_Recuperar', 'Standard8', true )
                  ->appendLocation( __('SolicitarInvitacion'), 'Personas_Invitacion', 'Standard8', true )
                  ->appendLocation( __('SolicitarInvitacion'), $this );
                  //->appendSidebarNav( __('Ingresar'), 'Personas_Ingresar', 'Standard8');
    }
}