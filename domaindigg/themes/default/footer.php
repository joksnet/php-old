<!-- END CONTENT -->
    </div>
    <div id="footer">
      <a href="<?php echo $config['root']; ?>"><?php _e('Home'); ?></a>
<?php if ( !( Session::$logged ) ) : ?>
    | <a href="<?php echo $config['root']; ?>signup/"><?php _e('SignUp'); ?></a>
    | <a href="<?php echo $config['root']; ?>signin/"><?php _e('SignIn'); ?></a>
<?php else : ?>
    | <a href="<?php echo $config['root']; ?>dd/"><?php e($config['sitename']); ?></a>
    | <a href="<?php echo $config['root']; ?>signout/"><?php _e('SignOut'); ?></a>
<?php endif; ?>
    | <a href="<?php echo $config['root']; ?>tos/"><?php _e('TOS'); ?></a>
    | <a href="<?php echo $config['root']; ?>about/"><?php _e('About'); ?></a>
    | <a href="<?php echo $config['root']; ?>contact/"><?php _e('Contact'); ?></a>
      <br /><?php _e('Copyright'); ?>
    </div>
  </div>
  <div id="wrapbottom"><!-- --></div>
</body>
</html>