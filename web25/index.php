<?php
  header('Content-Type: text/html; charset=UTF-8');
  $user = 'u';
  $pass = '';
  $db = new PDO('mysql:host=localhost;dbname=u', $user, $pass, [
    PDO::ATTR_PERSISTENT => true, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
  ]);
  $session_is_login = !empty($_COOKIE[session_name()]) && session_start() && !empty($_SESSION['login']);

  if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $messages = array();
    if (!empty($_COOKIE['save'])) {
      setcookie('save', '', 100000);
      setcookie('login', '', 100000);
      setcookie('pass', '', 100000);
      $messages[] = 'Спасибо, результаты сохранены. ';
      if (!empty($_COOKIE['pass'])) {
        $messages[] = sprintf(
          'Вы можете <a href="login.php">войти</a> с логином <strong>%s</strong>
          и паролем <strong>%s</strong> для изменения данных.',
          strip_tags($_COOKIE['login']),
          strip_tags($_COOKIE['pass'])
        );
      }
    }

    $errors = array();
    $errors['name'] = !empty($_COOKIE['name_error']);
    $errors['phone'] = !empty($_COOKIE['phone_error']);
    $errors['mail'] = !empty($_COOKIE['mail_error']);
    $errors['ymd'] = !empty($_COOKIE['ymd_error']);
    $errors['gender'] = !empty($_COOKIE['gender_error']);
    $errors['bio'] = !empty($_COOKIE['bio_error']);
    $errors['check'] = !empty($_COOKIE['check_error']);

    $error_in_session = $errors['name'] || $errors['phone'] || $errors['mail']
      || $errors['ymd'] || $errors['gender'] || $errors['bio'] || $errors['check'];

    if ($errors['name']) {
      setcookie('name_error', '', 100000);
      $messages[] = '<div class="error">Input Correct NAME.</div>';
    } if ($errors['phone']) {
      setcookie('phone_error', '', 100000);
      $messages[] = '<div class="error">Input Correct PHONE.</div>';
    } if ($errors['mail']) {
      setcookie('mail_error', '', 100000);
      $messages[] = '<div class="error">Input Correct MAIL.</div>';
    } if ($errors['ymd']) {
      setcookie('ymd_error', '', 100000);
      $messages[] = '<div class="error">Input Correct DATA.</div>';
    } if ($errors['gender']) {
      setcookie('gender_error', '', 100000);
      $messages[] = '<div class="error">Input gender.</div>';
    } if ($errors['bio']) {
      setcookie('bio_error', '', 100000);
      $messages[] = '<div class="error">Input BIO.</div>';
    } if ($errors['check']) {
      setcookie('check_error', '', 100000);
      $messages[] = '<div class="error">Read contract.</div>';
    }

    $values = array();
    $values['name'] = empty($_COOKIE['name_value']) ? '' : strip_tags($_COOKIE['name_value']);
    $values['phone'] = empty($_COOKIE['phone_value']) ? '' : strip_tags($_COOKIE['phone_value']);
    $values['mail'] = empty($_COOKIE['mail_value']) ? '' : strip_tags($_COOKIE['mail_value']);
    $values['ymd'] = empty($_COOKIE['ymd_value']) ? '' : strip_tags($_COOKIE['ymd_value']);
    $values['gender'] = empty($_COOKIE['gender_value']) ? '' : strip_tags($_COOKIE['gender_value']);
    $values['bio'] = empty($_COOKIE['bio_value']) ? '' : strip_tags($_COOKIE['bio_value']);
    $values['check'] = empty($_COOKIE['check_value']) ? '' : strip_tags($_COOKIE['check_value']);

    if (!$error_in_session && $session_is_login) {
      try {
        $sel_id = $db -> prepare("SELECT id from users where login=:login");
        $sel_id -> execute(['login' => $_SESSION['login']]);
        $res_id = $sel_id -> fetch();
        $user_id = $res_id['id'];
      } catch(PDOException $e) {
        print('Error : ' . $e -> getMessage());
        exit();
      } try {
        $sel_per = $db -> prepare("SELECT name, phone, mail, ymd, gender, bio from persons where id=:id");
        $sel_per -> execute(['id' => $user_id]);
        $person = $sel_per -> fetch();
      } catch(PDOException $e) {
        print('Error : ' . $e -> getMessage());
        exit();
      }
      $values['name'] = strip_tags($person['name']);
      $values['phone'] = strip_tags($person['phone']);
      $values['mail'] = strip_tags($person['mail']);
      $values['ymd'] = strip_tags($person['ymd']);
      $values['gender'] = strip_tags($person['gender']);
      $values['bio'] = strip_tags($person['bio']);
      printf('Вход с логином %s, uid %d', $_SESSION['login'], $_SESSION['uid']);
    }
    include('form.php');
  } else {
    if(!empty($_POST['login_session']) && !$session_is_login){
      header('Location: ./login.php');
      exit();
    }
    if(!empty($_POST['destroy']) && $session_is_login){
      session_destroy();
      header('Location: ./');
      exit();
    }

    $errors = FALSE;
    if (empty($_POST['name']) || !preg_match('/^[a-z ]+$/i', $_POST['name'])) {
      setcookie('name_error', '1', time() + 24 * 60 * 60);
      setcookie('name_value', $_POST['name'], time() + 24 * 60 * 60);
      $errors = TRUE;
    } else {
      setcookie('name_value', $_POST['name'], time() + 30 * 24 * 60 * 60);
    } if (empty($_POST['phone']) || !preg_match('/^\+?[0-9]+$/', $_POST['phone'])) {
      setcookie('phone_error', '1', time() + 24 * 60 * 60);
      setcookie('phone_value', $_POST['phone'], time() + 24 * 60 * 60);
      $errors = TRUE;
    } else {
      setcookie('phone_value', $_POST['phone'], time() + 30 * 24 * 60 * 60);
    } if (empty($_POST['mail']) || !preg_match('/^[0-9a-zA-Z_]+@[a-z]+\.[a-z]+/', $_POST['mail'])) {
      setcookie('mail_error', '1', time() + 24 * 60 * 60);
      setcookie('mail_value', $_POST['mail'], time() + 24 * 60 * 60);
      $errors = TRUE;
    } else {
      setcookie('mail_value', $_POST['mail'], time() + 30 * 24 * 60 * 60);
    } if (empty($_POST['ymd']) || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $_POST['ymd'])) {
      setcookie('ymd_error', '1', time() + 24 * 60 * 60);
      setcookie('ymd_value', $_POST['ymd'], time() + 24 * 60 * 60);
      $errors = TRUE;
    } else {
      setcookie('ymd_value', $_POST['ymd'], time() + 30 * 24 * 60 * 60);
    } if (empty($_POST['gender']) || !preg_match('/^[MF]$/', $_POST['gender'])) {
      setcookie('gender_error', '1', time() + 24 * 60 * 60);
      setcookie('gender_value', $_POST['gender'], time() + 24 * 60 * 60);
      $errors = TRUE;
    } else {
      setcookie('gender_value', $_POST['gender'], time() + 30 * 24 * 60 * 60);
    } if (empty($_POST['bio'])) {
      setcookie('bio_error', '1', time() + 24 * 60 * 60);
      setcookie('bio_value', $_POST['bio'], time() + 24 * 60 * 60);
      $errors = TRUE;
    } else {
      setcookie('bio_value', $_POST['bio'], time() + 30 * 24 * 60 * 60);
    } if (empty($_POST['check'])) {
      setcookie('check_error', '1', time() + 24 * 60 * 60);
      setcookie('check_value', $_POST['check'], time() + 24 * 60 * 60);
      $errors = TRUE;
    } else {
      setcookie('check_value', $_POST['check'], time() + 30 * 24 * 60 * 60);
    } if ($errors) {
      header('Location: index.php');
      exit();
    } else {
      setcookie('name_error', '', 100000);
      setcookie('phone_error', '', 100000);
      setcookie('mail_error', '', 100000);
      setcookie('ymd_error', '', 100000);
      setcookie('gender_error', '', 100000);
      setcookie('bio_error', '', 100000);
      setcookie('check_error', '', 100000);
    }

    if ($session_is_login) {
      try {
        $sel_id = $db -> prepare("SELECT id from users where login=:login");
        $sel_id -> execute(['login' => $_SESSION['login']]);
        $res_id = $sel_id -> fetch();
        $user_id = $res_id['id'];
      } catch(PDOException $e) {
        print('Error : ' . $e -> getMessage());
        exit();
      } try {
        $upd_per = $db -> prepare(
          "UPDATE persons SET
            name = :name,
            phone = :phone,
            mail = :mail,
            ymd = :ymd,
            gender = :gender,
            bio = :bio
          WHERE id = :id"
        );
        $upd_per -> execute([
          'name' => $_POST['name'],
          'phone' => $_POST['phone'],
          'mail' => $_POST['mail'],
          'ymd' => $_POST['ymd'],
          'gender' => $_POST['gender'],
          'bio' => $_POST['bio'],
          'id' => $user_id
        ]);
      } catch(PDOException $e) {
        print('Error : ' . $e -> getMessage());
        exit();
      } try {
        $del_langs = $db -> prepare("DELETE FROM connections WHERE id=:id");
        $del_langs -> execute(['id' => $user_id]);
        foreach($_POST['lang'] as $lang) {
          $ins_langs = $db -> prepare("INSERT INTO connections (id, lang) VALUES (:id, :lang)");
          $ins_langs -> execute(['id' => $user_id, 'lang' => $lang]);
        }
      } catch(PDOException $e) {
        print('Error : ' . $e -> getMessage());
        exit();
      }
    } else {
      try {
        $ins_per = $db -> prepare(
          "INSERT INTO persons (name, phone, mail, ymd, gender, bio) VALUES (:name, :phone, :mail, :ymd, :gender, :bio)"
        );
        $ins_per -> execute([
          'name'=>$_POST['name'],
          'phone'=>$_POST['phone'],
          'mail'=>$_POST['mail'],
          'ymd'=>$_POST['ymd'],
          'gender'=>$_POST['gender'],
          'bio'=>$_POST['bio']
        ]);
      } catch(PDOException $e) {
        print('Error : ' . $e -> getMessage());
        exit();
      } try {
        $last_id = $db -> lastInsertId();
        if(!empty($_POST['lang'])){
          foreach ($_POST['lang'] as $lang) {
            $ins_langs = $db -> prepare("INSERT INTO connections (id, lang) VALUES (:id, :lang)");
            $ins_langs -> execute(['id' => $last_id, 'lang' => $lang]);
          }
        }
        $login = 'u' . $last_id;
        $pass = rand(1000, 9999);
        setcookie('login', $login);
        setcookie('pass', $pass);
        $_SESSION['login'] = $login;
        $_SESSION['uid'] = session_id();
        $ins_user = $db -> prepare("INSERT INTO users (login, pass, id) VALUES (:login, :pass, :id)");
        $ins_user -> execute(['login' => $login, 'pass' => $pass, 'id' => $last_id]);
      } catch(PDOException $e) {
        print('Error : ' . $e -> getMessage());
        exit();
      }
    }
    setcookie('save', '1');
    header('Location: ./');
  }
?>
