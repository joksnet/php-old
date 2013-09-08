<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
  <title>Gt<?php echo ( $t ) ? " - $t" : ''; ?><?php echo ( $unread ) ? " ($unread)" : ( ( $_SESSION['unread'] ) ? " ({$_SESSION['unread']})" : '' ); ?><?php echo ( $subject ) ? " - $subject" : ''; ?></title>
  <link rel="stylesheet" href="/gt.css" type="text/css" media="all" />
  <link rel="shortcut icon" href="/favicon.ico" />
</head>

<body>
  <h1>
    <span style="color: blue;">G</span><span style="color: red;">m</span><span style="color: yellow;">a</span><span style="color: blue;">i</span><span style="color: green;">l</span> text
  </h1>
<?php if ( !( isLogin() ) ) : ?>
<?php if ( isset($_POST['username']) ) : ?>
  <p>Wrong login.</p>
<?php endif; ?>
  <form action="/login.php" method="post">
    <div>
      Username: <input type="text" name="username"<?php echo ( isset($_POST['username']) ) ? ' value="' . $_POST['username'] . '"' : ''; ?> />
    </div>
    <div>
      Password: <input type="password" name="password" />
    </div>
    <div>
      <input type="submit" value="Sign In" />
    </div>
  </form>
<?php else : ?>
<?php if ( $message ) : ?>
  <div class="message">
    <h2><?php echo $subject; ?>
      <span>by <?php echo $from; ?> on <?php echo $date; ?></span>
    </h2>
    <p><?php echo $message; ?></p>
  </div>
<?php endif; ?>
<?php if ( $mails ) : ?>
<?php foreach ( $mails as $mail ) : ?>
  <div class="mail <?php echo ( $mail['seen'] == 0 ) ? 'unread' : 'read'; ?>">
<?php if ( $mail['email'] ) : ?>
    <abbr title="<?php echo $mail['email']; ?>"><?php echo $mail['from']; ?></abbr>
<?php else : ?>
    <span><?php echo $mail['from']; ?></span>
<?php endif; ?>
    <span><!-- --><?php echo str_repeat(' ', 28 - strlen($mail['from'])); ?></span>
    <a href="/index.php?i=<?php echo $mail['num']; ?>"><?php echo $mail['subject']; ?></a>
    <span><!-- --><?php echo str_repeat(' ', 96 - strlen($mail['subject'])); ?></span>
    <span><?php echo $mail['date'] ?></span>
  </div>
<?php endforeach; ?>
<?php endif; ?>
  <p><!-- --></p>
<?php if ( $pPrev > 0 || $pNext > 0 ) : ?>
  <div>Nav :
<?php if ( $pPrev > 0 ) : ?>
  <a href="/index.php?p=<?php echo $pPrev; ?><?php echo ( $t == 'Inbox' ) ? '' : "&amp;t=" . urlencode($t); ?>">[Anterior]</a>
<?php endif; ?>
<?php if ( $pNext > 0 ) : ?>
  <a href="/index.php?p=<?php echo $pNext; ?><?php echo ( $t == 'Inbox' ) ? '' : "&amp;t=" . urlencode($t); ?>">[Siguiente]</a>
<?php endif; ?>
  </div>
<?php endif; ?>
<?php if ( $tags ) : ?>
  <div>Tags:
<?php foreach ( $tags as $tag ) : ?>
  <a href="/index.php?t=<?php echo urlencode( $tag ); ?>">[<?php echo $tag; ?>]</a>
<?php endforeach; ?>
  </div>
<?php endif; ?>
  <p>
    <b><?php echo $_SESSION['username']; ?>@gmail.com</b>
    <a href="/index.php">[Inbox]</a>
    <a href="/logout.php">[Logout]</a>
  </p>
<?php endif; ?>
  <p>
    &copy; <a href="http://bundleweb.com.ar/" title="Software as a Service">Bundle Software</a>
  </p>
</body>
</html>