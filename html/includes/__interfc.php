<?php
# vim: syntax=php tabstop=4 softtabstop=0 noexpandtab laststatus=1 ruler

/**
 * html/includes/__interfc.php
 *
 * Class for UNetLab interfaces. 
 *
 * @author Andrea Dainese <andrea.dainese@gmail.com>
 * @copyright 2014-2016 Andrea Dainese
 * @license BSD-3-Clause https://github.com/dainok/unetlab/blob/master/LICENSE
 * @link http://www.unetlab.com/
 * @version 20160719
 * @property type $id Interface ID. It's mandatory and automatically set during contruction phase.
 * @property type $name Name of the interface. It's optional.
 * @property type $network_id Remote network ID for Ethernet interfaces. If not set, it's automatically set to 0.
 * @property type $remote_id Remote node ID for Serial interfaces. If not set, it's automatically set to 0.
 * @property type $remote_if Remote interface ID for Serial interfaces. If not set, it's automatically set to 0. 
 * @property type $type Type of the interface. It's mandatory.
 */

class Interfc {
	private $id;
	private $name;
	private $network_id;
	private $remote_id;
	private $remote_if;
	private $type;

	/**
	 * Constructor which creates an interface.
	 * Parameters:
	 * - $name
	 * - $network_id
	 * - $remote_id
	 * - $remote_if
	 * - $type*
	 * *mandatory
	 *
	 * @param   Array   $p                  Parameters
	 * @return  void
	 *
	 */
	public function __construct($p, $id) {
		// Mandatory parameters
		if (!isset($p['type'])) {
			// Missing mandatory parameters
			error_log(date('M d H:i:s ').'ERROR: '.$GLOBALS['messages'][10000]);
			throw new Exception('10000');
			return 10000;
		}

		if (!checkInterfcType($p['type'])) {
			// Type is not valid
			error_log(date('M d H:i:s ').'ERROR: '.$GLOBALS['messages'][10001]);
			throw new Exception('10001');
			return 10001;
		}

		// Optional parameters
		if (isset($p['name']) && $p['name'] === '') {
			// Name is empty, ignored
			unset($p['name']);
			error_log(date('M d H:i:s ').'WARNING: '.$GLOBALS['messages'][10002]);
		}

		if ($p['type'] == 'ethernet') {
			if (isset($p['network_id']) && (int) $p['network_id'] <= 0) {
				// Network ID is not valid
				unset($p['network_id']);
				error_log(date('M d H:i:s ').'WARNING: '.$GLOBALS['messages'][10003]);
			}
		}
				
		if ($p['type'] == 'serial') {
			if (isset($p['remote_id']) && (int) $p['remote_id'] <= 0) {
				// Remote ID is not valid
				unset($p['remote_id']);
				error_log(date('M d H:i:s ').'WARNING: '.$GLOBALS['messages'][10006]);
			}

			if (isset($p['remote_if']) && (int) $p['remote_if'] < 0) {
				// Remote interface is not valid
				unset($p['remote_if']);
				error_log(date('M d H:i:s ').'WARNING: '.$GLOBALS['messages'][10007]);
			}
		}

		// Now building the interface
		$this -> id = (int) $id;
		$this -> type = $p['type'];
		if (isset($p['name'])) $this -> name = htmlentities($p['name']);

		// Building ethernet interface
		if ($p['type'] == 'ethernet') {
			if (isset($p['network_id'])) $this -> name = (int) $p['network_id'];
		}

		// Building serial interface
		if ($p['type'] == 'serial') {
			if (isset($p['remote_id'])) $this -> name = (int) $p['remote_id'];
			if (isset($p['remote_if'])) $this -> name = (int) $p['remote_if'];
		}
	}

	/**
	 * Method to add or replace the interface metadata.
	 * Editable attributes:
	 * - left
	 * - name
	 * - top
	 * If an attribute is set and is valid, then it will be used. If an
	 * attribute is not set, then the original is maintained. If in attribute
	 * is set and empty '', then the current one is deleted.
	 *
	 * @param   Array   $p                  Parameters
	 * @return  int                         0 means ok
	 */
	public function edit($p) {
		$modified = False;

		if (isset($p['name']) && $p['name'] === '') {
			// Name is empty, unset the current one
			unset($p['name']);
			$modified = True;
		} else if (isset($p['name'])) {
			$this -> name = htmlentities($p['name']);
			$modified = True;
		}

		if ($this -> type == 'ethernet') {
			if (isset($p['remote_id']) || isset($p['remote_if'])) {
				// Unneeded attributes
				unset($p['remote_id']);
				unset($p['remote_if']);
				error_log(date('M d H:i:s ').'WARNING: '.$GLOBALS['messages'][10004]);
			}

			if (isset($p['network_id']) && $p['network_id'] === '') {
				// Remote network is 0, unset the current one
				unset($this -> network_id);
				$modified = True;
			} else if (isset($p['network_id']) && (int) $p['network_id'] <= 0) {
				// Network ID is not valid
				error_log(date('M d H:i:s ').'WARNING: '.$GLOBALS['messages'][10003]);
			} else if (isset($p['network_id'])) {
				$this -> network_id = (int) $p['network_id'];
				$modified = True;
			}
		}
				
		if ($this -> type == 'serial') {
			if (isset($p['network_id'])) {
				// Unneeded attributes
				unset($p['network_id']);
				error_log(date('M d H:i:s ').'WARNING: '.$GLOBALS['messages'][10005]);
			}

			if (isset($p['remote_id']) && $p['remote_id'] === '') {
				// Remote node ID is 0, unset the current one
				unset($this -> remote_id);
				unset($this -> remote_if);
				$modified = True;
			} else {
				if (isset($p['remote_id']) && (int) $p['remote_id'] <= 0) {
					// Remote ID is not valid
					error_log(date('M d H:i:s ').'WARNING: '.$GLOBALS['messages'][10006]);
				} else if (isset($p['remote_id'])) {
					$this -> remote_id = (int) $p['remote_id'];
					$modified = True;
				}

				if (isset($p['remote_if']) && (int) $p['remote_if'] < 0) {
					// Remote IF is not valid
					error_log(date('M d H:i:s ').'WARNING: '.$GLOBALS['messages'][10007]);
				} else if (isset($p['remote_if'])) {
					$this -> remote_if = (int) $p['remote_if'];
					$modified = True;
				}
			}
		}

		if (isset($p['color'])) {
			$this -> color = (string) $p['color'];
			$modified = True;
		} else if (!isset($this -> color)) {
			$this -> color = "";
			$modified = True;
		} 
		if (isset($p['style'])) {
			$this -> style = (string) $p['style'];
			$modified = True;
		} else if (!isset($this -> style)) {
			$this -> style = "";
			$modified = True;
		}
		if (isset($p['linkstyle'])) {
			$this -> linkstyle = (string) $p['linkstyle'];
			$modified = True;
		} else if (!isset($this -> linkstyle)) {
			$this -> linkstyle = "";
			$modified = True;
		}
		if (isset($p['label'])) {
			$this -> label = (string) $p['label'];
			$modified = True;
		} else if (!isset($this -> label)) {
			$this -> label = "";
			$modified = True;
		}
		if (isset($p['labelpos'])) {
			$this -> labelpos = (string) $p['labelpos'];
			$modified = True;
		} else if (!isset($this -> labelpos)) {
			$this -> labelpos = "";
			$modified = True;
		}
		if (isset($p['stub'])) {
			$this -> stub = (string) $p['stub'];
			$modified = True;
		} else if (!isset($this -> stub)) {
			$this -> stub = "";
			$modified = True;	
		}
		if (isset($p['curviness'])) {
			$this -> curviness = (string) $p['curviness'];
			$modified = True;
		} else if (!isset($this -> curviness)) {
			$this -> curviness = "";
			$modified = True;
		}
		if (isset($p['beziercurviness'])) {
			$this -> beziercurviness = (string) $p['beziercurviness'];
			$modified = True;
		} else if (!isset($this -> beziercurviness)) {
			$this -> beziercurviness = "";
			$modified = True;
		}
		if (isset($p['round'])) {
			$this -> round = (string) $p['round'];
			$modified = True;
		} else if (!isset($this -> round)) {
			$this -> round = "";
			$modified = True;
		}
		if (isset($p['midpoint'])) {
			$this -> midpoint = (string) $p['midpoint'];
			$modified = True;
		} else if (!isset($this -> midpoint)) {
			$this -> midpoint = "";
			$modified = True;
		}
		if (isset($p['srcpos'])) {
			$this -> srcpos = (string) $p['srcpos'];
			$modified = True;
		} else if (!isset($this -> srcpos)) {
			$this -> srcpos = 0.15;
			$modified = True;
		}
		if (isset($p['dstpos'])) {
			$this -> dstpos = (string) $p['dstpos'];
			$modified = True;
		} else if (!isset($this -> dstpos)) {
			$this -> dstpos = 0.85;
			$modified = True;
		}
		if (isset($p['destination_bandwidth'])) {
			$this -> destination_bandwidth = (int) $p['destination_bandwidth'];
			$modified = True;
		} else if (!isset($this -> destination_bandwidth)) {
			$this -> destination_bandwidth = 0;
			$modified = True;
		}
		if (isset($p['destination_delay'])) {
			$this -> destination_delay = (int) $p['destination_delay'];
			$modified = True;
		} else if (!isset($this -> destination_delay)) {
			$this -> destination_delay = 0;
			$modified = True;
		}
		if (isset($p['destination_jitter'])) {
			$this -> destination_jitter = (int) $p['destination_jitter'];
			$modified = True;
		} else if (!isset($this -> destination_jitter)) {
			$this -> destination_jitter = 0;
			$modified = True;
		}
		if (isset($p['destination_loss'])) {
			$this -> destination_loss = (int) $p['destination_loss'];
			$modified = True;
		} else if (!isset($this -> destination_loss)) {
			$this -> destination_loss = 0;
			$modified = True;
		}
		if (isset($p['destination_suspend'])) {
			$this -> destination_suspend = (int) $p['destination_suspend'];
			$modified = True;
		} else if (!isset($this -> destination_suspend)) {
			$this -> destination_suspend = 0;
			$modified = True;
		}
		if (isset($p['source_bandwidth'])) {
			$this -> source_bandwidth = (int) $p['source_bandwidth'];
			$modified = True;
		} else if (!isset($this -> source_bandwidth)) {
			$this -> source_bandwidth = 0;
			$modified = True;
		}
		if (isset($p['source_delay'])) {
			$this -> source_delay = (int) $p['source_delay'];
			$modified = True;
		} else if (!isset($this -> source_delay)) {
			$this -> source_delay = 0;
			$modified = True;
		}
		if (isset($p['source_jitter'])) {
			$this -> source_jitter = (int) $p['source_jitter'];
			$modified = True;
		} else if (!isset($this -> source_jitter)) {
			$this -> source_jitter = (int) $p['source_jitter'];
			$modified = True;
		}
		if (isset($p['source_loss'])) {
			$this -> source_loss = (int) $p['source_loss'];
			$modified = True;
		} else if (!isset($this -> source_loss)) {
			$this -> source_loss = 0;
			$modified = True;
		}
		if (isset($p['source_suspend'])) {
			$this -> source_suspend = (int) $p['source_suspend'];
			$modified = True;
		} else if (!isset($this -> source_suspend)) {
			$this -> source_suspend = 0;
			$modified = True;
		}

		if ($modified) {
			// At least an attribute is changed
			return 0;
		} else {
			// No attribute has been changed
			error_log(date('M d H:i:s ').'ERROR: '.$GLOBALS['messages'][10008]);
			return 10008;
		}
	}

	/**
	 * Method to get network name.
	 *
	 * @return  string                      Network name
	 */
	public function getName() {
		if (isset($this -> name)) {
			return $this -> name;
		} else {
			// By default return an empty string
			return '';
		}
	}

	/**
	 * Method to get remote network ID.
	 * 
	 * @return	string                      Remote network ID or 0 if not set or not "ethernet" type
	 */
	public function getNetworkId() {
		if ($this -> type == 'ethernet' && isset($this -> network_id)) {
			return $this -> network_id;
		} else {
			return 0;
		}
	}

	/**
	 * Method to get interface ID.
	 * 
	 * @return	string                      interface ID or 0 if not set or not "ethernet" type
	 */
	public function getId() {
		if ($this -> type == 'ethernet' && isset($this -> id)) {
			return $this -> id;
		} else {
			return 0;
		}
	}

	/**
	 * Method to get interface type.
	 * 
	 * @return	string                      Interface type
	 */
	public function getNType() {
		return $this -> type;
	}

	/**
	 * Method to get remote node ID.
	 * 
	 * @return	int                         Remote node ID or 0 if not connected or not "serial" type
	 */
	public function getRemoteId() {
		if ($this -> type == 'serial' && isset($this -> remote_id)) {
			return $this -> remote_id;
		} else {
			return 0;
		}
	}

	/**
	 * Method to get remote interface ID.
	 * 
	 * @return	int                         Remote interface ID or 0 if not connected or not "serial" type
	 */
	public function getRemoteIf() {
		if ($this -> type == 'serial' && isset($this -> remote_if)) {
			return $this -> remote_if;
		} else {
			return 0;
		}
	}
}
?>
