<?php require_once "$root/themes/$theme/header.php"; ?>

<h2><?php _e('ProjectsAddSubtitle'); ?></h2>

<?php if ( $error ) : ?>
<div class="error">
  <p><?php _e('ErrorInfo'); ?></p>
  <ul>
<?php if ( $error['name'] ) : ?>
    <li><?php _e('ErrorName'); ?></li>
<?php endif; ?>
<?php if ( $error['description'] ) : ?>
    <li><?php _e('ErrorDescription'); ?></li>
<?php endif; ?>
  </ul>
</div>
<?php endif; ?>

<form action="<?php echo $config['root']; ?>dd/projects/add/" method="post">
  <dl>
    <dt>
      <label for="name"><?php _e('Name'); ?></label>
      <span class="required">*</span>
    </dt>
    <dd>
      <input type="text" name="name" id="name" maxlength="40" value="<?php eraw($name); ?>" />
      <p><?php _e('ProjectsAddDescription'); ?></p>
    </dd>

    <dt>
      <label for="description"><?php _e('Description'); ?></label>
    </dt>
    <dd>
      <textarea name="description" id="description" rows="2" cols="40"><?php eraw($description); ?></textarea>
    </dd>
  </dl>
  <p>
    <input type="submit" value="<?php _e('ProjectSave'); ?>" /> <?php _e('OrCancel', "{$config['root']}dd/projects/"); ?>
  </p>
</form>

<?php require_once "$root/themes/$theme/footer.php"; ?>