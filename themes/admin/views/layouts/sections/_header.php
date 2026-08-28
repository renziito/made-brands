<div class="header ">
    <a href="#" class="btn-link toggle-sidebar d-lg-none pg pg-menu" data-toggle="sidebar">
    </a>
    <div class="">
        <div class="brand inline  m-l-10 ">
            <img src="<?= Yii::app()->theme->baseUrl  ?>/assets/img/logo.png" alt="logo"
                data-src="<?= Yii::app()->theme->baseUrl  ?>/assets/img/logo.png"
                data-src-retina="<?= Yii::app()->theme->baseUrl  ?>/assets/img/logo.png">
        </div>
    </div>
    <div class="d-flex align-items-center">
        <div class="pull-left p-r-10 fs-14 font-heading d-lg-block d-none">
            Hora del servidor: <?= date('Y-m-d H:i:s') ?> || <span class="semi-bold"><?= Yii::app()->user->username ?></span>
        </div>
        <div class="dropdown pull-right d-lg-block d-none">
            <button class="profile-dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <span class="thumbnail-wrapper d32 circular inline">
                    <?php
                    $image = Yii::app()->theme->baseUrl . "/assets/img/avatar.jpg"; ?>
                    <img src="<?= $image ?>" alt=""
                        data-src="<?= $image ?>"
                        data-src-retina="<?= $image ?>" width="32" height="32">
                </span>
            </button>
            <div class="dropdown-menu dropdown-menu-right profile-dropdown" role="menu">
                <a href="<?= Yii::app()->createAbsoluteUrl('logout') ?>" class="clearfix bg-master-lighter dropdown-item">
                    <span class="pull-left">Logout</span>
                    <span class="pull-right"><i class="pg-power"></i></span>
                </a>
            </div>
        </div>

    </div>
</div>