<?php if(!class_exists('raintpl')){exit;}?><?php if( $page == 'config' ){ ?>

<!-- BEGIN Main Content -->
<div class="row-fluid">
    <div class="span12">
        <div class="box">
            <div class="box-title">
                <h3><i class="icon-reorder"></i>اعدادات اللعبة</h3>
                <div class="box-tool">
                </div>
            </div>
            <div class="box-content">
                <form action="" class="form-horizontal"  method="POST">

                    <div class="control-group">
                        <label class="control-label" for="plus1">ايام حساب بلاس</label>
                        <div class="controls">
                            <div class="span12">
                                <input type="text" name="plus1" id="plus1" class="input-medium" value="<?php echo $gameConfig["plus"]["plus1"];?>" data-rule-required="true" />
                            </div>
                        </div>
                    </div>

                    <div class="control-group">
                        <label class="control-label" for="plus2">ايام زيادة الانتاج</label>
                        <div class="controls">
                            <div class="span12">
                                <input type="text" name="plus2" id="plus2" class="input-medium" value="<?php echo $gameConfig["plus"]["plus2"];?>" data-rule-required="true" />
                            </div>
                        </div>
                    </div>

                    <div class="control-group">
                        <label class="control-label" for="plus3">سعر حساب بلاس</label>
                        <div class="controls">
                            <div class="span12">
                                <input type="text" name="plus3" id="plus3" class="input-medium" value="<?php echo $gameConfig["plus"]["plus3"];?>" data-rule-required="true" />
                            </div>
                        </div>
                    </div>

                    <div class="control-group">
                        <label class="control-label" for="plus4">سعر زيادة الانتاج</label>
                        <div class="controls">
                            <div class="span12">
                                <input type="text" name="plus4" id="plus4" class="input-medium" value="<?php echo $gameConfig["plus"]["plus4"];?>" data-rule-required="true" />
                            </div>
                        </div>
                    </div>

                    <div class="control-group">
                        <label class="control-label" for="plus5">سعر انهاء البناء فورا</label>
                        <div class="controls">
                            <div class="span12">
                                <input type="text" name="plus5" id="plus5" class="input-medium" value="<?php echo $gameConfig["plus"]["plus5"];?>" data-rule-required="true" />
                            </div>
                        </div>
                    </div>

                    <div class="control-group">
                        <label class="control-label" for="plus6">سعر تاجر المبادله</label>
                        <div class="controls">
                            <div class="span12">
                                <input type="text" name="plus6" id="plus6" class="input-medium" value="<?php echo $gameConfig["plus"]["plus6"];?>" data-rule-required="true" />
                            </div>
                        </div>
                    </div>

                    <div class="control-group">
                        <label class="control-label" for="plus7">سعر انهاء الجنود فورا</label>
                        <div class="controls">
                            <div class="span12">
                                <input type="text" name="plus7" id="plus7" class="input-medium" value="<?php echo $gameConfig["plus"]["plus7"];?>" data-rule-required="true" />
                            </div>
                        </div>
                    </div>

                    <div class="control-group">
                        <label class="control-label" for="plus8">سعر وصول التعزيزات فورا</label>
                        <div class="controls">
                            <div class="span12">
                                <input type="text" name="plus8" id="plus8" class="input-medium" value="<?php echo $gameConfig["plus"]["plus8"];?>" data-rule-required="true" />
                            </div>
                        </div>
                    </div>

                    <div class="control-group">
                        <label class="control-label" for="plus9">سعر الموارد</label>
                        <div class="controls">
                            <div class="span12">
                                <input type="text" name="plus9" id="plus9" class="input-medium" value="<?php echo $gameConfig["plus"]["plus9"];?>" data-rule-required="true" />
                            </div>
                        </div>
                    </div>

                    <div class="control-group">
                        <label class="control-label" for="plus10">عدد الموارد</label>
                        <div class="controls">
                            <div class="span12">
                                <input type="text" name="plus10" id="plus10" class="input-medium" value="<?php echo $gameConfig["plus"]["plus10"];?>" data-rule-required="true" />
                            </div>
                        </div>
                    </div>

                    <div class="form-actions">
                        <input type="submit" class="btn btn-primary" name="submit" value="تعديل">
                        <button type="button" class="btn">مسح</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- END Main Content -->
<?php }elseif( $page == 'offer' ){ ?>

<div class="row-fluid">
    <div class="span12">
        <div class="box">
            <div class="box-title">
                <h3><i class="icon-table"></i> عروض البلس</h3>
                <div class="box-tool">
                </div>
            </div>
            <div class="box-content">
                <table class="table table-striped table-hover fill-head">
                    <thead>
                        <tr>
                            <th>الاسم</th>
                            <th>الذهب</th>
                            <th>التكلفة</th>
                            <th>اضافى</th>
                            <th>الصورة</th>
                            <th style="width: 150px">اوامر</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $counter1=-1; if( isset($packages) && is_array($packages) && sizeof($packages) ) foreach( $packages as $key1 => $value1 ){ $counter1++; ?>

                        <tr>
                            <td><?php echo $value1["name"];?></td>
                            <td><?php echo $value1["gold"];?></td>
                            <td><?php echo $value1["cost"];?></td>
                            <td><?php echo $value1["bonus"];?></td>
                            <td><img width="50" height="50" src="<?php echo add_style($value1["image"], GAME_ASSETS_DIR.'/default/plus/'); ?>" /></td>
                            <td>
                                <a class="btn btn-primary btn-small" href="?page=offers&id=<?php echo $value1["id"];?>"><i class="icon-edit"></i> </a>
                            </td>
                        </tr>
                        <?php } ?>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<!-- BEGIN Main Content -->
<div class="row-fluid">
    <div class="span12">
        <div class="box">
            <div class="box-title">
                <h3><i class="icon-reorder"></i>اضافة عرض بلس</h3>
                <div class="box-tool">
                </div>
            </div>
            <div class="box-content">
                <form action="" class="form-horizontal" method="POST">

                    <div class="control-group">
                        <label class="control-label" for="name">الاسم</label>
                        <div class="controls">
                            <div class="span10">
                                <input type="text" name="name" id="name" class="input-medium" data-rule-required="true" />
                            </div>
                        </div>
                    </div>

                    <div class="control-group">
                        <label class="control-label" for="gold">الذهب</label>
                        <div class="controls">
                            <div class="span10">
                                <input type="text" name="gold" id="gold" class="input-medium" data-rule-required="true" data-rule-number="true" />
                            </div>
                        </div>
                    </div>

                    <div class="control-group">
                        <label class="control-label" for="cost">التكلفة</label>
                        <div class="controls">
                            <div class="span10">
                                <input type="text" name="cost" id="cost" class="input-medium" data-rule-required="true" data-rule-number="true" />
                            </div>
                        </div>
                    </div>

                    <div class="control-group">
                        <label class="control-label" for="bonus">اضافى</label>
                        <div class="controls">
                            <div class="span10">
                                <input type="text" name="bonus" id="bonus" class="input-medium" data-rule-required="true" data-rule-number="true" />
                            </div>
                        </div>
                    </div>

                    <div class="control-group">
                        <label class="control-label" for="image">الصورة</label>
                        <div class="controls">
                            <div class="span10">
                                <input type="text" name="image" id="image" class="input-medium" data-rule-required="true"  />
                            </div>
                        </div>
                    </div>


                    <div class="form-actions">
                        <input type="submit" class="btn btn-primary" value="اضافة">
                        <button type="button" class="btn">مسح</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- END Main Content -->
<?php }elseif( $page == 'edit_offer' ){ ?>

<!-- BEGIN Main Content -->
<div class="row-fluid">
    <div class="span12">
        <div class="box">
            <div class="box-title">
                <h3><i class="icon-reorder"></i>تعديل عرض بلس</h3>
                <div class="box-tool">
                </div>
            </div>
            <div class="box-content">
                <form action="" class="form-horizontal" method="POST">
                <input type="hidden" name="id" value="<?php echo $package["id"];?>" />

                    <div class="control-group">
                        <label class="control-label" for="name">الاسم</label>
                        <div class="controls">
                            <div class="span10">
                                <input type="text" name="name" id="name" class="input-medium" data-rule-required="true" value="<?php echo $package["name"];?>" />
                            </div>
                        </div>
                    </div>

                    <div class="control-group">
                        <label class="control-label" for="gold">الذهب</label>
                        <div class="controls">
                            <div class="span10">
                                <input type="text" name="gold" id="gold" class="input-medium" data-rule-required="true" value="<?php echo $package["gold"];?>"  />
                            </div>
                        </div>
                    </div>

                    <div class="control-group">
                        <label class="control-label" for="cost">التكلفة</label>
                        <div class="controls">
                            <div class="span10">
                                <input type="text" name="cost" id="cost" class="input-medium" data-rule-required="true" value="<?php echo $package["cost"];?>" />
                            </div>
                        </div>
                    </div>
                    
                    <div class="control-group">
                        <label class="control-label" for="bonus">اضافى</label>
                        <div class="controls">
                            <div class="span10">
                                <input type="text" name="bonus" id="bonus" class="input-medium" data-rule-required="true" value="<?php echo $package["bonus"];?>" />
                            </div>
                        </div>
                    </div>

                    <div class="control-group">
                        <label class="control-label" for="image">الصورة</label>
                        <div class="controls">
                            <div class="span10">
                                <input type="text" name="image" id="image" class="input-medium" data-rule-required="true" value="<?php echo $package["image"];?>" />
                            </div>
                        </div>
                    </div>


                    <div class="form-actions">
                        <input type="submit" class="btn btn-primary" value="تعديل">
                        <button type="button" class="btn">مسح</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- END Main Content -->

<?php }elseif( $page == 'payment' ){ ?>

<!-- BEGIN Main Content -->
<div class="row-fluid">
    <div class="span12">
        <div class="box">
            <div class="box-title">
                <h3><i class="icon-reorder"></i>تعديل حساب دفع</h3>
                <div class="box-tool">
                </div>
            </div>
            <div class="box-content">
                <form action="" class="form-horizontal" method="POST">

                    <div class="control-group">
                        <label class="control-label" for="name">الاسم</label>
                        <div class="controls">
                            <div class="span10">
                                <input type="text" name="name" id="name" class="input-medium" data-rule-required="true" value="<?php echo $payments["name"];?>" />
                            </div>
                        </div>
                    </div>


                    <div class="control-group">
                        <label class="control-label" for="image">الصورة</label>
                        <div class="controls">
                            <div class="span10">
                                <input type="text" name="image" id="image" class="input-medium" data-rule-required="true" value="<?php echo $payments["image"];?>"  />
                            </div>
                        </div>
                    </div>

                    <div class="control-group">
                        <label class="control-label" for="merchant_id">اسم التاجر</label>
                        <div class="controls">
                            <div class="span10">
                                <input type="text" name="merchant_id" id="merchant_id" class="input-medium" data-rule-required="true" value="<?php echo $payments["merchant_id"];?>" />
                            </div>
                        </div>
                    </div>

                    <div class="control-group">
                        <label class="control-label" for="currency">العملة</label>
                        <div class="controls">
                            <div class="span10">
                                <input type="text" name="currency" id="currency" class="input-medium" data-rule-required="true" value="<?php echo $payments["currency"];?>" />
                            </div>
                        </div>
                    </div>

                    <div class="control-group">
                        <label class="control-label" for="bonus">اضافي</label>
                        <div class="controls">
                            <div class="span10">
                                <input type="text" name="bonus" id="bonus" class="input-medium" data-rule-required="true" value="<?php echo $payments["bonus"];?>" />
                            </div>
                        </div>
                    </div>



                    <div class="form-actions">
                        <input type="submit" class="btn btn-primary" value="تعديل">
                        <button type="button" class="btn">مسح</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- END Main Content -->
<?php } ?>