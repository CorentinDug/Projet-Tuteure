

<html>

<head>

    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script type='text/javascript' src='https://ajax.googleapis.com/ajax/libs/jquery/1.6.1/jquery.min.js'></script>
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
</head>

<body>

<!-- Ce UL Sera rempli avec les données du tableau PHP -->

<ul></ul>

<?php
$tab = array("valeur 1", "valeur 2", "valeur 3");
/* ... */
?>

<!-- Au moment où tu écris le code JS : -->
<script>
    var tab = <?php json_encode($tab); ?>;
    // code JS utilisant tab
    window.alert(tab); // affiche le tableau en JavaScript
    // ...
</script>

</body>

</html>
