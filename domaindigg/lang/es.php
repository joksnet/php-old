<?php

$charset = 'utf-8';
$lang = array(

    /**
     * Welcome
     */
    'Home' => 'Principal',
    'Welcome' => 'Bienvenidos!',

    /**
     * Sign Up
     */
    'SignUp' => 'Registrarse',
    'SignUpTitle' => 'Registrarse',
    'SignUpSubtitle' => 'Crear una nueva cuenta',
    'SignUpDescription' => 'Por favor, rellene el siguiente formulario para crear su cuenta.',
    'SignUpInfo' => 'Al hacer clic en <em>Registrarse</em>, usted confirma que tiene m�s de 13 a�os de edad y acepta los #0T�rminos del servicio#.',
    'SignUpButton' => 'Registrarse �',

    /**
     * Sign In
     */
    'SignIn' => 'Identificarse',
    'SignInTitle' => 'Identificarse',
    'SignInSubtitle' => 'Ingresar en Domain Digg',
    'SignInButton' => 'Ingresar �',
    'SignInSignUp' => '� No tiene una cuenta ? Registrese',
    'SignInRestore' => '� Olvid� su clave ?',

    /**
     * Restore
     */
    'RestoreTitle' => 'Recuperar clave',
    'RestoreSubtitle' => 'Recuperar clave',
    'RestoreButton' => 'Recuperar �',

    /**
     * Sign Out
     */
    'SignOut' => 'Salir',

    /**
     * Terms of service
     */
    'TOS' => 'T�rminos del servicio',

    /**
     * About
     */
    'About' => 'Acerca',

    /**
     * Contact
     */
    'Contact' => 'Contacto',

    /**
     * Captcha
     */
    'CaptchaAlt' => '',
    'CaptchaDescription' => 'Complete las letras que ve en la imagen superior.',

    /**
     * TLDs
     */
    'TLDs' => 'Dominios de nivel superior',
    'TLDsEmpty' => 'No existen dominios de nivel superior, #0sugiera# uno.',
    'TLDsSuggest' => 'Sugerir dominio de nivel superior',
    'TLDsSuggestInfo' => 'Si quiere puede #0sugerirnos# un dominio de nivel superior.',
    'TLDsSuggetExample' => 'Ej. <em>es</em>, <em>fr</em>, <em>com.ar</em>',
    'TLDsSuggestSave' => 'Sugerir',

    /**
     * Errors
     */
    'ErrorInfo' => 'Se generaron los siguientes errores:',
    'ErrorCaptcha' => 'El c�digo de seguridad no corresponde con la imagen. Vuelva a intentarlo.',
    'ErrorEmailAddress' => 'La direcci�n de correo electr�nico ingresada es no v�lida.',
    'ErrorPassword' => 'La clave que ingres� es muy corta. La longitud m�mima es de 6 caracteres.',
    'ErrorPasswordAgain' => 'Las claves que ingres� no concuerdan.',
    'ErrorSignIn' => 'Correo electr�nico o contrase�a no v�lida.',
    'ErrorName' => 'El nombre que ingres� es muy corto. La longitud m�mima es de 3 caracteres.',
    'ErrorDescription' => 'La descripci�n que ingres� es muy larga. La longitud m�xima es de 140 caracteres.',
    'ErrorMessage' => 'El mensaje que ingres� es muy largo. La longitud m�xima es de 140 caracteres.',
    'ErrorDomain' => 'Ingrese un dominio v�lido. Este puede contener letras y puntos �nicamente.',
    'ErrorDomainExists' => 'El dominio ingresado ya existe.',

    /**
     * Forms
     */
    'EmailAddress' => 'Correo Electr�nico',
    'Password' => 'Clave',
    'PasswordAgain' => 'Repetir Clave',

    'Name' => 'Nombre',
    'Description' => 'Descripci�n',
    'Message' => 'Mensaje',
    'Domain' => 'Dominio',

    'OrCancel' => '� #0Cancelar#',

    'Access' => 'Accesos',
    'Edit' => 'Editar',
    'Delete' => 'Eliminar',

    'Previous' => 'Anterior',
    'Next' => 'Siguiente',

    /**
     * Aplication
     */
    'ViewAll' => 'Ver listado completo',

    'Projects' => 'Proyectos',
    'ProjectsTitle' => 'Proyectos',
    'ProjectsEmpty' => 'No existen proyectos creados. Haga clic #0aqu�# para agregar uno nuevo.',

    'ProjectNamesEmpty' => 'No existen nombres sugeridos para <em>$0</em>, puede #1sugerir# uno.',
    'ProjectNamesEmptyOwner' => 'No existen nombres, puede agregar uno haciendo clic #0aqu�#. Si quiere puede #1invitar# amigos a participar.',

    'ProjectAdd' => 'Agregar proyecto',
    'ProjectSave' => 'Guardar proyecto',
    'ProjectSuggest' => 'Sugerir',

    'ProjectNotFound' => 'Proyecto no encontrado.',
    'ProjectAccessDenied' => 'No tiene permisos para acceder a este proyecto.',

    'ProjectsAddTitle' => 'Proyectos: Agregar',
    'ProjectsAddSubtitle' => 'Agregar proyecto',
    'ProjectsAddDescription' => 'Posiblemente si est� utilizando este servicio no conozca el nombre todav�a. Puede completar este campo con lo que desee: el c�digo interno del proyecto, una palabra que lo resuma, etc.',

    'ProjectsEditTitle' => 'Proyectos: Editar',
    'ProjectsEditSubtitle' => 'Editar <em>$0</em>',

    'ProjectsDeleteTitle' => 'Proyectos: Eliminar',
    'ProjectsDeleteSubtitle' => 'Eliminar <em>$0</em>',
    'ProjectsDeleteConfirm' => 'Confirmar eliminar',

    'ProjectsAccessTitle' => 'Proyectos: Accesos',
    'ProjectsAccessSubtitle' => 'Accesos a <em>$0</em>',
    'ProjectsAccessEmpty' => 'No existen permisos creados. Utilice el formulario debajo para invitar a alguien a su proyecto.',
    'ProjectsAccessInvited' => 'Correo electr�nico inexistente; se envi� una invitaci�n.',
    'ProjectsAccessAdd' => 'Permitir acceso',
    'ProjectsAccessAddDescription' => 'Ingrese la direcci�n de correo electr�nico que quiera brindarle acceso a su proyecto. Si no se encuentra registrado, se le enviar� una invitaci�n.',

    'ProjectsAccessNotices' => 'Tiene invitaciones nuevas.',
    'ProjectsAccessNoticesDissmiss' => 'Ocultar alerta',

    'ProjectsEditTLDs' => 'Dominios de nivel superior',
    'ProjectsEditTLDsDescription' => 'Se chequear� autom�ticamente la disponibilidad de los dominios propuestos para este proyecto. Si el dominio de nivel superior no se encuentra entre los siguientes puede #0sugerir# uno.',
    'ProjectsEditTLDsEmpty' => 'No existen dominios de nivel superior, #0sugiera# uno.',

    /**
     * Footer
     */
    'Copyright' => 'Copyright � 2009. Todos los derechos reservados'
);