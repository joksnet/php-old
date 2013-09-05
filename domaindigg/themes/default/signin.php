<?php require_once "$root/themes/$theme/header.php"; ?>

<h2><?php _e('SignInSubtitle'); ?></h2>

<?php if ( $error ) : ?>
<div class="error">
  <p><?php _e('ErrorInfo'); ?></p>
  <ul>
<?php if ( $error['email'] ) : ?>
    <li><?php _e('ErrorEmailAddress'); ?></li>
<?php endif; ?>
<?php if ( $error['password'] ) : ?>
    <li><?php _e('ErrorPassword'); ?></li>
<?php endif; ?>
<?php if ( $error['signin'] ) : ?>
    <li><?php _e('ErrorSignIn'); ?></li>
<?php endif; ?>
  </ul>
</div>
<?php endif; ?>

<form action="<?php echo $config['root']; ?>signin/" method="post">
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
  </dl>
  <p>
<?php if ( isset($_GET['next']) ) : ?>
    <input type="hidden" name="next" id="next" value="<?php echo $_GET['next']; ?>" />
<?php endif; ?>
    <input type="submit" tabindex="3" value="<?php _e('SignInButton'); ?>" />
  </p>
  <p>
    <a href="<?php echo $config['root']; ?>signin/restore/"><?php _e('SignInRestore'); ?></a><br />
    <a href="<?php echo $config['root']; ?>signup/"><?php _e('SignInSignUp'); ?></a>
  </p>
</form>

<?php require_once "$root/themes/$theme/footer.php"; ?>