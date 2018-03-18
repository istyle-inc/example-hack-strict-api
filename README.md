# Nazg Framework - Response Type Assert Sample

Example). **Hal, Type Assert Middleware, Cors**

## Get Strated

```bash
$ git clone https://github.com/ytake/nazg-sample-app.git
$ hhvm -d xdebug.enable=0 -d hhvm.jit=0 -d hhvm.php7.all=1\
 -d hhvm.hack.lang.auto_typecheck=0 $(which composer) install
```

## Example

```bash
$ curl http://nazg-sample-app.vagrant/
```

### Response

```
Content-Type application/hal+json
```

```json
{
  "id": 1234,
  "name": "ytake",
  "title": "type-assert for api response",
  "_links": {
    "self": {
      "href": "/",
      "type": "application/json"
    }
  },
  "_embedded": {
    "enviroments": [
      {
        "name": "HHVM/Hack",
        "_links": {
          "self": {
            "href": "https://docs.hhvm.com/"
          }
        }
      },
      {
        "name": "zend-diactoros",
        "_links": {
          "self": {
            "href": "https://zendframework.github.io/zend-diactoros/"
          }
        }
      }
    ]
  }
}
```

### Response Type Assert Middleware

```hack
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

```
