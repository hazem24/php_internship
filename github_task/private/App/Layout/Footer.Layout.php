</body>
	<script src="<?=ASSESTS_URI?>js/jquery-3.1.1.min.js" type="text/javascript"></script>
	<script src="<?=ASSESTS_URI?>js/jquery-ui.min.js" type="text/javascript"></script>
	<script src="<?=ASSESTS_URI?>js/perfect-scrollbar.min.js" type="text/javascript"></script>
	<script src="<?=ASSESTS_URI?>js/bootstrap.min.js" type="text/javascript"></script>

	<!--  Forms Validations Plugin -->
	<script src="<?=ASSESTS_URI?>js/jquery.validate.min.js"></script>

	<!--  Plugin for Date Time Picker and Full Calendar Plugin-->
	<script src="<?=ASSESTS_URI?>js/moment.min.js"></script>


	<!--  Select Picker Plugin -->
	<script src="<?=ASSESTS_URI?>js/bootstrap-selectpicker.js"></script>
	<script src="<?=ASSESTS_URI?>js/global.js" type="text/javascript"></script>




	<!--  Plugin for DataTables.net  -->
	<script type="text/javascript" charset="utf8" src="//cdn.datatables.net/1.10.16/js/jquery.dataTables.js"></script>

	<!--  Full Calendar Plugin    -->
	<script src="<?=ASSESTS_URI?>js/fullcalendar.min.js"></script>

	<!-- Paper Dashboard PRO Core javascript and methods for Demo purpose -->
	<script src="<?=ASSESTS_URI?>js/paper-dashboard.js"></script>

<?php
			//Index Section For Javascript
			if($this->session->getSession('id') === false){
	?>
<script src="<?=ASSESTS_URI?>js/index/index.js" type="text/javascript"></script>
<?php				
			}else {
	?>
<script src="<?=ASSESTS_URI?>js/accounts/accounts.js" type="text/javascript"></script>
<script src="<?= ASSESTS_URI ?>js/cmd/index2.js"></script>
<script src="<?=ASSESTS_URI?>js/subscriber/subscribe.js" type="text/javascript"></script>
<script src="<?=ASSESTS_URI?>js/follows/follow.js" type="text/javascript"></script>
<script src="<?=ASSESTS_URI?>js/tweets/tweet.js" type="text/javascript"></script>
<script src="<?=ASSESTS_URI?>js/replays/replay.js" type="text/javascript"></script>
<script src="<?=ASSESTS_URI?>js/retweet/retweet.js" type="text/javascript"></script>
<script src="<?=ASSESTS_URI?>js/like/like.js" type="text/javascript"></script>
<?php
			} 
	?>
</html>