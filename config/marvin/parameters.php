<?php
try {
    $dsnParts = parse_url($_ENV['DATABASE_URL']);
    $pdo = new PDO(
        $dsnParts['scheme'] . ':dbname=' . trim($dsnParts['path'], '/') . ';host=' . $dsnParts['host'],
        $dsnParts['user'],
        $dsnParts['pass']
    );

    $stmt = $pdo->prepare('SELECT tag, value FROM parameter ORDER BY type ASC, subtype ASC, param_order ASC;');
    $stmt->execute();
    $parameters = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($parameters as $parameter) {
        $container->setParameter(
            $parameter['tag'],
            $parameter['value']
        );
    }
} catch (Exception $e) {
    throw new Exception('Config load error');
}