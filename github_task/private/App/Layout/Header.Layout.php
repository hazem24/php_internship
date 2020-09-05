<!doctype html>
<html lang="ar">
<head>
	<meta charset="utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<title>Github</title>
	<meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport' />
    <meta name="viewport" content="width=device-width" />
    <link href="<?=ASSESTS_URI ?>css/bootstrap.min.css" rel="stylesheet" />
    <link href="<?=ASSESTS_URI ?>css/paper-dashboard.css" rel="stylesheet"/>
    <!--  Fonts and icons     -->
    <link href="http://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css" rel="stylesheet">
    <link href='https://fonts.googleapis.com/css?family=Muli:400,300' rel='stylesheet' type='text/css'>
	<link href="<?=ASSESTS_URI ?>css/themify-icons.css" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.16/css/jquery.dataTables.css">

    <script src="<?=ASSESTS_URI ?>sweetalert/sweetalert.min.js"></script>
    <link rel=stylesheet type=text/css href="<?=ASSESTS_URI ?>sweetalert/sweetalert.css">
    <link rel=stylesheet type=text/css href="<?=ASSESTS_URI ?>sweetalert/twitter.css">
    <style>.main-CMD{background-color:#FFF;color:#000000;font-family:'Fira Mono',Monospace;margin:0;overflow-x:hidden}.main-CMD a{color:inherit}.main-CMD a:hover{color:#f00;background-color:#5f5b00}.main-CMD .stream{margin-top:8px}.main-CMD .line{margin:0;padding:0;display:flex;flex-direction:row;margin:0 4px;padding-top:2px;width:calc(100% - 12px)}.main-CMD .line p{display:inline-block;margin:0;padding:0}.main-CMD .line .name{max-width:80px;min-width:80px;text-align:right;padding-right:6px}.main-CMD .editline{background-color:#e50c0c;padding:2px 4px 0 4px;width:calc(100%);margin:0;margin-bottom:8px}.main-CMD .editline .edit{min-width:calc(100% - 200px);outline:0}.main-CMD .editline .time{user-select:none;cursor:default}.whitet{color:#ffffae}.redt{color:#d75f5f}.important{color:#e3a786}.bluet{color:#5f8787}.greent{color:#afaf00}.selft{color:#83a598}::selection{color:#ffffae;background:#005f5f}::-webkit-scrollbar{background-color:#3a3a3a;width:10px;height:10px}::-webkit-scrollbar-thumb{background-color:#bcbcbc}::-webkit-scrollbar-corner{background-color:#3a3a3a}::-webkit-resizer{background-color:#3a3a3a}.phjspenheader:hover{background-color:#d44c2a}</style>

</head>
<body>
<?php
    if($this->session->getSession('id') !== false):
?>
<div class="main-panel">
			<nav class="navbar navbar-default">
	            <div class="container-fluid">
					<div class="navbar-minimize">
						<button id="minimizeSidebar" class="btn btn-fill btn-icon"><i class="ti-more-alt"></i></button>
					</div>
	                <div class="navbar-header">
	                    <button type="button" class="navbar-toggle">
	                        <span class="sr-only">Toggle navigation</span>
	                        <span class="icon-bar bar1"></span>
	                        <span class="icon-bar bar2"></span>
	                        <span class="icon-bar bar3"></span>
	                    </button>
	                    <a class="navbar-brand" href="">
							نظره عامه
						</a>
	                </div>
	                <div class="collapse navbar-collapse">

	                </div>
	            </div>
	        </nav>
<div class="wrapper">
	    <div class="sidebar" data-background-color="brown" data-active-color="danger">
	    <!--
			Tip 1: you can change the color of the sidebar's background using: data-background-color="white | brown"
			Tip 2: you can change the color of the active button using the data-active-color="primary | info | success | warning | danger"
		-->
			<div class="logo">
				<a href="http://www.creative-tim.com" class="simple-text logo-mini">
					GO
				</a>

				<a href="http://www.creative-tim.com" class="simple-text logo-normal">
					GO TREND
				</a>
			</div>
	    	<div class="sidebar-wrapper">
				<div class="user">
	                <div class="info">
						<div class="photo">
		                </div>

	                    <a data-toggle="collapse" href="#collapseExample" class="collapsed">
	                        <span>
								<?=$this->session->getSession('serial')?>
		                        <b class="caret"></b>
							</span>
	                    </a>
						<div class="clearfix"></div>

	                    <div class="collapse" id="collapseExample">
	                        <ul class="nav">
	                            <li>
									<a href="<?=BASE_URL.LINK_SIGN.'index/logout'?>">
										<span class="sidebar-mini">خ</span>
										<span class="sidebar-normal">تسجيل خروج</span>
									</a>
								</li>
	                        </ul>
	                    </div>
	                </div>
	            </div>
	            <ul class="nav">
	                <li class="active">
	                    <a data-toggle="collapse" href="#dashboardOverview" aria-expanded="true">
	                        <i class="ti-panel"></i>
	                        <p>احصائيات
                                <b class="caret"></b>
                            </p>
	                    </a>
						<div class="collapse in" id="dashboardOverview">
							<ul class="nav">
								<li class="active">
									<a href="../dashboard/overview.html">
										<span class="sidebar-mini">ع</span>
										<span class="sidebar-normal">نظره عامه</span>
									</a>
								</li>
							</ul>
						</div>
	                </li>
					<li>
						<a data-toggle="collapse" href="#formsExamples">
	                        <i class="ti-clipboard"></i>
	                        <p>
								اضافه
	                           <b class="caret"></b>
	                        </p>
	                    </a>
	                    <div class="collapse" id="formsExamples">
							<ul class="nav">
								<li>
									<a href="<?=BASE_URL.LINK_SIGN.'accounts/addAccounts'?>">
										<span class="sidebar-mini">ح</span>
										<span class="sidebar-normal">حسابات</span>
									</a>
								</li>
								<li>
									<a href="<?=BASE_URL.LINK_SIGN.'subscribe/addSub'?>">
										<span class="sidebar-mini">اش</span>
										<span class="sidebar-normal">اشتراكات</span>
									</a>
								</li>
	                        </ul>
	                    </div>
	                </li>
	                <li>
						<a data-toggle="collapse" href="#tablesExamples">
	                        <i class="ti-view-list-alt"></i>
	                        <p>
								عرض
	                           <b class="caret"></b>
	                        </p>
	                    </a>
	                    <div class="collapse" id="tablesExamples">
							<ul class="nav">
								<li>
									<a href="<?=BASE_URL.LINK_SIGN.'accounts/showAccounts'?>">
										<span class="sidebar-mini">ح</span>
										<span class="sidebar-normal">عرض الحسابات</span>
									</a>
								</li>
								<li>
									<a href="<?=BASE_URL.LINK_SIGN.'subscribe/showSub'?>">
										<span class="sidebar-mini">اش</span>
										<span class="sidebar-normal">اشتراكات</span>
									</a>
								</li>
	                        </ul>
	                    </div>
					</li>
					<li>
						<a data-toggle="collapse" href="#formsExamples22">
	                        <i class="ti-clipboard"></i>
	                        <p>
								يدوي
	                           <b class="caret"></b>
	                        </p>
	                    </a>
	                    <div class="collapse" id="formsExamples22">
							<ul class="nav">
								<li>
									<a href="<?=BASE_URL.LINK_SIGN.'retweet/fastRetweets'?>">
										<span class="sidebar-mini">رت</span>
										<span class="sidebar-normal">رتويت سريع</span>
									</a>
								</li>
								<li>
									<a href="<?=BASE_URL.LINK_SIGN.'like/fastLike'?>">
										<span class="sidebar-mini">اع</span>
										<span class="sidebar-normal">اعجالات سريعه</span>
									</a>
								</li>
								<li>
									<a href="<?=BASE_URL.LINK_SIGN.'replay/fastReplays'?>">
										<span class="sidebar-mini">رد</span>
										<span class="sidebar-normal">ردود سريعه</span>
									</a>
								</li>
	                        </ul>
	                    </div>
	                </li>
	            </ul>
	    	</div>
	    </div>


<?php
endif;
?>
