<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>EmbegeQ App</title>
    <!-- asset helper example -->
    <?php echo \vite_q('main.js'); ?>
</head>
<body>
    <!-- q_yield('content') -->
    <?php if (isset($content)) echo $content; ?>
</body>
</html>
