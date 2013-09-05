<?php require_once "$root/themes/$theme/header.php"; ?>

<h2><?php _e('TLDs'); ?></h2>

<div class="list">
<?php if ( $tlds ) : ?>
  <ul>
<?php $i = 0; ?>
<?php foreach ( $tlds as $tid => $tld ) : ?>
    <li class="<?php echo ( $i % 2 == 0 ) ? 'odd' : 'even'; ?>">
      <span class="domain">.<?php e( strtoupper($tld['domain']) ); ?></span>
      <span class="small">(<?php e($tld['description']); ?>)</span>
    </li>
<?php $i++; ?>
<?php endforeach; ?>
  </ul>
<?php else : ?>
  <p><?php _e('TLDsEmpty', "{$config['root']}tlds/suggest/"); ?></p>
<?php endif; ?>
</div>

<p><?php _e('TLDsSuggestInfo', "{$config['root']}tlds/suggest/"); ?></p>

<?php require_once "$root/themes/$theme/footer.php"; ?>