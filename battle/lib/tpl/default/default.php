<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
  <title>Battle - <?php echo pageTitle() ?></title>
  <link rel="shortcut icon" href="/favicon.ico" />
  <style type="text/css">
  /*<![CDATA[*/
<?php echo importCss('layout.css') ?>
<?php echo importCss('screen.css') ?>
  /*]]>*/
  </style>
  <?php echo includeJS('jquery.js') ?>
</head>

<body>
  <div id="wrap">
    <div id="sidebar">
      <h2><?php echo lang('Top_5') ?></h2>
      <?php echo xhtmlCodesBests() ?>

    </div>
  </div>
</body>
</html>