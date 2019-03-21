<?php 
/** @var array $list */
?>
<ul>

<?php foreach ($list as $index): ?>
    <li> <a href="/users/<?= $index ?>"> user <?= $index ?> </a> </li>
<?php endforeach; ?>

</ul>