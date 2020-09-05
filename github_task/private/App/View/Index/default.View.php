

<h1> Github Login System (Hazem Khaled). </h1>
<br>
<?php

    if ($session->getSession('error')) echo "<h4>" . $session->getSession('error') . "</h4>";
    $session->unsetSession("error");
?>
<form method = "POST" action = "<?= $login_url->url  . "/normalLogin"; ?>">
    <div class="form-group">
        <input type="text"  name = "email" placeholder="Username/Email" class="form-control" />
        <br>
        <input type="password"  name="password" class="form-control" />
        <br>
        <input type="submit" value = "Sign-in" class="btn btn-primary" />
    </div>
</form>

<br>

<a href = "<?= $login_url->url  . "/githubBridge"; ?>" class="btn btn-block btn-social btn-github">
    <span class="fa fa-github"></span> Sign in with github
  </a>