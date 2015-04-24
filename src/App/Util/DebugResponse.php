<?php

namespace App\Util;

use Symfony\Component\HttpFoundation\Response;

class DebugResponse extends Response
{
    public function __construct($content = '', $status = 200, $headers = [])
    {
        parent::__construct($this->wrapContent($content), $status, $headers);
    }

    private function wrapContent($content)
    {
        return <<<HTML
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
</head>
<body>
<pre>
$content
</pre>
</body></html>
HTML;
    }

}
