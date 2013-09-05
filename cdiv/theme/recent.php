  <h3><?php _l('Recent'); ?></h3>
<?php $recent = getRecent(10); ?>
<?php if ( $recent ) : ?>
  <ol class="thumbs">
<?php foreach ( $recent as $id => $name ) : ?>
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
