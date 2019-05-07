<?php if(!class_exists('raintpl')){exit;}?><?php if( isset($notice) ){ ?>

<div class="alert alert-<?php echo $status;?>">
    <strong><?php echo $message;?></strong>
</div>
<?php } ?>

<div class="row-fluid">
  <div class="span12">
    <div class="box">
      <div class="box-title">
          <h3><i class="icon-table"></i> حذف الرسائل المرسلة من لاعب</h3>
          <div class="box-tool">
          </div>
      </div>
      <div class="box-content">
          <form action="" class="form-horizontal" method="POST">
              <div class="control-group">
                <label class="control-label" for="username">اسم العضو</label>
                <div class="controls">
                    <div class="span10">
                        <input type="text" name="username" id="username" class="input-medium" data-rule-required="true" />
                    </div>
                </div>
              </div>

              <div class="form-actions">
                  <input type="submit" class="btn btn-primary" value="حذف">
                  <button type="button" class="btn">مسح</button>
              </div>

          </form>
      </div>
    </div>
  </div>
</div>