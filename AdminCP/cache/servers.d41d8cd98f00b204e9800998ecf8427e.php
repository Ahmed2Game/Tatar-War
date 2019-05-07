<?php if(!class_exists('raintpl')){exit;}?><?php if( $page == 'show' ){ ?>

<div class="row-fluid">
    <div class="span12">
        <div class="box">
            <div class="box-title">
                <h3><i class="icon-table"></i>عرض السيرفرات</h3>
                <div class="box-tool">
                </div>
            </div>
            <div class="box-content">
                <table class="table table-striped table-hover fill-head">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>عدد اللاعبين</th>
                            <th>تاريخ البداية</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $counter1=-1; if( isset($servers) && is_array($servers) && sizeof($servers) ) foreach( $servers as $key1 => $value1 ){ $counter1++; ?>

                        <tr>
                            <td><?php echo $value1["id"];?></td>
                            <td><?php echo $value1["players_count"];?></td>
                            <td><?php echo $value1["start_date"];?></td>
                        </tr>
                        <?php } ?>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php }elseif( $page == 'add' ){ ?>

<!-- BEGIN Main Content -->
<div class="row-fluid">
    <div class="span12">
        <div class="box">
            <div class="box-title">
                <h3><i class="icon-reorder"></i>اضافة سيرفر</h3>
                <div class="box-tool">
                </div>
            </div>
            <div class="box-content">
                <form action="" class="form-horizontal" method="POST">
                    <input class="text" name="post_type"  type="hidden" value="add" />
                    <div class="control-group">
                        <label class="control-label span4" for="user">اسم المستخدم (cpanel user)</label>
                        <div class="controls">
                            <div class="span8">
                                <input type="text" name="user" id="user" class="input-medium" placeholder="Cpanel Login User" value="" data-rule-required="true" />
                            </div>
                        </div>
                    </div>

                    <div class="control-group">
                        <label class="control-label span4" for="password">كلمة المرور  (cpanel password)</label>
                        <div class="controls">
                            <div class="span8">
                                <input type="password" name="password" id="password" placeholder="Cpanel Login Password" class="input-medium" value="" data-rule-required="true" />
                            </div>
                        </div>
                    </div>

                    <div class="form-actions">
                        <input type="submit" class="btn btn-primary" value="اضافة">
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<div class="row-fluid">
    <div class="span12">
        <div class="box">
            <div class="box-title">
                <h3><i class="icon-reorder"></i>اضافة سيرفر يدوي بعد انشاء قاعدة البيانات يدويا</h3>
                <div class="box-tool">
                </div>
            </div>
            <div class="box-content">
                <form action="" class="form-horizontal" method="POST">
                    <input class="text" name="post_type"  type="hidden" value="add" />
                    <div class="form-actions">
                        <input type="submit" class="btn btn-primary" value=" اضافة السيرفر يدويا">
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- END Main Content -->
<?php }elseif( $page == 'edit' ){ ?>

<!-- BEGIN Main Content -->
<div class="row-fluid">
    <div class="span12">
        <div class="box">
            <div class="box-title">
                <h3><i class="icon-reorder"></i>اعادة السيرفر </h3>
                <div class="box-tool">
                </div>
            </div>
            <div class="box-content">
                <form action="" class="form-horizontal" method="POST">
                    <input class="text" name="post_type"  type="hidden" value="reset" />
                    <div class="control-group">
                        <label class="control-label" for="time">عدد الساعات قبل الافتتاح</label>
                        <div class="controls">
                            <div class="span10">
                                <input type="text" name="time" id="time" class="input-small" value="" data-rule-required="true" data-rule-number="true" />
                            </div>
                        </div>
                    </div>


                    <div class="form-actions">
                        <input type="submit" class="btn btn-primary" value="اعادة السيرفر">
                    </div>
                </form>
                <form action="" class="form-horizontal"  method="POST">
                    <input class="text" name="post_type"  type="hidden" value="new" />
                    <div class="form-actions">
                        <input type="submit" class="btn btn-primary" value=" تثبيت السيرفر الجديد">
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- END Main Content -->
<?php } ?>

