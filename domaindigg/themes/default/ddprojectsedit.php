<?php require_once "$root/themes/$theme/header.php"; ?>

<?php if ( $pid && $project ) : ?>

<h2><?php _e('ProjectsEditSubtitle', $project['name']); ?></h2>

<?php if ( $access ) : ?>

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

<form action="<?php echo $config['root']; ?>dd/projects/<?php echo $pid; ?>/edit/" method="post">
  <dl>
    <dt>
      <label for="name"><?php _e('Name'); ?></label>
      <span class="required">*</span>
    </dt>
    <dd>
      <input type="text" name="name" id="name" maxlength="40" value="<?php eraw($name); ?>" />
    </dd>

    <dt>
      <label for="description"><?php _e('Description'); ?></label>
    </dt>
    <dd>
      <textarea name="description" id="description" rows="2" cols="40"><?php eraw($description); ?></textarea>
    </dd>

    <dd class="radio">
      <input type="radio" name="public" id="public" value="1"<?php echo ( $public ) ? ' checked="checked"' : ''; ?> />&nbsp;<label for="public"><?php _e('Public'); ?></label>
      <input type="radio" name="public" id="private" value="0"<?php echo ( !( $public ) ) ? ' checked="checked"' : ''; ?> />&nbsp;<label for="private"><?php _e('Private'); ?></label>
    </dd>
  </dl>

  <h3><?php _e('ProjectsEditTLDs'); ?></h3>
  <p><?php _e('ProjectsEditTLDsDescription', "{$config['root']}tlds/suggest/"); ?></p>
<?php if ( $tlds ) : ?>
  <div class="list">
    <ul>
<?php foreach ( $tlds as $tid => $tld ) : ?>
      <li>
        <input type="checkbox" class="checkbox" name="domain[<?php echo $tid; ?>]" id="<?php echo strtolower( str_replace('.', '', $tld['domain']) ); ?>" value="1"<?php echo ( $tld['pid'] ) ? ' checked="checked"' : ''; ?> />
        <label for="<?php echo strtolower( str_replace('.', '', $tld['domain']) ); ?>">
          <span class="domain">.<?php e( strtoupper( $tld['domain'] ) ); ?></span>
          <span class="small">(<?php e($tld['description']); ?>)</span>
        </label>
      </li>
<?php endforeach; ?>
    </ul>
  </div>
<?php else : ?>
  <p><?php _e('ProjectsEditTLDsEmpty', "{$config['root']}tlds/suggest/"); ?></p>
<?php endif; ?>

  <p>
    <input type="submit" value="<?php _e('ProjectSave'); ?>" /> <?php _e('OrCancel', "{$config['root']}dd/projects/"); ?>
  </p>
</form>

<?php else : ?>
<p><?php _e('ProjectAccessDenied'); ?></p>
<?php endif; ?>

<?php else : ?>
<p><?php _e('ProjectNotFound'); ?></p>
<?php endif; ?>

<?php require_once "$root/themes/$theme/footer.php"; ?>