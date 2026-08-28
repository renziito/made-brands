<nav class="page-sidebar" data-pages="sidebar">
    <div class="sidebar-header">
        <img src="<?= Yii::app()->theme->baseUrl  ?>/assets/img/logo_reverse.png" alt="logo" class="brand"
            data-src="<?= Yii::app()->theme->baseUrl  ?>/assets/img/logo_reverse.png" data-src-retina="<?= Yii::app()->theme->baseUrl  ?>/assets/img/logo_reverse.png"
            width="150" style="margin-top:-8px;">
    </div>
    <div class="sidebar-menu">
        <ul class="menu-items">
            <?php foreach (Menu::getMenu() as $k => $menu): ?>
                <?php if ($menu['link']): ?>
                    <li class="<?= ($k == 0 ? 'm-t-30 ' : '') . ($menu['class']) ?>" id="menu-li">
                        <a href="<?= $menu['link'] ?>">
                            <span class="title"><?= $menu['name'] ?></span>
                            <?php if (isset($menu['sub'])): ?>
                                <span class="<?= $menu['class'] ?> arrow"></span></a>
                    <?php endif; ?>
                    </a>
                    <span class="bg-success icon-thumbnail"><i class="<?= $menu['icon'] ?>"></i></span>
                    <?php if (isset($menu['sub'])): ?>
                        <ul class="sub-menu">
                            <?php foreach ($menu['sub'] as $item): ?>
                                <li class="<?= $item['class'] ?>">
                                    <a href="<?= $item['link'] ?>"><?= $item['name'] ?></a>
                                    <span class="icon-thumbnail">
                                        <?php if (preg_match('#\b' . preg_quote('fas', '#') . '\b#i', $item['icon'])): ?>
                                            <i class="<?= $item['icon'] ?>"></i>
                                        <?php else: ?>
                                            <?= $item['icon'] ?>
                                        <?php endif; ?>
                                    </span>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                    </li>
                <?php endif; ?>
            <?php endforeach; ?>
        </ul>
        <div class="clearfix"></div>
    </div>
</nav>

<script>
    document.addEventListener('DOMContentLoaded', function() {

        document.querySelectorAll('.sub-menu').forEach(subMenu => {

            const parent = subMenu.closest('li'); // el li padre (menu-li)
            const arrow = document.querySelector('#menu-li .arrow');

            if (!parent) return;

            const hasActive = subMenu.querySelector('li.open.active');

            if (hasActive) {
                parent.classList.add('open', 'active');
                arrow.classList.add('open', 'active');
            } else {
                arrow.classList.remove('open', 'active');
                parent.classList.remove('open', 'active');
            }

        });

    });
</script>