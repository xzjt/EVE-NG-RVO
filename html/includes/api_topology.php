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
						$output['data'][] = Array(
							'beziercurviness' => $interface -> beziercurviness,
							'color' => $interface -> color,
							'curviness' => $interface -> curviness,
							'destination' => 'network'.$interface -> getNetworkId(),
							'destination_bandwidth' => 0,
							'destination_delay' => 0,
							'destination_interfaceId' => 'network',
							'destination_jitter' => 0,
							'destination_label' => '',
							'destination_loss' => 0,
							'destination_node_name' => 'network',
							'destination_suspend' => 0,
							'destination_type' => 'network',
							'dstpos' => $interface -> dstpos,
							'label' => $interface -> label,
							'labelpos' => $interface -> labelpos,
							'linkstyle' => $interface -> linkstyle,
							'midpoint' => $interface -> midpoint,
							'network_id' => $interface -> getNetworkId(),
							'round' => $interface -> round,
							'source' => 'node'.$node_id,
							'source_bandwidth' => $interface -> source_bandwidth,
							'source_delay' => $interface -> source_delay,
							'source_interfaceId' => $interface -> getId(),
							'source_jitter' => $interface -> source_jitter,
							'source_label' => $interface -> getName(),
							'source_loss' => $interface -> source_loss,
							'source_node_name' => $node -> getName(),
							'source_suspend' => $interface -> source_suspend,
							'source_type' => 'node',
							'srcpos' => $interface -> srcpos,
							'stub' => $interface -> stub,
							'style' => $interface -> style,
							'type' => 'ethernet'
						);
						break;
					case 0:
						// Network not used
						break;
					case 1:
						// Only one connected node
						$output['data'][] = Array(
							'beziercurviness' => $interface -> beziercurviness,
							'color' => $interface -> color,
							'curviness' => $interface -> curviness,
							'destination' => 'network'.$interface -> getNetworkId(),
							'destination_bandwidth' => 0,
							'destination_delay' => 0,
							'destination_interfaceId' => 'network',
							'destination_jitter' => 0,
							'destination_label' => '',
							'destination_loss' => 0,
							'destination_node_name' => 'network',
							'destination_suspend' => 0,
							'destination_type' => 'network',
							'dstpos' => $interface -> dstpos,
							'label' => $interface -> label,
							'labelpos' => $interface -> labelpos,
							'linkstyle' => $interface -> linkstyle,
							'midpoint' => $interface -> midpoint,
							'network_id' => $interface -> getNetworkId(),
							'round' => $interface -> round,
							'source' => 'node'.$node_id,
							'source_bandwidth' => $interface -> source_bandwidth,
							'source_delay' => $interface -> source_delay,
							'source_interfaceId' => $interface -> getId(),
							'source_jitter' => $interface -> source_jitter,
							'source_label' => $interface -> getName(),
							'source_loss' => $interface -> source_loss,
							'source_node_name' => $node -> getName(),
							'source_suspend' => $interface -> source_suspend,
							'source_type' => 'node',
							'srcpos' => $interface -> srcpos,
							'stub' => $interface -> stub,
							'style' => $interface -> style,
							'type' => 'ethernet'
						);
						break;
					case 2:
						// P2P Link
						if ($lab -> getNetworks()[$interface -> getNetworkId()] -> isCloud() || $lab -> getNetworks()[$interface -> getNetworkId()] -> getVisibility() == 1) {
							// Cloud are never printed as P2P link or Visibility is on
							$output['data'][] = Array(
								'beziercurviness' => $interface -> beziercurviness,
								'color' => $interface -> color,
								'curviness' => $interface -> curviness,
								'destination' => 'network'.$interface -> getNetworkId(),
								'destination_bandwidth' => 0,
								'destination_delay' => 0,
								'destination_interfaceId' => 'network',
								'destination_jitter' => 0,
								'destination_label' => '',
								'destination_loss' => 0,
								'destination_node_name' => 'network',
								'destination_suspend' => 0,
								'destination_type' => 'network',
								'dstpos' => $interface -> dstpos,
								'label' => $interface -> label,
								'labelpos' => $interface -> labelpos,
								'linkstyle' => $interface -> linkstyle,
								'midpoint' => $interface -> midpoint,
								'network_id' => $interface -> getNetworkId(),
								'round' => $interface -> round,
								'source' => 'node'.$node_id,
								'source_bandwidth' => $interface -> source_bandwidth,
								'source_delay' => $interface -> source_delay,
								'source_interfaceId' => $interface -> getId(),
								'source_jitter' => $interface -> source_jitter,
								'source_label' => $interface -> getName(),
								'source_loss' => $interface -> source_loss,
								'source_node_name' => $node -> getName(),
								'source_suspend' => $interface -> source_suspend,
								'source_type' => 'node',
								'srcpos' => $interface -> srcpos,
								'stub' => $interface -> stub,
								'style' => $interface -> style,
								'type' => 'ethernet'
							);
						} else {
							foreach ($lab -> getNodes() as $remote_node_id => $remote_node) {
								foreach ($remote_node -> getEthernets() as $remote_interface) {
									if ($interface -> getNetworkId() == $remote_interface -> getNetworkId()) {
										// To avoid duplicates, only print if source node_id > destination node_id
										if ($node_id > $remote_node_id) {
											$output['data'][] = Array(
												'beziercurviness' => $interface -> beziercurviness,
												'color' => $interface -> color,
												'curviness' => $interface -> curviness,
												'destination' => 'node'.$remote_node_id,
												'destination_bandwidth' => $remote_interface -> destination_bandwidth,
												'destination_delay' => $remote_interface -> destination_delay,
												'destination_interfaceId' => $remote_interface -> getId(),
												'destination_jitter' => $remote_interface -> destination_jitter,
												'destination_label' => $remote_interface -> getName(),
												'destination_loss' => $remote_interface -> destination_loss,
												'destination_node_name' => $remote_node -> getName(),
												'destination_suspend' => $remote_interface -> destination_suspend,
												'destination_type' => 'node',
												'dstpos' => $interface -> dstpos,
												'label' => $interface -> label,
												'labelpos' => $interface -> labelpos,
												'linkstyle' => $interface -> linkstyle,
												'midpoint' => $interface -> midpoint,
												'network_id' => $interface -> getNetworkId(),
												'round' => $interface -> round,
												'source' => 'node'.$node_id,
												'source_bandwidth' => $interface -> source_bandwidth,
												'source_delay' => $interface -> source_delay,
												'source_interfaceId' => $interface -> getId(),
												'source_jitter' => $interface -> source_jitter,
												'source_label' => $interface -> getName(),
												'source_loss' => $interface -> source_loss,
												'source_node_name' => $node -> getName(),
												'source_suspend' => $interface -> source_suspend,
												'source_type' => 'node',
												'srcpos' => $interface -> srcpos,
												'stub' => $interface -> stub,
												'style' => $interface -> style,
												'type' => 'ethernet'
											);
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
				$output['data'][] = Array(
					'beziercurviness' => $interface -> beziercurviness,
					'color' => $interface -> color,
					'curviness' => $interface -> curviness,
					'destination' => 'node'.$interface -> getRemoteID(),
					'destination_bandwidth' => "",
					'destination_delay' => "",
					'destination_interfaceId' => "",
					'destination_jitter' => "",
					'destination_label' => $lab -> getNodes()[$interface -> getRemoteID()] -> getSerials()[$interface -> getRemoteIf()] -> getName(),
					'destination_loss' => "",
					'destination_node_name' => "",
					'destination_suspend' => "",
					'destination_type' => 'node',
					'dstpos' => $interface -> dstpos,
					'label' => $interface -> label,
					'labelpos' => $interface -> labelpos,
					'linkstyle' => $interface -> linkstyle,
					'midpoint' => $interface -> midpoint,
					'network_id' => $interface -> getNetworkId(),
					'round' => $interface -> round,
					'source' => 'node'.$node_id,
					'source_bandwidth' => $interface -> source_bandwidth,
					'source_delay' => $interface -> source_delay,
					'source_interfaceId' => $interface -> getId(),
					'source_jitter' => $interface -> source_jitter,
					'source_label' => $interface -> getName(),
					'source_loss' => $interface -> source_loss,
					'source_node_name' => $node -> getName(),
					'source_suspend' => $interface -> source_suspend,
					'source_type' => 'node',
					'srcpos' => $interface -> srcpos,
					'stub' => $interface -> stub,
					'style' => $interface -> style,
					'type' => 'serial',
				);
			}
		}
	}

	return $output;
}
?>
