<body class="profile-page">
<div class="tim-row" id="navbar-row">
<div id="navbar">
<nav class="navbar navbar-danger" role="navigation">
<div class="container-fluid">
<div class="navbar-header">
<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
<span class="sr-only">Toggle navigation</span>
<span class="icon-bar"></span>
<span class="icon-bar"></span>
<span class="icon-bar"></span>
</button>
<a class="navbar-brand" href="<?=BASE_URL?>"><img src="<?=ASSESTS_URI ?>images/afterglow_logo_l.png" style="width:150px;margin-top:-40px"></a>
</div>
<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1" style="float:right">
<ul class="nav navbar-nav">
<li style="float:left">
</li>
<li class="dropdown">
<a href="#" class="dropdown-toggle" data-toggle="dropdown">اشتراكات <b class="caret"></b></a>
<ul class="dropdown-menu">
<li><a href="<?=BASE_URL . LINK_SIGN?>subscribe/addSub">أضافه اشتراك </a></li>
<li><a href="<?=BASE_URL . LINK_SIGN?>subscribe/showSub">الاشتراكات</a></li>
</ul>
</li>
<li class="dropdown">
<a href="#" class="dropdown-toggle" data-toggle="dropdown">الحسابات <b class="caret"></b></a>
<ul class="dropdown-menu">
<li><a href="<?=BASE_URL . LINK_SIGN?>accounts/addAccounts">اضافه حسابات</a></li>
<li><a href="<?=BASE_URL . LINK_SIGN?>accounts/showAccounts">عرض الحسابات</a></li>
<li><a href="<?=BASE_URL . LINK_SIGN?>accounts/settings">أعدادات الحسابات</a></li>
<li><a href="<?=BASE_URL . LINK_SIGN?>accounts/weekReady">تجهيز الحسابات</a></li>
<li><a href="<?=BASE_URL . LINK_SIGN?>accounts/notWork">حسابات لا تعمل</a></li>
</ul>
</li>
<li class="dropdown">
<a href="#" class="dropdown-toggle" data-toggle="dropdown">المتابعين <b class="caret"></b></a>
<ul class="dropdown-menu">
<li><a href="<?=BASE_URL . LINK_SIGN?>follows/increaseFollow">زياده المتابعين</a></li>
</ul>
</li>
<li class="dropdown">
<a href="#" class="dropdown-toggle" data-toggle="dropdown">تغريدات <b class="caret"></b></a>
<ul class="dropdown-menu">
<li><a href="<?=BASE_URL . LINK_SIGN?>tweet/fastTweets">التغريد السريع</a></li>
<li><a href="<?=BASE_URL . LINK_SIGN?>tweet/addTweets">أضافه تغريدات</a></li>
<li><a href="<?=BASE_URL . LINK_SIGN?>tweet/tweetTimer">تغريد زمنى</a></li>
</ul>
</li>
<li class="dropdown">
<a href="#" class="dropdown-toggle" data-toggle="dropdown">ردود <b class="caret"></b></a>
<ul class="dropdown-menu">
<li><a href="<?=BASE_URL . LINK_SIGN?>replay/fastReplays">ردود سريعه</a></li>
<li><a href="<?=BASE_URL . LINK_SIGN?>replay/intelligentReplay">رودو ذكيه</a></li>
</ul>
</li>
<li class="dropdown">
<a href="#" class="dropdown-toggle" data-toggle="dropdown">ريتويت <b class="caret"></b></a>
<ul class="dropdown-menu">
<li><a href="<?=BASE_URL . LINK_SIGN?>retweet/fastRetweets">ريتويت سريع</a></li>
<li><a href="<?=BASE_URL . LINK_SIGN?>retweet/retweetsTimer">ريتويت زمنى</a></li>
</ul>
</li>
<li class="dropdown">
<a href="#" class="dropdown-toggle" data-toggle="dropdown">أعجابات <b class="caret"></b></a>
<ul class="dropdown-menu">
<li><a href="<?=BASE_URL . LINK_SIGN?>like/fastLike">اعجاب سريع</a></li>
<li><a href="<?=BASE_URL . LINK_SIGN?>like/likesTimer">أعجاب زمنى</a></li>
</ul>
</li>
<li><a href="<?=BASE_URL . LINK_SIGN?>index/logout">تسجيل الخروج</a></li>
</ul>
</div>
</div>
</nav>
</div>
<br>