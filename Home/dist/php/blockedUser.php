<!doctype html>
<html>
<head>
<meta charset="utf-8">
<script type="text/javascript" src="dist/js/unLockUsers.js"></script>

</head>
<input type="hidden" id="UserID" value="<?php echo $_GET['UID']?>" />
<table>
<tr>
<th>Admin Password</th>
<th>New Password</th>
<th>Confirm</th>
</tr>
<tr>
	<td><input type="password" class="form-control" id="adminPwd"  /></td>
    <td><input type="password" class="form-control" id="newPwd"  /></td>
    <td><input type="password" class="form-control" id="confPwd"  /></td>
</tr>
</table><br>
<button class="btn btn-success btn-sm" id="ConfirmNewPwd">Un-Lock</button>
</html>