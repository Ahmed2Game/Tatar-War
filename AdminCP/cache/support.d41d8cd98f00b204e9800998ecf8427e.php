<?php if(!class_exists('raintpl')){exit;}?><?php if( $page == 'show' ){ ?>

 <div class="row-fluid" >
    <div class="span6">
        <div class="box">
            <div class="box-title">
                <h3><i class="icon-th-large"></i>التنقل</h3>
            </div>
            <div class="box-content">
                <p>
                    <a href="support?page=show"><button class="btn">كل التذاكر</button></a>
                    <a href="support?page=show&status=0"><button class="btn btn-primary">الجديدة</button></a>
                    <a href="support?page=show&status=1"><button class="btn btn-info">مجاب عليها</button></a>
                    <a href="support?page=show&status=2"><button class="btn btn-gray">بانتظار الرد</button></a>
                    <a href="support?page=show&status=3"><button class="btn btn-danger">مغلقة</button></a>
                </p>
            </div>
        </div>
    </div>
</div>
<div class="row-fluid">
    <div class="span12">
        <div class="box">
            <div class="box-title">
                <h3><i class="icon-table"></i> عرض التذاكر</h3>
                <div class="box-tool">
                </div>
            </div>
            <div class="box-content">
                <table class="table table-striped table-hover fill-head">
                    <thead>
                        <tr>
                            <th>S</th>
                            <th>اللاعب</th>
                            <th>عنوان التذكرة</th>
                            <th>القسم</th>
                            <th>تاريخ التذكرة</th>
                            <th>حالة التذكرة</th>
                            <th>اوامر</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $counter1=-1; if( isset($tickets) && is_array($tickets) && sizeof($tickets) ) foreach( $tickets as $key1 => $value1 ){ $counter1++; ?>

                        <?php if( $value1["type"] == 1 ){ ?>

                          <?php $cat = 'قسم الشكاوى ضد اللاعبين';;?>

                        <?php }elseif( $value1["type"] == 2 ){ ?>

                          <?php $cat = 'قسم الاستفسارات في اللعبه';;?>

                        <?php }elseif( $value1["type"] == 3 ){ ?>

                          <?php $cat = 'قسم مشاكل الذهب والدفع';;?>

                        <?php }elseif( $value1["type"] == 4 ){ ?>

                          <?php $cat = 'قسم مشاكل اللعبة والاقتراحات';;?>

                        <?php } ?>


                        <?php if( $value1["status"] == 0 ){ ?>

                          <?php $status = '<span class="btn btn-primary">تذكرة جديدة</a>';;?>

                        <?php }elseif( $value1["status"] == 1 ){ ?>

                          <?php $status = '<span class="btn btn-info">مجاب عليها</a>';;?>

                        <?php }elseif( $value1["status"] == 2 ){ ?>

                          <?php $status = '<span class="btn btn-gray">بانتظار الرد</a>';;?>

                        <?php }elseif( $value1["status"] == 3 ){ ?>

                          <?php $status = '<span class="btn btn-danger">مغلقة</a>';;?>

                        <?php } ?>

                        <tr>
                            <td><?php echo $value1["server_id"];?></td>
                            <td><?php echo $value1["username"];?></td>
                            <td><a class="" href="support?page=read&id=<?php echo $value1["id"];?>"><?php echo $value1["title"];?></a></td>
                            <td><?php echo $cat;?></td>
                            <td style="direction: ltr;"><?php echo $value1["added_time"];?></td>
                            <td><?php echo $status;?></td>
                            <td>
                                <a class="btn btn-danger btn-small" onclick="return confirm('هل انت متاكد من حذف التذكرة ؟');" href="support?page=delete&id=<?php echo $value1["id"];?>"><i class="icon-trash"></i> </a>
                            </td>
                        </tr>
                        <?php } ?>

                    </tbody>
                </table>
                <div class="pagination">
                    <?php echo $pagination;?>

                </div>
            </div>
        </div>
    </div>
</div>
<?php }elseif( $page == 'read' ){ ?>

<div class="row-fluid">
    <div class="span8">
        <div class="box">
            <div class="box-title">
                <h3><i class="icon-file"></i> عرض تذكرة " <?php echo $ticket["title"];?> "</h3>
            </div>
            <div class="box-content">
                <ul class="messages">
                    <li>
                        <div style="margin-left:0;">
                            <div>
                                <h5><?php echo $ticket["username"];?></h5>
                                <span class="time" style="float:left;"><i class="icon-time"></i> <?php echo $ticket["added_time"];?></span>
                            </div>
                            <p><?php echo nl2br($ticket["content"]); ?></p>
                        </div>
                    </li>
                  <?php $counter1=-1; if( isset($replaies) && is_array($replaies) && sizeof($replaies) ) foreach( $replaies as $key1 => $value1 ){ $counter1++; ?>

                    <li>
                        <div style="margin-left:0;">
                            <div>
                                <h5><?php echo $value1["replaier_name"];?></h5>
                                <?php if( $value1["is_player"] == 0 ){ ?><label class="label label-info">ادارة</label><?php } ?>

                                <span class="time" style="float:left;"><i class="icon-time"></i> <?php echo $value1["added_time"];?></span>
                            </div>
                            <p><?php echo nl2br($value1["replay"]); ?></p>
                             <a class="btn btn-danger btn-small" onclick="return confirm('هل انت متاكد من حذف الرد ؟');" href="support?page=read&id=<?php echo $ticket["id"];?>&cid=<?php echo $value1["id"];?>"><i class="icon-trash"> حذف </i></a>
                        </div>
                    </li>
                  <?php } ?>

                </ul>

                <div class="messages-input-form">
                    <form method="POST" action="">
                    <input type="hidden" name="ticketid" value="<?php echo $ticket["id"];?>" />
                        <div class="input">
                            <textarea name="replay" class="input-block-level" placeholder="اكتب هنا" rows="4"></textarea>
                        </div>
                        <div class="buttons">
                            <button type="submit" class="btn btn-primary"><i class="icon-share-alt"></i></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="span4">
      <div class="box">
        <div class="box-title">
            <h3><i class="icon-table"></i> اعدادات التذكرة</h3>
            <div class="box-tool">
            </div>
        </div>
        <div class="box-content">
            <form action=""  method="POST"  class="form-horizontal">
            <input type="hidden" name="ticketid" value="<?php echo $ticket["id"];?>" />
                <div class="control-group">
                  <div>
                      <div class="span11">
                        <select name="status" >
                              <option <?php if( $ticket["status"] == 0 ){ ?>selected="selected"<?php } ?> value="0">تذكرة جديدة</option>
                              <option <?php if( $ticket["status"] == 1 ){ ?>selected="selected"<?php } ?> value="1">مجاب عليها</option>
                              <option <?php if( $ticket["status"] == 2 ){ ?>selected="selected"<?php } ?> value="2">فى انتظار الرد</option>
                              <option <?php if( $ticket["status"] == 3 ){ ?>selected="selected"<?php } ?> value="3">مغلقة</option>
                        </select>
                      </div>
                  </div>
                </div>

                <div class="control-group">
                  <div>
                      <div class="span11">
                        <select name="type" >
                              <option <?php if( $ticket["type"] == 1 ){ ?>selected="selected"<?php } ?> value="1">قسم الشكاوى ضد اللاعبين</option>
                              <option <?php if( $ticket["type"] == 2 ){ ?>selected="selected"<?php } ?> value="2">قسم الاستفسارات في اللعبه</option>
                              <option <?php if( $ticket["type"] == 3 ){ ?>selected="selected"<?php } ?> value="3">قسم مشاكل الذهب والدفع</option>
                              <option <?php if( $ticket["type"] == 4 ){ ?>selected="selected"<?php } ?> value="4">قسم مشاكل اللعبة والاقتراحات</option>
                        </select>
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
<?php } ?>