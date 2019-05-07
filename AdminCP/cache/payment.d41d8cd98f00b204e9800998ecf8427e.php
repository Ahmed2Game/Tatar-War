<?php if(!class_exists('raintpl')){exit;}?><div class="row-fluid">
  <div class="span8">
    <div class="box">
      <div class="box-title">
          <h3><i class="icon-table"></i> البحث</h3>
      </div>
      <div class="box-content">
          <form action="" class="form-horizontal">
              <div class="control-group">
              <label class="control-label" for="type"> تصنيف العرض</label>
                     <div class="controls">
                        <select class="input-medium" name="type" tabindex="1">
                          <option>اختر نوع العملية</option>
                          <option value="1">مكتمله</option>
						  <option value="0">لم تكتمل</option>
                        </select>
                     </div>
              </div>

              <div class="form-actions">
                  <input type="submit" class="btn btn-primary" value="عرض">
              </div>

          </form>
      </div>
    </div>
  </div>
  <div class="span4">
    <div class="box">
      <div class="box-title">
          <h3><i class="icon-table"></i> البحث</h3>
          <div class="box-tool">
          </div>
      </div>
      <div class="box-content">
          <form action="" class="form-horizontal">
              <div class="control-group">
                <label class="control-label" for="word">كلمة البحث</label>
                <div class="controls">
                    <div class="span10">
                        <input type="text" name="word" id="word" class="input-small" data-rule-required="true" />
                    </div>
                </div>
              </div>

              <div class="control-group">
                        <label class="control-label">نوع البحث</label>
                       <div class="controls">
                          <label class="radio inline">
                              <input type="radio" name="id" value="1" checked /> رقم العملية
                          </label>
                          <label class="radio inline">
                              <input type="radio" name="id" value="2" /> اسم اللعب
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

<div class="row-fluid">
    <div class="span12">
        <div class="box">
            <div class="box-title">
                <h3><i class="icon-table"></i> العمليات الشرائية</h3>
                <div class="box-tool">
                </div>
            </div>
            <div class="box-content">
                <table class="table table-striped table-hover fill-head">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>رقم العملية</th>
                            <th>اسم اللاعب</th>
                            <th>الذهب</th>
                            <th>المبلغ</th>
                            <th>الوقت</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php $counter1=-1; if( isset($dataList) && is_array($dataList) && sizeof($dataList) ) foreach( $dataList as $key1 => $value1 ){ $counter1++; ?>

                        <tr>
                          <td><?php echo $key1;?></td>
                          <td><?php echo $value1["transID"];?></td>
                          <td>
                              <a href="<?php echo $url;?>profile?uid=<?php echo $value1["userid"];?>"><?php echo $value1["usernam"];?></a>
                          </td>
                          <td><?php echo $value1["golds"];?></td>
                          <td><?php echo $value1["money"];?> <?php echo $value1["currency"];?></td>
                          <td><?php echo $value1["time"];?></td>
                        </tr>
                    <?php } ?>

                    </tbody>
                </table>
                <ul class="pager">
                    <li class="previous"><a href="<?php echo $getNextLink;?>">اقدم &larr;</a></li>
                    <li class="next"><a href="<?php echo $getPreviousLink;?>">&rarr; احدث</a></li>
                </ul>
            </div>
        </div>
    </div>
</div>