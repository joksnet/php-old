<?php require_once "$root/themes/$theme/header.php"; ?>

<?php if ( $pid && $project ) : ?>

<h2><?php e( $project['name'] ); ?></h2>
<p><?php e($project['description']); ?></p>

<?php if ( $access ) : ?>
<?php if ( $names ) : ?>
<table cellpadding="0" cellspacing="0">
  <thead>
    <tr>
      <th><?php _e('Name'); ?></th>
      <th>&nbsp;</th>
    </tr>
  </thead>
  <tfoot>
    <tr>
      <td colspan="2" class="right">
        <a href="<?php echo $config['root']; ?>dd/projects/<?php echo $pid; ?>/suggest/"><?php _e('ProjectSuggest'); ?></a>
      </td>
    </tr>
  </tfoot>
  <tbody>
  </tbody>
</table>
<?php else : ?>
<?php if ( $project['uid'] == Session::_('uid') ) : ?>
<p><?php _e('ProjectNamesEmptyOwner', "{$config['root']}dd/projects/$pid/suggest/", "{$config['root']}dd/projects/$pid/access/"); ?></p>
<?php else : ?>
<p><?php _e('ProjectNamesEmpty', $project['name'], "{$config['root']}dd/projects/$pid/suggest/"); ?></p>
<?php endif; ?>
<?php endif; ?>

<?php else : ?>
<p><?php _e('ProjectAccessDenied'); ?></p>
<?php endif; ?>

<?php else : ?>
<p><?php _e('ProjectNotFound'); ?></p>
<?php endif; ?>

<?php require_once "$root/themes/$theme/footer.php"; ?>