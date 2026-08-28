<ol class="breadcrumb">
    <?php foreach ($this->breadcrumbs as $key => $breadcrumb): ?>
        <li class="breadcrumb-item">
            <?php if (is_array($breadcrumb)): ?>
                <a href="<?= $breadcrumb[0] ?>"><?= $key ?></a>
            <?php else: ?>
                <?= $breadcrumb ?>
            <?php endif; ?>
        </li>
    <?php endforeach; ?>
</ol>