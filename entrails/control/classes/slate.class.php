<?php
/**
 * Slate - PHP Template Engine by Shay Anderson
 *
 * Slate is free software and is distributed WITHOUT ANY WARRANTY
 *
 * @version $v: 1.0.r32 Fri May 13 19:05:31 EST 2011 $;
 * @copyright Copyright (c) 2012 ShayAnderson.com
 * @license http://www.gnu.org/licenses/gpl.html GPL License
 * @link http://www.shayanderson.com/projects/slate-php-template-engine.htm
 */

/**
 * Slate Main Class
 *
 * @package Slate
 * @category Slate
 * @name Slate
 * @version 1.0
 * @author Shay Anderson 04.11
 */
final class Slate {
	/**
	 * Configuration settings
	 *
	 * @var array $_cfg
	 */
	private static $_cfg = array(
		/**
		 * Optional header placed in cache files
		 *
		 * @var string
		 */
		"cache_header" => null,

		/**
		 * Cache default lifetime in seconds (3600 = 1 hour)
		 *
		 * @var int
		 */
		"cache_lifetime" => 3600,

		/**
		 * Turn on/off global caching
		 *
		 * @var bool
		 */
		"caching" => false,

		/**
		 * Cache directory
		 *
		 * @var string
		 */
		"dir_cache" => "cache/",

		/**
		 * Template directory
		 *
		 * @var string
		 */
		"dir_tpl" => "tpl/",

		/**
		 * Error type (set to 0 to suppress errors)
		 * 
		 * @var int
		 */
		"error_type" => E_USER_ERROR,

		/**
		 * Cache file extension
		 *
		 * @var string
		 */
		"ext_cache" => ".cache.tpl",

		/**
		 * Template file extension
		 *
		 * @var string
		 */
		"ext_tpl" => ".tpl"
	);

	/**
	 * Template params
	 *
	 * @var array $_tpl
	 */
	private $_tpl = array(
		/**
		 * List of successfully cached template files
		 *
		 * @var array
		 */
		"cached_files" => array(),
		
		/**
		 * Compiled template content
		 *
		 * @var string
		 */
		"compiled" => null,

		/**
		 * Original raw template name (as passed to object)
		 *
		 * @var string
		 */
		"name" => null
	);

	/**
	 * Variables used by template
	 *
	 * @var array $_vars
	 */
	private $_vars = array();

	/**
	 * Write template cache file
	 *
	 * @param string $cache_filename
	 */
	private function _cacheWrite($cache_filename = null) {
		if((int)self::$_cfg["cache_lifetime"] > 0 && $this->_tpl["compiled"] !== null) {
			if(!is_writable(self::$_cfg["dir_cache"])) {
				$this->_error("Failed to write cache file \"{$cache_filename}\" "
					. "(cache directory \"" . self::$_cfg["dir_cache"] . "\" is not writable)");
			}

			file_put_contents($cache_filename, self::$_cfg["cache_header"] . $this->_tpl["compiled"]);
		}
	}

	/**
	 * Compile template
	 *
	 * @param string $template
	 * @param bool $init_only
	 *
	 * @TODO add block logic (see notes)
	 */
	private function _compile($template = null, $init_only = false) {
		$filename = $cache_filename = null;

		if($this->_tpl["compiled"] === null || $template != $this->_tpl["name"]) {
			$this->_tpl["name"] = $template;

			$filename = self::$_cfg["dir_tpl"] . $template . self::$_cfg["ext_tpl"];

			if(self::$_cfg["caching"] && (int)self::$_cfg["cache_lifetime"] > 0) {
				if(isset($this->_tpl["cached_files"][$template])) {
					$this->_tpl["compiled"] = file_get_contents($this->_tpl["cached_files"][$template]);

					return;
				}

				$cache_filename = self::$_cfg["dir_cache"] . rawurlencode($template . self::$_cfg["ext_cache"]);

				if(file_exists($cache_filename)) {
					if( (time() - filemtime($cache_filename)) < self::$_cfg["cache_lifetime"]) {
						$this->_tpl["compiled"] = file_get_contents($cache_filename);

						$this->_tpl["cached_files"][$template] = $cache_filename;

						return;
					} else {
						unlink($cache_filename);
					}
				}
			}

			if(!file_exists($filename)) {
				$this->_error("Failed to load template \"{$filename}\" (template file not found)");

				return;
			}
		} else {
			return;
		}

		if($init_only) {
			return;
		}

		$regex_tags = array(
			"(\{\*(?:.*?))",
			"((?:.*?)\*\})", // eof comment

			"(\{func=(?:\w+\(.*?\))\})",

			"(\{if.*?\}.*?\{\/if\})",

			"(\{include=(?:.*?)\})",

			"(\{literal\})",
			"(\{\/literal\})", // eof literal

			"(\{loop=\\$(?:\w+)\})",
			"(\{\/loop\})" // eof loop
		);

		$regex_tags = "/" . implode("|", $regex_tags) . "/";

		$template_parts = preg_split($regex_tags, file_get_contents($filename), -1, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);

		$in_comment = $in_loop = 0;
		$in_literal = false;
		$in_loops = array();

		while($raw = array_shift($template_parts)) {
			if($in_comment && preg_match("/(?:.*?)\*\}/", $raw)) {
				$in_comment--;
			} else if($in_comment) {
				if(preg_match("/\{\*(?:.*?)/", $raw)) {
					$in_comment++;
				}
			} else if(preg_match("/\{\*(?:.*?)/", $raw)) {
				$in_comment++;
			} else if($in_literal && preg_match("/\{\/literal\}/", $raw)) {
				$in_literal = false;

				$this->_tpl["compiled"] .= str_replace("{/literal}", null, $raw);
			} else if($in_literal) {
				$this->_tpl["compiled"] .= $raw;
			} else if(preg_match("/\{literal\}/", $raw)) {
				$in_literal = true;

				$this->_tpl["compiled"] .= str_replace("{literal}", null, $raw);
			} else if(preg_match("/\{func=(\w+)\((.*?)\)\}/", $raw, $matches)) {
				if(isset($matches[1])) {
					if(is_callable($matches[1])) {
						$this->_tpl["compiled"] .= isset($matches[2]) ? call_user_func_array($matches[1], explode(",", $matches[2]))
							: call_user_func($matches[1]);
					}
				}
			} else if(preg_match("/(?:\{include=(.*?)\})/", $raw, $matches)) {
				if(count($matches) && isset($matches[1])) {
					$tmp = new self;
					$tmp->set($this->_vars);

					$this->_tpl["compiled"] .= $tmp->fetch($matches[1]);

					unset($tmp);
				}
			} else if(preg_match("/\{loop=\\$(\w+)\}/", $raw, $matches)) {
				if(isset($matches[1])) {
					$in_loop++;

					$in_loops[$in_loop] = $matches[1];
				}
			} else if($in_loop && preg_match("/\{\/loop\}/", $raw, $matches)) {
				if(isset($in_loops[$in_loop])) {
					unset($in_loops[$in_loop]);
				}

				$in_loop--;
			} else if($in_loop && isset($in_loops[$in_loop])) {
				if(isset($this->_vars[$in_loops[$in_loop]]) && is_array($this->_vars[$in_loops[$in_loop]])) {
					$i = 0;

					foreach($this->_vars[$in_loops[$in_loop]] as $k => $v) {
						if(!is_array($v)) {
							$this->_tpl["compiled"] .= str_replace("{\$counter}", $i, str_replace("{\$key}", $k, str_replace("{\$value}",
								$this->_formatCompiledValue($v), $raw)));
						} elseif(preg_match_all("/\{\\$(\w+)\.(\w+)\}/", $raw, $matches)) {
							if(!empty($matches[2])) {
								$tmp = $raw;

								foreach($matches[2] as $el) {
									if(isset($this->_vars[$in_loops[$in_loop]][$k][$el])) {
										$tmp = str_replace("{\$counter}", $i, str_replace("{\$key}", $k, preg_replace("/\{\\\$value\.{$el}\}/",
											$this->_formatCompiledValue($this->_vars[$in_loops[$in_loop]][$k][$el]), $tmp)));
									}
								}

								$this->_tpl["compiled"] .= preg_replace("/\{\\$\w+\.\w+\}/", null, $tmp);
							}

						}

						$i++;
					}
				}
			} else if(preg_match("#\{if.*?\}.*?\{\/if\}#", $raw, $matches)) {
				preg_match("#\{if \\$(\w+)\=?.*?\}#", $matches[0], $var);
				$var = isset($var[1]) ? $var[1] : null;

				preg_match("#\{if \\$\w+\=?.*?\}(.*?)(?:\{else\}|\{\/if\})#", $matches[0], $if);
				$if = isset($if[1]) ? $if[1] : "";

				$else = null;
				if(strpos($matches[0], "{else}") !== false) {
					preg_match("#\{else\}(.*?)\{\/if\}#", $matches[0], $else);
					$else = isset($else[1]) ? $else[1] : "";
				}

				if(preg_match("#\{if \\$\w+\}.*?\{\/if\}#", $matches[0])) {
					$this->_tpl["compiled"] .= $this->_replaceVars( isset($this->_vars[$var]) && $this->_vars[$var] ? $if : $else );
				} else if(preg_match("#\{if \\$\w+(?:\=\=|\!\=|\>\=|\<\=|\>|\<).*?\}.*?\{\/if\}#", $matches[0])) {
					preg_match("#\{if \\$\w+(\=\=|\!\=|\>\=|\<\=|\>|\<)(.*?)\}#", $matches[0], $condition);

					if(isset($condition[1]) && isset($condition[2]) && isset($this->_vars[$var])) {
						$true = false;

						eval(" \$true = \$this->_vars[\$var] {$condition[1]} \$condition[2]; ");

						$this->_tpl["compiled"] .= $this->_replaceVars( $true ? $if : $else );
					} else {
						$this->_tpl["compiled"] .= $this->_replaceVars($else);
					}
				}
			} else {
				$this->_tpl["compiled"] .= $this->_replaceVars($raw);
			}
		}

		if($cache_filename) {
			$this->_cacheWrite($cache_filename);
		}
	}

	/**
	 * Trigger error
	 *
	 * @param string $error_message
	 */
	private function _error($error_message = null) {
		if(self::$_cfg["error_type"]) {
			trigger_error($error_message, self::$_cfg["error_type"]);
		}
	}

	/**
	 * Apply modifiers for compiled output (and ensures values are scalar)
	 *
	 * @param mixed $value
	 * @param string $tag
	 * @return mixed
	 */
	private function _formatCompiledValue($value = null, $tag = null) {
		if(!is_scalar($value)) {
			return null;
		}

		preg_match_all("/\{\\$(?:[\w\.].*?)\|(\w+)\}/", $tag, $matches);

		if(isset($matches[1][0])) {
			$matches[1][0] = strtolower($matches[1][0]);

			switch($matches[1][0]) {
				case "b":
				case "i":
				case "u":
					$value = "<{$matches[1][0]}>{$value}</{$matches[1][0]}>";
					break;
				case "capitalize":
					$value = ucwords($value);
					break;
				case "escape":
					$value = rawurlencode($value);
					break;
				case "lower":
					$value = strtolower($value);
					break;
				case "upper":
					$value = strtoupper($value);
					break;
			}
		}

		return $value;
	}

	/**
	 * Replace template variables with values
	 *
	 * @param string $html
	 * @return string
	 */
	private function _replaceVars($html = null) {
		preg_match_all("/\{\\$(\w+)=(.*?)\}/", $html, $matches);

		for($i = 0; $i < count($matches[0]); $i++) {
			if(isset($matches[1][$i])) {
				$this->_vars[$matches[1][$i]] = isset($matches[2][$i]) ? $this->_formatCompiledValue($matches[2][$i]) : null;
			}

			$c = 0;
			$html = str_replace($matches[0][$i], null, $html, $c);
		}

		preg_match_all("/\{\\$(\w+)(?:\|(\w+))?\}/", $html, $matches);

		for($i = 0; $i < count($matches[0]); $i++) {
			$val = null;

			if(isset($matches[1][$i], $this->_vars[$matches[1][$i]])) {
				$val = $this->_vars[$matches[1][$i]];
			}

			$html = str_replace($matches[0][$i], $this->_formatCompiledValue($val, $matches[0][$i]), $html);
		}

		preg_match_all("/\{\\$\.(\w+)\.(\w+)(?:\|(\w+))?\}/", $html, $matches);

		for($i = 0; $i < count($matches[0]); $i++) {
			$val = null;

			if(isset($matches[1][$i], $matches[2][$i])) {
				$var = $matches[2][$i];

				switch($matches[1][$i]) {
					case "const":
						$val = defined($var) ? constant($var) : null;
						break;
					case "cookie":
						$val = isset($_COOKIE[$var]) ? $_COOKIE[$var] : null;
						break;
					case "get":
						$val = isset($_GET[$var]) ? $_GET[$var] : null;
						break;
					case "post":
						$val = isset($_POST[$var]) ? $_POST[$var] : null;
						break;
					case "session":
						$val = isset($_SESSION[$var]) ? $_SESSION[$var] : null;
						break;
				}
			}

			$html = str_replace($matches[0][$i], $this->_formatCompiledValue($val, $matches[0][$i]), $html);
		}
		unset($var);

		preg_match_all("/\{\\$(\w+)\.(\w+)(?:\|(\w+))?\}/", $html, $matches);

		for($i = 0; $i < count($matches[0]); $i++) {
			$val = null;

			if(isset($matches[1][$i], $matches[2][$i], $this->_vars[$matches[1][$i]]) && is_array($this->_vars[$matches[1][$i]])
				&& array_key_exists($matches[2][$i], $this->_vars[$matches[1][$i]])) {
				$val = $this->_vars[$matches[1][$i]][$matches[2][$i]];
			}

			$html = str_replace($matches[0][$i], $this->_formatCompiledValue($val, $matches[0][$i]), $html);
		}

		preg_match_all("/\{\\$(\w+)\-\>(\w+)(?:\|(\w+))?\}/", $html, $matches);

		for($i = 0; $i < count($matches[0]); $i++) {
			$val = null;

			if(isset($matches[1][$i], $matches[2][$i], $this->_vars[$matches[1][$i]]) && is_object($this->_vars[$matches[1][$i]])
				&& property_exists($this->_vars[$matches[1][$i]], $matches[2][$i])) {
				$val = $this->_vars[$matches[1][$i]]->$matches[2][$i];
			}

			$html = str_replace($matches[0][$i], $this->_formatCompiledValue($val, $matches[0][$i]), $html);
		}

		preg_match_all("/\{\\$(\w+)\-\>([a-zA-Z_]+)\((.*?)\)\}/", $html, $matches);

		for($i = 0; $i < count($matches[0]); $i++) {
			$val = null;

			if(isset($matches[1][$i], $matches[2][$i], $this->_vars[$matches[1][$i]]) && is_object($this->_vars[$matches[1][$i]])
				&& method_exists($this->_vars[$matches[1][$i]], "{$matches[2][$i]}")) {
				$val = isset($matches[3][$i]) ? $this->_vars[$matches[1][$i]]->$matches[2][$i]($matches[3][$i])
					: $this->_vars[$matches[1][$i]]->$matches[2][$i]();
			}

			$html = str_replace($matches[0][$i], $this->_formatCompiledValue($val), $html);
		}

		return $html;
	}

	/**
	 * Check if cache exists for file
	 *
	 * @param string $template
	 * @param int $cache_lifetime (seconds)
	 * @return bool
	 */
	public function cache($template = null, $cache_lifetime = 0) {
		if((int)$cache_lifetime > 0) {
			self::setConfig("cache_lifetime", $cache_lifetime);
		}

		$this->_compile($template, true);

		return isset($this->_tpl["cached_files"][$template]);
	}

	/**
	 * Flush all cache files
	 */
	public function cacheFlush() {
		array_map("unlink", glob(self::$_cfg["dir_cache"] . "*" . self::$_cfg["ext_cache"]));
	}

	/**
	 * Display compiled template
	 *
	 * @param string $template
	 */
	public function display($template = null) {
		$this->_compile($template);

		print $this->_tpl["compiled"];
	}

	/**
	 * Method for static usage of Slate
	 *
	 * @return Slate
	 */
	public static function engine() {
		static $slate = null;

		if($slate === null) {
			$slate = new self;
		}

		return $slate;
	}

	/**
	 * Return compiled template buffer
	 *
	 * @param string $template
	 * @return string
	 */
	public function fetch($template = null) {
		$this->_compile($template);

		return $this->_tpl["compiled"];
	}

	/**
	 * Variable getter
	 *
	 * @param string $var
	 * @return mixed
	 */
	public function get($var = null) {
		if(isset($this->_vars[$var])) {
			return $this->_vars[$var];
		}
	}

	/**
	 * Set variable for template use
	 *
	 * @param mixed $var
	 * @param mixed $value
	 */
	public function set($var = null, $value = null) {
		if(!is_array($var)) {
			$this->_vars[$var] = $value;
		} else {
			$this->_vars += $var;
		}
	}

	/**
	 * Set configuration settings
	 *
	 * @param mixed $key
	 * @param string $value
	 */
	public static function setConfig($key = null, $value = null) {
		if(is_array($key)) {
			foreach($k as $v) {
				$this->setConfig($k, $v);
			}
		} else {
			if(array_key_exists($key, self::$_cfg)) {
				self::$_cfg[$key] = trim($value);
			}
		}
	}
}
?>