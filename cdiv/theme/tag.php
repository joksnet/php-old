<?php require_once "$root/theme/header.php"; ?>

  <h2><?php _e($tag); ?></h2>
<?php if ( $errors ) : ?>
  <ol class="thumbs">
<?php foreach ( $errors as $id => $name ) : ?>
    <li>
      <a href="<?php _u("/$id"); ?>" title="<?php _e( $name ); ?>">
        <img src="<?php _u("/img/$id/thumb"); ?>" alt="<?php _e( $name ); ?>" />
      </a>
    </li>
<?php endforeach; ?>
  </ol>
<?php else : ?>
  <p><?php _l('NoData'); ?></p>
<?php endif; // $recent ?>


<?php require_once "$root/theme/footer.php"; ?>