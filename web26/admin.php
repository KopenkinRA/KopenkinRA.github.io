<?php
  header('Content-Type: text/html; charset=UTF-8');
  $user = 'u67356';
  $pass = '5898284';
  $db = new PDO('mysql:host=localhost;dbname=u67356', $user, $pass, [
    PDO::ATTR_PERSISTENT => true, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
  ]);

  function is_login($db, $login){
    $sel = $db -> prepare('SELECT login FROM users WHERE login=:login');
    $sel -> execute(['login' => $login]);
    $id = $sel -> fetch();
    return !empty($id);
  } function id_by_login($db, $login){
    $sel_id = $db -> prepare('SELECT id FROM users WHERE login=:login');
    $sel_id -> execute(['login' => $login]);
    $id = $sel_id -> fetch();
    return $id['id'];
  } function is_admin($db, $login, $pass){
    try {
      $sel_pass = $db->prepare("SELECT pass from admins WHERE login=:login");
      $sel_pass -> execute(['login' => $login]);
      $res = $sel_pass -> fetch();
    } catch(PDOException $e) {
      print('Error : ' . $e->getMessage());
      exit();
    }
    if(!empty($res) && $res['pass'] == $pass) return true;
    else return false;
  } function errors_from_cookie(){
    $errors = array();
    $errors['login'] = !empty($_COOKIE['login_error']);
    $errors['name'] = !empty($_COOKIE['name_error']);
    $errors['phone'] = !empty($_COOKIE['phone_error']);
    $errors['mail'] = !empty($_COOKIE['mail_error']);
    $errors['ymd'] = !empty($_COOKIE['ymd_error']);
    $errors['gender'] = !empty($_COOKIE['gender_error']);
    $errors['bio'] = !empty($_COOKIE['bio_error']);

    if ($errors['login']) setcookie('login_error', '', 100000);
    if ($errors['name']) setcookie('name_error', '', 100000);
    if ($errors['phone']) setcookie('phone_error', '', 100000);
    if ($errors['mail']) setcookie('mail_error', '', 100000);
    if ($errors['ymd']) setcookie('ymd_error', '', 100000);
    if ($errors['gender']) setcookie('gender_error', '', 100000);
    if ($errors['bio']) setcookie('bio_error', '', 100000);
    return $errors;
  } function values_from_cookie(){
    $values = array();
    $values['login'] = empty($_COOKIE['login_value']) ? '' : strip_tags($_COOKIE['login_value']);
    $values['name'] = empty($_COOKIE['name_value']) ? '' : strip_tags($_COOKIE['name_value']);
    $values['phone'] = empty($_COOKIE['phone_value']) ? '' : strip_tags($_COOKIE['phone_value']);
    $values['mail'] = empty($_COOKIE['mail_value']) ? '' : strip_tags($_COOKIE['mail_value']);
    $values['ymd'] = empty($_COOKIE['ymd_value']) ? '' : strip_tags($_COOKIE['ymd_value']);
    $values['gender'] = empty($_COOKIE['gender_value']) ? '' : strip_tags($_COOKIE['gender_value']);
    $values['bio'] = empty($_COOKIE['bio_value']) ? '' : strip_tags($_COOKIE['bio_value']);
    return $values;
  } function set_values_from_login($db, $values, $login){
    try {
      $sel_per = $db -> prepare("
        SELECT b.name, b.phone, b.mail, b.ymd, b.gender, b.bio
        FROM users a, persons b
        WHERE a.login=:login and b.id=a.id
      ");
      $sel_per -> execute(['login' => $login]);
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
  } function error_in_post(){
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
    }
    return $errors;
  } function update_table($db, $id){
    try {
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
        'id' => $id
      ]);
    } catch(PDOException $e) {
      print('Error : ' . $e -> getMessage());
      exit();
    } try {
      $del_langs = $db -> prepare("DELETE FROM connections WHERE id=:id");
      $del_langs -> execute(['id' => $id]);
      foreach($_POST['lang'] as $lang) {
        $ins_langs = $db -> prepare("INSERT INTO connections (id, lang) VALUES (:id, :lang)");
        $ins_langs -> execute(['id' => $id, 'lang' => $lang]);
      }
    } catch(PDOException $e) {
      print('Error : ' . $e -> getMessage());
      exit();
    }
  } function delete_from_table($db, $id){
    try {
      $del_lang = $db -> prepare("DELETE FROM connections WHERE id=:id");
      $del_lang -> execute(['id' => $id]);
    } catch(PDOException $e) {
      print('Error : ' . $e -> getMessage());
      exit();
    }
    try {
      $del_pers = $db -> prepare("DELETE FROM persons WHERE id=:id");
      $del_pers -> execute(['id' => $id]);
    } catch(PDOException $e) {
      print('Error : ' . $e -> getMessage());
      exit();
    }
    try {
      $del_user = $db -> prepare("DELETE FROM users WHERE id=:id");
      $del_user -> execute(['id' => $id]);
    } catch(PDOException $e) {
      print('Error : ' . $e -> getMessage());
      exit();
    }
  } function insert_into_table($db){
    try {
      $ins_per = $db -> prepare("
        INSERT INTO persons (name, phone, mail, ymd, gender, bio)
        VALUES (:name, :phone, :mail, :ymd, :gender, :bio)
      ");
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
    } catch(PDOException $e) {
      print('Error : ' . $e -> getMessage());
      exit();
    }
  } function clear_error_cookie(){
    setcookie('name_error', '', 100000);
    setcookie('phone_error', '', 100000);
    setcookie('mail_error', '', 100000);
    setcookie('ymd_error', '', 100000);
    setcookie('gender_error', '', 100000);
    setcookie('bio_error', '', 100000);
  } function langs($db, $id){
    try {
      $langs = '';
      $sel_lang = $db -> prepare("
        SELECT DISTINCT a.lang
        FROM language a, connections c
        WHERE c.lang=a.lang_id and c.id=:id
      ");
      $sel_lang -> execute(['id' => $id]);
      $lang = $sel_lang -> fetch();
      if(!empty($lang)){
        $langs = $lang['lang'];
        $lang = $sel_lang -> fetch();
        while(!empty($lang)){
          $langs = $langs . ', ' . $lang['lang'];
          $lang = $sel_lang -> fetch();
        }
      }
    } catch(PDOException $e) {
      print('Error : ' . $e -> getMessage());
      exit();
    }
    return $langs;
  } function langs_counts($db){
    $langs = array();
    try {
      $sel_lang = $db -> prepare("
        SELECT a.lang as lang, count(*) as count
        FROM language a, connections b
        WHERE a.lang_id=b.lang
        GROUP BY b.lang
      ");
      $sel_lang -> execute();
      $lang = $sel_lang -> fetch();
      while(!empty($lang)){
        $langs[] = $lang;
        $lang = $sel_lang -> fetch();
      }
    } catch(PDOException $e) {
      print('Error : ' . $e -> getMessage());
      exit();
    }
    return $langs;
  } function select_from_table($db){
    $persons = array();
    try {
      $sel_per = $db -> prepare("
        SELECT a.login, a.pass, a.id, b.name, b.phone, b.mail, b.ymd, b.gender, b.bio
        FROM users a, persons b
        WHERE b.id=a.id
      ");
      $sel_per -> execute();
      while($fetch = $sel_per -> fetch()){
        $person['login'] = $fetch['login'];
        $person['pass'] = $fetch['pass'];
        $person['id'] = $fetch['id'];
        $person['name'] = $fetch['name'];
        $person['phone'] = $fetch['phone'];
        $person['mail'] = $fetch['mail'];
        $person['ymd'] = $fetch['ymd'];
        $person['gender'] = $fetch['gender'];
        $person['bio'] = $fetch['bio'];
        $person['langs'] = langs($db, $person['id']);
        $persons[] = $person;
      }
    } catch(PDOException $e) {
      print('Error : ' . $e -> getMessage());
      exit();
    }
    return $persons;
  }

  if (
      empty($_SERVER['PHP_AUTH_USER']) ||
      empty($_SERVER['PHP_AUTH_PW']) ||
      !is_admin(
        $db,
        $_SERVER['PHP_AUTH_USER'],
        $_SERVER['PHP_AUTH_PW']
      )
    ) {
    header('HTTP/1.1 401 Unanthorized');
    header('WWW-Authenticate: Basic realm="My site"');
    print('<h1>401 Требуется авторизация</h1>');
    exit();
  } else {
    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
      $persons = select_from_table($db);
      $errors = errors_from_cookie();
      $values = values_from_cookie();
      $langs = langs_counts($db);
      $lang_names = array();
      $lang_counts = array();
      foreach($langs as $lang){
        $lang_names[] = '<td>'.$lang['lang'].'</td>
        ';
        $lang_counts[] = '<td>'.$lang['count'].'</td>
        ';
      }
      $persons_messages = array();
      foreach($persons as $person){
        $col = '';
        foreach($person as $field){
          $col = $col.' <td>'.$field.'</td>
          ';
        }
        $persons_messages[] = '<tr>'.$col.'</tr>
        ';
      }
      include('form.php');
    } else {
      if(is_login($db, $_POST['login'])) {
        $id = id_by_login($db, $_POST['login']);
        if($_POST['command'] == 'update' && !error_in_post()) {
          update_table($db, $id);
          clear_error_cookie();
        }
        if($_POST['command'] == 'delete') {
          delete_from_table($db, $id);
          clear_error_cookie();
        }
        setcookie('login_error', '', 100000);
      } else {
        setcookie('login_error', '1', time() + 24 * 60 * 60);
      }
      setcookie('login_value', $_POST['login'], time() + 24 * 60 * 60);
      header('Location: ./admin.php');
    }
  }
?>