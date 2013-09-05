<?php require_once "$root/themes/$theme/header.php"; ?>

<h2><?php _e('RestoreSubtitle'); ?></h2>

<?php if ( $error ) : ?>
<div class="error">
  <p><?php _e('ErrorInfo'); ?></p>
  <ul>
<?php if ( $error['email'] ) : ?>
    <li><?php _e('ErrorEmailAddress'); ?></li>
<?php endif; ?>
  </ul>
</div>
<?php endif; ?>

<form action="<?php echo $config['root']; ?>signin/restore/" method="post">
  <dl>
    <dt>
      <label for="email"><?php _e('EmailAddress'); ?></label>
      <span class="required">*</span>
    </dt>
    <dd><input type="text" name="email" id="email" tabindex="1" maxlength="70" value="<?php eraw($email); ?>" /></dd>
  </dl>
  <p>
    <input type="submit" tabindex="2" value="<?php _e('RestoreButton'); ?>" />
  </p>
</form>

<?php require_once "$root/themes/$theme/footer.php"; ?>