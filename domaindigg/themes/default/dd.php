<?php require_once "$root/themes/$theme/header.php"; ?>

<?php if ( !( empty($notices) ) ) : ?>
<div class="notices">
  <form action="<?php echo $config['root']; ?>dd/" method="get">
    <p class="hide">
      <input type="hidden" name="noticed" id="noticed" value="1" />
      <input type="submit" value="<?php _e('ProjectsAccessNoticesDissmiss'); ?>" />
    </p>
  </form>
  <p><?php _e('ProjectsAccessNotices'); ?></p>
  <ul>
<?php foreach ( $notices as $aid => $notice ) : ?>
    <li>
      <a href="<?php echo $config['root']; ?>dd/projects/<?php echo $notice['pid']; ?>/"><?php e($notice['name']); ?></a>
      <span class="message"><?php e($notice['message']); ?></span>
    </li>
<?php endforeach; ?>
  </ul>
</div>
<?php endif; ?>

<div class="box">
  <h3><?php _e('Projects'); ?></h3>
<?php if ( $projects ) : ?>
  <ul>
<?php $i = 0; ?>
<?php foreach ( $projects as $pid => $project ) : ?>
    <li class="<?php echo ( $i % 2 == 0 ) ? 'odd' : 'even'; ?><?php echo ( $project['own'] ) ? ' own' : ''; ?>">
      <div class="actions">
        <a href="<?php echo $config['root']; ?>dd/projects/<?php echo $pid; ?>/access/" class="access" title="<?php _e('Access'); ?>"><span><?php _e('Edit'); ?></span></a>
        <a href="<?php echo $config['root']; ?>dd/projects/<?php echo $pid; ?>/edit/" class="edit" title="<?php _e('Edit'); ?>"><span><?php _e('Edit'); ?></span></a>
        <a href="<?php echo $config['root']; ?>dd/projects/<?php echo $pid; ?>/delete/" class="delete" title="<?php _e('Delete'); ?>"><span><?php _e('Delete'); ?></span></a>
      </div>
      <a href="<?php echo $config['root']; ?>dd/projects/<?php echo $pid; ?>/"><?php e($project['name']); ?></a>
    </li>
<?php $i++; ?>
<?php endforeach; ?>
  </ul>
  <p class="footer">
    <a href="<?php echo $config['root']; ?>dd/projects/"><?php _e('ViewAll'); ?></a>
  </p>
<?php else : ?>
  <p><?php _e('ProjectsEmpty', "{$config['root']}dd/projects/add/"); ?></p>
<?php endif; ?>
</div>

<div class="clear"><!-- --></div>

<?php require_once "$root/themes/$theme/footer.php"; ?>