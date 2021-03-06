<?hh

/**
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 * This software consists of voluntary contributions made by many individuals
 * and is licensed under the MIT license.
 *
 * Copyright (c) 2017-2018 Yuuki Takezawa
 */
return [
  \Nazg\Foundation\Service::ROUTES => ImmMap {
    /**
     * As Middleware, it needs to be implemented class.
     *
     * HttpMethod => ImmMap {
     *   'endpoint' => ImmVector {
     *     MiddlewareClass::class, // \Interop\Http\Server\MiddlewareInterface implements Class
     *   } 
     * }
     * 
     * use enum HttpMethod 
     * \Nazg\Http\HttpMethod::HEAD
     * \Nazg\Http\HttpMethod::GET
     * \Nazg\Http\HttpMethod::POST
     * \Nazg\Http\HttpMethod::PATCH
     * \Nazg\Http\HttpMethod::PUT
     * \Nazg\Http\HttpMethod::DELETE
     * 
     * Assigning Middleware To Route
     * 'endpoint' => ImmVector {
     *   first - MiddlewareClass::class,
     *   second - RouteMiddlewareClass::class,
     * } 
     * Or
     * 'endpoint' => ImmVector {
     *   first - RouteMiddlewareClass::class,
     *   second - MiddlewareClass::class,
     * }      
     */
    \Nazg\Http\HttpMethod::GET => ImmMap {
      '/' => shape(
        'middleware' => ImmVector {
          App\Middleware\ResponseAssertMiddleware::class,
          App\Action\IndexAction::class
        },
      )
    },
  },
];
