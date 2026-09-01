<div class="footer-bottom__copyright">

    <span>

        © <?php echo date('Y'); ?>

        <?= WebUtils::getSiteSetting(
            'site_name'
        ) ?>

    </span>


    <span>

        <?= WebUtils::getMenuItemByKey(
            'all_rights_reserved',
            $languageId
        )['label'] ?>

    </span>

</div>