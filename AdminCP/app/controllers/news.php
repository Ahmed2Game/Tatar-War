<?php

load_core('Admin');

class News_Controller extends AdminController
{

    /**
     * Constructor Method
     * This method defines template layout && view file and load model
     */
    public function __construct()
    {
        parent::__construct();
        $this->viewFile = "news";
    }


    /**
     * Index Method
     *
     * This method defines template layout && view file and load model
     * @return void
     */
    public function index()
    {
        $this->load_model('News', 'news');
        if ($_POST) {
            switch (get('page')) {
                case 'add':
                    if (post('subject') != "" AND post('editor1') != "" AND post('lang') != "") {
                        $this->news->create(post('subject'), post('editor1'), post('lang'));
                        $this->viewData['flash_message'] = array('success', "تم انشاء الخبر بنجاح");
                    } else {
                        $this->viewData['flash_message'] = array('error', "لابد من ملئ كل الحقول");
                    }
                    break;
                case 'edit':
                    if (post('subject') != "" AND post('editor1') != "" AND post('lang') != "" And is_get('id')) {
                        $this->news->update(post('subject'), post('editor1'), post('lang'), get('id'));
                        $this->viewData['flash_message'] = array('success', "تم تعديل الخبر بنجاح");
                    } else {
                        $this->viewData['flash_message'] = array('error', "لابد من ملئ كل الحقول");
                    }
                    break;
            }
        }

        if (is_get('page')) {
            if (get('page') == 'add') {
                $this->viewData['page'] = 'add';
            } elseif (get('page') == 'edit' AND is_get('id')) {
                $this->viewData['data'] = $this->news->get_row(get('id'));
                $this->viewData['page'] = 'edit';
            } elseif (get('page') == 'show') {
                $this->viewData['page'] = 'show';
                if (is_get('delete')) {
                    $success = $this->news->delete(get('delete'));
                    if ($success) {
                        $this->viewData['flash_message'] = array('success', "تم الحذف بنجاح");
                    } else {
                        $this->viewData['flash_message'] = array('error', "لم يتم حذف الخبر");
                    }
                }
                $this->viewData['news'] = $this->news->get_all();
            } else {
                return header("Location: index.php");
            }
        } else {
            return header("Location: index.php");
        }

    }

}

//end file
?>
