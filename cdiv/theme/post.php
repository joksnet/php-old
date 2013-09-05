<?php require_once "$root/theme/header.php"; ?>

<h2><?php _l('Post'); ?></h2>
<form action="<?php _u('/post'); ?>" method="post" enctype="multipart/form-data">
  <dl class="form">
    <dt><label for="name"><?php _l('SiteName'); ?></label></dt>
    <dd>
      <input type="text" name="name" id="name" maxlength="140" value="<?php _e( ( isset($_POST['name']) ) ? $_POST['name'] : '' ); ?>" />
      <span><?php _l( ( isset($invalid['name']) ) ? 'InvalidField' : '&nbsp;' ); ?></span>
    </dd>

    <dt><label for="url"><?php _l('ExampleURL'); ?></label></dt>
    <dd>
      <input type="text" name="url" id="url" maxlength="255" value="<?php _e( ( isset($_POST['url']) ) ? $_POST['url'] : '' ); ?>" />
      <span><?php _l( ( isset($invalid['url']) ) ? 'InvalidField' : '&nbsp;' ); ?></span>
    </dd>

    <dt><label for="screen"><?php _l('Screenshot'); ?></label></dt>
    <dd>
      <input type="file" name="screen" id="screen" />
      <span><?php _l( ( isset($invalid['screen']) ) ? 'InvalidField' : '&nbsp;' ); ?></span>
    </dd>

    <dt><label for="tags"><?php _l('Tags'); ?></label></dt>
    <dd>
      <input type="text" name="tags" id="tags" value="<?php _e( ( isset($_POST['tags']) ) ? $_POST['tags'] : '' ); ?>" />
      <span><?php _l( ( isset($invalid['tags']) ) ? 'InvalidField' : '&nbsp;' ); ?></span>
    </dd>

    <dd>
      <input type="hidden" name="drugs" id="drugs" value="" />
      <input type="submit" class="submit" value="<?php _l('Post'); ?>" />
    </dd>
  </dl>
</form>

<?php require_once "$root/theme/footer.php"; ?>