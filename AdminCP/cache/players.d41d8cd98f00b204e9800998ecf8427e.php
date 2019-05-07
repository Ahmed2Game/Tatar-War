<?php if(!class_exists('raintpl')){exit;}?><?php if( $page == 'search' ){ ?>

<!-- BEGIN Main Content -->
<div class="row-fluid">
    <div class="span6">
        <div class="box">
            <div class="box-title">
                <h3><i class="icon-reorder"></i>البحث عن اللاعبين</h3>
                <div class="box-tool">
                </div>
            </div>
            <div class="box-content">
                <form action="?page=search" class="form-horizontal" method="POST">

                    <div class="control-group">
                        <label class="control-label" for="searchword">كلمة البحث</label>
                        <div class="controls">
                            <div class="span4">
                                <input type="text" name="searchword" id="searchword" class="input-xlarge" data-rule-required="true" data-rule-number="true" />
                            </div>
                        </div>
                    </div>

                    <div class="control-group">
                        <label class="control-label">نوع البحث</label>
                       <div class="controls">
                          <label class="radio inline">
                              <input type="radio" name="type" value="1" checked /> اسم اللاعب
                          </label>
                          <label class="radio inline">
                              <input type="radio" name="type" value="2" /> اسم التحالف
                          </label>
                          <label class="radio inline">
                              <input type="radio" name="type" value="3" /> IP
                          </label>
                          <label class="radio inline">
                              <input type="radio" name="type" value="4" /> نوع اللاعب
                          </label>
                       </div>
                    </div>

                    <div class="form-actions">
                        <input type="submit" class="btn btn-primary" value="بحث">
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="span6">
        <div class="box">
            <div class="box-title">
                <h3><i class="icon-reorder"></i>البحث عن عضوية اساسية</h3>
                <div class="box-tool">
                </div>
            </div>
            <div class="box-content">
                <form action="?page=search" class="form-horizontal" method="POST">

                    <div class="control-group">
                        <label class="control-label" for="searchword2">كلمة البحث</label>
                        <div class="controls">
                            <div class="span4">
                                <input type="text" name="searchword2" id="searchword2" class="input-xlarge" data-rule-required="true" data-rule-number="true" />
                            </div>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label">نوع البحث</label>
                       <div class="controls">
                          <label class="radio inline">
                              <input type="radio" name="type2" value="1" checked /> اسم اللاعب
                          </label>
                          <label class="radio inline">
                              <input type="radio" name="type2" value="2" /> عدد الذهب
                          </label>
                       </div>
                    </div>
                    <div class="form-actions">
                        <input type="submit" class="btn btn-primary" value="بحث">
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php if( $_POST && isset($list) && $list != null ){ ?>

<div class="row-fluid">
    <div class="span12">
        <div class="box">
            <div class="box-title">
                <h3><i class="icon-table"></i> عرض اللاعبين</h3>
                <div class="box-tool">
                </div>
            </div>
            <div class="box-content">
                <table class="table table-striped table-hover fill-head">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>اسم اللاعب</th>
                            <th>اسم التحالف</th>
                            <th style="width: 150px">اوامر</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php $_c = 0;?>

                    <?php $counter1=-1; if( isset($list) && is_array($list) && sizeof($list) ) foreach( $list as $key1 => $value1 ){ $counter1++; ?>

                    <?php $_c = $_c + 1;?>

                        <tr>
                            <td><?php echo $_c;?></td>
                            <td>
                                <a href="<?php echo $url;?>profile?uid=<?php echo $value1["id"];?>"><?php echo $value1["name"];?></a>
                            </td>
                            <td>
                                <a href="<?php echo $url;?>alliance?id=<?php echo $value1["alliance_id"];?>"><?php echo $value1["alliance_name"];?></a>
                            </td>
                            <td>
                                <a class="btn btn-primary btn-small" href="?page=edit&id=<?php echo $value1["id"];?>"><i class="icon-edit"></i> </a>
                            
                                <a class="btn btn-danger btn-small" href="?page=delete&id=<?php echo $value1["id"];?>" onclick="return confirm('هل انت متاكد من حذف اللاعب ؟');"><i class="icon-trash"></i> </a>
                            </td>
                        </tr>
                      <?php } ?>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php }elseif( $_POST && isset($list2) && $list2 != null ){ ?>

<div class="row-fluid">
    <div class="span12">
        <div class="box">
            <div class="box-title">
                <h3><i class="icon-table"></i> عرض اللاعبين</h3>
                <div class="box-tool">
                </div>
            </div>
            <div class="box-content">
                <table class="table table-striped table-hover fill-head">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>اسم اللاعب</th>
                            <th>عدد الذهب</th>
                            <th style="width: 150px">اوامر</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php $_c = 0;?>

                    <?php $counter1=-1; if( isset($list2) && is_array($list2) && sizeof($list2) ) foreach( $list2 as $key1 => $value1 ){ $counter1++; ?>

                    <?php $_c = $_c + 1;?>

                        <tr>
                            <td><?php echo $_c;?></td>
                            <td>
                                <a href="<?php echo $url;?>profile?uid=<?php echo $value1["id"];?>"><?php echo $value1["name"];?></a>
                            </td>
                            <td><?php if( isset($value1["gold_buy"]) ){ ?><?php echo $value1["gold_buy"];?><?php }elseif( isset($value1["gold_num"]) ){ ?><?php echo $value1["gold_num"];?><?php } ?></td>
                            <td>
                                <a class="btn btn-primary btn-small" href="?page=edit2&id=<?php echo $value1["id"];?>"><i class="icon-edit"></i> </a>
                            </td>
                        </tr>
                      <?php } ?>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php } ?>

<?php }elseif( $page == 'gold' ){ ?>

<div class="row-fluid">
    <div class="span12">
    <?php if( isset($sc) ){ ?>

    <div class="alert alert-success">
        <button class="close" data-dismiss="alert">&times;</button>
        <strong>تم ارسال الذهب للاعب بنجاح</strong>
    </div>
    <?php } ?>

        <div class="box">
            <div class="box-title">
                <h3><i class="icon-reorder"></i>ارسال الذهب للاعبين</h3>
                <div class="box-tool">
                </div>
            </div>
            <div class="box-content">
                <form action="?page=gold" class="form-horizontal" method="POST">

                    <div class="control-group">
                        <label class="control-label" for="name">اسم اللاعب</label>
                        <div class="controls">
                            <div class="span12">
                                <input type="text" name="name" id="name" class="input-xlarge" data-rule-required="true" data-rule-number="true" />
                            </div>
                        </div>
                    </div>

                    <div class="control-group">
                        <label class="control-label" for="gold">عدد الذهب</label>
                        <div class="controls">
                            <div class="span12">
                                <input type="text" name="gold" id="gold" class="input-xlarge" data-rule-required="true" data-rule-number="true" />
                            </div>
                        </div>
                    </div>
                    <div class="form-actions">
                        <input type="submit" class="btn btn-primary" value="ارسال">
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php }elseif( $page == 'activate' ){ ?>

<div class="row-fluid">
    <div class="span12">
    <?php if( isset($sc) ){ ?>

    <div class="alert alert-success">
        <button class="close" data-dismiss="alert">&times;</button>
        <strong>تم ارسال الرسالة للاعب بنجاح</strong>
    </div>
    <?php } ?>

        <div class="box">
            <div class="box-title">
                <h3><i class="icon-reorder"></i>ارسال رسالة تفعيل للاعبين</h3>
                <div class="box-tool">
                </div>
            </div>
            <div class="box-content">
                <form action="?page=activate" class="form-horizontal" method="POST">

                    <div class="control-group">
                        <label class="control-label" for="name">اسم اللاعب</label>
                        <div class="controls">
                            <div class="span12">
                                <input type="text" name="name" id="name" class="input-xlarge" data-rule-required="true" data-rule-number="true" />
                            </div>
                        </div>
                    </div>
                    <div class="form-actions">
                        <input type="submit" class="btn btn-primary" value="ارسال">
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php }elseif( $page == 'email' ){ ?>

<div class="row-fluid">
    <div class="span12">
    <?php if( isset($sc) ){ ?>

    <div class="alert alert-success">
        <button class="close" data-dismiss="alert">&times;</button>
        <strong>تم اضافة الاميل بنجاح</strong>
    </div>
    <?php } ?>

        <div class="box">
            <div class="box-title">
                <h3><i class="icon-reorder"></i>اضافة اميل الى السيرفر الخاص</h3>
                <div class="box-tool">
                </div>
            </div>
            <div class="box-content">
                <form action="?page=email" class="form-horizontal" method="POST">
                    <div class="control-group">
                        <label class="control-label" for="email">البريد الالكتروني</label>
                        <div class="controls">
                            <div class="span12">
                                <input type="text" name="email" id="email" class="input-xlarge" data-rule-required="true" data-rule-number="true" />
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
                <h3><i class="icon-table"></i> عرض الاميلات</h3>
                <div class="box-tool">
                </div>
            </div>
            <div class="box-content">
                <table class="table table-striped table-hover fill-head">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>البريد الالكترونى</th>
                            <th style="width: 150px">حذف</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php $_c = 0;?>

                    <?php $counter1=-1; if( isset($emails) && is_array($emails) && sizeof($emails) ) foreach( $emails as $key1 => $value1 ){ $counter1++; ?>

                    <?php $_c = $_c + 1;?>

                        <tr>
                            <td><?php echo $_c;?></td>
                            <td><?php echo $value1["uemail"];?></td>
                            <td>
                                <a class="btn btn-danger btn-small" href="?page=email&id=<?php echo $value1["uemail"];?>" onclick="return confirm('هل انت متاكد من حذف البريد ؟');"><i class="icon-trash"></i> </a>
                            </td>
                        </tr>
                      <?php } ?>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php }elseif( $page == 'edit2' ){ ?>

<div class="row-fluid">
    <div class="span12">
    <?php if( isset($sc) ){ ?>

    <div class="alert alert-success">
        <button class="close" data-dismiss="alert">&times;</button>
        <strong>تم تعديل بيانات اللاعب بنجاح</strong>
    </div>
    <?php } ?>

        <div class="box">
            <div class="box-title">
                <h3><i class="icon-reorder"></i>تعديل الاعب "<?php echo $p["name"];?>"</h3>
                <div class="box-tool">
                </div>
            </div>
            <div class="box-content">
                <form action="?page=update2" class="form-horizontal" method="POST">
                    <div class="control-group">
                        <label class="control-label" for="id">رقم اللاعب</label>
                        <div class="controls">
                            <div class="span10">
                                <input type="text" name="id" id="id" class="input-small" data-rule-required="true" data-rule-number="true" value="<?php echo $p["id"];?>" />
                            </div>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="name">اسم اللاعب</label>
                        <div class="controls">
                            <div class="span12">
                                <input type="text" name="name" id="name" class="input-large" data-rule-required="true" value="<?php echo $p["name"];?>" />
                            </div>
                        </div>
                    </div>

                    <div class="control-group">
                        <label class="control-label" for="pwd">كلمة المرور</label>
                        <div class="controls">
                            <div class="span12">
                                <input type="text" name="pwd" id="pwd" class="input-large" data-rule-required="true" value="" />
                            </div>
                        </div>
                    </div>

                    <div class="control-group">
                        <label class="control-label" for="email">البريد الالكترونى</label>
                        <div class="controls">
                            <div class="span12">
                                <input type="text" name="email" id="email" class="input-large" data-rule-required="true" value="<?php echo $p["email"];?>" />
                            </div>
                        </div>
                    </div>


                    <div class="control-group">
                        <label class="control-label">حالة اللاعب</label>
                       <div class="controls">
                          <label class="radio inline">
                              <input type="radio" name="is_active" value="1" <?php if( $p["is_active"] ){ ?>checked<?php } ?> /> مفعل
                          </label>
                          <label class="radio inline">
                              <input type="radio" name="is_active" value="0" <?php if( !$p["is_active"] ){ ?>checked<?php } ?> /> غير مفعل
                          </label>
                       </div>
                    </div>
                    
                    <div class="control-group">
                        <label class="control-label" for="invite_by">مدعو عن طريق</label>
                        <div class="controls">
                            <div class="span12">
                                <input type="text" name="invite_by" id="invite_by" class="input-large" data-rule-required="true" value="<?php echo $p["invite_by"];?>" />
                            </div>
                        </div>
                    </div>


                    <div class="control-group">
                        <label class="control-label" for="house_name">السكن</label>
                        <div class="controls">
                            <div class="span12">
                                <input type="text" name="house_name" id="house_name" class="input-large" data-rule-required="true" value="<?php echo $p["house_name"];?>" />
                            </div>
                        </div>
                    </div>

                    <div class="control-group">
                        <label class="control-label" for="gold_num">عدد الذهب</label>
                        <div class="controls">
                            <div class="span12">
                                <input type="text" name="gold_num" id="gold_num" class="input-medium" data-rule-required="true" value="<?php echo $p["gold_num"];?>" />
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
                    
<?php }elseif( $page == 'edit' ){ ?>

<!-- BEGIN Main Content -->
<div class="row-fluid">
    <div class="span12">
    <?php if( isset($sc) ){ ?>

    <div class="alert alert-success">
        <button class="close" data-dismiss="alert">&times;</button>
        <strong>تم تعديل بيانات اللاعب بنجاح</strong>
    </div>
    <?php } ?>

        <div class="box">
            <div class="box-title">
                <h3><i class="icon-reorder"></i>تعديل الاعب "<?php echo $p["name"];?>"</h3>
                <div class="box-tool">
                </div>
            </div>
            <div class="box-content">
                <form action="?page=update" class="form-horizontal" method="POST">

                    <div class="control-group">
                        <label class="control-label" for="id">رقم اللاعب</label>
                        <div class="controls">
                            <div class="span10">
                                <input type="text" name="id" id="id" class="input-small" data-rule-required="true" data-rule-number="true" value="<?php echo $p["id"];?>" />
                            </div>
                        </div>
                    </div>

                    <div class="control-group">
                        <label class="control-label" for="tribe_id">رقم القبيلة</label>
                        <div class="controls">
                            <div class="span10">
                                <input type="text" name="tribe_id" id="tribe_id" class="input-small" data-rule-required="true" data-rule-number="true" value="<?php echo $p["tribe_id"];?>" />
                            </div>
                        </div>
                    </div>

                    <div class="control-group">
                        <label class="control-label" for="alliance_id">رقم التحالف</label>
                        <div class="controls">
                            <div class="span10">
                                <input type="text" name="alliance_id" id="alliance_id" class="input-small" data-rule-required="true" data-rule-number="true" value="<?php echo $p["alliance_id"];?>" />
                            </div>
                        </div>
                    </div>


                    <div class="control-group">
                        <label class="control-label" for="alliance_name">اسم التحالف</label>
                        <div class="controls">
                            <div class="span12">
                                <input type="text" name="alliance_name" id="alliance_name" class="input-large" data-rule-required="true" value="<?php echo $p["alliance_name"];?>" />
                            </div>
                        </div>
                    </div>

                    <div class="control-group">
                        <label class="control-label" for="alliance_roles">صلاحيات التحالف</label>
                        <div class="controls">
                            <div class="span12">
                                <input type="text" name="alliance_roles" id="alliance_roles" class="input-large" data-rule-required="true" value="<?php echo $p["alliance_roles"];?>" />
                            </div>
                        </div>
                    </div>

                    <div class="control-group">
                        <label class="control-label" for="name">اسم اللاعب</label>
                        <div class="controls">
                            <div class="span12">
                                <input type="text" name="name" id="name" class="input-large" data-rule-required="true" value="<?php echo $p["name"];?>" />
                            </div>
                        </div>
                    </div>

                    

                    <div class="control-group">
                        <label class="control-label">محظور ؟</label>
                       <div class="controls">
                          <label class="radio inline">
                              <input type="radio" name="is_blocked" value="1" <?php if( $p["is_blocked"] ){ ?>checked<?php } ?> /> نعم
                          </label>
                          <label class="radio inline">
                              <input type="radio" name="is_blocked" value="0" <?php if( !$p["is_blocked"] ){ ?>checked<?php } ?> /> لا
                          </label>
                       </div>
                    </div>


                  <div class="control-group">
                        <label class="control-label">نوع اللاعب</label>
                       <div class="controls">
                          <label class="radio inline">
                              <input type="radio" name="player_type" value="1" <?php if( $p["player_type"] == 1 ){ ?>checked<?php } ?> /> لاعب
                          </label>
                          <label class="radio inline">
                              <input type="radio" name="player_type" value="2" <?php if( $p["player_type"] == 2 ){ ?>checked<?php } ?> /> مدير
                          </label>
                          <label class="radio inline">
                              <input type="radio" name="player_type" value="4" <?php if( $p["player_type"] == 4 ){ ?>checked<?php } ?> /> دعم
                          </label>
                          <label class="radio inline">
                              <input type="radio" name="player_type" value="3" <?php if( $p["player_type"] == 3 ){ ?>checked<?php } ?> /> تتار
                          </label>
                       </div>
                    </div>


                    <div class="control-group">
                        <label class="control-label">البلس</label>
                       <div class="controls">
                          <label class="radio inline">
                              <input type="radio" name="active_plus_account" value="1" <?php if( $p["active_plus_account"] ){ ?>checked<?php } ?> /> مفعل
                          </label>
                          <label class="radio inline">
                              <input type="radio" name="active_plus_account" value="0" <?php if( !$p["active_plus_account"] ){ ?>checked<?php } ?> /> غير مفعل
                          </label>
                       </div>
                    </div>


                    <div class="control-group">
                        <label class="control-label" for="total_people_count">عدد السكان</label>
                        <div class="controls">
                            <div class="span12">
                                <input type="text" name="total_people_count" id="total_people_count" class="input-medium" data-rule-required="true" value="<?php echo $p["total_people_count"];?>" />
                            </div>
                        </div>
                    </div>


                    <div class="control-group">
                        <label class="control-label" for="villages_count">عدد القرى</label>
                        <div class="controls">
                            <div class="span12">
                                <input type="text" name="villages_count" id="villages_count" class="input-medium" data-rule-required="true" value="<?php echo $p["villages_count"];?>" />
                            </div>
                        </div>
                    </div>


                  <div class="control-group">
                        <label class="control-label" for="villages_id">ارقام القرى</label>
                        <div class="controls">
                            <div class="span12">
                                <input type="text" name="villages_id" id="villages_id" class="input-xlarge" data-rule-required="true" value="<?php echo $p["villages_id"];?>" />
                            </div>
                        </div>
                    </div>


                    <div class="control-group">
                        <label class="control-label" for="hero_troop_id">رقم جندى البطل</label>
                        <div class="controls">
                            <div class="span12">
                                <input type="text" name="hero_troop_id" id="hero_troop_id" class="input-medium" data-rule-required="true" value="<?php echo $p["hero_troop_id"];?>" />
                            </div>
                        </div>
                    </div>

                    <div class="control-group">
                        <label class="control-label" for="hero_level">مستوى البطل</label>
                        <div class="controls">
                            <div class="span12">
                                <input type="text" name="hero_level" id="hero_level" class="input-medium" data-rule-required="true" value="<?php echo $p["hero_level"];?>" />
                            </div>
                        </div>
                    </div>

                    <div class="control-group">
                        <label class="control-label" for="hero_points">نقاط البطل</label>
                        <div class="controls">
                            <div class="span12">
                                <input type="text" name="hero_points" id="hero_points" class="input-medium" data-rule-required="true" value="<?php echo $p["hero_points"];?>" />
                            </div>
                        </div>
                    </div>

                    <div class="control-group">
                          <label class="control-label" for="hero_name">اسم البطل</label>
                          <div class="controls">
                              <div class="span12">
                                  <input type="text" name="hero_name" id="hero_name" class="input-large" data-rule-required="true" value="<?php echo $p["hero_name"];?>" />
                              </div>
                          </div>
                    </div>


                    <div class="control-group">
                          <label class="control-label" for="hero_in_village_id">رقم القريه الموجود فيها البطل </label>
                          <div class="controls">
                              <div class="span12">
                                  <input type="text" name="hero_in_village_id" id="hero_in_village_id" class="input-medium" data-rule-required="true" value="<?php echo $p["hero_in_village_id"];?>" />
                              </div>
                          </div>
                    </div>

                    <div class="control-group">
                          <label class="control-label" for="attack_points">نقاط الهجوم </label>
                          <div class="controls">
                              <div class="span12">
                                  <input type="text" name="attack_points" id="attack_points" class="input-medium" data-rule-required="true" value="<?php echo $p["attack_points"];?>" />
                              </div>
                          </div>
                    </div>

                    <div class="control-group">
                          <label class="control-label" for="defense_points">نقاط الدفاع </label>
                          <div class="controls">
                              <div class="span12">
                                  <input type="text" name="defense_points" id="defense_points" class="input-medium" data-rule-required="true" value="<?php echo $p["defense_points"];?>" />
                              </div>
                          </div>
                    </div>

                    <div class="control-group">
                          <label class="control-label" for="week_attack_points">نقاط مهاجم الاسبوع  </label>
                          <div class="controls">
                              <div class="span12">
                                  <input type="text" name="week_attack_points" id="week_attack_points" class="input-medium" data-rule-required="true" value="<?php echo $p["week_attack_points"];?>" />
                              </div>
                          </div>
                    </div>

                    <div class="control-group">
                          <label class="control-label" for="week_defense_points">نقاط مدافع الاسبوع  </label>
                          <div class="controls">
                              <div class="span12">
                                  <input type="text" name="week_defense_points" id="week_defense_points" class="input-medium" data-rule-required="true" value="<?php echo $p["week_defense_points"];?>" />
                              </div>
                          </div>
                    </div>

                    <div class="control-group">
                          <label class="control-label" for="week_dev_points">نقاط مطور الاسبوع </label>
                          <div class="controls">
                              <div class="span12">
                                  <input type="text" name="week_dev_points" id="week_dev_points" class="input-medium" data-rule-required="true" value="<?php echo $p["week_dev_points"];?>" />
                              </div>
                          </div>
                    </div>

                    <div class="control-group">
                          <label class="control-label" for="week_thief_points">نقاط سارق الاسبوع </label>
                          <div class="controls">
                              <div class="span12">
                                  <input type="text" name="week_thief_points" id="week_thief_points" class="input-medium" data-rule-required="true" value="<?php echo $p["week_thief_points"];?>" />
                              </div>
                          </div>
                    </div>

                    <div class="control-group last">
                        <label for="ip_his" class="control-label">سجل الايبيهات</label>
                        <div class="controls">
                            <textarea name="ip_his" id="ip_his" rows="5" class="input-block-level"><?php echo $p["ip_his"];?></textarea>
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