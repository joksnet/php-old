<?php dhTheme('header'); ?>

<div class="breadcrumb"><a href="<?php echo dhUrl('index'); ?>"><?php echo dhLang('Principal'); ?></a> &raquo; <a href="<?php echo dhUrl('register'); ?>"><?php echo dhLang('Registrese'); ?></a></div>
<div id="tabs-wrapper" class="clear-block">
  <h2 class="with-tabs"><?php echo dhLang('Registrese'); ?></h2>
  <ul class="tabs primary">
    <li class="active"><a href="<?php echo dhUrl('register'); ?>"><?php echo dhLang('Registrese'); ?></a></li>
    <li><a href="<?php echo dhUrl('login'); ?>"><?php echo dhLang('Ingresar'); ?></a></li>
    <li><a href="<?php echo dhUrl('lostpassword'); ?>"><?php echo dhLang('Recuperar Clave'); ?></a></li>
  </ul>
</div>
<?php if ( dhNValid() ) : ?>
<div class="messages error">
  <ul>
<?php if ( dhNValid('fullname') ) : ?>
    <li><?php echo dhLang('Ingrese su nombre y apellido.'); ?></li>
<?php endif; ?>
<?php if ( dhNValid('name') ) : ?>
    <li><?php echo dhLang('Elija un nombre de usuario.'); ?></li>
<?php endif; ?>
<?php if ( dhNValid('pass') ) : ?>
    <li><?php echo dhLang('Complete su clave.'); ?></li>
<?php endif; ?>
<?php if ( dhNValid('pass2.required') ) : ?>
    <li><?php echo dhLang('Vuelva a repetir la clave.'); ?></li>
<?php endif; ?>
<?php if ( dhNValid('pass2.equal') ) : ?>
    <li><?php echo dhLang('Las claves no concuerdan.'); ?></li>
<?php endif; ?>
<?php if ( dhNValid('question') ) : ?>
    <li><?php echo dhLang('Genere una pregunta secreta.'); ?></li>
<?php endif; ?>
<?php if ( dhNValid('anwser') ) : ?>
    <li><?php echo dhLang('Responda la pregunta.'); ?></li>
<?php endif; ?>
  </ul>
</div>
<?php endif; ?>
<div class="clear-block">
  <form action="<?php echo dhUrl('register'); ?>" method="post">
    <div>
      <div id="edit-fullname-wrapper" class="form-item">
        <label for="edit-fullname"><?php echo dhLang('Nombre Completo:'); ?><span class="form-required" title="<?php echo dhLang('Este campo es requerido.'); ?>">*</span></label>
        <input type="text" id="edit-fullname" name="fullname" class="form-text required<?php echo ( dhNValid('fullname') ) ? ' error' : ''; ?>" maxlength="90" size="60" value="<?php echo dhPost('fullname'); ?>" />
        <div class="description"><?php echo dhLang('Ingrese su nombre y apellido.'); ?></div>
      </div>
      <div id="edit-name-wrapper" class="form-item">
        <label for="edit-name"><?php echo dhLang('Nombre de Usuario:'); ?><span class="form-required" title="<?php echo dhLang('Este campo es requerido.'); ?>">*</span></label>
        <input type="text" id="edit-name" name="name" class="form-text required<?php echo ( dhNValid('name') ) ? ' error' : ''; ?>" maxlength="60" size="60" value="<?php echo dhPost('name'); ?>" />
        <div class="description"><?php echo dhLang('No use espacios; signos de puntuación no estan permitidos a excepción de puntos y guiones bajos.'); ?></div>
      </div>
      <fieldset>
        <legend><?php echo dhLang('Clave'); ?></legend>
        <div id="edit-pass-wrapper" class="form-item">
          <label for="edit-pass"><?php echo dhLang('Clave:'); ?><span class="form-required" title="<?php echo dhLang('Este campo es requerido.'); ?>">*</span></label>
          <input type="password" id="edit-pass" name="pass" class="form-text required<?php echo ( dhNValid('pass') ) ? ' error' : ''; ?>" maxlength="128" size="60" />
          <div class="description"><?php echo dhLang('Ingrese la clave relacionada a su nombre de usuario.'); ?></div>
        </div>
        <div id="edit-pass2-wrapper" class="form-item">
          <label for="edit-pass2"><?php echo dhLang('Repetir Clave:'); ?><span class="form-required" title="<?php echo dhLang('Este campo es requerido.'); ?>">*</span></label>
          <input type="password" id="edit-pass2" name="pass2" class="form-text required<?php echo ( dhNValid('pass2') ) ? ' error' : ''; ?>" maxlength="128" size="60" />
          <div class="description"><?php echo dhLang('Repita su clave.'); ?></div>
        </div>
      </fieldset>
      <div id="edit-question-wrapper" class="form-item">
        <label for="edit-question"><?php echo dhLang('Pregunta:'); ?><span class="form-required" title="<?php echo dhLang('Este campo es requerido.'); ?>">*</span></label>
        <input type="text" id="edit-question" name="question" class="form-text required<?php echo ( dhNValid('question') ) ? ' error' : ''; ?>" maxlength="255" size="60" value="<?php echo dhPost('question'); ?>" />
        <div class="description"><?php echo dhLang('Esta pregunta se le hará si necesita recuperar su clave.'); ?></div>
      </div>
      <div id="edit-anwser-wrapper" class="form-item">
        <label for="edit-anwser"><?php echo dhLang('Respuesta:'); ?><span class="form-required" title="<?php echo dhLang('Este campo es requerido.'); ?>">*</span></label>
        <input type="text" id="edit-anwser" name="anwser" class="form-text required<?php echo ( dhNValid('anwser') ) ? ' error' : ''; ?>" maxlength="255" size="60" value="<?php echo dhPost('anwser'); ?>" />
        <div class="description"><?php echo dhLang('Respuesta a la pregunta anterior.'); ?></div>
      </div>
      <input type="submit" class="form-submit" value="<?php echo dhLang('Registrese'); ?>" />
    </div>
  </form>
</div>

<?php dhTheme('footer'); ?>