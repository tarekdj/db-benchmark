<?php


require __DIR__ . '/vendor/autoload.php';

$useCache = TRUE;

date_default_timezone_set('Europe/Prague');

$connection = new Nette\Database\Connection(
	'mysql:dbname=blog',
	'root',
	''
);

$cacheStorage = new Nette\Caching\Storages\FileStorage(__DIR__ . '/temp');
$dao = new Nette\Database\Context(
	$connection,
	new Nette\Database\Reflection\DiscoveredReflection($connection, $useCache ? $cacheStorage : NULL),
	$useCache ? $cacheStorage : NULL
);


$time = -microtime(TRUE);
ob_start();

foreach ($dao->table('user') as $user) {
	echo "$user->name:\n";
	foreach ($user->related('article')->order('created')->limit(20) as $article) {
		echo "\t", $article->title,
			' in ', $article->category->name,
			' (', $article->related('comment')->count(), ")\n";
	}
}

ob_end_clean();

echo 'Time: ', sprintf('%0.3f', $time + microtime(TRUE)), ' s | ',
	'Memory: ', (memory_get_peak_usage() >> 20), ' MB | ',
	'PHP: ', PHP_VERSION, ' | ',
	'Nette: ', Nette\Framework::VERSION;
