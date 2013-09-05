<?php require_once "$root/theme/header.php"; ?>

<h2><?php _e($name); ?></h2>
<div class="screen screen-small">
  <a href="<?php _u("/$id/screen"); ?>" title="<?php _e($name); ?>">
    <img src="<?php _u("/img/$id/small"); ?>" alt="<?php _e($name); ?>" />
  </a>
</div>
<div class="info">
  <dl>
    <dt><?php _l('SiteName'); ?></dt>
    <dd><?php _e($name); ?></dd>

    <dt><?php _l('ExampleURL'); ?></dt>
    <dd><a href="<?php _e($url); ?>"><?php _e($url); ?></a></dd>

    <dt><?php _l('Tags'); ?></dt>
    <dd>
<?php while ( $tag = array_shift($tags) ) : ?>
      <a href="<?php _u("/tag/$tag"); ?>"><?php _e($tag); ?></a><?php echo ( sizeof($tags) > 0 ) ? ',' : ''; ?>

<?php endwhile; ?>
    </dd>
  </dl>

<?php if ( $related ) : ?>
  <h3><?php _l('Related'); ?></h3>
  <ol class="thumbs">
<?php foreach ( $related as $relatedID => $relatedName ) : ?>
    <li><a href="<?php _u("/$relatedID"); ?>" title="<?php _e($relatedName); ?>"><img src="<?php _u("/img/$relatedID/thumb"); ?>" alt="<?php _e($relatedName); ?>" /></a></li>
<?php endforeach; ?>
  </ol>
<?php endif; // $related ?>
  <div class="clear"><!-- --></div>
</div>

<?php if ( $comments ) : ?>
<h2><?php _l('Comments'); ?></h2>
<ol class="comments">
<?php foreach ( $comments as $commentID => $comment ) : ?>
  <li>
    <div class="author">
      <img src="http://www.gravatar.com/avatar/<?php echo md5($comment['email']); ?>?s=45" alt="<?php _e($comment['name']); ?>" />
      <ul>
<?php if ( empty($comment['url']) ) : ?>
        <li class="name"><?php _e($comment['name']); ?></li>
<?php else : ?>
        <li class="name"><a href="<?php echo $comment['url']; ?>" title="<?php _e($comment['name']); ?>"><?php _e($comment['name']); ?></a></li>
<?php endif; ?>
        <li class="date"><a href="#comment-<?php echo $commentID; ?>" name="comment-322"><?php echo date('d/m/Y H:i', $comment['posted']); ?></a></li>
      </ul>
    </div>
    <div class="comment">
<?php _t($comment['comment']); ?>
    </div>
    <div class="clear"><!-- --></div>
  </li>
<?php endforeach; ?>
</ol>
<?php endif; // $comments ?>
<h2><?php _l('PostAComment'); ?></h2>
<form action="<?php _u("/$id"); ?>" method="post">
  <dl class="form left">
    <dt>
      <label for="name"><?php _l('Name'); ?></label>
      <span><?php _l( ( isset($invalid['name']) ) ? 'InvalidField' : '&nbsp;' ); ?></span>
    </dt>
    <dd><input type="text" name="name" id="name" maxlength="40" value="<?php _e( ( isset($_POST['name']) ) ? $_POST['name'] : '' ); ?>" /></dd>

    <dt>
      <label for="email"><?php _l('Email'); ?></label>
      <span><?php _l( ( isset($invalid['email']) ) ? 'InvalidField' : '&nbsp;' ); ?></span>
    </dt>
    <dd><input type="text" name="email" id="email" maxlength="70" value="<?php _e( ( isset($_POST['email']) ) ? $_POST['email'] : '' ); ?>" /></dd>

    <dt>
      <label for="url"><?php _l('URL'); ?></label>
      <span><?php _l( ( isset($invalid['url']) ) ? 'InvalidField' : '&nbsp;' ); ?></span>
    </dt>
    <dd><input type="text" name="url" id="url" maxlength="255" value="<?php _e( ( isset($_POST['url']) ) ? $_POST['url'] : '' ); ?>" /></dd>

    <dd>
      <div class="vote">
        <input type="radio" class="radio" name="vote" id="good" value="1" checked="checked" />
        <label for="good"><img src="<?php _u('/theme/good.gif'); ?>" alt="" /></label>
        <input type="radio" class="radio" name="vote" id="bad" value="0" />
        <label for="bad"><img src="<?php _u('/theme/bad.gif'); ?>" alt="" /></label>
      </div>

      <input type="hidden" name="drugs" id="drugs" value="" />
      <input type="submit" class="submit" value="<?php _l('PostComment'); ?>" />
    </dd>
  </dl>
  <dl class="form right">
    <dt>
      <label for="comment"><?php _l('Comment'); ?></label>
      <span><?php _l( ( isset($invalid['comment']) ) ? 'InvalidField' : '&nbsp;' ); ?></span>
    </dt>
    <dd>
      <textarea name="comment" id="comment" cols="50" rows="11"><?php _e( ( isset($_POST['comment']) ) ? $_POST['comment'] : '' ); ?></textarea>
    </dd>
  </dl>
</form>

<?php require_once "$root/theme/footer.php"; ?>