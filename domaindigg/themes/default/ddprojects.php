<?php require_once "$root/themes/$theme/header.php"; ?>

<h2><?php _e('Projects'); ?></h2>

<?php if ( $projects ) : ?>
<table cellpadding="0" cellspacing="0">
  <thead>
    <tr>
      <th><?php _e('Name'); ?></th>
      <th><?php _e('Description'); ?></th>
      <th class="right">&nbsp;</th>
    </tr>
  </thead>
  <tfoot>
    <tr>
      <td class="pagination" colspan="2"><?php echo $pagination; ?></td>
      <td class="right">
        <a href="<?php echo $config['root']; ?>dd/projects/add/"><?php _e('ProjectAdd'); ?></a>
      </td>
    </tr>
  </tfoot>
  <tbody>
<?php $i = 0; ?>
<?php foreach ( $projects as $pid => $project ) : ?>
  <tr class="<?php echo ( $i % 2 == 0 ) ? 'odd' : 'even'; ?>">
    <td>
      <a href="<?php echo $config['root']; ?>dd/projects/<?php echo $pid; ?>/"><?php e($project['name']); ?></a>
    </td>
    <td><?php e($project['description']); ?></td>
    <td class="actions right">
      <a href="<?php echo $config['root']; ?>dd/projects/<?php echo $pid; ?>/access/" class="access"><?php _e('Access'); ?></a>
      <a href="<?php echo $config['root']; ?>dd/projects/<?php echo $pid; ?>/edit/" class="edit"><?php _e('Edit'); ?></a>
      <a href="<?php echo $config['root']; ?>dd/projects/<?php echo $pid; ?>/delete/" class="delete"><?php _e('Delete'); ?></a>
    </td>
  </tr>
<?php $i++; ?>
<?php endforeach; ?>
  </tbody>
</table>
<?php else : ?>
<p><?php _e('ProjectsEmpty', "{$config['root']}dd/projects/add/"); ?></p>
<?php endif; ?>

<?php require_once "$root/themes/$theme/footer.php"; ?>