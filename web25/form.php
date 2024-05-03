<html>
  <head>
    <link rel="stylesheet" href="style.css">
    <title>web1</title>
  </head>
  <body>

    <?php
      if (!empty($messages)) {
        print('<div id="messages">');
        foreach ($messages as $message) {
          print($message);
        }
        print('</div>');
      }
    ?>

    <div>
      <form <?php if(!$session_is_login) { print "class='not_display'"; } ?> action="index.php" method="POST">
        <input type="submit" name="destroy" value="Выход">
      </form>
      <form <?php if($session_is_login) { print "class='not_display'"; } ?> action="index.php" method="POST">
        <input type="submit" name="login_session" value="Войти">
      </form>
    </div>

    <div id="form" class="cont">
          <strong>Form</strong>
          <form action="index.php" method="POST">
              <br><label>FIO:<input name="name" placeholder="Введите Ваше имя"
                <?php if ($errors['name']) {print 'class="error"';} ?>
                value="<?php print $values['name']; ?>" 
              /></label>
              <br><label>TEL:<input name="phone" type="tel" placeholder="Ваш номер телефона"
                <?php if ($errors['phone']) {print 'class="error"';} ?>
                value="<?php print $values['phone']; ?>" 
              /></label>
              <br><label>MAIL:<input name="mail" type="email" placeholder="Ваш email"
                <?php if ($errors['mail']) {print 'class="error"';} ?>
                value="<?php print $values['mail']; ?>" 
              /></label>
              <br><label>DATE:<input name="ymd" type="date" value="2023-01-01"
                <?php if ($errors['ymd']) {print 'class="error"';} ?>
                value="<?php print $values['ymd']; ?>" 
              /></label>
              <br><label <?php if ($errors['gender']) {print 'class="error"';} ?> >GENDER  
              <input name="gender" type="radio" <?php if($values['gender'] == 'M'){ print 'checked="checked"'; } ?> value="M">MALE
              <input name="gender" type="radio" <?php if($values['gender'] == 'F'){ print 'checked="checked"'; } ?> value="F"?>FEMALE</label>
              <br><label>LANGUAGE:
              <br><select name="lang[]" multiple="multiple">
                  <option value="1">Pascal</option>
                  <option value="2">C</option>
                  <option value="3">C++</option>
                  <option value="4">Java</option>
                  <option value="5">JavaScript</option>
                  <option value="6">PHP</option>
                  <option value="7">Python</option>
                  <option value="8">Haskel</option>
                  <option value="9">Clojure</option>
                  <option value="10">Prolog</option>
                  <option value="11">Scala</option>
              </select></label>
              <br><label>BIO:<br><textarea name="bio" <?php if ($errors['bio']) {print 'class="error"';} ?> ><?php print $values['bio']; ?></textarea></label>
              <br><label <?php if ($errors['check']) {print 'class="error"';} ?> ><input name="check" type="checkbox">I read contract.</label>
              <br><label><input type="submit" value="SUBMIT"/></label>
          </form>
    </div>
  </body>
</html>