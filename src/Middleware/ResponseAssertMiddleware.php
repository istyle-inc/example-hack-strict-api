<?hh // strict

namespace App\Middleware;

use Facebook\TypeAssert;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class ResponseAssertMiddleware implements MiddlewareInterface {
  
  const type embeddedLinks = shape(
    'name' => string,
    '_links' => shape(
      'self' => shape(
        'href' => string
      )
    )
  );

  const type hateoasStructure = shape(
    'id' => int,
    'name' => string,
    'title' => string,
    '_links' => shape(
      'self' => shape(
        'href' => string,
        'type' => string
      )
    ),
    '_embedded' => shape(
      'enviroments' => array<self::embeddedLinks>
    )
  );

  public function process(
    ServerRequestInterface $request,
    RequestHandlerInterface $handler,
  ): ResponseInterface {
    $response = $handler->handle($request);
    $decode = json_decode($response->getBody()->getContents(), true);
    TypeAssert\matches_type_structure(
      type_structure(self::class, 'hateoasStructure'),
      $decode,
    );
    return $response;
  }
}
