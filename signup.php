<?php
  include_once 'header.php';
?>
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
  <section class = "main-container">
    <div class = "main-wrapper">
      <h2>Signup</h2>
      <form class="signup-form" action="includes/signup.inc.php" method="POST" enctype="multipart/form-data">
        <input type = "text" name = "first" placeholder="First Name">
        <input type = "text" name = "last" placeholder="Last Name">
        <input type = "text" name = "email" placeholder="Email">
        <input type = "text" name = "uid" placeholder="Username">
        <input type = "password" name = "pwd" placeholder="Password">
        <div class="captcha_wrapper">
          <div class="g-recaptcha" data-sitekey="6LfBQGEUAAAAALhb_EdAui-APicOoLBisQnHsm4v"></div>
        </div>
        <button type="submit" name="submit">Sign Up</button>
      </form>
    </div>
  </section>
<?php
  include_once 'footer.php';
?>
