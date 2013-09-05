<?php require_once "$root/themes/$theme/header.php"; ?>

<?php if ( $pid && $project ) : ?>

<h2><?php _e('ProjectsAccessSubtitle', $project['name']); ?></h2>

<?php if ( $access ) : ?>

<?php if ( $accesses ) : ?>
<table cellpadding="0" cellspacing="0">
  <thead>
    <tr>
      <th><?php _e('EmailAddress'); ?></th>
      <th><?php _e('Message'); ?></th>
      <th class="center">&nbsp;</th>
    </tr>
  </thead>
  <tbody>
<?php $i = 0; ?>
<?php foreach ( $accesses as $aid => $data ) : ?>
  <tr class="<?php echo ( $i % 2 == 0 ) ? 'odd' : 'even'; ?>">
    <td>
<?php if ( empty($data['uid']) ) : ?>
      <span><?php e($data['email']); ?></span>
<?php else : ?>
      <a href="<?php echo $config['root']; ?>users/<?php echo $data['uid']; ?>/"><?php e($data['email']); ?></a>
<?php endif; ?>
    </td>
    <td><?php e($data['message']); ?></td>
    <td class="center">
<?php if ( empty($data['uid']) ) : ?>
      <img src="<?php echo $config['root']; ?>themes/<?php echo $theme; ?>/images/0.gif" alt="<?php _e('ProjectsAccessInvited'); ?>" />
<?php else : ?>
      <img src="<?php echo $config['root']; ?>themes/<?php echo $theme; ?>/images/1.gif" alt="" />
<?php endif; ?>
    </td>
  </tr>
<?php $i++; ?>
<?php endforeach; ?>
  </tbody>
</table>
<?php else : ?>
<p><?php _e('ProjectsAccessEmpty'); ?></p>
<?php endif; ?>

<h3><?php _e('ProjectsAccessAdd'); ?></h3>
<p><?php _e('ProjectsAccessAddDescription'); ?></p>

<?php if ( $error ) : ?>
<div class="error">
  <p><?php _e('ErrorInfo'); ?></p>
  <ul>
<?php if ( $error['email'] ) : ?>
    <li><?php _e('ErrorEmailAddress'); ?></li>
<?php endif; ?>
<?php if ( $error['message'] ) : ?>
    <li><?php _e('ErrorMessage'); ?></li>
<?php endif; ?>
  </ul>
</div>
<?php endif; ?>

<form action="<?php echo $config['root']; ?>dd/projects/<?php echo $pid; ?>/access/" method="post">
  <dl>
    <dt>
      <label for="email"><?php _e('EmailAddress'); ?></label>
      <span class="required">*</span>
    </dt>
    <dd>
      <input type="text" name="email" id="email" maxlength="70" value="<?php eraw($email); ?>" />
    </dd>

    <dt>
      <label for="message"><?php _e('Message'); ?></label>
    </dt>
    <dd>
      <textarea name="message" id="message" rows="2" cols="40"><?php eraw($message); ?></textarea>
    </dd>
  </dl>
  <p>
    <input type="submit" value="<?php _e('ProjectsAccessAdd'); ?>" /> <?php _e('OrCancel', "{$config['root']}dd/projects/"); ?>
  </p>
</form>

<?php else : ?>
<p><?php _e('ProjectAccessDenied'); ?></p>
<?php endif; ?>

<?php else : ?>
<p><?php _e('ProjectNotFound'); ?></p>
<?php endif; ?>

<?php require_once "$root/themes/$theme/footer.php"; ?>