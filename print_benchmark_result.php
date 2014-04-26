<?php

function print_benchmark_result($library, $suffix = '')
{
	global $time;
	echo str_pad($library, 12) . ' | '.
		'Time: ',   str_pad(sprintf('%.3f', $time + microtime(TRUE)), 6, ' ', STR_PAD_LEFT), ' s | ',
		'Memory: ', str_pad(sprintf('%.1f', memory_get_peak_usage() / 1024 / 1024), 4, ' ', STR_PAD_LEFT), ' MB | ',
		'PHP: ', PHP_VERSION . ($suffix ? ' | ' . $suffix : '') . PHP_EOL;
}
