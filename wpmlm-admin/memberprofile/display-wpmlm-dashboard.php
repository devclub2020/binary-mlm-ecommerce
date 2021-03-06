<div class='wrap'>

<!--------------------------------------------------------------------------
Left side  
---------------------------------------------------------------------------->
<div class="dashboard">
	<div class="leftSide">
		<div id="personaldetails" class="widgetbox">
			<div class="title"><h3>Personal Details</h3></div>
			<table cellspacing="0" cellpadding="0" border="0" class="stdtable">
				<colgroup>
					<col class="con1" width="30%">
					<col class="con0">
				</colgroup>
				<thead>
					<tr>
						<th class="head1">Title</th>
						<th class="head1">Details</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td>ID</td>
						<td><?= $userDetail['userKey'] ?></td>
					</tr>
					<tr>
						<td>Name</td>
						<td><?= $userDetail['name']?></td>
					</tr>
					<tr>
						<td>Address</td>
						<td><?= $userDetail['address1']?><br /><?= $userDetail['address2']?></td>
					</tr>
					<tr>
						<td>City</td>
						<td><?= $userDetail['city']?></td>
					</tr>
					<tr>
						<td>Country</td>
						<td><?= $userDetail['country']?></td>
					</tr>
					
				</tbody>
			</table>
		</div>
		<div id="personaldetails" class="widgetbox">
			<div class="title"><h3>Total Business (PV)</h3></div>
			<table cellspacing="0" cellpadding="0" border="0" class="stdtable">
				<colgroup>
					<col class="con0">
					<col class="con1">
					<col class="con0">
				</colgroup>
				<thead>
					<tr>
						<th class="head1">Left</th>
						<th class="head1">Right</th>
						<th class="head1">Total</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td><?= $totalBus['left']?></td>
						<td><?= $totalBus['right']?></td>
						<td><?= $totalBus['total']?></td>
					</tr>
				</tbody>
			</table>
		</div>
		<div id="personaldetails" class="widgetbox">
			<div class="title"><h3>Payout Details</h3></div>
				<?php if(count($payoutArr) > 5 ){ ?>
				<div class="widgetoptions">
					<div class="right"><a href="<?= admin_url('admin.php?page=wpmlm-member-profile&tab=my-payout&uid='.$this->uid) ?>">View All Payout</a></div>
					<div class="total">&nbsp;</div>
				</div>
				<?php }?>
				<table cellspacing="0" cellpadding="0" border="0" class="stdtable">
				<colgroup>
					<col class="con0">
					<col class="con0">
				</colgroup>
				<thead>
					<tr>
						<th class="head0">Date</th>
						<th class="head0">Amount</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach($payoutArr as $payout  ) : ?>
					<tr>
						<td><?= $payout['payout_date']?></td>
						<td><?= $payout['paidAmount']?></td>
					</tr>
					<?php endforeach ?>
				</tbody>
				</table>
		</div>
		
		<!--Main left side div -->
	</div>
	
	<!--------------------------------------------------------------------------
	Right side  
	---------------------------------------------------------------------------->
	<div class="rightSide">
		<div id="leftleg" class="widgetbox">
			<div class="title"><h3>Left Leg</h3></div>
			<div class="widgetoptions">
				<div class="right"><a href="<?= admin_url('admin.php?page=wpmlm-member-profile&tab=my-left&uid='.$this->uid) ?>">View All Users</a></div>
				<div class="total">Total Members : <?= $myLeftTotal['total']; ?></div>
			</div>
			<table cellspacing="0" cellpadding="0" border="0" class="stdtable">
			<colgroup>
				<col class="con0">
				<col class="con0" width="40%">
			</colgroup>
			<thead>
				<tr>
					<th class="head0">Name</th>
					<th class="head0">Status</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach($myLeftArr as $myleft  ) : ?>
				<tr>
					<td><?= $myleft['name']?></td>
					<td><?= $myleft['payment_status']?></td>
				</tr>
				<?php endforeach ?>
			</tbody>
			</table>
		</div>
		<div id="rightleg" class="widgetbox">
			<div class="title"><h3>Right Leg</h3></div>
			<div class="widgetoptions">
				<div class="right"><a href="<?= admin_url('admin.php?page=wpmlm-member-profile&tab=my-right&uid='.$this->uid) ?>">View All Users</a></div>
				<div class="total">Total Members : <?= $myRightTotal['total']; ?></div>
			</div>
			<table cellspacing="0" cellpadding="0" border="0" class="stdtable">
			<colgroup>
				<col class="con0">
				<col class="con0" width="40%">
			</colgroup>
			<thead>
				<tr>
					<th class="head0">Name</th>
					<th class="head0">Status</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach($myRightArr as $myright  ) : ?>
				<tr>
					<td><?= $myright['name']?></td>
					<td><?= $myright['payment_status']?></td>
				</tr>
				<?php endforeach ?>
			</tbody>
			</table>
		</div>
		<div id="mypersonalsales" class="widgetbox">
			<div class="title"><h3>Personal Sales</h3></div>
			<div class="widgetoptions">
				<div class="right"><a href="<?= admin_url('admin.php?page=wpmlm-member-profile&tab=my-direct&uid='.$this->uid) ?>">View All Users</a></div>
				<div class="total">Total Members : <?= $myPerSalesTotal['total']?></div>
			</div>
			<table cellspacing="0" cellpadding="0" border="0" class="stdtable">
			<colgroup>
				<col class="con0">
				<col class="con0" width="40%">
			</colgroup>
			<thead>
				<tr>
					<th class="head0">Name</th>
					<th class="head0">Status</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach($myPerSalesArr as $myPerSales  ) : ?>
				<tr>
					<td><?= $myPerSales['name']?></td>
					<td><?= $myPerSales['payment_status']?></td>
				</tr>
				<?php endforeach ?>
			</tbody>
			</table>
		</div>
		
		<!--ride side div ends-->
	</div>
	
</div>
<div class="cBoth"></div>
		
</div>