<?php require_once "$root/themes/$theme/header.php"; ?>

<h2><?php _e('SignUpSubtitle'); ?></h2>

<?php if ( $error ) : ?>
<div class="error">
  <p><?php _e('ErrorInfo'); ?></p>
  <ul>
<?php if ( $error['captcha'] ) : ?>
    <li><?php _e('ErrorCaptcha'); ?></li>
<?php endif; ?>
<?php if ( $error['email'] ) : ?>
    <li><?php _e('ErrorEmailAddress'); ?></li>
<?php endif; ?>
<?php if ( $error['password'] ) : ?>
    <li><?php _e('ErrorPassword'); ?></li>
<?php endif; ?>
<?php if ( $error['passwordagain'] ) : ?>
    <li><?php _e('ErrorPasswordAgain'); ?></li>
<?php endif; ?>
  </ul>
</div>
<?php endif; ?>

<form action="<?php echo $config['root']; ?>signup/" method="post">
  <div class="captcha">
    <p><img src="<?php echo $config['root']; ?>captcha/?k=signup" alt="<?php _e('CaptchaAlt'); ?>" /></p>
    <p><?php _e('CaptchaDescription'); ?></p>
    <p><input type="text" name="captcha" id="captcha" size="7" maxlength="<?php echo CAPTCHA_LENGTH; ?>" value="" /></p>
  </div>

  <p><?php _e('SignUpDescription'); ?></p>

  <dl>
    <dt>
      <label for="email"><?php _e('EmailAddress'); ?></label>
      <span class="required">*</span>
    </dt>
    <dd><input type="text" name="email" id="email" tabindex="1" maxlength="70" value="<?php eraw($email); ?>" /></dd>

    <dt>
      <label for="password"><?php _e('Password'); ?></label>
      <span class="required">*</span>
    </dt>
    <dd><input type="password" name="password" id="password" tabindex="2" maxlength="32" /></dd>

    <dt>
      <label for="passwordagain"><?php _e('PasswordAgain'); ?></label>
      <span class="required">*</span>
    </dt>
    <dd><input type="password" name="passwordagain" id="passwordagain" tabindex="3" maxlength="32" /></dd>
  </dl>
  <p class="info"><?php _e('SignUpInfo', "{$config['root']}tos/"); ?></p>
  <p>
    <input type="submit" tabindex="4" value="<?php _e('SignUpButton'); ?>" />
  </p>
</form>

<?php require_once "$root/themes/$theme/footer.php"; ?>