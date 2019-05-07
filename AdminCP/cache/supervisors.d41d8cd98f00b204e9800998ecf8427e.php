<?php if(!class_exists('raintpl')){exit;}?><?php if( $page == 'show' ){ ?>

<div class="row-fluid">
    <div class="span12">
        <div class="box">
            <div class="box-title">
                <h3><i class="icon-table"></i> عرض المشرفين</h3>
                <div class="box-tool">
                </div>
            </div>
            <div class="box-content">
                <table class="table table-striped table-hover fill-head">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>اسم المشرف</th>
                            <th>بريد المشرف</th>
                            <th style="width: 150px">اوامر</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $counter1=-1; if( isset($supervisors) && is_array($supervisors) && sizeof($supervisors) ) foreach( $supervisors as $key1 => $value1 ){ $counter1++; ?>

                        <tr>
                            <td><?php echo $value1["id"];?></td>
                            <td><?php echo $value1["username"];?></td>
                            <td><?php echo $value1["email"];?></td>
                            <td>
                                <a class="btn btn-primary btn-small" href="supervisors?page=edit&id=<?php echo $value1["id"];?>"><i class="icon-edit"></i> </a>
                                <a class="btn btn-danger btn-small" onclick="return confirm('هل انت متاكد من حذف المشرف ؟');" href="supervisors?page=delete&id=<?php echo $value1["id"];?>"><i class="icon-trash"></i> </a>
                            </td>
                        </tr>
                        <?php } ?>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php }elseif( $page == 'register' ){ ?>

<!-- BEGIN Main Content -->
<div class="row-fluid">
    <div class="span12">
        <div class="box">
            <div class="box-title">
                <h3><i class="icon-reorder"></i>اضافة مشرف</h3>
                <div class="box-tool">
                </div>
            </div>
            <div class="box-content">
                <form action="supervisors" class="form-horizontal" method="POST">
                  <input type="hidden" name="post_type" value="register" />

                    <div class="control-group">
                        <label class="control-label" for="username">اسم المشرف</label>
                        <div class="controls">
                            <div class="span10">
                                <input type="text" name="username" id="username" class="input-xlarge"  />
                            </div>
                        </div>
                    </div>

                    <div class="control-group">
                        <label class="control-label" for="password">كلمة المرور</label>
                        <div class="controls">
                            <div class="span10">
                                <input type="text" name="password" id="password" class="input-xlarge" />
                            </div>
                        </div>
                    </div>

                    <div class="control-group">
                        <label class="control-label" for="email">البريد الالكترونى</label>
                        <div class="controls">
                            <div class="span10">
                                <input type="text" name="email" id="email" class="input-xlarge"  />
                            </div>
                        </div>
                    </div>

                    <div class="control-group">
                        <label class="control-label">مفعل</label>
                       <div class="controls">
                          <label class="radio inline">
                              <input type="radio" name="active" value="1" checked /> نعم
                          </label>
                          <label class="radio inline">
                              <input type="radio" name="active" value="0"  /> لا
                          </label>
                       </div>
                    </div>

                    <hr />
                    <div class="control-group">
                        <label class="control-label">التحكم بالمهام السريعة ؟</label>
                       <div class="controls">
                          <label class="radio inline">
                              <input type="radio" name="control_tasks" value="1" checked /> نعم
                          </label>
                          <label class="radio inline">
                              <input type="radio" name="control_tasks" value="0"  /> لا
                          </label>
                       </div>
                    </div>

                  <div class="control-group">
                        <label class="control-label">التحكم بالمشرفين</label>
                       <div class="controls">
                          <label class="radio inline">
                              <input type="radio" name="control_supervisors" value="1" checked /> نعم
                          </label>
                          <label class="radio inline">
                              <input type="radio" name="control_supervisors" value="0" /> لا
                          </label>
                       </div>
                  </div>


                  <div class="control-group">
                        <label class="control-label">الحكم باللاعبين  ؟</label>
                       <div class="controls">
                          <label class="radio inline">
                              <input type="radio" name="control_players" value="1" checked /> نعم
                          </label>
                          <label class="radio inline">
                              <input type="radio" name="control_players" value="0" /> لا
                          </label>
                       </div>
                  </div>


                  <div class="control-group">
                        <label class="control-label">التحكم بالقرى  ؟</label>
                       <div class="controls">
                          <label class="radio inline">
                              <input type="radio" name="control_villages" value="1" checked /> نعم
                          </label>
                          <label class="radio inline">
                              <input type="radio" name="control_villages" value="0" /> لا
                          </label>
                       </div>
                  </div>

                  <div class="control-group">
                        <label class="control-label">التحكم بالتحالفات  ؟</label>
                       <div class="controls">
                          <label class="radio inline">
                              <input type="radio" name="control_alliances" value="1" checked /> نعم
                          </label>
                          <label class="radio inline">
                              <input type="radio" name="control_alliances" value="0" /> لا
                          </label>
                       </div>
                  </div>


                  <div class="control-group">
                        <label class="control-label">التحكم بالعمليات الشرائية ؟</label>
                       <div class="controls">
                          <label class="radio inline">
                              <input type="radio" name="control_payment" value="1" checked /> نعم
                          </label>
                          <label class="radio inline">
                              <input type="radio" name="control_payment" value="0" /> لا
                          </label>
                       </div>
                  </div>

                  <div class="control-group">
                        <label class="control-label">يستطيع حظر اللاعبين ؟</label>
                       <div class="controls">
                          <label class="radio inline">
                              <input type="radio" name="control_ban_players" value="1" checked /> نعم
                          </label>
                          <label class="radio inline">
                              <input type="radio" name="control_ban_players" value="0" /> لا
                          </label>
                       </div>
                  </div>

                  <div class="control-group">
                        <label class="control-label">التحكم بالدعم الفنى ؟</label>
                       <div class="controls">
                          <label class="radio inline">
                              <input type="radio" name="control_support" value="1" checked /> نعم
                          </label>
                          <label class="radio inline">
                              <input type="radio" name="control_support" value="0" /> لا
                          </label>
                       </div>
                  </div>

                  <hr />

                <div class="control-group">
                        <label class="control-label">قسم الشكاوى ضد اللاعبين</label>
                       <div class="controls">
                          <label class="radio inline">
                              <input type="radio" name="cat_1" value="1" checked /> نعم
                          </label>
                          <label class="radio inline">
                              <input type="radio" name="cat_1" value="0" /> لا
                          </label>
                       </div>
                  </div>


                  <div class="control-group">
                        <label class="control-label">قسم الاستفسارات في اللعبه</label>
                       <div class="controls">
                          <label class="radio inline">
                              <input type="radio" name="cat_2" value="1" checked /> نعم
                          </label>
                          <label class="radio inline">
                              <input type="radio" name="cat_2" value="0" /> لا
                          </label>
                       </div>
                  </div>

                  <div class="control-group">
                        <label class="control-label">قسم مشاكل الذهب والدفع</label>
                       <div class="controls">
                          <label class="radio inline">
                              <input type="radio" name="cat_3" value="1" checked /> نعم
                          </label>
                          <label class="radio inline">
                              <input type="radio" name="cat_3" value="0" /> لا
                          </label>
                       </div>
                  </div>

                  <div class="control-group">
                        <label class="control-label">قسم مشاكل اللعبة والاقتراحات</label>
                       <div class="controls">
                          <label class="radio inline">
                              <input type="radio" name="cat_4" value="1" checked /> نعم
                          </label>
                          <label class="radio inline">
                              <input type="radio" name="cat_4" value="0" /> لا
                          </label>
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
<?php }elseif( $page == 'edit' ){ ?>

<?php $permission_array = json_decode($supervisor["permissions"], true);?>

<?php $permission = $permission_array['allow'];?>

<?php $support_permission = $permission_array['support'];?>

<!-- BEGIN Main Content -->
<div class="row-fluid">
    <div class="span12">
        <div class="box">
            <div class="box-title">
                <h3><i class="icon-reorder"></i>ىعديل مشرف "<?php echo $supervisor["username"];?>"</h3>
                <div class="box-tool">
                </div>
            </div>
            <div class="box-content">
                <form action="supervisors" class="form-horizontal" method="POST">
                  <input type="hidden" name="post_type" value="update" />
                  <input type="hidden" name="userid" value="<?php echo $supervisor["id"];?>" />
                    <div class="control-group">
                        <label class="control-label" for="username">اسم المشرف</label>
                        <div class="controls">
                            <div class="span10">
                                <input type="text" name="username" id="username" class="input-xlarge" data-rule-required="true" value="<?php echo $supervisor["username"];?>" />
                            </div>
                        </div>
                    </div>

                    <div class="control-group">
                        <label class="control-label" for="password">كلمة المرور</label>
                        <div class="controls">
                            <div class="span10">
                                <input type="text" name="password" id="password" class="input-xlarge" data-rule-required="true" />
                            </div>
                        </div>
                    </div>

                    <div class="control-group">
                        <label class="control-label" for="email">البريد الالكترونى</label>
                        <div class="controls">
                            <div class="span10">
                                <input type="text" name="email" id="email" class="input-xlarge" data-rule-required="true" value="<?php echo $supervisor["email"];?>" />
                            </div>
                        </div>
                    </div>


                    <?php if( $supervisor["id"] != '1' ){ ?>


                    <div class="control-group">
                        <label class="control-label">مفعل</label>
                       <div class="controls">
                          <label class="radio inline">
                              <input type="radio" name="active" value="1" <?php if( $supervisor["active"] ){ ?>checked<?php } ?> /> نعم
                          </label>
                          <label class="radio inline">
                              <input type="radio" name="active" value="0" <?php if( !$supervisor["active"] ){ ?>checked<?php } ?> /> لا
                          </label>
                       </div>
                    </div>


                    <hr />


                    <div class="control-group">
                        <label class="control-label">التحكم بالمهام السريعة ؟</label>
                       <div class="controls">
                          <label class="radio inline">
                              <input type="radio" name="control_tasks" value="1" <?php if( $permission["control_tasks"] ){ ?>checked<?php } ?> /> نعم
                          </label>
                          <label class="radio inline">
                              <input type="radio" name="control_tasks" value="0" <?php if( !$permission["control_tasks"] ){ ?>checked<?php } ?> /> لا
                          </label>
                       </div>
                    </div>


                  <div class="control-group">
                        <label class="control-label">الحكم باللاعبين  ؟</label>
                       <div class="controls">
                          <label class="radio inline">
                              <input type="radio" name="control_players" value="1" <?php if( $permission["control_players"] ){ ?>checked<?php } ?> /> نعم
                          </label>
                          <label class="radio inline">
                              <input type="radio" name="control_players" value="0" <?php if( !$permission["control_players"] ){ ?>checked<?php } ?> /> لا
                          </label>
                       </div>
                  </div>

                  <div class="control-group">
                        <label class="control-label">التحكم بالمشرفين ؟</label>
                       <div class="controls">
                          <label class="radio inline">
                              <input type="radio" name="control_supervisors" value="1" <?php if( $permission["control_supervisors"] ){ ?>checked<?php } ?> /> نعم
                          </label>
                          <label class="radio inline">
                              <input type="radio" name="control_supervisors" value="0" <?php if( !$permission["control_supervisors"] ){ ?>checked<?php } ?> /> لا
                          </label>
                       </div>
                  </div>


                  <div class="control-group">
                        <label class="control-label">التحكم بالقرى  ؟</label>
                       <div class="controls">
                          <label class="radio inline">
                              <input type="radio" name="control_villages" value="1" <?php if( $permission["control_villages"] ){ ?>checked<?php } ?> /> نعم
                          </label>
                          <label class="radio inline">
                              <input type="radio" name="control_villages" value="0" <?php if( !$permission["control_villages"] ){ ?>checked<?php } ?> /> لا
                          </label>
                       </div>
                  </div>

                  <div class="control-group">
                        <label class="control-label">التحكم بالتحالفات  ؟</label>
                       <div class="controls">
                          <label class="radio inline">
                              <input type="radio" name="control_alliances" value="1" <?php if( $permission["control_alliances"] ){ ?>checked<?php } ?> /> نعم
                          </label>
                          <label class="radio inline">
                              <input type="radio" name="control_alliances" value="0" <?php if( !$permission["control_alliances"] ){ ?>checked<?php } ?> /> لا
                          </label>
                       </div>
                  </div>

                  <div class="control-group">
                        <label class="control-label">التحكم بالعمليات الشرائية ؟</label>
                       <div class="controls">
                          <label class="radio inline">
                              <input type="radio" name="control_payment" value="1" <?php if( $permission["control_payment"] ){ ?>checked<?php } ?> /> نعم
                          </label>
                          <label class="radio inline">
                              <input type="radio" name="control_payment" value="0" <?php if( !$permission["control_payment"] ){ ?>checked<?php } ?> /> لا
                          </label>
                       </div>
                  </div>

                  <div class="control-group">
                        <label class="control-label">يستطيع حظر اللاعبين ؟</label>
                       <div class="controls">
                          <label class="radio inline">
                              <input type="radio" name="control_ban_players" value="1" <?php if( $permission["control_ban_players"] ){ ?>checked<?php } ?> /> نعم
                          </label>
                          <label class="radio inline">
                              <input type="radio" name="control_ban_players" value="0" <?php if( !$permission["control_ban_players"] ){ ?>checked<?php } ?> /> لا
                          </label>
                       </div>
                  </div>

                  <div class="control-group">
                        <label class="control-label">التحكم بالدعم الفنى ؟</label>
                       <div class="controls">
                          <label class="radio inline">
                              <input type="radio" name="control_support" value="1" <?php if( $permission["control_support"] ){ ?>checked<?php } ?> /> نعم
                          </label>
                          <label class="radio inline">
                              <input type="radio" name="control_support" value="0" <?php if( !$permission["control_support"] ){ ?>checked<?php } ?> /> لا
                          </label>
                       </div>
                  </div>


                <hr />

                <div class="control-group">
                        <label class="control-label">قسم الشكاوى ضد اللاعبين</label>
                       <div class="controls">
                          <label class="radio inline">
                              <input type="radio" name="cat_1" value="1" <?php if( $support_permission["cat_1"] ){ ?>checked<?php } ?> /> نعم
                          </label>
                          <label class="radio inline">
                              <input type="radio" name="cat_1" value="0" <?php if( !$support_permission["cat_1"] ){ ?>checked<?php } ?> /> لا
                          </label>
                       </div>
                  </div>


                  <div class="control-group">
                        <label class="control-label">قسم الاستفسارات في اللعبه</label>
                       <div class="controls">
                          <label class="radio inline">
                              <input type="radio" name="cat_2" value="1" <?php if( $support_permission["cat_2"] ){ ?>checked<?php } ?> /> نعم
                          </label>
                          <label class="radio inline">
                              <input type="radio" name="cat_2" value="0" <?php if( !$support_permission["cat_2"] ){ ?>checked<?php } ?> /> لا
                          </label>
                       </div>
                  </div>

                  <div class="control-group">
                        <label class="control-label">قسم مشاكل الذهب والدفع</label>
                       <div class="controls">
                          <label class="radio inline">
                              <input type="radio" name="cat_3" value="1" <?php if( $support_permission["cat_3"] ){ ?>checked<?php } ?> /> نعم
                          </label>
                          <label class="radio inline">
                              <input type="radio" name="cat_3" value="0" <?php if( !$support_permission["cat_3"] ){ ?>checked<?php } ?> /> لا
                          </label>
                       </div>
                  </div>

                  <div class="control-group">
                        <label class="control-label">قسم مشاكل اللعبة والاقتراحات</label>
                       <div class="controls">
                          <label class="radio inline">
                              <input type="radio" name="cat_4" value="1" <?php if( $support_permission["cat_4"] ){ ?>checked<?php } ?> /> نعم
                          </label>
                          <label class="radio inline">
                              <input type="radio" name="cat_4" value="0" <?php if( !$support_permission["cat_4"] ){ ?>checked<?php } ?> /> لا
                          </label>
                       </div>
                  </div>

                  <?php } ?>


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