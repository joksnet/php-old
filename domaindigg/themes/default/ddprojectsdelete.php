<?php require_once "$root/themes/$theme/header.php"; ?>

<?php if ( $pid && $project ) : ?>

<h2><?php _e('ProjectsDeleteSubtitle', $project['name']); ?></h2>

<?php if ( $access ) : ?>

<form action="<?php echo $config['root']; ?>dd/projects/<?php echo $pid; ?>/delete/" method="post">
  <p>
    <input type="hidden" name="confirm" id="confirm" value="1" />
    <input type="submit" value="<?php _e('ProjectsDeleteConfirm'); ?>" /> <?php _e('OrCancel', "{$config['root']}dd/projects/"); ?>
  </p>
</form>

<?php else : ?>
<p><?php _e('ProjectAccessDenied'); ?></p>
<?php endif; ?>

<?php else : ?>
<p><?php _e('ProjectNotFound'); ?></p>
<?php endif; ?>

<?php require_once "$root/themes/$theme/footer.php"; ?>