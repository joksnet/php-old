<?php require_once "$root/themes/$theme/header.php"; ?>

<h2><?php _e('TLDsSuggest'); ?></h2>

<?php if ( $error ) : ?>
<div class="error">
  <p><?php _e('ErrorInfo'); ?></p>
  <ul>
<?php if ( $error['captcha'] ) : ?>
    <li><?php _e('ErrorCaptcha'); ?></li>
<?php endif; ?>
<?php if ( $error['domain'] ) : ?>
    <li><?php _e('ErrorDomain'); ?></li>
<?php endif; ?>
<?php if ( $error['domainexists'] ) : ?>
    <li><?php _e('ErrorDomainExists'); ?></li>
<?php endif; ?>
<?php if ( $error['description'] ) : ?>
    <li><?php _e('ErrorDescription'); ?></li>
<?php endif; ?>
  </ul>
</div>
<?php endif; ?>

<form action="<?php echo $config['root']; ?>tlds/suggest/" method="post">
  <div class="captcha">
    <p><img src="<?php echo $config['root']; ?>captcha/?k=tldssuggest" alt="<?php _e('CaptchaAlt'); ?>" /></p>
    <p><?php _e('CaptchaDescription'); ?></p>
    <p><input type="text" name="captcha" id="captcha" size="7" maxlength="<?php echo CAPTCHA_LENGTH; ?>" value="" /></p>
  </div>

  <dl>
    <dt>
      <label for="domain"><?php _e('Domain'); ?></label>
      <span class="required">*</span>
    </dt>
    <dd>
      .<input type="text" name="domain" id="domain" maxlength="6" value="<?php echo $domain; ?>" />
      <span class="example"><?php _e('TLDsSuggetExample'); ?></span>
    </dd>

    <dt>
      <label for="description"><?php _e('Description'); ?></label>
    </dt>
    <dd>
      <textarea name="description" id="description" rows="2" cols="40"><?php echo $description; ?></textarea>
    </dd>
  </dl>
  <p>
    <input type="submit" value="<?php _e('TLDsSuggestSave'); ?>" /> <?php _e('OrCancel', "{$config['root']}tlds/"); ?>
  </p>
</form>

<?php require_once "$root/themes/$theme/footer.php"; ?>