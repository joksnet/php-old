<?php require_once "$root/theme/header.php"; ?>

<h2><?php _e($name); ?></h2>

<div class="screen screen-orig">
  <img src="<?php _u("/img/$id/orig") ?>" alt="<?php _e($name); ?>" />
</div>
<p>
  <a href="<?php _u("/$id"); ?>"><?php _l('Back'); ?></a>
</p>

<?php require_once "$root/theme/footer.php"; ?>