<?php

class Personas_Recuperar extends Wax_Document_Body_Element
{
    public function __construct( Standard8 $Standard8, $mail = '' )
    {
        parent::__construct('div');

        $SID = Standard8_Session::getSID();

        if ( !( empty($SID) ) )
        {
            throw new Wax_Exception(
                'User already sign in.'
            );
        }

        $form = Wax_Document::$body->createForm()
             ->showMessages(true)
             ->appendMessage('mail', __('CorreoElectronicoEsObligatorio') )
             ->appendText('mail', __('CorreoElectronico'), $mail, 60, true )
             ->appendSubmit('submit', __('RecuperarClave') );

        $this->appendChild( $Standard8->createTitleNav( __('RecuperarClave') ) )
             ->appendChild( $form );

        $Standard8->appendLocation( __('Ingresar'), 'Personas_Ingresar', 'Standard8' )
                  ->appendLocation( __('RecuperarClave'), 'Personas_Recuperar', 'Standard8', true )
                  ->appendLocation( __('SolicitarInvitacion'), 'Personas_Invitacion', 'Standard8', true )
                  ->appendLocation( __('RecuperarClave'), $this );
                  //->appendSidebarNav( __('Ingresar'), 'Personas_Ingresar', 'Standard8');
    }

    public function __onSubmit( $mail )
    {
        if ( !( empty($mail) ) )
        {
            $mail = strtolower($mail);

            $userData = Wax_Db::select()
                ->from( TABLE_PERSONAS, array('id_personas', 'usuario', 'correo') )
                ->where('correo', $mail)
                ->query()->fetchRow();

            if ( sizeof($userData) == 0 )
            {
                Wax_Messages::getInstance()
                    ->add( __('CorreoInexistente', Standard8_Uri::createUri('Personas_Invitacion', 'Standard8') ) );

                return array(false, 'Personas_Recuperar_' . $mail);
            }
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