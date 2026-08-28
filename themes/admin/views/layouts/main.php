<?php /* @var $this Controller */ ?>
<!DOCTYPE html>
<html>
    <head>
        <?php $this->renderPartial('//layouts/sections/_head'); ?>
        <script src="<?= Yii::app()->theme->getBaseUrl() ?>/assets/plugins/jquery/jquery-3.2.1.min.js" type="text/javascript"></script>
    </head>
    <body class="fixed-header dashboard menu-pin menu-behind">   
        <?php $this->renderPartial('//layouts/sections/_navbar'); ?>
        <div class="page-container ">
            <?php $this->renderPartial('//layouts/sections/_header'); ?>

            <div class="page-content-wrapper ">
                <div class="content ">
                    <div class="jumbotron" data-pages="parallax">
                        <div class=" container-fluid   container-fixed-lg sm-p-l-0 sm-p-r-0">
                            <div class="inner">
                                <?php if ($this->breadcrumbs) : ?>
                                    <?php $this->renderPartial('//layouts/sections/_breadcrumb', ['breadcrumbs' => $this->breadcrumbs]); ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <div class="container-fluid container-fixed-lg">
                        <?= $content ?>
                    </div>
                </div>

                <div class=" container-fluid  container-fixed-lg footer">
                    <div class="copyright sm-text-center">
                        <p class="small no-margin pull-left sm-pull-reset">
                            <span class="hint-text">Copyright &copy; <?= date('Y') ?> </span>
                            <span class="font-montserrat">made.brands</span>.
                            <span class="hint-text">All rights reserved. </span>
<!--                            <span class="sm-block">
                                <a href="#" class="m-l-10 m-r-10">Terms of use</a> <span class="muted">|</span> <a href="#" class="m-l-10">Privacy Policy</a>
                            </span>-->
                        </p>
                        <p class="small no-margin pull-right sm-pull-reset">
                            <span class="hint-text">Made with Love <i class="fas fa-heart"></i></span>
                        </p>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
        </div>

        <script>
            $(document).ready(function () {
                var Global = {
                    module: '<?= ($this->module) ? $this->module->id : '' ?>',
                    controller: '<?= $this->id ?>',
                    action: '<?= $this->action->id ?>',
                    absoluteUrl: '<?= Yii::app()->getBaseUrl(true) ?>',
                    baseUrl: '<?= Yii::app()->baseUrl ?>',
                };

                var select = $('.fa-select option');
                select.each(function (i, elem) {
                    var val = elem.text;
                    elem.innerHTML = '&' + val;
                });
                $(".select2").select2({
                    width: '100%'
                });
            });
        </script>
        <?php $this->renderPartial('//layouts/sections/_scripts'); ?>
    </body>
</html>