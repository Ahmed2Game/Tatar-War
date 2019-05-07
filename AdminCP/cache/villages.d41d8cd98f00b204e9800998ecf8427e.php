<?php if(!class_exists('raintpl')){exit;}?><?php if( $page == 'edit' ){ ?>

<!-- BEGIN Main Content -->
<div class="row-fluid">
    <div class="span12">
        <div class="box">
        <?php if( isset($sc) ){ ?>

            <div class="alert alert-success">
                <button class="close" data-dismiss="alert">&times;</button>
                <strong>تم تعديل بيانات القرية بنجاح</strong>
            </div>
        <?php } ?>

            <div class="box-title">
                <h3><i class="icon-reorder"></i>تعديل قرية "<?php echo $v["village_name"];?>"</h3>
                <div class="box-tool">
                </div>
            </div>
            <div class="box-content">
                <form action="?page=update" class="form-horizontal" method="POST">

                    <div class="control-group">
                        <label class="control-label" for="username">رقم القرية</label>
                        <div class="controls">
                            <div class="span10">
                                <input type="text" name="id" id="sitetitle" class="input-small" data-rule-required="true" data-rule-number="true" value="<?php echo $v["id"];?>" />
                            </div>
                        </div>
                    </div>

                    <div class="control-group">
                        <label class="control-label" for="username">الاحداثيات X</label>
                        <div class="controls">
                            <div class="span10">
                                <input type="text" name="rel_x" id="sitetitle" class="input-small" data-rule-required="true" data-rule-number="true" value="<?php echo $v["rel_x"];?>" />
                            </div>
                        </div>
                    </div>

                    <div class="control-group">
                        <label class="control-label" for="username">الاحداثيات Y</label>
                        <div class="controls">
                            <div class="span10">
                                <input type="text" name="rel_y" id="sitetitle" class="input-small" data-rule-required="true" data-rule-number="true" value="<?php echo $v["rel_y"];?>" />
                            </div>
                        </div>
                    </div>


                    <div class="control-group">
                        <label class="control-label" for="username">رقم القبيلة </label>
                        <div class="controls">
                            <div class="span12">
                                <input type="text" name="tribe_id" id="sitedesc" class="input-large" data-rule-required="true" value="<?php echo $v["tribe_id"];?>" />
                            </div>
                        </div>
                    </div>


                    <div class="control-group">
                        <label class="control-label" for="username">رقم اللاعب  </label>
                        <div class="controls">
                            <div class="span12">
                                <input type="text" name="player_id" id="sitemeta" class="input-large" data-rule-required="true"  value="<?php echo $v["player_id"];?>" />
                            </div>
                        </div>
                    </div>


                    <div class="control-group">
                        <label class="control-label" for="username">رقم التحالف </label>
                        <div class="controls">
                            <div class="span12">
                                <input type="text" name="alliance_id" id="sitedesc" class="input-large" data-rule-required="true" value="<?php echo $v["alliance_id"];?>" />
                            </div>
                        </div>
                    </div>


                    <div class="control-group">
                        <label class="control-label" for="username">اسم اللاعب  </label>
                        <div class="controls">
                            <div class="span12">
                                <input type="text" name="player_name" id="sitedesc" class="input-large" data-rule-required="true" value="<?php echo $v["player_name"];?>" />
                            </div>
                        </div>
                    </div>


                    <div class="control-group">
                        <label class="control-label" for="username">اسم القرية</label>
                        <div class="controls">
                            <div class="span12">
                                <input type="text" name="village_name" id="sitedesc" class="input-large" data-rule-required="true" value="<?php echo $v["village_name"];?>" />
                            </div>
                        </div>
                    </div>


                    <div class="control-group">
                        <label class="control-label" for="username">اسم التحالف </label>
                        <div class="controls">
                            <div class="span12">
                                <input type="text" name="alliance_name" id="sitedesc" class="input-large" data-rule-required="true" value="<?php echo $v["alliance_name"];?>" />
                            </div>
                        </div>
                    </div>


                    <div class="control-group">
                        <label class="control-label">عاصمة ؟</label>
                       <div class="controls">
                          <label class="radio inline">
                              <input type="radio" name="is_capital" value="1" <?php if( $v["is_capital"] ){ ?>checked<?php } ?> /> نعم
                          </label>
                          <label class="radio inline">
                              <input type="radio" name="is_capital" value="0" <?php if( !$v["is_capital"] ){ ?>checked<?php } ?> /> لا
                          </label>
                       </div>
                    </div>


                  <div class="control-group">
                        <label class="control-label">معجزة</label>
                       <div class="controls">
                          <label class="radio inline">
                              <input type="radio" name="is_special_village" value="1" <?php if( $v["is_special_village"] ){ ?>checked<?php } ?> /> نعم
                          </label>
                          <label class="radio inline">
                              <input type="radio" name="is_special_village" value="0" <?php if( !$v["is_special_village"] ){ ?>checked<?php } ?> /> ﻻ
                          </label>
                       </div>
                    </div>


                    <div class="control-group">
                        <label class="control-label">واحة</label>
                       <div class="controls">
                          <label class="radio inline">
                              <input type="radio" name="is_oasis" value="1" <?php if( $v["is_oasis"] ){ ?>checked<?php } ?> /> نعم
                          </label>
                          <label class="radio inline">
                              <input type="radio" name="is_oasis" value="0" <?php if( !$v["is_oasis"] ){ ?>checked<?php } ?> /> ﻻ
                          </label>
                       </div>
                    </div>


                    <div class="control-group">
                        <label class="control-label" for="username">عدد السكان</label>
                        <div class="controls">
                            <div class="span12">
                                <input type="text" name="people_count" id="sitedesc" class="input-large" data-rule-required="true" value="<?php echo $v["people_count"];?>" />
                            </div>
                        </div>
                    </div>

                    <div class="control-group">
                        <label class="control-label" for="username">استهلاك القمح</label>
                        <div class="controls">
                            <div class="span12">
                                <input type="text" name="crop_consumption" id="sitedesc" class="input-large" data-rule-required="true" value="<?php echo $v["crop_consumption"];?>" />
                            </div>
                        </div>
                    </div>


                    <div class="control-group last">
                        <label for="textarea5" class="control-label">الموارد</label>
                        <div class="controls">
                            <textarea name="resources" id="textarea5" rows="5" class="input-block-level"><?php echo $v["resources"];?></textarea>
                        </div>
                    </div>


                  <div class="control-group last">
                      <label for="textarea5" class="control-label">النقاط الحضارية</label>
                      <div class="controls">
                          <textarea name="cp" id="textarea5" rows="5" class="input-block-level"><?php echo $v["cp"];?></textarea>
                      </div>
                  </div>


                  <div class="control-group last">
                        <label for="textarea5" class="control-label">المبانى</label>
                        <div class="controls">
                            <textarea name="buildings" id="textarea5" rows="5" class="input-block-level"><?php echo $v["buildings"];?></textarea>
                        </div>
                  </div>


                  <div class="control-group last">
                        <label for="textarea5" class="control-label">القوات</label>
                        <div class="controls">
                            <textarea name="troops_num" id="textarea5" rows="5" class="input-block-level"><?php echo $v["troops_num"];?></textarea>
                        </div>
                  </div>


                    <div class="control-group">
                        <label class="control-label" for="username">ارقام الواحات المحتلة من القرية </label>
                        <div class="controls">
                            <div class="span12">
                                <input type="text" name="village_oases_id" id="sitedesc" class="input-xlarge" data-rule-required="true" value="<?php echo $v["village_oases_id"];?>" />
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
<?php }elseif( $page == 'search' ){ ?>

<!-- BEGIN Main Content -->
<div class="row-fluid">
    <div class="span12">
        <div class="box">
            <div class="box-title">
                <h3><i class="icon-reorder"></i>البحث عن القرى</h3>
                <div class="box-tool">
                </div>
            </div>
            <div class="box-content">
                <form action="?page=search" class="form-horizontal" method="POST">

                    <div class="control-group">
                        <label class="control-label" for="username">كلمة البحث</label>
                        <div class="controls">
                            <div class="span12">
                                <input type="text" name="searchword" id="sitetitle" class="input-xlarge" data-rule-required="true" />
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
                              <input type="radio" name="type" value="2" /> عدد الجيش
                          </label>
                          <label class="radio inline">
                              <input type="radio" name="type" value="3" /> الاستهلاك
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
<!-- END Main Content -->
<?php if( $_POST && $list ){ ?>

<div class="row-fluid">
    <div class="span12">
        <div class="box">
            <div class="box-title">
                <h3><i class="icon-table"></i> عرض نتائج البحث</h3>
                <div class="box-tool">
                </div>
            </div>
            <div class="box-content">
                <table class="table table-striped table-hover fill-head">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>اسم القرية</th>
                            <?php if( post('type') == 2 ){ ?>

                            <th>اسم اللاعب</th>
                            <th>عدد الجيش</th>
                            <?php }else{ ?>

                            <th>عاصمة ؟</th>
                            <th>معجزة ؟</th>
                            <?php } ?>

                            <th>عدد السكان</th>
                            <th>الاستهلاك</th>
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
                                <a href="<?php echo $url;?>village3?id=<?php echo $value1["id"];?>"><?php echo $value1["village_name"];?></a>
                            </td>
                            <?php if( post('type') == 2 ){ ?>

                            <td>
                                <a href="<?php echo $url;?>profile?uid=<?php echo $value1["player_id"];?>"><?php echo $value1["player_name"];?></a>
                            </td>
                            <td><?php echo $value1["troop"];?></td>
                            <?php }else{ ?>

                            <td><?php echo $value1["is_capital"];?></td>
                            <td><?php echo $value1["is_special_village"];?></td>
                            <?php } ?>

                            <td><?php echo $value1["people_count"];?></td>
                            <td><?php echo $value1["crop_consumption"];?></td>
                            <td>
                                <a class="btn btn-primary btn-small" href="?page=edit&id=<?php echo $value1["id"];?>"><i class="icon-edit"></i> </a>
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

<?php } ?>