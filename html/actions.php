<?php
# vim: syntax=php tabstop=4 softtabstop=0 noexpandtab laststatus=1 ruler

/**
 *
 * @author Pete
 */
if($_GET["action"]=="fix")
	{
		$o = "" ;
		$cmd = "sudo /opt/unetlab/wrappers/unl_wrapper -a fixpermissions 2>&1";
		exec($cmd, $o, $rc);
		if ($rc == 0) {
			$output['code'] = $rc;
			$output['messages'] = "Fix permissions Success";
			$output['status'] = 'Success';
		} else {
			$output['code'] = $rc;
			$output['messages'] = "Permission denied";
			$output['status'] = 'Failed';
		};
	}
	elseif ($_GET["action"]=="iol")
	{
		$o = "" ;
		$cmd = 'sudo CiscoKeyGen 2>&1';
		exec($cmd, $o, $rc);
		if ($rc == 0) {
			$output['code'] = $rc;
			$output['messages'] = "Generate IOU License Success";
			$output['status'] = 'Success';
		} else {
			$output['code'] = $rc;
			$output['messages'] = "Permission denied";
			$output['status'] = 'Failed';
		};
	}
	else {
		$output['code'] = '400';
		$output['messages'] = "Unknown Error. Why did API doesn't respond?";
		$output['status'] = 'Failed';
	}

?>
<?php echo json_encode($output)?>
