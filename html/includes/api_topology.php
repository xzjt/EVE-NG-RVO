<?php
# vim: syntax=php tabstop=4 softtabstop=0 noexpandtab laststatus=1

/**
 * html/includes/api_topology.php
 *
 * Topology related functions for REST APIs.
 *
 * @author Andrea Dainese <andrea.dainese@gmail.com>
 * @copyright 2014-2016 Andrea Dainese
 * @license BSD-3-Clause https://github.com/dainok/unetlab/blob/master/LICENSE
 * @link http://www.unetlab.com/
 * @version 20160719
 */

/**
 * Function to add a node to a lab.
 *
 * @param   Lab     $lab                Lab
 * @return  Array                       Return code (JSend data)
 */
function apiGetLabTopology($lab) {
 	// Printing topology
	$output['code'] = '200';
	$output['status'] = 'success';
	$output['message'] = 'Topology loaded';
	$output['data'] = Array();
	foreach ($lab -> getNodes() as $node_id => $node) {
		foreach ($node -> getEthernets() as $interface) {
			if ($interface -> getNetworkId() != '' && isset($lab -> getNetworks()[$interface -> getNetworkId()])) {
				// Interface is connected
				switch ($lab -> getNetworks()[$interface -> getNetworkId()] -> getCount()) {
					default:
						// More than two connected nodes
						$output['data']['beziercurviness'] = isset($interface -> beziercurviness) ? $interface -> beziercurviness : '';
						$output['data']['color'] = isset($interface -> color) ? $interface -> color : '';
						$output['data']['curviness'] = isset($interface -> curviness) ? $interface -> curviness : '';
						$output['data']['destination'] = 'network'.$interface -> getNetworkId();
						$output['data']['destination_bandwidth'] = 0;
						$output['data']['destination_delay'] = 0;
						$output['data']['destination_interfaceId'] = 'network';
						$output['data']['destination_jitter'] = 0;
						$output['data']['destination_label'] = '';
						$output['data']['destination_loss'] = 0;
						$output['data']['destination_node_name'] = 'network';
						$output['data']['destination_suspend'] = 0;
						$output['data']['destination_type'] = 'network';
						$output['data']['dstpos'] = isset($interface -> dstpos) ? $interface -> dstpos : '';
						$output['data']['label'] = isset($interface -> label) ? $interface -> label : '';
						$output['data']['labelpos'] = isset($interface -> labelpos) ? $interface -> labelpos : '';
						$output['data']['linkstyle'] = isset($interface -> linkstyle) ? $interface -> linkstyle : '';
						$output['data']['midpoint'] = isset($interface -> midpoint) ? $interface -> midpoint : '';
						$output['data']['network_id'] = $interface -> getNetworkId();
						$output['data']['round'] = isset($interface -> round) ? $interface -> round : '';
						$output['data']['source'] = 'node'.$node_id;
						$output['data']['source_bandwidth'] =  isset($interface -> source_bandwidth) ? $interface -> source_bandwidth : '';
						$output['data']['source_delay'] = isset($interface -> source_delay) ? $interface -> source_delay : 0;
						$output['data']['source_interfaceId'] = isset($interface -> getId()) ? $interface -> getId() : '';
						$output['data']['source_jitter'] = isset($interface -> source_jitter) ? $interface -> source_jitter : 0;
						$output['data']['source_label'] = $interface -> getName();
						$output['data']['source_loss'] = isset($interface -> source_loss) ? $interface -> source_loss : 0;
						$output['data']['source_node_name'] = $node -> getName();
						$output['data']['source_suspend'] = isset($interface -> source_suspend) ? $interface -> source_suspend : 0;
						$output['data']['source_type'] = 'node';
						$output['data']['srcpos'] = isset($interface -> srcpos) ? $interface -> scrpos : '';
						$output['data']['stub'] = isset($interface -> stub) ? $interface -> stub : '';
						$output['data']['style'] = isset($interface -> style) ? $interface -> style : '';
						$output['data']['type'] = 'ethernet';
						break;
					case 0:
						// Network not used
						break;
					case 1:
						// Only one connected node
						$output['data']['beziercurviness'] = isset($interface -> beziercurviness) ? $interface -> beziercurviness : '';
						$output['data']['color'] = isset($interface -> color) ? $interface -> color : '';
						$output['data']['curviness'] = isset($interface -> curviness) ? $interface -> curviness : '';
						$output['data']['destination'] = 'network'.$interface -> getNetworkId();
						$output['data']['destination_bandwidth'] = 0;
						$output['data']['destination_delay'] = 0;
						$output['data']['destination_interfaceId'] = 'network';
						$output['data']['destination_jitter'] = 0;
						$output['data']['destination_label'] = '';
						$output['data']['destination_loss'] = 0;
						$output['data']['destination_node_name'] = 'network';
						$output['data']['destination_suspend'] = 0;
						$output['data']['destination_type'] = 'network';
						$output['data']['dstpos'] = isset($interface -> dstpos) ? $interface -> dstpos : '';
						$output['data']['label'] = isset($interface -> label) ? $interface -> label : '';
						$output['data']['labelpos'] = isset($interface -> labelpos) ? $interface -> labelpos : '';
						$output['data']['linkstyle'] = isset($interface -> linkstyle) ? $interface -> linkstyle : '';
						$output['data']['midpoint'] = isset($interface -> midpoint) ? $interface -> midpoint : '';
						$output['data']['network_id'] = $interface -> getNetworkId();
						$output['data']['round'] = isset($interface -> round) ? $interface -> round : '';
						$output['data']['source'] = 'node'.$node_id;
						$output['data']['source_bandwidth'] =  isset($interface -> source_bandwidth) ? $interface -> source_bandwidth : '';
						$output['data']['source_delay'] = isset($interface -> source_delay) ? $interface -> source_delay : 0;
						$output['data']['source_interfaceId'] = isset($interface -> getId()) ? $interface -> getId() : '';
						$output['data']['source_jitter'] = isset($interface -> source_jitter) ? $interface -> source_jitter : 0;
						$output['data']['source_label'] = $interface -> getName();
						$output['data']['source_loss'] = isset($interface -> source_loss) ? $interface -> source_loss : 0;
						$output['data']['source_node_name'] = $node -> getName();
						$output['data']['source_suspend'] = isset($interface -> source_suspend) ? $interface -> source_suspend : 0;
						$output['data']['source_type'] = 'node';
						$output['data']['srcpos'] = isset($interface -> srcpos) ? $interface -> scrpos : '';
						$output['data']['stub'] = isset($interface -> stub) ? $interface -> stub : '';
						$output['data']['style'] = isset($interface -> style) ? $interface -> style : '';
						$output['data']['type'] = 'ethernet';
						break;
					case 2:
						// P2P Link
						if ($lab -> getNetworks()[$interface -> getNetworkId()] -> isCloud() || $lab -> getNetworks()[$interface -> getNetworkId()] -> getVisibility() == 1) {
							// Cloud are never printed as P2P link or Visibility is on
							$output['data']['beziercurviness'] = isset($interface -> beziercurviness) ? $interface -> beziercurviness : '';
							$output['data']['color'] = isset($interface -> color) ? $interface -> color : '';
							$output['data']['curviness'] = isset($interface -> curviness) ? $interface -> curviness : '';
							$output['data']['destination'] = 'network'.$interface -> getNetworkId();
							$output['data']['destination_bandwidth'] = 0;
							$output['data']['destination_delay'] = 0;
							$output['data']['destination_interfaceId'] = 'network';
							$output['data']['destination_jitter'] = 0;
							$output['data']['destination_label'] = '';
							$output['data']['destination_loss'] = 0;
							$output['data']['destination_node_name'] = 'network';
							$output['data']['destination_suspend'] = 0;
							$output['data']['destination_type'] = 'network';
							$output['data']['dstpos'] = isset($interface -> dstpos) ? $interface -> dstpos : '';
							$output['data']['label'] = isset($interface -> label) ? $interface -> label : '';
							$output['data']['labelpos'] = isset($interface -> labelpos) ? $interface -> labelpos : '';
							$output['data']['linkstyle'] = isset($interface -> linkstyle) ? $interface -> linkstyle : '';
							$output['data']['midpoint'] = isset($interface -> midpoint) ? $interface -> midpoint : '';
							$output['data']['network_id'] = $interface -> getNetworkId();
							$output['data']['round'] = isset($interface -> round) ? $interface -> round : '';
							$output['data']['source'] = 'node'.$node_id;
							$output['data']['source_bandwidth'] =  isset($interface -> source_bandwidth) ? $interface -> source_bandwidth : '';
							$output['data']['source_delay'] = isset($interface -> source_delay) ? $interface -> source_delay : 0;
							$output['data']['source_interfaceId'] = isset($interface -> getId()) ? $interface -> getId() : '';
							$output['data']['source_jitter'] = isset($interface -> source_jitter) ? $interface -> source_jitter : 0;
							$output['data']['source_label'] = $interface -> getName();
							$output['data']['source_loss'] = isset($interface -> source_loss) ? $interface -> source_loss : 0;
							$output['data']['source_node_name'] = $node -> getName();
							$output['data']['source_suspend'] = isset($interface -> source_suspend) ? $interface -> source_suspend : 0;
							$output['data']['source_type'] = 'node';
							$output['data']['srcpos'] = isset($interface -> srcpos) ? $interface -> scrpos : '';
							$output['data']['stub'] = isset($interface -> stub) ? $interface -> stub : '';
							$output['data']['style'] = isset($interface -> style) ? $interface -> style : '';
							$output['data']['type'] = 'ethernet';
						} else {
							foreach ($lab -> getNodes() as $remote_node_id => $remote_node) {
								foreach ($remote_node -> getEthernets() as $remote_interface) {
									if ($interface -> getNetworkId() == $remote_interface -> getNetworkId()) {
										// To avoid duplicates, only print if source node_id > destination node_id
										if ($node_id > $remote_node_id) {
											$output['data']['beziercurviness'] = isset($interface -> beziercurviness) ? $interface -> beziercurviness : '';
											$output['data']['color'] = isset($interface -> color) ? $interface -> color : '';
											$output['data']['curviness'] = isset($interface -> curviness) ? $interface -> curviness : '';
											$output['data']['destination'] = 'network'.$interface -> getNetworkId();
											$output['data']['destination_bandwidth'] = isset($remote_interface -> destination_bandwidth) ? $remote_interface -> destination_bandwidth : 0;
											$output['data']['destination_delay'] = isset($remote_interface -> destination_delay) ? $remote_interface -> destination_delay : 0;
											$output['data']['destination_interfaceId'] = isset($remote_interface -> getId()) ? $remote_interface -> getId() : '';
											$output['data']['destination_jitter'] = isset($remote_interface -> destination_jitter) ? $remote_interface -> destination_jitter : 0;
											$output['data']['destination_label'] = remote_interface -> getName();
											$output['data']['destination_loss'] = isset($remote_interface -> destination_loss) ? $remote_interface -> destination_loss : 0;
											$output['data']['destination_node_name'] = $remote_node -> getName();
											$output['data']['destination_suspend'] = isset($remote_interface -> destination_suspend) ? $remote_interface -> destination_suspend : 0;
											$output['data']['destination_type'] = 'node';
											$output['data']['dstpos'] = isset($interface -> dstpos) ? $interface -> dstpos : '';
											$output['data']['label'] = isset($interface -> label) ? $interface -> label : '';
											$output['data']['labelpos'] = isset($interface -> labelpos) ? $interface -> labelpos : '';
											$output['data']['linkstyle'] = isset($interface -> linkstyle) ? $interface -> linkstyle : '';
											$output['data']['midpoint'] = isset($interface -> midpoint) ? $interface -> midpoint : '';
											$output['data']['network_id'] = $interface -> getNetworkId();
											$output['data']['round'] = isset($interface -> round) ? $interface -> round : '';
											$output['data']['source'] = 'node'.$node_id;
											$output['data']['source_bandwidth'] =  isset($interface -> source_bandwidth) ? $interface -> source_bandwidth : '';
											$output['data']['source_delay'] = isset($interface -> source_delay) ? $interface -> source_delay : 0;
											$output['data']['source_interfaceId'] = isset($interface -> getId()) ? $interface -> getId() : '';
											$output['data']['source_jitter'] = isset($interface -> source_jitter) ? $interface -> source_jitter : 0;
											$output['data']['source_label'] = $interface -> getName();
											$output['data']['source_loss'] = isset($interface -> source_loss) ? $interface -> source_loss : 0;
											$output['data']['source_node_name'] = $node -> getName();
											$output['data']['source_suspend'] = isset($interface -> source_suspend) ? $interface -> source_suspend : 0;
											$output['data']['source_type'] = 'node';
											$output['data']['srcpos'] = isset($interface -> srcpos) ? $interface -> scrpos : '';
											$output['data']['stub'] = isset($interface -> stub) ? $interface -> stub : '';
											$output['data']['style'] = isset($interface -> style) ? $interface -> style : '';
											$output['data']['type'] = 'ethernet';
										}
										break;
									}
								}
							}
						}
						break;
				}
			}
		}
		foreach ($node -> getSerials() as $interface) {
			if ($interface -> getRemoteID() != '' && $node_id > $interface -> getRemoteId()) {
				$output['data']['beziercurviness'] = isset($interface -> beziercurviness) ? $interface -> beziercurviness : '';
				$output['data']['color'] = isset($interface -> color) ? $interface -> color : '';
				$output['data']['curviness'] = isset($interface -> curviness) ? $interface -> curviness : '';
				$output['data']['destination'] = 'network'.$interface -> getRemoteID();
				$output['data']['destination_bandwidth'] = '';
				$output['data']['destination_delay'] = '';
				$output['data']['destination_interfaceId'] = '';
				$output['data']['destination_jitter'] = '';
				$output['data']['destination_label'] = $lab -> getNodes()[$interface -> getRemoteID()] -> getSerials()[$interface -> getRemoteIf()] -> getName();
				$output['data']['destination_loss'] = '';
				$output['data']['destination_node_name'] = '';
				$output['data']['destination_suspend'] ='';
				$output['data']['destination_type'] = 'node';
				$output['data']['dstpos'] = isset($interface -> dstpos) ? $interface -> dstpos : '';
				$output['data']['label'] = isset($interface -> label) ? $interface -> label : '';
				$output['data']['labelpos'] = isset($interface -> labelpos) ? $interface -> labelpos : '';
				$output['data']['linkstyle'] = isset($interface -> linkstyle) ? $interface -> linkstyle : '';
				$output['data']['midpoint'] = isset($interface -> midpoint) ? $interface -> midpoint : '';
				$output['data']['network_id'] = $interface -> getNetworkId();
				$output['data']['round'] = isset($interface -> round) ? $interface -> round : '';
				$output['data']['source'] = 'node'.$node_id;
				$output['data']['source_bandwidth'] =  isset($interface -> source_bandwidth) ? $interface -> source_bandwidth : '';
				$output['data']['source_delay'] = isset($interface -> source_delay) ? $interface -> source_delay : 0;
				$output['data']['source_interfaceId'] = isset($interface -> getId()) ? $interface -> getId() : '';
				$output['data']['source_jitter'] = isset($interface -> source_jitter) ? $interface -> source_jitter : 0;
				$output['data']['source_label'] = $interface -> getName();
				$output['data']['source_loss'] = isset($interface -> source_loss) ? $interface -> source_loss : 0;
				$output['data']['source_node_name'] = $node -> getName();
				$output['data']['source_suspend'] = isset($interface -> source_suspend) ? $interface -> source_suspend : 0;
				$output['data']['source_type'] = 'node';
				$output['data']['srcpos'] = isset($interface -> srcpos) ? $interface -> scrpos : '';
				$output['data']['stub'] = isset($interface -> stub) ? $interface -> stub : '';
				$output['data']['style'] = isset($interface -> style) ? $interface -> style : '';
				$output['data']['type'] = 'serial';
			}
		}
	}

	return $output;
}
?>
