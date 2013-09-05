  <h2><?php _l('Popular'); ?></h2>
<?php $popular = getPopular(5); ?>
<?php if ( $popular ) : ?>
  <ol class="list">
<?php foreach ( $popular as $id => $data ) : ?>
    <li>
      <div class="screen screen-small">
        <a href="<?php _u("/$id"); ?>" title="<?php _e($data['name']); ?>">
          <img src="<?php _u("/img/$id/thumb"); ?>" alt="<?php _e($data['name']); ?>" />
        </a>
      </div>
      <div class="info">
        <h4>
          <a href="<?php _u("/$id"); ?>" title="<?php _e($data['name']); ?>"><?php _e($data['name']); ?></a>
        </h4>
        <h5>
          <a href="<?php echo $data['url']; ?>" title="<?php _e($data['name']); ?>"><?php echo $data['url']; ?></a>
        </h5>
<?php if ( !( empty($data['tags']) ) ) : ?>
        <ol class="tags">
<?php $tagI = 0; ?>
<?php foreach ( $data['tags'] as $tag ) : ?>
          <li<?php echo ( $tagI == 0 ) ? ' class="first"' : ''; ?>><a href="<?php _u("/tag/$tag"); ?>"><?php _e($tag); ?></a></li>
<?php $tagI++; ?>
<?php endforeach; ?>
        </ol>
<?php endif; // $data['tags'] ?>
      </div>
      <div class="clear"><!-- --></div>
    </li>
<?php endforeach; ?>
  </ol>
<?php else : ?>
  <p><?php _l('NoData'); ?></p>
<?php endif; // $popular ?>