<?php

class Auth
{
    private $hash_salt_1 = 'us_$dUDN4N-53';
    private $hash_salt_2 = 'Yu23ds09*d?';
    private $lang = array('login_lockedout' => "لقد تم حظرك !", 'login_wait30' => "من فضلك قم بالانتظار لمدة 30 دقيقة.", 'login_email_empty' => "حقل البريد فارغ !", 'login_email_short' => "حقل البريد قصير !", 'login_password_empty' => "حقل كلمة المرور فارغ !", 'login_password_short' => "حقل كلمة المرور قصير !", 'login_incorrect' => "بيانات الدخول غير صحيحة !", 'login_attempts_remaining' => "%d عدد محاولات متبقية لك !", 'login_success' => "تم تسجيل الدخول بنجاح !", 'login_already' => "انت مسجل دخول بالفعل !", 'register_username_empty' => "اسم المستخدم فارغ !", 'register_username_short' => "اسم المستخدم قصير جدا !", 'register_password_empty' => "حقل كلمة المرور فارغ !", 'register_password_short' => "حقل كلمة المرور قصير جدا !", 'register_password_nomatch' => "كلمات المرور غير متطابقة !", 'register_password_username' => "لا يمكن ان تحتوى كلمة المرور على اسم المستخدم !", 'register_email_empty' => "حقل البريد فارغ !", 'register_email_short' => "حقل البريد قصير جدا !", 'register_email_invalid' => "البريد غير صالح !", 'register_username_exist' => "اسم المستخدم موجود من قبل !", 'register_email_exist' => "البريد موجود من قبل !", 'register_success' => "تم تسجيل العضوية بنجاح !", 'register_email_loggedin' => "انت مسجل دخول بالفعل :) !", 'deletesession_invalid' => "السيشن غير صالح !", 'sessioninfo_invalid' => "السيشن غير صالح !", 'changepass_username_empty' => "اسم المستخدم فارغ !", 'changepass_username_short' => "اسم المستخدم قصير !", 'changepass_currpass_empty' => "حقل كلمة المرور الحالية فارغ !", 'changepass_currpass_short' => "حقل كلمة المرور الحالية قصير جدا !", 'changepass_newpass_empty' => "حقل كلمة المرور الجديدة فارغ !", 'changepass_newpass_short' => "حقل كلم المرور الجديدة قصير جدا !", 'changepass_password_username' => "كلمة المرور يجب الا تحتوى على اسم المستخدم!", 'changepass_password_nomatch' => "كلمات المرور غير متطابقة!", 'changepass_username_incorrect' => "اسم المستخدم غير صحيح !", 'changepass_success' => "تم تغيير كلمة المرور بنجاح !", 'changepass_currpass_incorrect' => "كلمة المرور الحالية غير صحيحة!", 'resetpass_lockedout' => "لقم تم حظرك !", 'resetpass_wait30' => "برجاء الانتظار لمدة 30 دقيقة ثم المحاولة مرة اخرى.", 'resetpass_email_empty' => "حقل البريد فارغ!", 'resetpass_email_short' => "حقل البريد قصير جدا !", 'resetpass_email_invalid' => "البريد غير صالح !", 'resetpass_email_incorrect' => "البريد غير صحيح !", 'resetpass_attempts_remaining' => "%d عدد محاولات متبقية !", 'resetpass_email_sent' => "تم ارسال رسالة طلب تغيير كلمة المرور الى بريدك !", 'resetpass_key_empty' => "كود استرجاع كلمة المرور فارغ !", 'resetpass_key_short' => "كود استرجاع كلمة المرور قصير جدا !", 'resetpass_newpass_empty' => "جقل كلمة المرور الجديدة فارغة!", 'resetpass_newpass_short' => "كلمة المرور الجديدة قصيرة جدا!", 'resetpass_newpass_username' => "كلمة المرور الجديدة لا يجب ان تحتوى على اسم المستخدم !", 'resetpass_newpass_nomatch' => "كلمات المرور غير متطابقة!", 'resetpass_username_incorrect' => "اسم المستخدم غير صحيح !", 'resetpass_success' => "تم تغيير كلمة المرور بنجاح!", 'resetpass_key_incorrect' => "كود استرجاع كلمة المرور غير صحيح !", 'deleteaccount_username_empty' => "Error encountered !", 'deleteaccount_username_short' => "Error encountered !", 'deleteaccount_username_long' => "Error encountered !", 'deleteaccount_password_empty' => "Password field is empty !", 'deleteaccount_password_short' => "Password is too short !", 'deleteaccount_password_long' => "Password is too long !", 'deleteaccount_username_incorrect' => "المشرف غير موجود !", 'deleteaccount_success' => "Account deleted successfully !", 'deleteaccount_password_incorrect' => "Password is incorrect !", 'logactivity_username_short' => "Error encountered !", 'logactivity_username_long' => "Error encountered !", 'logactivity_action_empty' => "Error encountered !", 'logactivity_action_short' => "Error encountered !", 'logactivity_action_long' => "Error encountered !", 'logactivity_addinfo_long' => "Error encountered !");
    private $max_attempts = 5;
    private $session_duration = '+1 month';
    private $security_duration = '+30 minutes';

    private $db_conn;
    public $errormsg = array();
    public $successmsg;

    public function __construct()
    {
        require SERVER_DIR . '/db.php';
        $this->db_conn = new pdo("$server:host=$hostname;dbname=$database", $username, $password);
        $this->db_conn->query("SET NAMES utf8");
        $this->db_conn->query("SET CHARACTER SET utf8");
    }

    /*
     * Log user
     * @param string $email
     * @param string $password
     * @return boolean
     */
    public function login($email, $password)
    {
        if (!isset($_COOKIE["auth_session"])) {
            $attcount = $this->getattempt($_SERVER['REMOTE_ADDR']);

            if ($attcount >= $this->max_attempts) {
                $this->errormsg[] = $this->lang['login_lockedout'];
                $this->errormsg[] = $this->lang['login_wait30'];

                return false;
            } else {
                // Input verification :

                if (strlen($email) == 0) {
                    $this->errormsg[] = $this->lang['login_email_empty'];
                    return false;
                } elseif (strlen($email) < 3) {
                    $this->errormsg[] = $this->lang['login_email_short'];
                    return false;
                } elseif (strlen($password) == 0) {
                    $this->errormsg[] = $this->lang['login_password_empty'];
                    return false;
                } elseif (strlen($password) > 30) {
                    $this->errormsg[] = $this->lang['login_password_short'];
                    return false;
                } else {
                    // Input is valid

                    $password = $this->hashpass($password);

                    $query = $this->db_conn->prepare("SELECT username FROM users WHERE email = ? AND password = ?");
                    $query->execute(array(
                        $email,
                        $password,
                    ));
                    $count = $query->rowCount();
                    list($username) = $query->fetch();

                    if ($count == 0) {
                        // email and / or password are incorrect

                        $this->errormsg[] = $this->lang['login_incorrect'];

                        $this->addattempt($_SERVER['REMOTE_ADDR']);

                        $attcount = $attcount + 1;
                        $remaincount = $this->max_attempts - $attcount;

                        $this->LogActivity("UNKNOWN", "AUTH_LOGIN_FAIL", "بيانات الدخول غير صحيحة - {$email} / {$password}");

                        $this->errormsg[] = sprintf($this->lang['login_attempts_remaining'], $remaincount);

                        return false;
                    } else {
                        // Account is activated

                        $this->newsession($username);
                        $this->LogActivity($username, "AUTH_LOGIN_SUCCESS", "تم تسجيل الدخول");
                        $this->successmsg[] = $this->lang['login_success'];

                        return true;
                    }
                }
            }
        } else {
            // User is already logged in

            $this->errormsg[] = $this->lang['login_already'];

            return false;
        }
    }

    /*
     * Register a new user into the database
     * @param string $username
     * @param string $password
     * @param string $verifypassword
     * @param string $email
     * @param array permissions
     * @return boolean
     */

    public function register($username, $password, $verifypassword, $email, $permissions = array())
    {
        // Input Verification :

        if (strlen($username) == 0) {
            $this->errormsg[] = $this->lang['register_username_empty'];
        } elseif (strlen($username) < 3) {
            $this->errormsg[] = $this->lang['register_username_short'];
        }
        if (strlen($password) == 0) {
            $this->errormsg[] = $this->lang['register_password_empty'];
        } elseif (strlen($password) < 5) {
            $this->errormsg[] = $this->lang['register_password_short'];
        } elseif ($password !== $verifypassword) {
            $this->errormsg[] = $this->lang['register_password_nomatch'];
        } elseif (strstr($password, $username)) {
            $this->errormsg[] = $this->lang['register_password_username'];
        }
        if (strlen($email) == 0) {
            $this->errormsg[] = $this->lang['register_email_empty'];
        } elseif (strlen($email) < 5) {
            $this->errormsg[] = $this->lang['register_email_short'];
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->errormsg[] = $this->lang['register_email_invalid'];
        }

        if (is_array($this->errormsg) && count($this->errormsg) == 0) {
            // Input is valid

            $query = $this->db_conn->prepare("SELECT * FROM users WHERE username=?");
            $query->execute(array(
                $username,
            ));
            $count = $query->rowCount();

            if ($count != 0) {
                // Username already exists

                $this->LogActivity("UNKNOWN", "AUTH_REGISTER_FAIL", "اسم المستخدم ({$username}) موجود من قبل");

                $this->errormsg[] = $this->lang['register_username_exist'];

                return false;
            } else {
                // Username is not taken

                $query = $this->db_conn->prepare("SELECT * FROM users WHERE email=?");
                $query->execute(array(
                    $email,
                ));
                $count = $query->rowCount();

                if ($count != 0) {
                    // Email address is already used

                    $this->LogActivity("UNKNOWN", "AUTH_REGISTER_FAIL", "البريد ({$email}) مستخدم من قبل ");

                    $this->errormsg[] = $this->lang['register_email_exist'];

                    return false;
                } else {
                    // Email address isn't already used

                    $password = $this->hashpass($password);
                    $permissions = json_encode($permissions);

                    $query = $this->db_conn->prepare("INSERT INTO users (username, password, email, permissions) VALUES (?, ?, ?, ?)");
                    $query->execute(array(
                        $username,
                        $password,
                        $email,
                        $permissions,
                    ));

                    $this->LogActivity($username, "AUTH_REGISTER_SUCCESS", "تم تسجيل العضوية بنجاح");

                    $this->successmsg[] = $this->lang['register_success'];

                    return true;
                }
            }
        } else {
            return false;
        }

    }

    /*
     * Select users from the database
     * @return array
     */
    public function getAll()
    {
        $query = $this->db_conn->prepare("SELECT * FROM users ORDER BY id DESC");
        $query->execute();

        return $query->fetchALL();
    }

    /*
     * Select user from the database
     * @return array
     */
    public function getOne($userid)
    {
        $query = $this->db_conn->prepare("SELECT * FROM users WHERE id = $userid");
        $query->execute();

        return $query->fetch();
    }

    /*
     * update user at the database
     * @return void
     */
    public function update($username, $password, $email, $active, $permissions, $userid)
    {
        if (strlen($username) == 0) {
            $this->errormsg[] = $this->lang['register_username_empty'];
        } elseif (strlen($username) < 3) {
            $this->errormsg[] = $this->lang['register_username_short'];
        }

        if (strlen($password) != 0) {
            if (strlen($password) < 5) {
                $this->errormsg[] = $this->lang['register_password_short'];
            } elseif (strstr($password, $username)) {
                $this->errormsg[] = $this->lang['register_password_username'];
            }
        }

        if (strlen($email) == 0) {
            $this->errormsg[] = $this->lang['register_email_empty'];
        } elseif (strlen($email) < 5) {
            $this->errormsg[] = $this->lang['register_email_short'];
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->errormsg[] = $this->lang['register_email_invalid'];
        }

        if (is_array($this->errormsg) && count($this->errormsg) == 0) {
            $permissions = $permissions == 'all' ? 'all' : json_encode($permissions);
            if (strlen($password) != 0) {
                $password = $this->hashpass($password);
                $query = $this->db_conn->prepare("UPDATE users SET username='$username',password='$password',email='$email',
		    		active='$active',permissions='$permissions' WHERE id='$userid'");
            } else {
                $query = $this->db_conn->prepare("UPDATE users SET username='$username',email='$email',
		    		active='$active',permissions='$permissions' WHERE id='$userid'");
            }
            $query->execute();
            $this->successmsg[] = 'تم تعديل المشرف بنجاح .';

            return true;
        } else {
            return false;
        }
    }

    /*
     * Creates a new session for the provided username and sets cookie
     * @param string $username
     */
    public function newsession($username)
    {
        $hash = md5(microtime());

        // Fetch User ID :

        $query = $this->db_conn->prepare("SELECT id FROM users WHERE username=?");
        $query->execute(array(
            $username,
        ));
        list($uid) = $query->fetch();

        // Delete all previous sessions :

        $query = $this->db_conn->prepare("DELETE FROM sessions WHERE username=?");
        $query->execute(array(
            $username,
        ));

        $ip = $_SERVER['REMOTE_ADDR'];
        $expiredate = date("Y-m-d H:i:s", strtotime($this->session_duration));
        $expiretime = strtotime($expiredate);

        $query = $this->db_conn->prepare("INSERT INTO sessions (uid, username, hash, expiredate, ip) VALUES (?, ?, ?, ?, ?)");
        $query->execute(array(
            $uid,
            $username,
            $hash,
            $expiredate,
            $ip,
        ));

        setcookie("auth_session", $hash, $expiretime);
    }

    public function updatesession($username, $userid)
    {
        $query = $this->db_conn->prepare("UPDATE sessions SET username=? WHERE uid=?");
        $query->execute(array(
            $username,
            $userid,
        ));
    }

    /*
     * Deletes the user's session based on hash
     * @param string $hash
     */
    public function deletesession($hash)
    {
        $query = $this->db_conn->prepare("SELECT username FROM sessions WHERE hash=?");
        $query->execute(array(
            $hash,
        ));
        $count = $query->rowCount();
        list($username) = $query->fetch();

        if ($count == 0) {
            // Hash doesn't exist

            $this->LogActivity("UNKNOWN", "AUTH_LOGOUT", "تم حذف السيشن - سيشن قاعدة البيانات لم تحذف - الرقم العشواءى ({$hash}) اصبح غير موجود ");

            $this->errormsg[] = $this->lang['deletesession_invalid'];

            setcookie("auth_session", $hash, time() - 3600);
        } else {
            // Hash exists, Delete all sessions for that username :

            $query = $this->db_conn->prepare("DELETE FROM sessions WHERE username=?");
            $query->execute(array(
                $username,
            ));

            $this->LogActivity($username, "AUTH_LOGOUT", "تم حذف السيشن - سيشن قاعدة البيانات لم تحذف - الرقم العشواءى ({$hash})");

            setcookie("auth_session", $hash, time() - 3600);
        }
    }

    /*
     * Provides an associative array of user info based on session hash
     * @param string $hash
     * @return array $session
     */

    public function sessioninfo($hash)
    {
        $query = $this->db_conn->prepare("SELECT uid, username, expiredate, ip FROM sessions WHERE hash=?");
        $query->execute(array(
            $hash,
        ));
        $count = $query->rowCount();
        list($session['uid'], $session['username'], $session['expiredate'], $session['ip']) = $query->fetch();

        if ($count == 0) {
            // Hash doesn't exist

            $this->errormsg[] = $this->lang['sessioninfo_invalid'];

            setcookie("auth_session", $hash, time() - 3600);

            return false;
        } else {
            // Hash exists

            return $session;
        }
    }

    /*
     * Checks if session is valid (Current IP = Stored IP + Current date < expire date)
     * @param string $hash
     * @return bool
     */
    public function checksession($hash)
    {
        $query = $this->db_conn->prepare("SELECT username, expiredate, ip FROM sessions WHERE hash=?");
        $query->bindParam(1, $hash, PDO::PARAM_STR);
        $query->execute();
        $count = $query->rowCount();
        list($username, $db_expiredate, $db_ip) = $query->fetch(PDO::FETCH_NUM);

        if ($count == 0) {
            // Hash doesn't exist

            setcookie("auth_session", $hash, time() - 3600);

            $this->LogActivity($username, "AUTH_CHECKSESSION", "تم حذف السيشن - الرقم العشواءى ({$hash}) غير موجود");

            return false;
        } else {
            if ($_SERVER['REMOTE_ADDR'] != $db_ip) {
                // Hash exists, but IP has changed

                $query = $this->db_conn->prepare("DELETE FROM sessions WHERE username=?");
                $query->execute(array(
                    $username,
                ));

                setcookie("auth_session", $hash, time() - 3600);

                $this->LogActivity($username, "AUTH_CHECKSESSION", "تم حذف الكوكيز - اى بى مختلف ( المسجل بقاعدة البيانات : {$db_ip} / الحالى : " . $_SERVER['REMOTE_ADDR'] . " )");

                return false;
            } else {
                $expiredate = strtotime($db_expiredate);
                $currentdate = strtotime(date("Y-m-d H:i:s"));

                if ($currentdate > $expiredate) {
                    // Hash exists, IP is the same, but session has expired

                    $query = $this->db_conn->prepare("DELETE FROM sessions WHERE username=?");
                    $query->execute(array(
                        $username,
                    ));

                    setcookie("auth_session", $hash, time() - 3600);

                    $this->LogActivity($username, "AUTH_CHECKSESSION", "تم حذف الكوكيز - تم انتهاء السيشن ( تاريخ الانتهاء : {$db_expiredate} )");

                    return false;
                } else {
                    // Hash exists, IP is the same, date < expiry date

                    return true;
                }
            }
        }
    }

    /*
     * Returns a random string, length can be modified
     * @param int $length
     * @return string $key
     */
    private function randomkey($length = 10)
    {
        $chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890";
        $key = "";

        for ($i = 0; $i < $length; $i++) {
            $key .= $chars{rand(0, strlen($chars) - 1)};
        }

        return $key;
    }

    /*
     * Changes a user's password, providing the current password is known
     * @param string $username
     * @param string $currpass
     * @param string $newpass
     * @param string $verifynewpass
     * @return boolean
     */
    public function changepass($username, $currpass, $newpass, $verifynewpass)
    {

        if (strlen($username) == 0) {
            $this->errormsg[] = $this->lang['changepass_username_empty'];
        } elseif (strlen($username) < 3) {
            $this->errormsg[] = $this->lang['changepass_username_short'];
        }
        if (strlen($currpass) == 0) {
            $this->errormsg[] = $this->lang['changepass_currpass_empty'];
        } elseif (strlen($currpass) < 5) {
            $this->errormsg[] = $this->lang['changepass_currpass_short'];
        }
        if (strlen($newpass) == 0) {
            $this->errormsg[] = $this->lang['changepass_newpass_empty'];
        } elseif (strlen($newpass) < 5) {
            $this->errormsg[] = $this->lang['changepass_newpass_short'];
        } elseif (strstr($newpass, $username)) {
            $this->errormsg[] = $this->lang['changepass_password_username'];
        } elseif ($newpass !== $verifynewpass) {
            $this->errormsg[] = $this->lang['changepass_password_nomatch'];
        }

        if (is_array($this->errormsg) && count($this->errormsg) == 0) {
            $currpass = $this->hashpass($currpass);
            $newpass = $this->hashpass($newpass);

            $query = $this->db_conn->prepare("SELECT password FROM users WHERE username=?");
            $query->execute(array(
                $username,
            ));
            $count = $query->rowCount();
            list($db_currpass) = $query->fetch();

            if ($count == 0) {
                $this->LogActivity("UNKNOWN", "AUTH_CHANGEPASS_FAIL", "اسم المستخدم غير صحيح ({$username})");

                $this->errormsg[] = $this->lang['changepass_username_incorrect'];

                return false;
            } else {
                if ($currpass == $db_currpass) {
                    $query = $this->db_conn->prepare("UPDATE users SET password=? WHERE username=?");
                    $query->bind_param("ss", $newpass, $username);
                    $query->execute(array(
                        $newpass,
                        $username,
                    ));

                    $this->LogActivity($username, "AUTH_CHANGEPASS_SUCCESS", "تم تغيير كلمة المرور");

                    $this->successmsg[] = $this->lang['changepass_success'];

                    return true;
                } else {
                    $this->LogActivity($username, "AUTH_CHANGEPASS_FAIL", "كلمة المرور الحالية غير صحيحة ( فى قاعدة البيانات : {$db_currpass} / المرسلة : {$currpass} )");

                    $this->errormsg[] = $this->lang['changepass_currpass_incorrect'];

                    return false;
                }
            }
        } else {
            return false;
        }
    }

    /*
     * Give the user the ability to change their password if the current password is forgotten
     * by sending email to the email address associated to that user
     * @param string $username
     * @param string $email
     * @param string $key
     * @param string $newpass
     * @param string $verifynewpass
     * @return boolean
     */
    public function resetpass($username = '0', $email = '0', $key = '0', $newpass = '0', $verifynewpass = '0')
    {
        $attcount = $this->getattempt($_SERVER['REMOTE_ADDR']);

        if ($attcount >= $this->max_attempts) {
            $this->errormsg[] = $this->lang['resetpass_lockedout'];
            $this->errormsg[] = $this->lang['resetpass_wait30'];

            return false;
        } else {
            if ($username == '0' && $key == '0') {
                if (strlen($email) == 0) {
                    $this->errormsg[] = $this->lang['resetpass_email_empty'];
                } elseif (strlen($email) < 5) {
                    $this->errormsg[] = $this->lang['resetpass_email_short'];
                } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $this->errormsg[] = $this->lang['resetpass_email_invalid'];
                }

                $resetkey = $this->randomkey(15);

                $query = $this->db_conn->prepare("SELECT username FROM users WHERE email=?");
                $query->execute(array(
                    $email,
                ));
                $count = $query->rowCount();
                list($username) = $query->fetch();

                if ($count == 0) {
                    $this->errormsg[] = $this->lang['resetpass_email_incorrect'];

                    $attcount = $attcount + 1;
                    $remaincount = $this->max_attempts - $attcount;

                    $this->LogActivity("UNKNOWN", "AUTH_RESETPASS_FAIL", "البريد غير صحيح ({$email})");

                    $this->errormsg[] = sprintf($this->lang['resetpass_attempts_remaining'], $remaincount);

                    $this->addattempt($_SERVER['REMOTE_ADDR']);

                    return false;
                } else {
                    $query = $this->db_conn->prepare("UPDATE users SET resetkey=? WHERE username=?");
                    $query->execute(array(
                        $resetkey,
                        $username,
                    ));

                    $message_from = $this->email_from;
                    $message_subj = $this->site_name . " - طلب استرجاع كلمة المرور !";
                    $message_cont = "Hello {$username}<br/><br/>";
                    $message_cont .= "انت طلبات استرجاع كلمة المرور الخاصة بك فى  " . $this->site_name . "<br/>";
                    $message_cont .= "لاتمام العملية بنجاح , من فضلك اتبع الرابط التالى :<br/><br/>";
                    $message_cont .= "<b><a href=\"" . $this->base_url . "?page=forgot&username={$username}&key={$resetkey}\">استرجاع كلمة المرور</a></b>";
                    $message_head = "From: {$message_from}" . "\r\n";
                    $message_head .= "MIME-Version: 1.0" . "\r\n";
                    $message_head .= "Content-type: text/html; charset=UTF-8" . "\r\n";

                    mail($email, $message_subj, $message_cont, $message_head);

                    $this->successmsg[] = $this->lang['resetpass_email_sent'];

                    return true;
                }
            } else {
                // Reset Password

                if (strlen($key) == 0) {
                    $this->errormsg[] = $this->lang['resetpass_key_empty'];
                } elseif (strlen($key) < 15) {
                    $this->errormsg[] = $this->lang['resetpass_key_short'];
                }
                if (strlen($newpass) == 0) {
                    $this->errormsg[] = $this->lang['resetpass_newpass_empty'];
                } elseif (strlen($newpass) < 5) {
                    $this->errormsg[] = $this->lang['resetpass_newpass_short'];
                } elseif (strstr($newpass, $username)) {
                    $this->errormsg[] = $this->lang['resetpass_newpass_username'];
                } elseif ($newpass !== $verifynewpass) {
                    $this->errormsg[] = $this->lang['resetpass_newpass_nomatch'];
                }

                if (is_array($this->errormsg) && count($this->errormsg) == 0) {
                    $query = $this->db_conn->prepare("SELECT resetkey FROM users WHERE username=?");
                    $query->execute(array(
                        $username,
                    ));
                    $count = $query->rowCount();
                    list($db_key) = $query->fetch();

                    if ($count == 0) {
                        $this->errormsg[] = $this->lang['resetpass_username_incorrect'];

                        $attcount = $attcount + 1;
                        $remaincount = $this->max_attempts - $attcount;

                        $this->LogActivity("UNKNOWN", "AUTH_RESETPASS_FAIL", "اسم المستخدم غير صحيح ({$username})");

                        $this->errormsg[] = sprintf($this->lang['resetpass_attempts_remaining'], $remaincount);

                        $this->addattempt($_SERVER['REMOTE_ADDR']);

                        return false;
                    } else {
                        if ($key == $db_key) {
                            $newpass = $this->hashpass($newpass);

                            $resetkey = '0';

                            $query = $this->db_conn->prepare("UPDATE users SET password=?, resetkey=? WHERE username=?");
                            $query->execute(array(
                                $newpass,
                                $resetkey,
                                $username,
                            ));

                            $this->LogActivity($username, "AUTH_RESETPASS_SUCCESS", "تم استرجاع كلمة المرور - تغيير كود الاسترجاع");

                            $this->successmsg[] = $this->lang['resetpass_success'];

                            return true;
                        } else {
                            $this->errormsg[] = $this->lang['resetpass_key_incorrect'];

                            $attcount = $attcount + 1;
                            $remaincount = 5 - $attcount;

                            $this->LogActivity($username, "AUTH_RESETPASS_FAIL", "كود الاسترجاع غير صحيح ( DB : {$db_key} / المرسل : {$key} )");

                            $this->errormsg[] = sprintf($this->lang['resetpass_attempts_remaining'], $remaincount);

                            $this->addattempt($_SERVER['REMOTE_ADDR']);

                            return false;
                        }
                    }
                } else {
                    return false;
                }
            }
        }
    }

    /*
     * Deletes a user's account. Requires user's password
     * @param string $username
     * @param string $password
     * @return boolean
     */

    public function deleteaccount($userid)
    {
        $query = $this->db_conn->prepare("SELECT id FROM users WHERE id=?");
        $query->execute(array(
            $userid,
        ));
        $count = $query->rowCount();

        if ($count == 0) {
            $this->LogActivity("UNKNOWN", "AUTH_DELETEACCOUNT_FAIL", "رقم اللاعب غير صحيح ({$userid})");
            $this->errormsg[] = $this->lang['deleteaccount_username_incorrect'];

            return false;
        } else {
            $query = $this->db_conn->prepare("DELETE FROM users WHERE id=?");
            $query->execute(array(
                $userid,
            ));

            $query = $this->db_conn->prepare("DELETE FROM sessions WHERE id=?");
            $query->execute(array(
                $userid,
            ));

            $this->LogActivity($userid, "AUTH_DELETEACCOUNT_SUCCESS", "تم مسح العضوية - تم مسح السيشن");

            return true;
        }
    }

    /*
     * Adds a new attempt to database based on user's IP
     * @param string $ip
     */

    public function addattempt($ip)
    {
        $query = $this->db_conn->prepare("SELECT count FROM attempts WHERE ip = ?");
        $query->execute(array(
            $ip,
        ));
        $count = $query->rowCount();
        list($attempt_count) = $query->fetch();

        if ($count == 0) {
            // No record of this IP in attempts table already exists, create new

            $attempt_expiredate = date("Y-m-d H:i:s", strtotime($this->security_duration));
            $attempt_count = 1;

            $query = $this->db_conn->prepare("INSERT INTO attempts (ip, count, expiredate) VALUES (?, ?, ?)");
            $query->execute(array(
                $ip,
                $attempt_count,
                $attempt_expiredate,
            ));
        } else {
            // IP Already exists in attempts table, add 1 to current count

            $attempt_expiredate = date("Y-m-d H:i:s", strtotime($this->security_duration));
            $attempt_count = $attempt_count + 1;

            $query = $this->db_conn->prepare("UPDATE attempts SET count=?, expiredate=? WHERE ip=?");
            $query->execute(array(
                $attempt_count,
                $attempt_expiredate,
                $ip,
            ));
        }
    }

    /*
     * Provides amount of attempts already in database based on user's IP
     * @param string $ip
     * @return int $attempt_count
     */

    public function getattempt($ip)
    {
        $query = $this->db_conn->prepare("SELECT count FROM attempts WHERE ip = ?");
        $query->execute(array(
            $ip,
        ));
        $count = $query->rowCount();
        list($attempt_count) = $query->fetch();

        if ($count == 0) {
            $attempt_count = 0;
        }

        return $attempt_count;
    }

    /*
     * Logs users actions on the site to database for future viewing
     * @param string $username
     * @param string $action
     * @param string $additionalinfo
     * @return boolean
     */

    public function LogActivity($username, $action, $additionalinfo = "none")
    {

        if (strlen($username) == 0) {
            $username = "GUEST";
        } elseif (strlen($username) < 3) {
            $this->errormsg[] = $this->lang['logactivity_username_short'];
            return false;
        }

        if (strlen($action) == 0) {
            $this->errormsg[] = $this->lang['logactivity_action_empty'];
            return false;
        } elseif (strlen($action) < 3) {
            $this->errormsg[] = $this->lang['logactivity_action_short'];
            return false;
        }

        if (strlen($additionalinfo) == 0) {
            $additionalinfo = "none";
        } elseif (strlen($additionalinfo) > 500) {
            $this->errormsg[] = $this->lang['logactivity_addinfo_long'];
            return false;
        }

        if (is_array($this->errormsg) && count($this->errormsg) == 0) {
            $ip = $_SERVER['REMOTE_ADDR'];
            $date = date("Y-m-d H:i:s");

            $query = $this->db_conn->prepare("INSERT INTO activitylog (date, username, action, additionalinfo, ip) VALUES (?, ?, ?, ?, ?)");
            $query->execute(array(
                $date,
                $username,
                $action,
                $additionalinfo,
                $ip,
            ));

            return true;
        }
    }

    /*
     * Hash user's password with SHA512, base64_encode, ROT13 and salts !
     * @param string $password
     * @return string $password
     */

    private function hashpass($password)
    {
        $password = hash("SHA512", base64_encode(str_rot13(hash("SHA512", str_rot13($this->hash_salt_1 . $password . $this->hash_salt_2)))));
        return $password;
    }
}
