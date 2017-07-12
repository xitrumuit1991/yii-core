<?php
class SiteController extends FrontController
{

    public $attempts = MAX_TIME_TO_SHOW_CAPTCHA;
    public $counter;

    public function actionDangTin()
    {
        $this->pageTitle = 'Đăng Tin'.' - '.Yii::app()->setting->getItem('defaultPageTitle');
        if(Yii::app()->user->id)
            $user = Users::model()->findByPk(Yii::app()->user->id);
        else
            $user = NULL;

        $model =  new TinRaoVat('dang_tin');
        if(isset($_POST['TinRaoVat']))
        {
            $model->attributes = $_POST['TinRaoVat'];
            // array('address,email,id, title, short_content, content, status, image1, image2, order_display, is_hot, is_new, phone, mobile, state_id, city, created_date, updated_date, slug, job_id, updated_date_status, view, loai_tin, post_user_id, edit_user_id, post_user_name, edit_user_name', 'safe'),
            $model->short_content = StringHelper::limitStringLength( trim( strip_tags($model->content) ),100 );
            $model->content = trim(strip_tags($model->content));
            $model->loai_tin = $_POST['TinRaoVat']['loai_tin'];
            /*khi post Tin lên thì status Inactive (chưa payment)
            Sau khi payment thành công thì update status Active
            Status này cũng có thể được update bởi Mod và Admin*/
            $model->status = STATUS_NEW;
            // updated_date_status,post_user_id, edit_user_id, post_user_name, edit_user_name
            if(!empty($user))
            {
                $model->post_user_id = Yii::app()->user->id;
                $model->post_user_name = $user->full_name;
            }

            if($model->validate())
            {
                if($model->save())
                {
                    $model->saveImage('image1');
                    $model->saveImage('image2');
                    $this->setMessageSuccess('Đăng tin thành công! Đợi admin duyệt.');
                    SendEmail::mailAdminAfterDangTin($model);
                    /*redirect qua trang thanh toán vd: paypal
                    Sau khi thanh toán xong thì update status Active*/
                    if($model->loai_tin==TIN_3_NGAY)
                    {
                        $model = new TinRaoVat('dang_tin');
                    }
                    else{
                        $this->redirect(LINK_PAYSIMPLE);    
                    }
                    
                }
            }
        }
        $this->render('raovat/dang_tin', array(
            'model' => $model,
        ));
    }

    public function actionPaySuccess()
    {
        $this->pageTitle = 'Đăng Tin Success'.' - '.Yii::app()->setting->getItem('defaultPageTitle');
        $this->render('raovat/dang_tin_success', array(
            // 'model' => $model,
        ));
    }

    public function actionPayError()
    {
        $this->pageTitle = 'Đăng Tin Error'.' - '.Yii::app()->setting->getItem('defaultPageTitle');
        $this->render('raovat/dang_tin_error', array(
            // 'model' => $model,
        ));
    }

    /*Khi payment thanh toán thành công thì update lại status và updated_date_status*/
    public function actionAccessPayment() 
    {
        if(isset($_POST))
        {
            echo '<pre>';
            print_r($_POST);
            echo '</pre>';
            $path = YII_UPLOAD_DIR.'/paysimple.txt';
            $myfile = fopen($path, "w") or die("Unable to open file!");
            $txt = $_POST;
            fwrite($myfile, $txt);
            fclose($myfile);
        }
    }

    public function sendPaySimple()
    {
        // $userName = "jdmcpa4u";
        // $superSecretCode = "<CODE HERE>";
        // $timestamp = gmdate("c");
        // $hmac = hash_hmac("sha256", $timestamp, $superSecretCode, true); //note the raw output parameter
        // $hmac = base64_encode($hmac);                                                                                                                                                                                                                            
        // $auth = "Authorization: PSSERVER AccessId = $userName; Timestamp = $timestamp; Signature = $hmac";
        $url = "https://api.paysimple.com/v4/payment";
        $post_args      = json_encode(array('AccountId'  => 37706,'Amount' => $_POST['hamount']));
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $post_args);
        // curl_setopt($curl, CURLOPT_HTTPHEADER, array($auth));
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

        $server_output = curl_exec($curl);

        curl_close ($curl);

        echo '<pre>';
        print_r($server_output);
        echo '</pre>';
        


        /*
        if ( isset( $_POST['submit-form'] ) ) {

            // $userName = "<MYUSERNAME>";
            // $superSecretCode = "<CODE HERE>";
            // $timestamp = gmdate("c");
            // $hmac = hash_hmac("sha256", $timestamp, $superSecretCode, true); //note the raw output parameter
            // $hmac = base64_encode($hmac);                                                                                                                                                                                                                            
            // $auth = "Authorization: PSSERVER AccessId = $userName; Timestamp = $timestamp; Signature = $hmac";
            $url = "https://api.paysimple.com/v4/payment";

            $post_args      = json_encode(array('AccountId'  => 37706,'Amount' => $_POST['hamount']));

            $curl = curl_init();
            curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $post_args);
            // curl_setopt($curl, CURLOPT_HTTPHEADER, array($auth));

            $result = curl_exec($curl);

            var_dump(curl_exec($curl));
            $responseCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            curl_close($curl);
            echo "<br>response: $responseCode <br><br>";
            die();
            }
        */
    }

    

    public function actionIndex()
    {
        $this->pageTitle = Yii::app()->setting->getItem('defaultPageTitle');

        $a = new TinRaoVat;
        $b = new TinRaoVat;
        $list_hot = $a->searchListHotIndex();
        $list_khac = $b->searchListKhacIndex();
        // echo '<pre>';
        // print_r($list_hot);
        // echo '</pre>';

        // echo '<pre>';
        // print_r($list_khac);
        // echo '</pre>';
        // die;

        $this->render('index', array(
            'list_hot' => $list_hot ,
            'list_khac' => $list_khac ,
        ));
    }

    public function actionListTin()
    {
        $state=NULL;
        $job=NULL;
        $get_tin=NULL;
        if(isset($_GET['TinRaoVat']))
        {
            $get_tin = $_GET['TinRaoVat'];
            if(!empty($_GET['TinRaoVat']['s_state_id']))
                $state = State::model()->findByPk($_GET['TinRaoVat']['s_state_id']);

            if(!empty($_GET['TinRaoVat']['s_job_id']))
                $job = Job::model()->findByPk($_GET['TinRaoVat']['s_job_id']);
        }

        if(isset($_GET['j_slug']))
        {
            $job = Job::getDetailBySlug($_GET['j_slug']);
            if(!empty($job))
                $get_tin['s_job_id'] = $job->id;
        }



        $this->pageTitle = 'Tin Rao Vặt - '. Yii::app()->setting->getItem('defaultPageTitle');

        $a = new TinRaoVat();
        $list_raovat_dataProvider = $a->searchListTin($get_tin);

        $this->render('raovat/list_tin', array(
            'list_raovat_dataProvider' => $list_raovat_dataProvider,
            'state'=>$state,
            'job'=>$job,
        ));
    }

    public function actionTinDetail($slug)
    {
        $model = TinRaoVat::getDetailBySlug($slug);
            if(empty($model) || $model->status == STATUS_INACTIVE ) 
                throw new CHttpException(404, 'Sorry! The requested page does not exist.');

        $this->pageTitle = $model->title.' '. Yii::app()->setting->getItem('defaultPageTitle');

        $a = new TinRaoVat();
        $list_raovat_trong_tuan = $a->searchListHotTrongTuan($model->id);

        $this->render('raovat/rao_vat_detail', array(
            'model' => $model,
            'list_raovat_trong_tuan'=>$list_raovat_trong_tuan,
        ));
    }

    public function accessRules()
    {
        return array(
            array('allow',
                'actions' => array('captcha','actions'),
                'users' => array('*'),
            ),
        );
    }
    
    public function actions()
    {
        return array(
            // captcha action renders the CAPTCHA image displayed on the contact page
            'captcha'=>array(
                'class'=>'CCaptchaAction',
                'backColor'=>0xFFFFFF,
            ),
        );
        // return array(
        //     // captcha action renders the CAPTCHA image displayed on the contact page
        //     'captcha' => array(
        //         'class' => 'CCaptchaAction',
        //         'backColor' => 0xFFFFFF,
        //     ),
        //     // page action renders "static" pages stored under 'protected/views/site/pages'
        //     // They can be accessed via: index.php?r=site/page&view=FileName
        //     'page' => array(
        //         'class' => 'CViewAction',
        //     ),
        //     'ajax.'=>'application.components.widget.RegistorWidget',
        //     'ajaxlogin.'=>'application.components.widget.LoginWidget',
        //     'ajaxjoin.'=>'application.components.widget.JoinWidget',
        // );
    }
    
    private function captchaRequired() {
        return Yii::app()->session->itemAt('captchaRequired') >= $this->attempts;
    }
    
    public function actionError()
    {
        if ($error = Yii::app()->errorHandler->error) {
            if (Yii::app()->request->isAjaxRequest)
                echo $error['message'];
            else
                $this->render('error', $error);
        }
    }

    protected function performAjaxValidation($model)
    {
        try {
            if (isset($_POST['ajax'])) {
                echo CActiveForm::validate($model);
                Yii::app()->end();
            }
        } catch (Exception $e) {
            Yii::log("Exception " . print_r($e, true), 'error');
            throw  new CHttpException("Exception " . print_r($e, true));
        }
    }


    

    public function actionContactUs()
    {
        $this->pageTitle = 'Liên Hệ ' . ' - ' . Yii::app()->params['defaultPageTitle'];
        $model = new ContactForm('create');
        //auto fill
        // if (isset(Yii::app()->user->id)) {
        //     $mUser = Users::model()->findByPk(Yii::app()->user->id);
        //     if ($mUser) {
        //         $model->name = $mUser->full_name;
        //         $model->email = $mUser->email;
        //         $model->phone = $mUser->phone;
        //         $model->company = $mUser->company;
        //     }

        // }
        if (isset($_POST['ContactForm'])) {
            $model->attributes = $_POST['ContactForm'];
            if ($model->validate()) 
            {
                $model->message = '<br>' . nl2br($model->message);
                
                if (!empty($model->email)) 
                {
                    // SendEmail::confirmContactMailToUser($model);
                }
                SendEmail::sendContactMailToAdmin($model);

                Yii::app()->user->setFlash('msg', 'Thank you for contacting us. We will respond to you as soon as possible.');
                $this->refresh();
            } else {
                // Yii::log(print_r($model->getErrors(), true), 'error', 'SiteController.actionContact');
            }
        }

        $this->render('contact_us', array(
            'model' => $model,
        ));
    }

}